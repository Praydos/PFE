<?php

use App\Models\MpProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
});

it('prevents guests from accessing mp products index', function () {
    $this->get(route('mp-products.index'))->assertRedirect(route('login'));
});

it('allows admin to view mp products index', function () {
    MpProduct::create([
        'code_article' => 'MP-001',
        'editeur' => 'Bookland',
        'nom' => 'Test MP Product',
    ]);

    $this->actingAs($this->admin)
        ->get(route('mp-products.index'))
        ->assertStatus(200)
        ->assertSee('Test MP Product');
});

it('allows admin to view create mp product page', function () {
    $this->actingAs($this->admin)
        ->get(route('mp-products.create'))
        ->assertStatus(200);
});

it('allows admin to store a new mp product', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('mp-products.store'), [
            'code_article' => 'MP-NEW',
            'editeur' => 'Esprit du livre',
            'nom' => 'Nouveau Matériel',
        ]);

    $response->assertRedirect(route('mp-products.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('mp_products', [
        'code_article' => 'MP-NEW',
        'nom' => 'Nouveau Matériel',
    ]);
});

it('fails to store mp product with duplicate code_article', function () {
    MpProduct::create([
        'code_article' => 'MP-DUP',
        'editeur' => 'Bookland',
        'nom' => 'Existing',
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('mp-products.store'), [
            'code_article' => 'MP-DUP',
            'editeur' => 'Bookland',
            'nom' => 'Duplicate',
        ]);

    $response->assertSessionHasErrors(['code_article']);
});

it('allows admin to view edit mp product page', function () {
    $mp_product = MpProduct::create([
        'code_article' => 'MP-EDIT',
        'editeur' => 'Bookland',
        'nom' => 'Product Edit View',
    ]);

    $this->actingAs($this->admin)
        ->get(route('mp-products.edit', $mp_product))
        ->assertStatus(200)
        ->assertSee('Product Edit View');
});

it('allows admin to update mp product', function () {
    $mp_product = MpProduct::create([
        'code_article' => 'MP-UPDATE',
        'editeur' => 'Bookland',
        'nom' => 'Old Name',
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('mp-products.update', $mp_product), [
            'code_article' => 'MP-UPDATED',
            'editeur' => 'Bookland',
            'nom' => 'Updated Name',
        ]);

    $response->assertRedirect(route('mp-products.index'));
    $this->assertDatabaseHas('mp_products', [
        'id' => $mp_product->id,
        'code_article' => 'MP-UPDATED',
        'nom' => 'Updated Name',
    ]);
});

it('allows admin to delete empty mp product', function () {
    $mp_product = MpProduct::create([
        'code_article' => 'MP-DEL',
        'editeur' => 'Bookland',
        'nom' => 'To Delete',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('mp-products.destroy', $mp_product));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('mp_products', ['id' => $mp_product->id]);
});
