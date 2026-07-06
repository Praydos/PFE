<?php

use App\Models\Adoption;
use App\Models\AnneeScolaire;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Product;
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
        'name' => 'Adoption Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => User::factory()->rbo()->create()->id,
    ]);

    $this->compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole Adoption',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Addr',
        'status' => 'actif',
    ]);

    $this->annee = AnneeScolaire::factory()->create(['is_active' => true, 'is_closed' => false]);
    $this->contact = Contact::create(['nom' => 'Directeur', 'ville_id' => $this->ville->id]);
    $this->product = Product::create([
        'source' => 'bookland',
        'titre' => 'Methode Anglais',
        'type' => 'Livre',
        'isbn_13' => '9781231231231',
    ]);
});

it('prevents guests from accessing adoptions index', function () {
    $this->get(route('adoptions.index'))->assertRedirect(route('login'));
});

it('allows admin to view adoptions index', function () {
    Adoption::create([
        'compte_id' => $this->compte->id,
        'product_id' => $this->product->id,
        'contact_id' => $this->contact->id,
        'methode' => 'Methode Directe',
        'annee_scolaire_id' => $this->annee->id,
        'quantity' => 10,
        'date_adoption' => now()->toDateString(),
        'delegate_id' => $this->delegue->id,
        'niveau' => 'CP',
        'cycle' => 'primaire',
        'type_adoption' => 'BOOKLAND',
    ]);

    $this->actingAs($this->admin)
        ->get(route('adoptions.index'))
        ->assertStatus(200)
        ->assertSee('Methode Anglais');
});

it('allows delegue to view create adoption page', function () {
    $this->actingAs($this->delegue)
        ->get(route('adoptions.create'))
        ->assertStatus(200);
});

it('allows delegue to store a new manual adoption', function () {
    $response = $this->actingAs($this->delegue)
        ->post(route('adoptions.store'), [
            'compte_id' => $this->compte->id,
            'contact_id' => $this->contact->id,
            'methode' => 'Methode Interactive',
            'annee_scolaire_id' => $this->annee->id,
            'date_adoption' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'niveau' => 'CE1',
                    'cycle' => 'primaire',
                    'quantity' => 20,
                    'type_adoption' => 'BOOKLAND',
                    'isbn' => null,
                    'sous_categorie' => null,
                ]
            ],
        ]);

    $response->assertRedirect(route('adoptions.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('adoptions', [
        'compte_id' => $this->compte->id,
        'product_id' => $this->product->id,
        'niveau' => 'CE1',
        'type_adoption' => 'BOOKLAND',
    ]);
});

it('fails to store duplicate adoption for same year and product', function () {
    Adoption::create([
        'compte_id' => $this->compte->id,
        'product_id' => $this->product->id,
        'contact_id' => $this->contact->id,
        'methode' => 'Existante',
        'annee_scolaire_id' => $this->annee->id,
        'quantity' => 10,
        'date_adoption' => now()->toDateString(),
        'delegate_id' => $this->delegue->id,
        'niveau' => 'CP',
        'cycle' => 'primaire',
        'type_adoption' => 'BOOKLAND',
    ]);

    $response = $this->actingAs($this->delegue)
        ->post(route('adoptions.store'), [
            'compte_id' => $this->compte->id,
            'contact_id' => $this->contact->id,
            'methode' => 'Dupliquée',
            'annee_scolaire_id' => $this->annee->id,
            'date_adoption' => now()->toDateString(),
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'niveau' => 'CP',
                    'cycle' => 'primaire',
                    'quantity' => 30,
                    'type_adoption' => 'BOOKLAND',
                    'isbn' => null,
                    'sous_categorie' => null,
                ]
            ],
        ]);

    $response->assertSessionHasErrors(['products']);
});

it('allows delegue to delete adoption', function () {
    $adoption = Adoption::create([
        'compte_id' => $this->compte->id,
        'product_id' => $this->product->id,
        'contact_id' => $this->contact->id,
        'methode' => 'Existante',
        'annee_scolaire_id' => $this->annee->id,
        'quantity' => 10,
        'date_adoption' => now()->toDateString(),
        'delegate_id' => $this->delegue->id,
        'niveau' => 'CP',
        'cycle' => 'primaire',
        'type_adoption' => 'BOOKLAND',
    ]);

    $response = $this->actingAs($this->delegue)
        ->delete(route('adoptions.destroy', $adoption));

    $response->assertRedirect(route('adoptions.index'));
    $this->assertDatabaseMissing('adoptions', ['id' => $adoption->id]);
});
