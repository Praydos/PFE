<?php

use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use App\Models\Compte;
use App\Models\Quartier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->rbo = User::factory()->rbo()->create();
    $this->delegue = User::factory()->delegue()->create();
});

it('prevents guests from accessing zones index', function () {
    $this->get(route('zones.index'))->assertRedirect(route('login'));
});

it('allows admin to view zones index', function () {
    $ville = Ville::factory()->create();
    $zone = Zone::forceCreate([
        'name' => 'Test Zone Listing',
        'ville_id' => $ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('zones.index'))
        ->assertStatus(200)
        ->assertSee('Test Zone Listing');
});

it('allows admin to view create zone page', function () {
    $this->actingAs($this->admin)
        ->get(route('zones.create'))
        ->assertStatus(200);
});

it('allows admin to store a new zone', function () {
    $ville = Ville::factory()->create();
    // Assign user to ville in rboVilles to pass the validation rbo check
    $this->rbo->rboVilles()->attach($ville->id);

    $response = $this->actingAs($this->admin)
        ->post(route('zones.store'), [
            'name' => 'New Zone',
            'ville_id' => $ville->id,
            'rbo_id' => $this->rbo->id,
        ]);

    $response->assertRedirect(route('zones.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('zones', [
        'name' => 'New Zone',
        'ville_id' => $ville->id,
        'rbo_id' => $this->rbo->id,
    ]);
});

it('fails to store zone if rbo is not assigned to the ville', function () {
    $ville = Ville::factory()->create();
    // RBO not attached to ville

    $response = $this->actingAs($this->admin)
        ->post(route('zones.store'), [
            'name' => 'New Zone',
            'ville_id' => $ville->id,
            'rbo_id' => $this->rbo->id,
        ]);

    $response->assertSessionHasErrors(['rbo_id']);
});

it('allows admin to view edit zone page', function () {
    $ville = Ville::factory()->create();
    $zone = Zone::forceCreate([
        'name' => 'Zone to Edit',
        'ville_id' => $ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('zones.edit', $zone))
        ->assertStatus(200)
        ->assertSee('Zone to Edit');
});

it('allows admin to update zone', function () {
    $ville = Ville::factory()->create();
    $zone = Zone::forceCreate([
        'name' => 'Old Zone',
        'ville_id' => $ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('zones.update', $zone), [
            'name' => 'Updated Zone',
            'ville_id' => $ville->id,
            'rbo_id' => $this->rbo->id,
        ]);

    $response->assertRedirect(route('zones.index'));
    $this->assertDatabaseHas('zones', [
        'id' => $zone->id,
        'name' => 'Updated Zone',
    ]);
});

it('allows admin to delete empty zone', function () {
    $ville = Ville::factory()->create();
    $zone = Zone::forceCreate([
        'name' => 'Empty Zone',
        'ville_id' => $ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('zones.destroy', $zone));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('zones', ['id' => $zone->id]);
});

it('prevents deletion of zone with comptes', function () {
    $ville = Ville::factory()->create();
    $zone = Zone::forceCreate([
        'name' => 'Zone With Comptes',
        'ville_id' => $ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    $quartier = Quartier::forceCreate([
        'nom' => 'Quartier 1',
        'zone_id' => $zone->id,
    ]);

    Compte::forceCreate([
        'etablissement' => 'Test Compte',
        'type' => 'ecole',
        'ville_id' => $ville->id,
        'zone_id' => $zone->id,
        'quartier_id' => $quartier->id,
        'adresse' => 'Test Address',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('zones.destroy', $zone));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('zones', ['id' => $zone->id]);
});

it('allows admin to detach delegate from zone', function () {
    $ville = Ville::factory()->create();
    $zone = Zone::forceCreate([
        'name' => 'Zone to detach from',
        'ville_id' => $ville->id,
        'rbo_id' => $this->rbo->id,
    ]);

    $zone->delegates()->attach($this->delegue->id);

    $this->assertDatabaseHas('delegue_zone', [
        'zone_id' => $zone->id,
        'delegue_id' => $this->delegue->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('zones.detachDelegate', [$zone, $this->delegue]));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('delegue_zone', [
        'zone_id' => $zone->id,
        'delegue_id' => $this->delegue->id,
    ]);
});
