<?php

use App\Http\Controllers\VilleController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuartierController;

Route::get('/', fn () => redirect()->route('comptes.index'));
Route::get('/users/roles', [UserController::class, 'roles'])->name('users.roles');

Route::resource('villes', VilleController::class);
Route::resource('zones', ZoneController::class);
Route::resource('users', UserController::class);
Route::resource('comptes', CompteController::class);
Route::resource('quartiers', QuartierController::class);



// fetch and zone asignemnt for delegue and rbo
Route::get('/users/{user}/zones', [UserController::class, 'getZones'])->name('users.zones.get');
Route::post('/users/{user}/zones', [UserController::class, 'updateZones'])->name('users.zones.update');

//fetch comptes for a given delegue 
Route::get('/users/{user}/comptes', [UserController::class, 'getComptes'])->name('users.comptes');
Route::post('/users/{user}/comptes', [UserController::class, 'updateComptes']);
