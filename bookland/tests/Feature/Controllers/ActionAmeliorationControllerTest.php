<?php

use App\Models\ActionAmelioration;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\User;
use App\Models\Ville;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->ville = Ville::factory()->create();

    $this->zone = Zone::forceCreate([
        'name' => 'AA Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => collect([User::factory()->rbo()->create()->id])->first(),
    ]);

    $this->compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole AA',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Addr AA',
        'status' => 'actif',
    ]);

    $this->contact = Contact::create(['nom' => 'Contact AA', 'ville_id' => $this->ville->id]);
});

it('prevents guests from accessing actions amelioration index', function () {
    $this->get(route('actions-amelioration.index'))->assertRedirect(route('login'));
});

it('allows admin to view actions amelioration index', function () {
    ActionAmelioration::create([
        'numero' => 'AA-2026-0001',
        'compte_id' => $this->compte->id,
        'emetteur_id' => $this->contact->id,
        'dateAA' => now()->toDateString(),
        'type' => 'Action corrective',
        'origine' => 'Reclamation',
        'statut' => 'brouillon',
    ]);

    $this->actingAs($this->admin)
        ->get(route('actions-amelioration.index'))
        ->assertStatus(200)
        ->assertSee('AA-2026-0001');
});

it('allows delegue to create action amelioration', function () {
    $this->actingAs($this->delegue)
        ->get(route('actions-amelioration.create'))
        ->assertStatus(200);
});

it('allows delegue to store a new action amelioration', function () {
    $response = $this->actingAs($this->delegue)
        ->post(route('actions-amelioration.store'), [
            'compte_id' => $this->compte->id,
            'emetteur_id' => $this->contact->id,
            'dateAA' => now()->toDateString(),
            'type' => 'Action corrective',
            'origine' => 'Reclamation',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('actions_amelioration', [
        'compte_id' => $this->compte->id,
        'type' => 'Action corrective'
    ]);
});

it('allows admin to delete action amelioration', function () {
    $aa = ActionAmelioration::create([
        'numero' => 'AA-2026-0002',
        'compte_id' => $this->compte->id,
        'emetteur_id' => $this->contact->id,
        'dateAA' => now()->toDateString(),
        'type' => 'Action corrective',
        'origine' => 'Reclamation',
        'statut' => 'brouillon',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('actions-amelioration.destroy', $aa));

    $response->assertRedirect(route('actions-amelioration.index'));
    $this->assertDatabaseMissing('actions_amelioration', ['id' => $aa->id]);
});
