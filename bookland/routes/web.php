<?php

/**
 * ── Breeze setup (run once in terminal) ─────────────────────────────────────
 *
 *   composer require laravel/breeze --dev
 *   php artisan breeze:install blade   # or 'react' / 'vue'
 *   npm install && npm run build
 *   php artisan migrate
 *
 * Then add 'role' middleware alias (see RoleMiddleware.php for instructions).
 * ────────────────────────────────────────────────────────────────────────────
 *
 * Access matrix
 * ┌────────────────────────────┬───────┬─────┬─────────┐
 * │ Resource                   │ Admin │ RBO │ Délégué │
 * ├────────────────────────────┼───────┼─────┼─────────┤
 * │ Villes / Zones / Quartiers │  ✓    │  ✗  │   ✗     │
 * │ Users CRUD                 │  ✓    │  ✗  │   ✗     │
 * │ Users › roles page         │  ✓    │  ✓* │   ✓*    │
 * │ Zone / Compte assignments  │  ✓    │  ✗  │   ✗     │
 * │ Comptes CRUD               │  ✓    │  ✓* │   ✓*    │
 * └────────────────────────────┴───────┴─────┴─────────┘
 *  * scoped in the controller to their own data
 */

use App\Http\Controllers\VilleController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\QuartierController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AnneScolaireController;
use App\Http\Controllers\ConsignationController;
use App\Http\Controllers\BssController;
use App\Http\Controllers\RetourController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\EffectifController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DemandeSpecimenController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\ActionAmeliorationController;
use App\Http\Controllers\MpDeliveryController;
use App\Http\Controllers\MpProductController;

use Illuminate\Support\Facades\Route;


