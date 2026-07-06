<?php

use App\Models\Bss;
use App\Models\Compte;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Consignation;
use App\Models\AnneeScolaire;
use App\Models\User;
use App\Models\Ville;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
    $this->ville = Ville::factory()->create();

    $this->zone = Zone::forceCreate([
        'name' => 'BSS Zone',
        'ville_id' => $this->ville->id,
        'rbo_id' => collect([User::factory()->rbo()->create()->id])->first(),
    ]);

    $this->compte = Compte::forceCreate([
        'type' => 'ecole',
        'etablissement' => 'Ecole BSS',
        'ville_id' => $this->ville->id,
        'zone_id' => $this->zone->id,
        'delegue_id' => $this->delegue->id,
        'adresse' => 'Addr',
        'status' => 'actif',
    ]);

    $this->contact = Contact::create(['nom' => 'Contact BSS', 'ville_id' => $this->ville->id]);
    $this->product = Product::factory()->create(['titre' => 'Livre Test']);
    $this->annee = AnneeScolaire::factory()->create(['is_active' => true, 'is_closed' => false]);
});

it('prevents guests from accessing bss index', function () {
    $this->get(route('bss.index'))->assertRedirect(route('login'));
});

it('allows delegue to view bss create page', function () {
    $this->actingAs($this->delegue)
        ->get(route('bss.create'))
        ->assertStatus(200);
});

it('allows delegue to store a new bss', function () {
    $response = $this->actingAs($this->delegue)
        ->post(route('bss.store'), [
            'compte_id' => $this->compte->id,
            'contact_id' => $this->contact->id,
            'recupere_par_type' => 'contact',
            'recupere_par_nom_contact' => 'John Doe',
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                ]
            ],
        ]);

    $response->assertRedirect(route('bss.index'));
    $this->assertDatabaseHas('bsses', [
        'compte_id' => $this->compte->id,
        'delegate_id' => $this->delegue->id,
    ]);
});

it('consumes consignation correctly when storing bss', function () {
    Consignation::create([
        'delegate_id' => $this->delegue->id,
        'product_id' => $this->product->id,
        'annee_scolaire_id' => $this->annee->id,
        'quantity' => 5,
    ]);

    $response = $this->actingAs($this->delegue)
        ->post(route('bss.store'), [
            'compte_id' => $this->compte->id,
            'contact_id' => $this->contact->id,
            'recupere_par_type' => 'contact',
            'recupere_par_nom_contact' => 'John Doe',
            'products' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                ]
            ],
        ]);

    $response->assertRedirect(route('bss.index'));

    // Check consignation decreased to 3
    $this->assertDatabaseHas('consignations', [
        'delegate_id' => $this->delegue->id,
        'product_id' => $this->product->id,
        'quantity' => 3,
    ]);
});

it('allows admin to delete bss', function () {
    $bss = Bss::create([
        'numero' => 'BSS-TEST-1',
        'compte_id' => $this->compte->id,
        'contact_id' => $this->contact->id,
        'delegate_id' => $this->delegue->id,
        'annee_scolaire_id' => $this->annee->id,
        'date_bss' => now()->toDateString(),
        'recupere_par_type' => 'contact',
        'recupere_par_nom' => 'Test',
        'statut' => 'valide',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('bss.destroy', $bss));

    $response->assertRedirect(route('bss.index'));
    $this->assertDatabaseMissing('bsses', ['id' => $bss->id]);
});
