<?php

use App\Http\Controllers\AgenceController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoyageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| PAGE D'ACCUEIL
|--------------------------------------------------------------------------
|
| Si l'utilisateur est déjà connecté :
|       / → dashboard
|
| Sinon :
|       / → login
|
*/

Route::get('/', function () {

    if (auth()->check()) {

        return redirect()->route('dashboard');

    }

    return redirect()->route('login');

});



/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->middleware('auth')
  ->name('dashboard');



/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
|
| Les routes login, logout, register, mot de passe oublié,
| etc. sont gérées par Breeze dans routes/auth.php.
|
*/

require __DIR__.'/auth.php';



/*
|--------------------------------------------------------------------------
| PROFIL UTILISATEUR
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});



/*
|--------------------------------------------------------------------------
| UTILISATEURS
|--------------------------------------------------------------------------
|
| Réservé à l'administrateur.
|
*/

Route::middleware([
    'auth',
    'role:admin'
])->group(function () {

    Route::resource(
        'users',
        UserController::class
    );

});



/*
|--------------------------------------------------------------------------
| AGENCES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,chef_agence'
])->group(function () {

    Route::resource(
        'agences',
        AgenceController::class
    )->except([
        'create',
        'edit',
        'show'
    ]);

});



/*
|--------------------------------------------------------------------------
| BUS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,chef_parc'
])->group(function () {

    Route::resource(
        'bus',
        BusController::class
    )->except([
        'create',
        'edit',
        'show'
    ]);

});



/*
|--------------------------------------------------------------------------
| CHAUFFEURS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,chef_parc'
])->group(function () {

    Route::resource(
        'chauffeurs',
        ChauffeurController::class
    )->except([
        'create',
        'edit',
        'show'
    ]);

});



/*
|--------------------------------------------------------------------------
| LIGNES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,directeur_exploitation'
])->group(function () {

    Route::resource(
        'lignes',
        LigneController::class
    )->except([
        'create',
        'edit',
        'show'
    ]);

});



/*
|--------------------------------------------------------------------------
| ÉQUIPES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::resource(
        'equipes',
        EquipeController::class
    )->except([
        'create',
        'edit',
        'show'
    ]);

});



/*
|--------------------------------------------------------------------------
| VOYAGES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::resource(
        'voyages',
        VoyageController::class
    )->except([
        'create',
        'show',
        'edit'
    ]);


    /*
    | Démarrer un voyage
    */

    Route::patch(
        '/voyages/{voyage}/demarrer',
        [VoyageController::class, 'start']
    )->name('voyages.start');


    /*
    | Terminer un voyage
    */

    Route::patch(
        '/voyages/{voyage}/terminer',
        [VoyageController::class, 'finish']
    )->name('voyages.finish');


    /*
    | Arrivée dans une agence
    */

    Route::patch(
        '/voyage-agences/{voyageAgence}/arrivee',
        [VoyageController::class, 'arrive']
    )->name('voyage-agences.arrivee');


    /*
    | Départ d'une agence
    */

    Route::patch(
        '/voyage-agences/{voyageAgence}/depart',
        [VoyageController::class, 'depart']
    )->name('voyage-agences.depart');

});



/*
|--------------------------------------------------------------------------
| PLANNING
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/planning',
        [PlanningController::class, 'index']
    )->name('planning.index');

});



/*
|--------------------------------------------------------------------------
| INCIDENTS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/incidents',
        [IncidentController::class, 'index']
    )->name('incidents.index');


    Route::post(
        '/incidents',
        [IncidentController::class, 'store']
    )->name('incidents.store');


    Route::get(
        '/incidents/{incident}',
        [IncidentController::class, 'show']
    )->name('incidents.show');


    Route::put(
        '/incidents/{incident}',
        [IncidentController::class, 'update']
    )->name('incidents.update');


    Route::delete(
        '/incidents/{incident}',
        [IncidentController::class, 'destroy']
    )->name('incidents.destroy');


    /*
    | Informations d'un voyage pour les incidents
    */

    Route::get(
        '/incidents/voyages/{voyage}/informations',
        [IncidentController::class, 'voyageInformations']
    )->name('incidents.voyage.informations');

});
