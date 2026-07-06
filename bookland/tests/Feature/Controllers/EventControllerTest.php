<?php

use App\Models\Event;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use App\Models\AnneeScolaire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->ville = Ville::factory()->create();

    $this->zone = Zone::forceCreate([
        'name' => 'Event Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => collect([User::factory()->rbo()->create()->id])->first(),
    ]);

    $this->delegue->zones()->attach($this->zone->id);

    $this->annee = AnneeScolaire::factory()->create(['is_active' => true, 'is_closed' => false]);
});

it('prevents guests from accessing events index', function () {
    $this->get(route('events.index'))->assertRedirect(route('login'));
});

it('allows delegue to view events index', function () {
    Event::create([
        'type' => 'Workshop',
        'editeur' => 'Bookland',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'annee_scolaire_id' => $this->annee->id,
        'date_event' => now()->toDateString(),
    ]);

    $this->actingAs($this->delegue)
        ->get(route('events.index'))
        ->assertStatus(200)
        ->assertSee('Workshop');
});

it('allows delegue to store a new event', function () {
    $response = $this->actingAs($this->delegue)
        ->post(route('events.store'), [
            'ville_id' => $this->ville->id,
            'type' => 'Workshop',
            'editeur' => 'Bookland',
            'date_event' => now()->toDateString(),
            'annee_scolaire_id' => $this->annee->id,
        ]);

    $event = Event::first();
    $response->assertRedirect(route('events.invite', $event));
    $this->assertDatabaseHas('events', [
        'type' => 'Workshop',
        'delegue_id' => $this->delegue->id
    ]);
});

it('allows delegue to invite contacts to event', function () {
    $event = Event::create([
        'type' => 'Workshop',
        'editeur' => 'Bookland',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'annee_scolaire_id' => $this->annee->id,
        'date_event' => now()->toDateString(),
    ]);

    $contact = Contact::create(['nom' => 'Nom1', 'ville_id' => $this->ville->id]);

    $response = $this->actingAs($this->delegue)
        ->post(route('events.store-invitations', $event), [
            'contact_ids' => [$contact->id]
        ]);

    $response->assertRedirect(route('events.show', $event));
    $this->assertDatabaseHas('event_contact', [
        'event_id' => $event->id,
        'contact_id' => $contact->id,
        'statut' => 'invite'
    ]);
});

it('allows admin to delete event', function () {
    $event = Event::create([
        'type' => 'Workshop',
        'editeur' => 'Bookland',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'annee_scolaire_id' => $this->annee->id,
        'date_event' => now()->toDateString(),
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('events.destroy', $event));

    $response->assertRedirect(route('events.index'));
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});