// Breeze auth routes (login, register, password reset, etc.)
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('comptes.index'));


    // ── Shared: all three authenticated roles ──────────────────────────────
    // Declared BEFORE the users resource so "roles" is not swallowed by {user}.

    Route::get('/users/roles', [UserController::class , 'roles'])
        ->name('users.roles')
        ->middleware('role:admin,rbo,delegue');


    //comptes and contacts routes
    Route::resource('comptes', CompteController::class)
        ->middleware('role:admin,rbo,delegue');

    Route::get('/users/{user}/assigned-comptes', [UserController::class , 'getAssignedComptes'])
        ->name('users.assigned-comptes');

    Route::get('/contacts/{contact}/comptes', [ContactController::class , 'getComptes'])
        ->name('contacts.comptes.get');

    Route::post('/contacts/{contact}/comptes', [ContactController::class , 'updateComptes'])
        ->name('contacts.comptes.update');






    // products index is visible to all, but create/edit/delete is admin only

    Route::get('/products', [ProductController::class , 'index'])
        ->name('products.index')->middleware('role:admin,rbo,delegue');


    Route::get('/users/{user}/assigned-zones', [UserController::class , 'getAssignedZones'])
        ->name('users.assigned-zones')
        ->middleware('role:admin,rbo,delegue');

    Route::get('/consignations', [ConsignationController::class , 'index'])
        ->name('consignations.index')->middleware('role:admin,rbo,delegue');
    Route::get('/consignations/{consignation}/create-bss', [ConsignationController::class , 'createBss'])
        ->name('consignations.create-bss')->middleware('role:admin,delegue');










    // ── Admin only ─────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

            // Core resources
            Route::resource('villes', VilleController::class);
            Route::resource('zones', ZoneController::class);
            Route::resource('quartiers', QuartierController::class);
            Route::resource('users', UserController::class);

            // Ville ↔ Zone assignment
            Route::post('/villes/{ville}/assign-zone',
            [VilleController::class , 'assignZone'])->name('villes.assignZone');

            //products routes for admin only
    
            Route::get('/products/create', [ProductController::class , 'create'])->name('products.create');
            Route::post('/products', [ProductController::class , 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class , 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class , 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class , 'destroy'])->name('products.destroy');

            Route::resource('mp-products', MpProductController::class)->except(['show']);


            // Zone ↔ Delegate detachment
            Route::post('/zones/{zone}/detach-delegate/{delegate}',
            [ZoneController::class , 'detachDelegate'])->name('zones.detachDelegate');

            // User ↔ Zone assignments (AJAX)
            Route::get('/users/{user}/zones',
            [UserController::class , 'getZones'])->name('users.zones.get');
            Route::post('/users/{user}/zones',
            [UserController::class , 'updateZones'])->name('users.zones.update');


            // RBO ↔ Ville assignments (AJAX)
            Route::get('/users/{user}/villes',
            [UserController::class , 'getVilles'])->name('users.villes');
            Route::post('/users/{user}/villes',
            [UserController::class , 'updateVilles']);

            // Delegué ↔ Compte assignments (AJAX — admin only, RBOs cannot self-assign)
            Route::get('/users/{user}/comptes',
            [UserController::class , 'getComptes'])->name('users.comptes');
            Route::post('/users/{user}/comptes',
            [UserController::class , 'updateComptes']);



            // annees scolaires routes (crud)
            Route::resource('annees-scolaires', AnneScolaireController::class);
            Route::post('/annees-scolaires/{annees_scolaire}/set-active', [AnneScolaireController::class , 'setActive'])->name('annees-scolaires.set-active');
            Route::post('/annees-scolaires/{annees_scolaire}/close', [AnneScolaireController::class , 'close'])->name('annees-scolaires.close');

            //consignation routes for admin only
            Route::get('/consignations/create', [ConsignationController::class , 'create'])->name('consignations.create');
            Route::post('/consignations', [ConsignationController::class , 'store'])->name('consignations.store');
            Route::get('/consignations/{consignation}/edit', [ConsignationController::class , 'edit'])->name('consignations.edit');
            Route::put('/consignations/{consignation}', [ConsignationController::class , 'update'])->name('consignations.update');
            Route::delete('/consignations/{consignation}', [ConsignationController::class , 'destroy'])->name('consignations.destroy');

        }
        );
        Route::get('/products/{product}', [ProductController::class , 'show'])
            ->name('products.show')->middleware('role:admin,rbo,delegue');

        // ── delegue and Admin ───────────────────────────────────────────────────
        Route::middleware('role:admin,delegue')->group(function () {

            /////---- Adoptions --------- ==========================================================================
            Route::get('/adoptions/create', [AdoptionController::class , 'create'])
                ->name('adoptions.create'); //delegue admin
    
            Route::post('/adoptions', [AdoptionController::class , 'store'])
                ->name('adoptions.store'); // admin delegue
    
            Route::get('/bss/{bss}/convert-adoption', [AdoptionController::class , 'convertFromBss'])
                ->name('adoptions.convert');
            Route::post('/bss/{bss}/convert-adoption', [AdoptionController::class , 'storeFromBss'])
                ->name('adoptions.store-convert');



        }
        );

        //adoptions authenticatoed routes for all three roles, but create/store is only for delegue and admin, and show is for all three
        Route::resource('adoptions', AdoptionController::class)
            ->except(['create', 'store']); // delegue admin rbo
    
        Route::get('/adoptions/{adoption}/show', [AdoptionController::class , 'show'])
            ->name('adoptions.show'); // delegue admin rbo      });




});

// contact routes to authorize later
Route::resource('contacts', ContactController::class);


Route::get('/api/comptes/{compte}/contacts', function (App\Models\Compte $compte) {
    return $compte->contacts;
})->name('api.compte.contacts');






// Specimen and BSS routes ============================================================================

