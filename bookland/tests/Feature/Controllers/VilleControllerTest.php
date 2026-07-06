<?php

use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
});

it('prevents guests from accessing villes index', function () {
    $this->get(route('villes.index'))->assertRedirect(route('login'));
});

it('prevents non-admins from accessing villes index', function () {
    $response = $this->actingAs($this->delegue)->get(route('villes.index'));
    expect(in_array($response->status(), [403, 302]))->toBeTrue();
});

it('allows admin to view villes index', function () {
    Ville::factory()->create(['nom' => 'Paris']);
    $this->actingAs($this->admin)
        ->get(route('villes.index'))
        ->assertStatus(200)
        ->assertSee('Paris');
});

it('allows admin to view create page', function () {
    $this->actingAs($this->admin)
        ->get(route('villes.create'))
        ->assertStatus(200);
});

it('allows admin to store ville', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('villes.store'), [
            'nom' => 'Nouvelle Ville',
        ]);

    $response->assertRedirect(route('villes.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('villes', [
        'nom' => 'Nouvelle Ville',
    ]);
});

it('fails to store ville with duplicate name', function () {
    Ville::factory()->create(['nom' => 'Doublon Ville']);

    $response = $this->actingAs($this->admin)
        ->post(route('villes.store'), [
            'nom' => 'Doublon Ville',
        ]);

    $response->assertSessionHasErrors(['nom']);
});

it('allows admin to view edit page', function () {
    $ville = Ville::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('villes.edit', $ville))
        ->assertStatus(200)
        ->assertSee($ville->nom);
});

it('allows admin to update ville', function () {
    $ville = Ville::factory()->create(['nom' => 'Old Ville']);

    $response = $this->actingAs($this->admin)
        ->put(route('villes.update', $ville), [
            'nom' => 'Updated Ville',
        ]);

    $response->assertRedirect(route('villes.index'));
    $this->assertDatabaseHas('villes', [
        'id' => $ville->id,
        'nom' => 'Updated Ville',
    ]);
});

it('fails to update ville with existing name', function () {
    $ville1 = Ville::factory()->create(['nom' => 'Ville One']);
    $ville2 = Ville::factory()->create(['nom' => 'Ville Two']);

    $response = $this->actingAs($this->admin)
        ->put(route('villes.update', $ville1), [
            'nom' => 'Ville Two',
        ]);

    $response->assertSessionHasErrors(['nom']);
});

it('allows admin to delete empty ville', function () {
    $ville = Ville::factory()->create();

    $response = $this->actingAs($this->admin)
        ->delete(route('villes.destroy', $ville));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('villes', ['id' => $ville->id]);
});

it('prevents deletion of ville with zones', function () {
    $ville = Ville::factory()->create();
    $rbo = User::factory()->rbo()->create();

    // Create zone directly to avoid needing a Zone factory if it doesn't exist
    $zone = Zone::forceCreate([
        'name' => 'Zone 1',
        'ville_id' => $ville->id,
        'rbo_id' => $rbo->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('villes.destroy', $ville));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('villes', ['id' => $ville->id]);
});

it('allows admin to assign a zone to a ville', function () {
    $ville1 = Ville::factory()->create();
    $ville2 = Ville::factory()->create();
    $rbo = User::factory()->rbo()->create();

    $zone = Zone::forceCreate([
        'name' => 'Zone to move',
        'ville_id' => $ville1->id,
        'rbo_id' => $rbo->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('villes.assignZone', $ville2), [
            'zone_id' => $zone->id,
        ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('zones', [
        'id' => $zone->id,
        'ville_id' => $ville2->id,
    ]);
});

it('prevents assigning a zone already in the ville', function () {
    $ville = Ville::factory()->create();
    $rbo = User::factory()->rbo()->create();

    $zone = Zone::forceCreate([
        'name' => 'Zone exists',
        'ville_id' => $ville->id,
        'rbo_id' => $rbo->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('villes.assignZone', $ville), [
            'zone_id' => $zone->id,
        ]);

    $response->assertSessionHas('error');
});
