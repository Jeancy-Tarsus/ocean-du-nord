<?php

use App\Http\Controllers\AgenceController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});



Route::middleware(['auth', 'role:admin,chef_agence'])->group(function () {

    Route::resource('agences', AgenceController::class)
        ->except(['create', 'edit', 'show']);

});


Route::middleware(['auth', 'role:admin,chef_parc'])->group(function () {

    Route::resource('bus', BusController::class)
        ->except(['create', 'edit', 'show']);

});

Route::middleware(['auth', 'role:admin,chef_parc'])->group(function () {

    Route::resource('chauffeurs', ChauffeurController::class)
        ->except(['create', 'edit', 'show']);

});

Route::middleware(['auth', 'role:admin,directeur_exploitation'])->group(function () {

    Route::resource('lignes', LigneController::class)
        ->except(['create', 'edit', 'show']);

});

Route::middleware(['auth'])->group(function () {

    Route::resource('equipes', EquipeController::class)
        ->except(['create', 'edit', 'show']);

});
