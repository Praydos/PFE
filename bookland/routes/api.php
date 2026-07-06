<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompteController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\BssController;
use App\Http\Controllers\Api\RetourController;
use App\Http\Controllers\Api\AdoptionController;
use App\Http\Controllers\Api\ExamenController;
use App\Http\Controllers\Api\FormationController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ActionController;
use App\Http\Controllers\Api\TacheController;
use App\Http\Controllers\Api\ActionAmeliorationController;
use App\Http\Controllers\Api\ReclamationController;
use App\Http\Controllers\Api\NonConformiteController;
use App\Http\Controllers\Api\DemandeSpecimenController;
use App\Http\Controllers\Api\MpDeliveryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;

// ── Public auth routes ─────────────────────────────────────────────────────
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// ── Protected routes ───────────────────────────────────────────────────────
Route::prefix('v1')->middleware('auth:sanctum')->name('api.v1.')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Comptes
    Route::apiResource('comptes', CompteController::class);

    // Contacts
    Route::get('/contacts/print-list', [ContactController::class, 'printList']);
    Route::apiResource('contacts', ContactController::class);
    Route::get('/contacts/{contact}/comptes', [ContactController::class, 'getComptes']);
    Route::post('/contacts/{contact}/comptes', [ContactController::class, 'updateComptes']);

    // Products (read-only for all, write for admin)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::middleware('role:admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });

    // BSS
    Route::get('/bss', [BssController::class, 'index']);
    Route::get('/bss/{bss}', [BssController::class, 'show']);
    Route::middleware('role:admin,delegue')->group(function () {
        Route::post('/bss', [BssController::class, 'store']);
    });
    Route::put('/bss/{bss}', [BssController::class, 'update']);
    Route::delete('/bss/{bss}', [BssController::class, 'destroy']);

    // Retours
    Route::get('/retours', [RetourController::class, 'index']);
    Route::middleware('role:admin,delegue')->group(function () {
        Route::post('/bss/{bss}/retour', [RetourController::class, 'store']);
    });

    // Adoptions
    Route::get('/adoptions', [AdoptionController::class, 'index']);
    Route::get('/adoptions/{adoption}', [AdoptionController::class, 'show']);
    Route::middleware('role:admin,delegue')->group(function () {
        Route::post('/adoptions', [AdoptionController::class, 'store']);
    });

    // Examens
    Route::apiResource('examens', ExamenController::class);
    Route::post('/examens/{examen}/change-status', [ExamenController::class, 'changeStatus']);

    // Formations
    Route::apiResource('formations', FormationController::class);
    Route::post('/formations/{formation}/change-status', [FormationController::class, 'changeStatus']);
    Route::post('/formations/{formation}/realiser', [FormationController::class, 'realiser']);
    Route::post('/formations/{formation}/devalider', [FormationController::class, 'devalider']);

    // Events
    Route::apiResource('events', EventController::class);
    Route::post('/events/{event}/invite', [EventController::class, 'storeInvitations']);
    Route::post('/events/{event}/contact/{contact}/status', [EventController::class, 'updateStatus']);

    // Actions (commercial)
    Route::apiResource('actions', ActionController::class);
    Route::post('/actions/{action}/realiser', [ActionController::class, 'realiser']);
    Route::post('/actions/{action}/valider', [ActionController::class, 'valider']);
    Route::post('/actions/{action}/devalider', [ActionController::class, 'devalider']);
    Route::post('/actions/{action}/annuler', [ActionController::class, 'annuler']);
    Route::post('/actions/{action}/reporter', [ActionController::class, 'reporter']);
    Route::get('/action-types-by-categorie', [ActionController::class, 'getActionTypesByCategorie']);
    Route::get('/moyens-by-action-type', [ActionController::class, 'getMoyensByActionType']);
    Route::get('/comptes/{compte}/mp-deliveries', [ActionController::class, 'mpDeliveriesForCompte']);

    // Tâches
    Route::apiResource('taches', TacheController::class);
    Route::post('/taches/{tache}/validate', [TacheController::class, 'validateTache']);
    Route::post('/taches/{tache}/cancel-recurrence', [TacheController::class, 'cancelRecurrence']);

    // Actions d'amélioration
    Route::apiResource('actions-amelioration', ActionAmeliorationController::class);
    Route::put('/actions-amelioration/{actions_amelioration}/suivi', [ActionAmeliorationController::class, 'updateSuivi']);
    Route::put('/actions-amelioration/{actions_amelioration}/efficacite', [ActionAmeliorationController::class, 'updateEfficacite']);

    // Réclamations
    Route::apiResource('reclamations', ReclamationController::class);

    // Non-conformités
    Route::apiResource('non-conformites', NonConformiteController::class);
    Route::put('/non-conformites/{non_conformite}/efficacite', [NonConformiteController::class, 'updateEfficacite']);

    // Demandes specimens
    Route::apiResource('demandes-specimens', DemandeSpecimenController::class);
    Route::post('/demandes-specimens/{demandes_specimen}/validate', [DemandeSpecimenController::class, 'validateRequest']);

    // MP Deliveries
    Route::apiResource('mp-deliveries', MpDeliveryController::class)->only(['index', 'show', 'store', 'destroy']);

    // Users (admin)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    });
    Route::get('/users/{user}/assigned-comptes', [UserController::class, 'getAssignedComptes']);
    Route::get('/users/{user}/assigned-zones', [UserController::class, 'getAssignedZones']);
});
