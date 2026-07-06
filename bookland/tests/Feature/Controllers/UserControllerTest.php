<?php

use App\Models\User;
use App\Models\Ville;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
});

it('prevents guests from accessing user index', function () {
    $this->get(route('users.index'))->assertRedirect(route('login'));
});

it('prevents non-admins from accessing user index', function () {
    // This assumes there's a middleware redirecting or returning 403.
    // In Laravel tests, often 403 or 302 to a dashboard happens for unauthorized roles.
    $response = $this->actingAs($this->delegue)->get(route('users.index'));

    // We expect a forbidden or a redirect depending on how the middleware handles it
    expect(in_array($response->status(), [403, 302]))->toBeTrue();
});

it('allows admin to view users index', function () {
    $this->actingAs($this->admin)
        ->get(route('users.index'))
        ->assertStatus(200)
        ->assertViewIs('users.index')
        ->assertSee($this->admin->nom);
});

it('allows admin to view user create page', function () {
    $this->actingAs($this->admin)
        ->get(route('users.create'))
        ->assertStatus(200)
        ->assertViewIs('users.create');
});

it('allows admin to store a new user', function () {
    $ville = Ville::factory()->create();

    $userData = [
        'nom' => 'Test',
        'prenom' => 'User',
        'email' => 'testuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'delegue',
        'is_active' => true,
        'ville_id' => $ville->id,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('users.store'), $userData);

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'email' => 'testuser@example.com',
        'nom' => 'Test',
        'role' => 'delegue',
        'ville_id' => $ville->id,
    ]);
});

it('fails to store a user with invalid data', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('users.store'), [
            'nom' => '', // Invalid
            'email' => 'not-an-email',
            'role' => 'invalid-role',
        ]);

    $response->assertSessionHasErrors(['nom', 'email', 'role', 'password']);
});

it('allows admin to view user edit page', function () {
    $user = User::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('users.edit', $user))
        ->assertStatus(200)
        ->assertViewIs('users.edit')
        ->assertSee($user->nom);
});

it('allows admin to update user', function () {
    $user = User::factory()->create([
        'nom' => 'Old Name'
    ]);

    $updateData = [
        'nom' => 'New Name',
        'prenom' => $user->prenom,
        'email' => $user->email,
        'role' => 'rbo',
        'is_active' => true,
    ];

    $response = $this->actingAs($this->admin)
        ->put(route('users.update', $user), $updateData);

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'nom' => 'New Name',
        'role' => 'rbo',
    ]);
});

it('fails to update user with invalid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->admin)
        ->put(route('users.update', $user), [
            'nom' => '',
            'email' => 'not-an-email',
        ]);

    $response->assertSessionHasErrors(['nom', 'email', 'role']);
});

it('allows admin to delete a user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->admin)
        ->delete(route('users.destroy', $user));

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('prevents admin from deleting themselves', function () {
    $response = $this->actingAs($this->admin)
        ->delete(route('users.destroy', $this->admin));

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('users', [
        'id' => $this->admin->id,
    ]);
});
