<?php

use App\Models\Tache;
use App\Models\User;
use App\Models\Contact;
use App\Models\Ville;
use App\Models\Compte;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->ville = Ville::factory()->create();

    $this->zone = Zone::forceCreate([
        'name' => 'Tache Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => collect([User::factory()->rbo()->create()->id])->first(),
    ]);

    $this->compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole Tache',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Addr',
        'status' => 'actif',
    ]);
});

it('prevents guests from accessing taches index', function () {
    $this->get(route('taches.index'))->assertRedirect(route('login'));
});

it('allows delegue to view taches index', function () {
    Tache::create([
        'objet' => 'Call client',
        'date_planification' => now()->toDateString(),
        'delegue_id' => $this->delegue->id,
        'is_validated' => false,
    ]);

    $this->actingAs($this->delegue)
        ->get(route('taches.index'))
        ->assertStatus(200)
        ->assertSee('Call client');
});

it('allows delegue to store a new tache', function () {
    $response = $this->actingAs($this->delegue)
        ->post(route('taches.store'), [
            'objet' => 'New Task',
            'date_planification' => now()->toDateString(),
            'heure' => '10:00',
        ]);

    $response->assertRedirect(route('taches.index'));
    $this->assertDatabaseHas('taches', [
        'objet' => 'New Task',
        'delegue_id' => $this->delegue->id
    ]);
});

it('allows admin to delete a tache', function () {
    $tache = Tache::create([
        'objet' => 'Delete Task',
        'date_planification' => now()->toDateString(),
        'delegue_id' => $this->delegue->id,
        'is_validated' => false,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('taches.destroy', $tache));

    $response->assertRedirect(route('taches.index'));
    $this->assertDatabaseMissing('taches', ['id' => $tache->id]);
});
