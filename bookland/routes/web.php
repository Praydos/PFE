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
    

    Route::get('/users/{user}/assigned-zones',[UserController::class, 'getAssignedZones'])
    ->name('users.assigned-zones')
    ->middleware('role:admin,rbo,delegue');

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
    });
});


Route::get('/users/{user}/assigned-comptes', [UserController::class, 'getAssignedComptes'])->name('users.assigned-comptes');

