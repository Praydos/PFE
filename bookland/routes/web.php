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
use Illuminate\Support\Facades\Route;


// Breeze auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    Route::get('/', fn () => redirect()->route('comptes.index'));
    

    // ── Shared: all three authenticated roles ──────────────────────────────
    // Declared BEFORE the users resource so "roles" is not swallowed by {user}.

    Route::get('/users/roles', [UserController::class, 'roles'])
        ->name('users.roles')
        ->middleware('role:admin,rbo,delegue');

    Route::resource('comptes', CompteController::class)
        ->middleware('role:admin,rbo,delegue');
    
    Route::resource('products', ProductController::class)
        ->middleware('role:admin,rbo,delegue');

    Route::get('/users/{user}/assigned-zones',[UserController::class, 'getAssignedZones'])
    ->name('users.assigned-zones')
    ->middleware('role:admin,rbo,delegue');

    Route::get('/consignations', [ConsignationController::class, 'index'])
    ->name('consignations.index')->middleware('role:admin,rbo,delegue');
    Route::get('/consignations/{consignation}/create-bss', [ConsignationController::class, 'createBss'])
    ->name('consignations.create-bss')->middleware('role:admin,delegue');




    // ── Admin only ─────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        // Core resources
        Route::resource('villes',    VilleController::class);
        Route::resource('zones',     ZoneController::class);
        Route::resource('quartiers', QuartierController::class);
        Route::resource('users',     UserController::class);

        // Ville ↔ Zone assignment
        Route::post('/villes/{ville}/assign-zone',
            [VilleController::class, 'assignZone'])->name('villes.assignZone');

        // Zone ↔ Delegate detachment
        Route::post('/zones/{zone}/detach-delegate/{delegate}',
            [ZoneController::class, 'detachDelegate'])->name('zones.detachDelegate');

        // User ↔ Zone assignments (AJAX)
        Route::get('/users/{user}/zones',
            [UserController::class, 'getZones'])->name('users.zones.get');
        Route::post('/users/{user}/zones',
            [UserController::class, 'updateZones'])->name('users.zones.update');
        

        // RBO ↔ Ville assignments (AJAX)
        Route::get('/users/{user}/villes',
            [UserController::class, 'getVilles'])->name('users.villes');
        Route::post('/users/{user}/villes',
            [UserController::class, 'updateVilles']);

        // Delegué ↔ Compte assignments (AJAX — admin only, RBOs cannot self-assign)
        Route::get('/users/{user}/comptes',
            [UserController::class, 'getComptes'])->name('users.comptes');
        Route::post('/users/{user}/comptes',
            [UserController::class, 'updateComptes']);



        // annees scolaires routes (crud)
        Route::resource('annees-scolaires', AnneScolaireController::class);
        Route::post('/annees-scolaires/{annees_scolaire}/set-active', [AnneScolaireController::class, 'setActive'])->name('annees-scolaires.set-active');
        Route::post('/annees-scolaires/{annees_scolaire}/close', [AnneScolaireController::class, 'close'])->name('annees-scolaires.close');

        //consignation routes for admin only
        Route::get('/consignations/create', [ConsignationController::class, 'create'])->name('consignations.create');
        Route::post('/consignations', [ConsignationController::class, 'store'])->name('consignations.store');
        Route::get('/consignations/{consignation}/edit', [ConsignationController::class, 'edit'])->name('consignations.edit');
        Route::put('/consignations/{consignation}', [ConsignationController::class, 'update'])->name('consignations.update');
        Route::delete('/consignations/{consignation}', [ConsignationController::class, 'destroy'])->name('consignations.destroy');

    });
});


Route::get('/users/{user}/assigned-comptes', [UserController::class, 'getAssignedComptes'])->name('users.assigned-comptes');
// contact routes to authorize later
Route::resource('contacts', ContactController::class);

Route::get('/contacts/{contact}/comptes', [ContactController::class, 'getComptes'])->name('contacts.comptes.get');
Route::post('/contacts/{contact}/comptes', [ContactController::class, 'updateComptes'])->name('contacts.comptes.update');


// // bss routes (to be authorized and updated later)
//     Route::resource('bss', BssController::class);
//     Route::post('/bss/{bss}/validate', [BssController::class, 'validateBss'])->name('bss.validate');
//     Route::post('/bss/{bss}/delivered', [BssController::class, 'markDelivered'])->name('bss.delivered');
//     Route::post('/bss/{bss}/control', [BssController::class, 'updateControl'])->name('bss.control');
//     Route::post('/bss/{bss}/feedback', [BssController::class, 'updateFeedback'])->name('bss.feedback');

//     // Returns (separate controller)
//     Route::get('/bss/{bss}/retour/create', [RetourController::class, 'create'])->name('retours.create');
//     Route::post('/bss/{bss}/retour', [RetourController::class, 'store'])->name('retours.store');


//     // API for dynamic contact loading (used in BSS create)
//     Route::middleware(['auth'])->get('/api/comptes/{compte}/contacts', function ($compteId) {
//         $compte = App\Models\Compte::findOrFail($compteId);
//         return response()->json($compte->contacts);
//     });

// Route::resource('consignations', ConsignationController::class);
// Route::resource('consignations/index', ConsignationController::class);
// Route::get('/consignations/{consignation}/create-bss', [ConsignationController::class, 'createBss'])->name('consignations.create-bss');



///






Route::middleware(['auth'])->group(function () {

    Route::get('/bss', [BssController::class, 'index'])->name('bss.index');

    // CREATE MUST BE BEFORE {bss}
    Route::get('/bss/create', [BssController::class, 'create'])->name('bss.create');
    Route::post('/bss', [BssController::class, 'store'])->name('bss.store');

    Route::get('/bss/{bss}', [BssController::class, 'show'])->name('bss.show');

    Route::get('/bss/{bss}/edit', [BssController::class, 'edit'])->name('bss.edit');
    Route::put('/bss/{bss}', [BssController::class, 'update'])->name('bss.update');

    Route::post('/bss/{bss}/validate', [BssController::class, 'validateBss'])->name('bss.validate');

    Route::delete('/bss/{bss}', [BssController::class, 'destroy'])
        ->name('bss.destroy')
        ->middleware('role:admin');
});
Route::get('/api/comptes/{compte}/contacts', function (App\Models\Compte $compte) {
    return $compte->contacts;
})->name('api.compte.contacts');


Route::get('/bss/{bss}/retour/create', [RetourController::class, 'create'])->name('retours.create');
Route::post('/bss/{bss}/retour', [RetourController::class, 'store'])->name('retours.store');
Route::get('/retours', [RetourController::class, 'index'])->name('retours.index');