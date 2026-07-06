<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
});

it('prevents guests from accessing products index', function () {
    $this->get(route('products.index'))->assertRedirect(route('login'));
});

it('allows admin to view products index', function () {
    Product::create([
        'source' => 'bookland',
        'titre' => 'Test Product Titre',
        'type' => 'Livre',
        'isbn_13' => '9781234567890',
    ]);

    $this->actingAs($this->admin)
        ->get(route('products.index'))
        ->assertStatus(200)
        ->assertSee('Test Product Titre');
});

it('allows admin to view product create page', function () {
    $this->actingAs($this->admin)
        ->get(route('products.create'))
        ->assertStatus(200);
});

it('allows admin to store a new product', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('products.store'), [
            'source' => 'esprit_du_livre',
            'titre' => 'Nouveau Livre',
            'type' => 'Livre',
            'isbn_13' => '9789876543210',
        ]);

    $response->assertRedirect(route('products.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('products', [
        'titre' => 'Nouveau Livre',
        'source' => 'esprit_du_livre',
    ]);
});

it('fails to store product with duplicate isbn', function () {
    Product::create([
        'source' => 'bookland',
        'titre' => 'Existing',
        'type' => 'Livre',
        'isbn_13' => '9780000000000',
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('products.store'), [
            'source' => 'bookland',
            'titre' => 'Duplicate ISBN',
            'type' => 'Livre',
            'isbn_13' => '9780000000000',
        ]);

    $response->assertSessionHasErrors(['isbn_13']);
});

it('allows admin to view product show page', function () {
    $product = Product::create([
        'source' => 'bookland',
        'titre' => 'Product Show Test',
        'type' => 'Livre',
    ]);

    $this->actingAs($this->admin)
        ->get(route('products.show', $product))
        ->assertStatus(200)
        ->assertSee('Product Show Test');
});

it('allows admin to view product edit page', function () {
    $product = Product::create([
        'source' => 'bookland',
        'titre' => 'Product to Edit',
        'type' => 'Livre',
    ]);

    $this->actingAs($this->admin)
        ->get(route('products.edit', $product))
        ->assertStatus(200)
        ->assertSee('Product to Edit');
});

it('allows admin to update product', function () {
    $product = Product::create([
        'source' => 'bookland',
        'titre' => 'Old Titre',
        'type' => 'Livre',
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('products.update', $product), [
            'source' => 'esprit_du_livre',
            'titre' => 'Updated Titre',
            'type' => 'Livre',
        ]);

    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'titre' => 'Updated Titre',
        'source' => 'esprit_du_livre',
    ]);
});

it('allows admin to delete empty product', function () {
    $product = Product::create([
        'source' => 'bookland',
        'titre' => 'To Delete',
        'type' => 'Livre',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('products.destroy', $product));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});
