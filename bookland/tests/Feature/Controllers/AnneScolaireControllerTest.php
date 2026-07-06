<?php

use App\Models\AnneeScolaire;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
});

it('prevents guests from accessing index', function () {
    $this->get(route('annees-scolaires.index'))->assertRedirect(route('login'));
});

it('prevents non-admins from accessing index', function () {
    $response = $this->actingAs($this->delegue)->get(route('annees-scolaires.index'));
    expect(in_array($response->status(), [403, 302]))->toBeTrue();
});

it('allows admin to view index', function () {
    AnneeScolaire::factory()->create(['libelle' => '2025-2026']);
    $this->actingAs($this->admin)
        ->get(route('annees-scolaires.index'))
        ->assertStatus(200)
        ->assertSee('2025-2026');
});

it('allows admin to view create page', function () {
    $this->actingAs($this->admin)
        ->get(route('annees-scolaires.create'))
        ->assertStatus(200);
});

it('allows admin to store annee scolaire', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('annees-scolaires.store'), [
            'libelle' => '2025-2026',
            'date_debut' => '2025-09-01',
            'date_fin' => '2026-06-30',
        ]);

    $response->assertRedirect(route('annees-scolaires.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('annees_scolaires', [
        'libelle' => '2025-2026',
        'is_active' => 1,
    ]);
});

it('fails to store with invalid data', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('annees-scolaires.store'), [
            'libelle' => '',
            'date_debut' => '2025-01-01',
            'date_fin' => '2024-01-01', // before date_debut
        ]);

    $response->assertSessionHasErrors(['libelle', 'date_fin']);
});

it('allows admin to view edit page', function () {
    $annee = AnneeScolaire::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('annees-scolaires.edit', $annee))
        ->assertStatus(200)
        ->assertSee($annee->libelle);
});

it('allows admin to update annee scolaire', function () {
    $annee = AnneeScolaire::factory()->create([
        'libelle' => 'Old Year',
        'date_debut' => '2020-09-01',
        'date_fin' => '2021-06-30',
        'is_closed' => false,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('annees-scolaires.update', $annee), [
            'libelle' => 'New Year',
            'date_debut' => '2020-09-01',
            'date_fin' => '2021-06-30',
        ]);

    $response->assertRedirect(route('annees-scolaires.index'));
    $this->assertDatabaseHas('annees_scolaires', [
        'id' => $annee->id,
        'libelle' => 'New Year',
    ]);
});

it('prevents update of closed annee scolaire', function () {
    $annee = AnneeScolaire::factory()->create([
        'is_closed' => true,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('annees-scolaires.update', $annee), [
            'libelle' => 'New Year',
            'date_debut' => '2020-09-01',
            'date_fin' => '2021-06-30',
        ]);

    $response->assertSessionHasErrors(['error']);
    $this->assertDatabaseHas('annees_scolaires', [
        'id' => $annee->id,
        'libelle' => $annee->libelle, // Remained unchanged
    ]);
});

it('prevents deletion of active annee scolaire', function () {
    $annee = AnneeScolaire::factory()->create(['is_active' => true]);

    $response = $this->actingAs($this->admin)
        ->delete(route('annees-scolaires.destroy', $annee));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('annees_scolaires', ['id' => $annee->id]);
});

it('allows deletion of inactive annee scolaire', function () {
    $annee = AnneeScolaire::factory()->create(['is_active' => false]);

    $response = $this->actingAs($this->admin)
        ->delete(route('annees-scolaires.destroy', $annee));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('annees_scolaires', ['id' => $annee->id]);
});

it('can set an annee scolaire as active', function () {
    $annee1 = AnneeScolaire::factory()->create(['is_active' => true, 'is_closed' => false]);
    $annee2 = AnneeScolaire::factory()->create(['is_active' => false, 'is_closed' => false]);

    $response = $this->actingAs($this->admin)
        ->post(route('annees-scolaires.set-active', $annee2));

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('annees_scolaires', ['id' => $annee1->id, 'is_active' => false]);
    $this->assertDatabaseHas('annees_scolaires', ['id' => $annee2->id, 'is_active' => true]);
});

it('can close an inactive annee scolaire', function () {
    $annee = AnneeScolaire::factory()->create(['is_active' => false, 'is_closed' => false]);

    $response = $this->actingAs($this->admin)
        ->post(route('annees-scolaires.close', $annee));

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('annees_scolaires', ['id' => $annee->id, 'is_closed' => true]);
});

it('cannot close an active annee scolaire', function () {
    $annee = AnneeScolaire::factory()->create(['is_active' => true, 'is_closed' => false]);

    $response = $this->actingAs($this->admin)
        ->post(route('annees-scolaires.close', $annee));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('annees_scolaires', ['id' => $annee->id, 'is_closed' => false]);
});
