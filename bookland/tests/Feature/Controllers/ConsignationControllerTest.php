<?php

use App\Models\Consignation;
use App\Models\Product;
use App\Models\User;
use App\Models\AnneeScolaire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->product = Product::factory()->create();
    $this->annee = AnneeScolaire::factory()->create(['is_active' => true, 'is_closed' => false]);
});

it('prevents guests from accessing consignations index', function () {
    $this->get(route('consignations.index'))->assertRedirect(route('login'));
});

it('allows delegue to view consignations index', function () {
    Consignation::create([
        'delegate_id' => $this->delegue->id,
        'product_id' => $this->product->id,
        'annee_scolaire_id' => $this->annee->id,
        'quantity' => 10,
    ]);

    $this->actingAs($this->delegue)
        ->get(route('consignations.index'))
        ->assertStatus(200);
});

it('prevents delegue from storing consignation', function () {
    $this->actingAs($this->delegue)
        ->post(route('consignations.store'), [
            'delegate_id' => $this->delegue->id,
            'product_id' => $this->product->id,
            'annee_scolaire_id' => $this->annee->id,
            'quantity' => 10,
        ])->assertStatus(403);
});

it('allows admin to store consignation', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('consignations.store'), [
            'delegate_id' => $this->delegue->id,
            'product_id' => $this->product->id,
            'annee_scolaire_id' => $this->annee->id,
            'quantity' => 50,
        ]);

    $response->assertRedirect(route('consignations.index'));
    $this->assertDatabaseHas('consignations', [
        'delegate_id' => $this->delegue->id,
        'product_id' => $this->product->id,
        'quantity' => 50,
    ]);
});

it('allows admin to delete consignation', function () {
    $consignation = Consignation::create([
        'delegate_id' => $this->delegue->id,
        'product_id' => $this->product->id,
        'annee_scolaire_id' => $this->annee->id,
        'quantity' => 10,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('consignations.destroy', $consignation));

    $response->assertRedirect(route('consignations.index'));
    $this->assertDatabaseMissing('consignations', ['id' => $consignation->id]);
});
