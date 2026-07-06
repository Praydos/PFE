<?php

use App\Models\Ville;
use App\Models\Zone;
use App\Models\Quartier;
use App\Models\User;
use App\Models\Compte;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->rbo = User::factory()->rbo()->create();
    $this->delegue = User::factory()->delegue()->create();

    $this->ville = Ville::factory()->create();
    $this->zone = Zone::forceCreate([
        'name' => 'Test Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => $this->rbo->id,
    ]);
});

it('prevents guests from accessing quartiers index', function () {
    $this->get(route('quartiers.index'))->assertRedirect(route('login'));
});

it('allows admin to view quartiers index', function () {
    Quartier::forceCreate([
        'nom' => 'Test Quartier',
        'zone_id' => $this->zone->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('quartiers.index'))
        ->assertStatus(200)
        ->assertSee('Test Quartier');
});

it('allows admin to view create quartier page', function () {
    $this->actingAs($this->admin)
        ->get(route('quartiers.create'))
        ->assertStatus(200);
});

it('allows admin to store a new quartier', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('quartiers.store'), [
            'nom' => 'Nouveau Quartier',
            'zone_id' => $this->zone->id,
        ]);

    $response->assertRedirect(route('quartiers.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('quartiers', [
        'nom' => 'Nouveau Quartier',
        'zone_id' => $this->zone->id,
    ]);
});

it('fails to store quartier with invalid data', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('quartiers.store'), [
            'nom' => '',
            'zone_id' => 99999, // Non-existent zone
        ]);

    $response->assertSessionHasErrors(['nom', 'zone_id']);
});

it('allows admin to view edit quartier page', function () {
    $quartier = Quartier::forceCreate([
        'nom' => 'Edit Quartier',
        'zone_id' => $this->zone->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('quartiers.edit', $quartier))
        ->assertStatus(200)
        ->assertSee('Edit Quartier');
});

it('allows admin to update quartier', function () {
    $quartier = Quartier::forceCreate([
        'nom' => 'Old Quartier',
        'zone_id' => $this->zone->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('quartiers.update', $quartier), [
            'nom' => 'Updated Quartier',
            'zone_id' => $this->zone->id,
        ]);

    $response->assertRedirect(route('quartiers.index'));
    $this->assertDatabaseHas('quartiers', [
        'id' => $quartier->id,
        'nom' => 'Updated Quartier',
    ]);
});

it('allows admin to delete empty quartier', function () {
    $quartier = Quartier::forceCreate([
        'nom' => 'Empty Quartier',
        'zone_id' => $this->zone->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('quartiers.destroy', $quartier));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('quartiers', ['id' => $quartier->id]);
});

it('prevents deletion of quartier with comptes', function () {
    $quartier = Quartier::forceCreate([
        'nom' => 'Quartier With Comptes',
        'zone_id' => $this->zone->id,
    ]);

    Compte::forceCreate([
        'etablissement' => 'Test Compte',
        'type' => 'ecole',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'quartier_id' => $quartier->id,
        'adresse' => 'Test Address',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('quartiers.destroy', $quartier));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('quartiers', ['id' => $quartier->id]);
});
