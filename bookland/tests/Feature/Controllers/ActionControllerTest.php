<?php

use App\Models\Action;
use App\Models\Compte;
use App\Models\User;
use App\Models\Ville;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->rbo = User::factory()->rbo()->create();
    $this->ville = Ville::factory()->create();

    $this->zone = Zone::forceCreate([
        'name' => 'Action Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    $this->compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole Action',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Addr',
        'status' => 'actif',
    ]);
});

it('prevents guests from accessing actions index', function () {
    $this->get(route('actions.index'))->assertRedirect(route('login'));
});

it('allows admin to view actions index', function () {
    Action::create([
        'objet' => 'Visite technique',
        'compte_id' => $this->compte->id,
        'delegue_id' => $this->delegue->id,
        'date_planification' => now()->toDateString(),
        'statut' => 'planifie',
        'type' => 'commercial',
    ]);

    $this->actingAs($this->admin)
        ->get(route('actions.index'))
        ->assertStatus(200)
        ->assertSee('Visite technique');
});

it('allows delegue to view actions create page', function () {
    $this->actingAs($this->delegue)
        ->get(route('actions.create'))
        ->assertStatus(200);
});

it('allows delegue to store a new action', function () {
    $response = $this->actingAs($this->delegue)
        ->post(route('actions.store'), [
            'objet' => 'Nouvelle vis',
            'compte_id' => $this->compte->id,
            'date_planification' => now()->toDateString(),
            'type' => 'commercial',
        ]);

    $response->assertRedirect(route('actions.index'));
    $this->assertDatabaseHas('actions', [
        'objet' => 'Nouvelle vis',
        'delegue_id' => $this->delegue->id
    ]);
});

it('allows delegue to edit action', function () {
    $action = Action::create([
        'objet' => 'Visite technique',
        'compte_id' => $this->compte->id,
        'delegue_id' => $this->delegue->id,
        'date_planification' => now()->toDateString(),
        'statut' => 'planifie',
        'type' => 'commercial',
    ]);

    $this->actingAs($this->delegue)
        ->get(route('actions.edit', $action))
        ->assertStatus(200);
});

it('allows delegue to mark action as realise with report', function () {
    $action = Action::create([
        'objet' => 'A réaliser',
        'compte_id' => $this->compte->id,
        'delegue_id' => $this->delegue->id,
        'date_planification' => now()->toDateString(),
        'statut' => 'planifie',
        'type' => 'commercial',
    ]);

    $response = $this->actingAs($this->delegue)
        ->post(route('actions.realiser', $action), [
            'rapport_titre' => 'Rapport Visite',
            'rapport_description' => 'Tout s\'est bien passé.',
            'rapport_date' => now()->toDateString(),
        ]);

    $response->assertRedirect(route('actions.show', $action));
    $this->assertDatabaseHas('actions', [
        'id' => $action->id,
        'statut' => 'valide',
        'rapport_titre' => 'Rapport Visite'
    ]);
});

it('allows admin to delete action', function () {
    $action = Action::create([
        'objet' => 'A annuler',
        'compte_id' => $this->compte->id,
        'delegue_id' => $this->delegue->id,
        'date_planification' => now()->toDateString(),
        'statut' => 'planifie',
        'type' => 'commercial',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('actions.destroy', $action));

    $response->assertRedirect(route('actions.index'));
    $this->assertDatabaseMissing('actions', ['id' => $action->id]);
});