Route::middleware(['auth'])->group(function () {

    Route::get('/bss', [BssController::class , 'index'])->name('bss.index');

    // CREATE MUST BE BEFORE {bss}
    Route::get('/bss/create', [BssController::class , 'create'])
        ->name('bss.create')->middleware('role:admin,delegue');

    Route::post('/bss', [BssController::class , 'store'])
        ->name('bss.store')->middleware('role:admin,delegue');

    Route::get('/bss/{bss}', [BssController::class , 'show'])
        ->name('bss.show');

    Route::get('/bss/{bss}/edit', [BssController::class , 'edit'])
        ->name('bss.edit');

    Route::put('/bss/{bss}', [BssController::class , 'update'])
        ->name('bss.update');

    // Route::post('/bss/{bss}/validate', [BssController::class, 'validateBss'])
    // ->name('bss.validate')->middleware('role:rbo,admin');

    Route::delete('/bss/{bss}', [BssController::class , 'destroy'])
        ->name('bss.destroy')
        ->middleware('role:admin');


    Route::get('/retours', [RetourController::class , 'index'])
        ->name('retours.index')->middleware('role:admin,rbo,delegue');

    Route::get('/bss/{bss}/retour/create', [RetourController::class , 'create'])
        ->name('retours.create')->middleware('role:admin,delegue'); // delegue admin

    Route::post('/bss/{bss}/retour', [RetourController::class , 'store'])
        ->name('retours.store')->middleware('role:admin,delegue'); // delegue admin

    Route::resource('mp-deliveries', MpDeliveryController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

// In routes/web.php


});



// =======================================================================================================



Route::resource('effectifs', EffectifController::class);

Route::post('/effectifs/{effectif}/validate', [EffectifController::class , 'validateRow'])    ->name('effectifs.validate'); // admin and rbo only, delegue cannot validate
Route::post('/effectifs/{effectif}/devalidate', [EffectifController::class , 'devalidateRow'])    ->name('effectifs.devalidate'); // admin and rbo only, delegue cannot devalidate

Route::get('/api/comptes/{compte}/effectif', [AdoptionController::class , 'getEffectifByNiveau'])    ->name('api.compte.effectif'); // returns effectif grouped by niveau for a given compte, used in the adoptions create form to show current effectif before creating an adoption

Route::get('/api/comptes/{compte}/niveaux', [AdoptionController::class , 'getNiveauxByCompte'])->name('api.compte.niveaux');


//========================================================================================================

// examens 

Route::resource('examens', ExamenController::class); // fix the order of routes in the controller to avoid conflicts with {examen} swallowing
Route::post('/examens/{examen}/change-status', [ExamenController::class , 'changeStatus'])->name('examens.change-status');


Route::resource('formations', FormationController::class);
Route::post('/formations/{formation}/change-status', [FormationController::class , 'changeStatus'])->name('formations.change-status');



//=========================================================================================================
//evenments routes Route::resource('events', EventController::class);
Route::resource('events', EventController::class);
Route::get('/events/{event}/invite', [EventController::class , 'inviteForm'])->name('events.invite');
Route::post('/events/{event}/invite', [EventController::class , 'storeInvitations'])->name('events.store-invitations');
Route::get('/events/{event}/statistics', [EventController::class , 'statistics'])->name('events.statistics');
Route::post('/events/{event}/contact/{contact}/status', [EventController::class , 'updateStatus'])->name('events.update-status');
Route::get('/api/events/contacts-by-city', [EventController::class , 'getContactsByCity'])->name('api.events.contacts-by-city');
Route::get('/api/events/all-contacts', [EventController::class , 'getAllContacts'])->name('api.events.all-contacts');



// RBO / Admin: create on behalf of a delegate — MUST be before resource() to avoid wildcard clash
Route::get('/actions/for-delegate/{delegate}',  [ActionController::class, 'createForDelegate'])->name('actions.createForDelegate')->middleware('auth', 'role:admin,rbo');
Route::post('/actions/for-delegate/{delegate}', [ActionController::class, 'storeForDelegate'])->name('actions.storeForDelegate')->middleware('auth', 'role:admin,rbo');

Route::get('/bss/for-delegate/{delegate}',  [BssController::class, 'createForDelegate'])->name('bss.createForDelegate')->middleware('auth', 'role:admin,rbo');
Route::post('/bss/for-delegate/{delegate}', [BssController::class, 'storeForDelegate'])->name('bss.storeForDelegate')->middleware('auth', 'role:admin,rbo');

Route::get('/examens/for-delegate/{delegate}',  [ExamenController::class, 'createForDelegate'])->name('examens.createForDelegate')->middleware('auth', 'role:admin,rbo');
Route::post('/examens/for-delegate/{delegate}', [ExamenController::class, 'storeForDelegate'])->name('examens.storeForDelegate')->middleware('auth', 'role:admin,rbo');

Route::get('/formations/for-delegate/{delegate}',  [FormationController::class, 'createForDelegate'])->name('formations.createForDelegate')->middleware('auth', 'role:admin,rbo');
Route::post('/formations/for-delegate/{delegate}', [FormationController::class, 'storeForDelegate'])->name('formations.storeForDelegate')->middleware('auth', 'role:admin,rbo');

Route::get('/events/for-delegate/{delegate}',  [EventController::class, 'createForDelegate'])->name('events.createForDelegate')->middleware('auth', 'role:admin,rbo');
Route::post('/events/for-delegate/{delegate}', [EventController::class, 'storeForDelegate'])->name('events.storeForDelegate')->middleware('auth', 'role:admin,rbo');

Route::resource('actions', ActionController::class);
Route::post('/actions/{action}/realiser', [ActionController::class, 'realiser'])->name('actions.realiser');
Route::post('/actions/{action}/valider', [ActionController::class, 'valider'])->name('actions.valider');
Route::post('/actions/{action}/devalider', [ActionController::class, 'devalider'])->name('actions.devalider');
Route::post('/actions/{action}/annuler', [ActionController::class, 'annuler'])->name('actions.annuler');
Route::post('/actions/{action}/reporter', [ActionController::class, 'reporter'])->name('actions.reporter');
Route::get('/api/action-types-by-categorie', [ActionController::class, 'getActionTypesByCategorie'])->name('api.action-types');
Route::get('/api/moyens-by-action-type', [ActionController::class, 'getMoyensByActionType'])->name('api.moyens');


// Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');

//=========================================================================================================
// requetes special 
Route::resource('demandes-specimens', DemandeSpecimenController::class);
Route::post('/demandes-specimens/{demandes_specimen}/validate', [DemandeSpecimenController::class , 'validateRequest'])->name('demandes-specimens.validate');


Route::get('/api/comptes/{compte}/details', function (App\Models\Compte $compte) {
    return response()->json([
    'ville_id' => $compte->ville_id,
    'ville_nom' => $compte->ville->nom,
    'zone_id' => $compte->zone_id,
    'zone_name' => $compte->zone->name,
    ]);
})->name('api.compte.details');

Route::get('/api/villes/{ville}/zones', function (App\Models\Ville $ville) {
    return $ville->zones;
})->name('api.ville.zones');

Route::get('/api/villes/{ville}/rbos', function (App\Models\Ville $ville) {
    return $ville->rbos()->get(['users.id', 'prenom', 'nom', 'email']);
})->name('api.ville.rbos');


//=========================================================================================================

Route::get('/agenda', [AgendaController::class , 'index'])->name('agenda.index');
Route::get('/agenda/events', [AgendaController::class , 'events'])->name('agenda.events');
Route::patch('/agenda/event/{type}/{id}/reschedule', [AgendaController::class , 'rescheduleEvent'])->name('agenda.reschedule');
Route::resource('vacations', VacationController::class)->only(['index', 'store', 'update', 'destroy'])    ->middleware('role:admin');

//=========================================================================================================
//taches routes
// List
Route::get('/taches', [TacheController::class , 'index'])->name('taches.index');

// Create
Route::get('/taches/create', [TacheController::class, 'create'])->name('taches.create');
Route::post('/taches', [TacheController::class, 'store'])->name('taches.store');

Route::get('/taches/for-delegate/{delegate}',  [TacheController::class, 'createForDelegate'])->name('taches.createForDelegate')->middleware('auth', 'role:admin,rbo');
Route::post('/taches/for-delegate/{delegate}', [TacheController::class, 'storeForDelegate'])->name('taches.storeForDelegate')->middleware('auth', 'role:admin,rbo');

// Show (IMPORTANT ONE)
Route::get('/taches/{tache}', [TacheController::class , 'show'])->name('taches.show');

// Edit
Route::get('/taches/{tache}/edit', [TacheController::class , 'edit'])->name('taches.edit');
Route::put('/taches/{tache}', [TacheController::class , 'update'])->name('taches.update');

// Delete
Route::delete('/taches/{tache}', [TacheController::class , 'destroy'])->name('taches.destroy');

// Custom action
Route::post('/taches/{tache}/validate', [TacheController::class , 'validateTache'])
    ->name('taches.validate');

Route::post('taches/{tache}/cancel-recurrence', [TacheController::class , 'cancelRecurrence'])
    ->name('taches.cancelRecurrence');


//=========================================================================================================

//actions amelioration routes
Route::resource('actions-amelioration', ActionAmeliorationController::class);
Route::get('/actions-amelioration/{actions_amelioration}/edit-suivi', [ActionAmeliorationController::class , 'editSuivi'])->name('actions-amelioration.edit-suivi');
Route::put('/actions-amelioration/{actions_amelioration}/suivi', [ActionAmeliorationController::class , 'updateSuivi'])->name('actions-amelioration.update-suivi');
Route::get('/actions-amelioration/{actions_amelioration}/edit-efficacite', [ActionAmeliorationController::class , 'editEfficacite'])->name('actions-amelioration.edit-efficacite');
Route::put('/actions-amelioration/{actions_amelioration}/efficacite', [ActionAmeliorationController::class , 'updateEfficacite'])->name('actions-amelioration.update-efficacite');
