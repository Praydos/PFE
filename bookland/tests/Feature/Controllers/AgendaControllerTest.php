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
    $this->ville = Ville::factory()->create();

    $this->zone = Zone::forceCreate([
        'name' => 'Agenda Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => collect([User::factory()->rbo()->create()->id])->first(),
    ]);

    $this->compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole Agenda',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Addr',
        'status' => 'actif',
    ]);
});

it('prevents guests from accessing agenda index', function () {
    $this->get(route('agenda.index'))->assertRedirect(route('login'));
});

it('allows delegue to view their agenda', function () {
    Action::create([
        'objet' => 'Test Agenda',
        'compte_id' => $this->compte->id,
        'delegue_id' => $this->delegue->id,
        'date_planification' => now()->toDateString(),
        'statut' => 'planifie',
        'type' => 'commercial',
    ]);

    $this->actingAs($this->delegue)
        ->get(route('agenda.index'))
        ->assertStatus(200);
});

it('fetches agenda events', function () {
    $action = Action::create([
        'objet' => 'AJAX Event',
        'compte_id' => $this->compte->id,
        'delegue_id' => $this->delegue->id,
        'date_planification' => now()->toDateString(),
        'statut' => 'planifie',
        'type' => 'commercial',
    ]);

    $response = $this->actingAs($this->delegue)
        ->getJson(route('agenda.events', ['start' => now()->subDay()->toDateString(), 'end' => now()->addDay()->toDateString()]));

    $response->assertStatus(200);
    $data = $response->json();
    $this->assertNotEmpty($data);
    $this->assertTrue(collect($data)->contains('title', 'AJAX Event – Ecole Agenda'));
});
