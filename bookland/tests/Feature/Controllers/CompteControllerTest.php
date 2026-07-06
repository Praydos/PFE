<?php

use App\Models\Compte;
use App\Models\User;
use App\Models\Ville;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->rbo = User::factory()->rbo()->create();
    $this->delegue = User::factory()->delegue()->create();

    $this->ville = Ville::factory()->create();
    $this->zone = Zone::forceCreate([
        'name' => 'Compte Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    // Assign delegue to zone so it belongs to them
    $this->zone->delegates()->attach($this->delegue->id);

    // RBO also needs ville mapping
    $this->rbo->rboVilles()->attach($this->ville->id);
});

it('prevents guests from accessing comptes index', function () {
    $this->get(route('comptes.index'))->assertRedirect(route('login'));
});

it('allows admin to view comptes index', function () {
    Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole Admin View',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => '123 Test',
        'status' => 'actif',
    ]);

    $this->actingAs($this->admin)
        ->get(route('comptes.index'))
        ->assertStatus(200)
        ->assertSee('Ecole Admin View');
});

it('allows delegue to view index and sees their comptes', function () {
    $this->actingAs($this->delegue)
        ->get(route('comptes.index'))
        ->assertStatus(200);
});

it('prevents delegue from accessing compte create page', function () {
    $this->actingAs($this->delegue)
        ->get(route('comptes.create'))
        ->assertSessionHas('error');
});

it('allows admin to store a new compte', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('comptes.store'), [
            'type' => 'ecole',
            'etablissement' => 'Nouvelle Ecole',
            'ville_id' => $this->ville->id,
            'zone_id' => $this->zone->id,
            'delegue_id' => $this->delegue->id,
            'adresse' => 'L\'adresse de test',
            'status' => 'actif',
        ]);

    $response->assertRedirect(route('comptes.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('comptes', [
        'etablissement' => 'Nouvelle Ecole',
        'delegue_id' => $this->delegue->id,
    ]);
});

it('prevents admin from storing compte with delegue not in zone', function () {
    $otherDelegue = User::factory()->delegue()->create();

    $response = $this->actingAs($this->admin)
        ->post(route('comptes.store'), [
            'type' => 'ecole',
            'etablissement' => 'Ecole Fail',
            'ville_id' => $this->ville->id,
            'zone_id' => $this->zone->id,
            'delegue_id' => $otherDelegue->id, // not attached to $this->zone
            'adresse' => 'L\'adresse',
            'status' => 'actif',
        ]);

    $response->assertSessionHasErrors(['delegue_id']);
});

it('allows delegue to update their compte operational info', function () {
    $compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Old Name',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Old Address',
        'status' => 'actif',
    ]);

    $response = $this->actingAs($this->delegue)
        ->put(route('comptes.update', $compte), [
            'type' => 'ecole',
            'etablissement' => 'New Name by Delegue',
            'adresse' => 'New Address by Delegue',
            'status' => 'actif',
        ]);

    $response->assertRedirect(route('comptes.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('comptes', [
        'id' => $compte->id,
        'etablissement' => 'New Name by Delegue',
        'adresse' => 'New Address by Delegue',
    ]);
});

it('prevents delegue from deleting their compte', function () {
    $compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'To be deleted?',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Address',
        'status' => 'actif',
    ]);

    // Role check manually aborts 403
    $response = $this->actingAs($this->delegue)
        ->delete(route('comptes.destroy', $compte));

    $response->assertStatus(403);
    $this->assertDatabaseHas('comptes', ['id' => $compte->id]);
});

it('allows admin to delete compte', function () {
    $compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'To be deleted',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Address',
        'status' => 'actif',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('comptes.destroy', $compte));

    $response->assertRedirect(route('comptes.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('comptes', ['id' => $compte->id]);
});
