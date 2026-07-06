<?php

use App\Models\AnneeScolaire;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Effectif;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->ville = Ville::factory()->create();

    $this->zone = Zone::forceCreate([
        'name' => 'Effectif Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => User::factory()->rbo()->create()->id,
    ]);

    $this->compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole Effectif',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Addr',
        'status' => 'actif',
    ]);

    $this->annee = AnneeScolaire::factory()->create(['is_active' => true, 'is_closed' => false]);

    $this->contact = Contact::create([
        'nom' => 'Directeur',
        'ville_id' => $this->ville->id,
    ]);
});

it('prevents guests from accessing effectifs index', function () {
    $this->get(route('effectifs.index'))->assertRedirect(route('login'));
});

it('allows admin to view effectifs index', function () {
    Effectif::create([
        'compte_id' => $this->compte->id,
        'annee_scolaire_id' => $this->annee->id,
        'niveau' => 'CP',
        'cycle' => 'primaire',
        'massar' => 100,
        'source_1' => $this->contact->id,
        'nombre_classes_1' => 3,
        'nombre_classes_2' => 0,
        'nombre_classes_3' => 0,
    ]);

    $this->actingAs($this->admin)
        ->get(route('effectifs.index'))
        ->assertStatus(200)
        ->assertSee('Ecole Effectif');
});

it('allows admin to view create effectif page', function () {
    $this->actingAs($this->admin)
        ->get(route('effectifs.create'))
        ->assertStatus(200);
});

it('allows admin to store a new effectif', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('effectifs.store'), [
            'compte_id' => $this->compte->id,
            'annee_scolaire_id' => $this->annee->id,
            'niveau' => 'CE1',
            'cycle' => 'primaire',
            'massar' => 120,
            'source_1' => $this->contact->id,
            'nombre_classes_1' => 4,
            'nombre_classes_2' => 0,
            'nombre_classes_3' => 0,
        ]);

    $response->assertRedirect(route('effectifs.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('effectifs', [
        'compte_id' => $this->compte->id,
        'niveau' => 'CE1',
    ]);
});

it('fails to store duplicate effectif target', function () {
    Effectif::create([
        'compte_id' => $this->compte->id,
        'annee_scolaire_id' => $this->annee->id,
        'niveau' => 'CE2',
        'cycle' => 'primaire',
        'massar' => 100,
        'source_1' => $this->contact->id,
        'nombre_classes_1' => 3,
        'nombre_classes_2' => 0,
        'nombre_classes_3' => 0,
    ]);

    $response = $this->actingAs($this->admin)
        ->post(route('effectifs.store'), [
            'compte_id' => $this->compte->id,
            'annee_scolaire_id' => $this->annee->id,
            'niveau' => 'CE2', // Duplicate niveau
            'cycle' => 'primaire',
            'massar' => 50,
            'source_1' => $this->contact->id,
            'nombre_classes_1' => 2,
        ]);

    $response->assertSessionHasErrors(['niveau']);
});

it('allows admin to update effectif', function () {
    $effectif = Effectif::create([
        'compte_id' => $this->compte->id,
        'annee_scolaire_id' => $this->annee->id,
        'niveau' => '1er',
        'cycle' => 'Lycée',
        'massar' => 200,
        'source_1' => $this->contact->id,
        'nombre_classes_1' => 5,
        'nombre_classes_2' => 0,
        'nombre_classes_3' => 0,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('effectifs.update', $effectif), [
            'compte_id' => $this->compte->id,
            'annee_scolaire_id' => $this->annee->id,
            'niveau' => '1er',
            'cycle' => 'Lycée',
            'massar' => 250, // Updated
            'source_1' => $this->contact->id,
            'nombre_classes_1' => 6,
        ]);

    $response->assertRedirect(route('effectifs.index'));
    $this->assertDatabaseHas('effectifs', [
        'id' => $effectif->id,
        'massar' => 250,
    ]);
});

it('allows admin to delete effectif', function () {
    $effectif = Effectif::create([
        'compte_id' => $this->compte->id,
        'annee_scolaire_id' => $this->annee->id,
        'niveau' => '2ème',
        'cycle' => 'Lycée',
        'massar' => 40,
        'source_1' => $this->contact->id,
        'nombre_classes_1' => 1,
        'nombre_classes_2' => 0,
        'nombre_classes_3' => 0,
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('effectifs.destroy', $effectif));

    $response->assertRedirect(route('effectifs.index'));
    $this->assertDatabaseMissing('effectifs', ['id' => $effectif->id]);
});
