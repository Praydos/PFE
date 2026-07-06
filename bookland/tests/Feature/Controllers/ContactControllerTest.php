<?php

use App\Models\Contact;
use App\Models\Ville;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->ville = Ville::factory()->create();
});

it('prevents guests from accessing contacts index', function () {
    $this->get(route('contacts.index'))->assertRedirect(route('login'));
});

it('allows admin to view contacts index', function () {
    Contact::create([
        'nom' => 'Contact_Test_Admin',
        'ville_id' => $this->ville->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('contacts.index'))
        ->assertStatus(200)
        ->assertSee('Contact_Test_Admin');
});

it('allows admin to view contact create page', function () {
    $this->actingAs($this->admin)
        ->get(route('contacts.create'))
        ->assertStatus(200);
});

it('allows admin to store a new contact', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('contacts.store'), [
            'nom' => 'Nouveau Contact',
            'prenom' => 'Jean',
            'email' => 'jean.contact@test.com',
            'ville_id' => $this->ville->id,
        ]);

    $response->assertRedirect(route('contacts.index'));

    $this->assertDatabaseHas('contacts', [
        'nom' => 'Nouveau Contact',
        'email' => 'jean.contact@test.com',
    ]);
});

it('fails to store contact with duplicate email', function () {
    Contact::create([
        'nom' => 'Existing Contact',
        'email' => 'dupe@example.com',
        'ville_id' => $this->ville->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('contacts.store'), [
            'nom' => 'New Contact',
            'email' => 'dupe@example.com',
            'ville_id' => $this->ville->id,
        ]);

    $response->assertSessionHasErrors(['email']);
});

it('allows admin to view contact edit page', function () {
    $contact = Contact::create([
        'nom' => 'To Edit',
        'ville_id' => $this->ville->id,
    ]);

    $this->actingAs($this->admin)
        ->get(route('contacts.edit', $contact))
        ->assertStatus(200)
        ->assertSee('To Edit');
});

it('allows admin to update contact', function () {
    $contact = Contact::create([
        'nom' => 'Old Contact',
        'ville_id' => $this->ville->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('contacts.update', $contact), [
            'nom' => 'Updated Contact',
            'ville_id' => $this->ville->id,
        ]);

    $response->assertRedirect(route('contacts.index'));
    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'nom' => 'Updated Contact',
    ]);
});

it('allows admin to delete empty contact', function () {
    $contact = Contact::create([
        'nom' => 'To Delete',
        'ville_id' => $this->ville->id,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('contacts.destroy', $contact));

    $response->assertRedirect(route('contacts.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
});
