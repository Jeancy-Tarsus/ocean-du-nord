<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');


        /*
        |--------------------------------------------------------------------------
        | UTILISATEURS
        |--------------------------------------------------------------------------
        */

        $users = User::with('agence')

            ->when($search, function ($query, $search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')

                        ->orWhere(
                            'email',
                            'like',
                            '%' . $search . '%'
                        );

                });

            })

            ->latest()

            ->paginate(10)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | AGENCES ACTIVES
        |--------------------------------------------------------------------------
        |
        | Elles seront utilisées dans les modals de création
        | et de modification.
        |
        */

        $agences = Agence::where('active', true)

            ->orderBy('nom')

            ->orderBy('ville')

            ->get();


        return view(
            'users.index',
            compact(
                'users',
                'agences',
                'search'
            )
        );
    }


    /**
     * Afficher le formulaire de création.
     *
     * Le projet utilise actuellement des modals depuis index.blade.php.
     * Cette méthode reste disponible pour ne pas casser le resource controller.
     */
    public function create()
    {
        $agences = Agence::where('active', true)

            ->orderBy('nom')

            ->orderBy('ville')

            ->get();


        return view(
            'users.create',
            compact('agences')
        );
    }


    /**
     * Enregistrer un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],

            'role' => [
                'required',

                Rule::in([
                    'admin',
                    'directeur_exploitation',
                    'chef_parc',
                    'chef_agence',
                    'chauffeur',
                ]),
            ],

            'agence_id' => [
                'nullable',
                'integer',
                'exists:agences,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | RÈGLE MÉTIER : CHEF D'AGENCE
        |--------------------------------------------------------------------------
        |
        | Un chef d'agence doit obligatoirement être
        | rattaché à une agence active.
        |
        */

        if ($validated['role'] === 'chef_agence') {

            $request->validate([

                'agence_id' => [
                    'required',
                    'integer',

                    Rule::exists('agences', 'id')
                        ->where(function ($query) {

                            $query->where('active', true);

                        }),
                ],

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | AGENCE
        |--------------------------------------------------------------------------
        |
        | Seul le chef d'agence possède une agence.
        |
        */

        $agenceId = null;


        if ($validated['role'] === 'chef_agence') {

            $agenceId = $validated['agence_id'];

        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        User::create([

            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            'role' => $validated['role'],

            'agence_id' => $agenceId,

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()

            ->route('users.index')

            ->with(
                'success',
                'Utilisateur créé avec succès.'
            );
    }


    /**
     * Afficher le formulaire de modification.
     *
     * Les modifications se font actuellement
     * dans un modal depuis index.blade.php.
     */
    public function edit(User $user)
    {
        $agences = Agence::where('active', true)

            ->orderBy('nom')

            ->orderBy('ville')

            ->get();


        return view(
            'users.edit',
            compact(
                'user',
                'agences'
            )
        );
    }


    /**
     * Modifier un utilisateur.
     */
    public function update(
        Request $request,
        User $user
    ) {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [

                'required',

                'email',

                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),

            ],

            'role' => [

                'required',

                Rule::in([
                    'admin',
                    'directeur_exploitation',
                    'chef_parc',
                    'chef_agence',
                    'chauffeur',
                ]),

            ],

            'agence_id' => [

                'nullable',

                'integer',

                'exists:agences,id',

            ],

            'password' => [

                'nullable',

                'confirmed',

                'min:8',

            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | RÈGLE MÉTIER : CHEF D'AGENCE
        |--------------------------------------------------------------------------
        */

        if ($validated['role'] === 'chef_agence') {

            $request->validate([

                'agence_id' => [

                    'required',

                    'integer',

                    Rule::exists(
                        'agences',
                        'id'
                    )->where(function ($query) {

                        $query->where(
                            'active',
                            true
                        );

                    }),

                ],

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS DE BASE
        |--------------------------------------------------------------------------
        */

        $user->name =
            $validated['name'];


        $user->email =
            $validated['email'];


        $user->role =
            $validated['role'];


        /*
        |--------------------------------------------------------------------------
        | AGENCE
        |--------------------------------------------------------------------------
        |
        | Chef d'agence :
        |     agence obligatoire.
        |
        | Tous les autres :
        |     aucune agence.
        |
        */

        if (
            $validated['role']
            ===
            'chef_agence'
        ) {

            $user->agence_id =
                $validated['agence_id'];

        } else {

            $user->agence_id =
                null;

        }


        /*
        |--------------------------------------------------------------------------
        | MOT DE PASSE
        |--------------------------------------------------------------------------
        |
        | Si le champ est vide, on conserve
        | l'ancien mot de passe.
        |
        */

        if (
            !empty(
                $validated['password']
            )
        ) {

            $user->password =
                Hash::make(
                    $validated['password']
                );

        }


        /*
        |--------------------------------------------------------------------------
        | SAUVEGARDE
        |--------------------------------------------------------------------------
        */

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()

            ->route('users.index')

            ->with(
                'success',
                'Utilisateur modifié avec succès.'
            );
    }


    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | EMPÊCHER L'AUTO-SUPPRESSION
        |--------------------------------------------------------------------------
        */

        if (
            $user->id === Auth::id()
        ) {

            return back()->with(
                'error',
                'Vous ne pouvez pas supprimer votre propre compte.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION
        |--------------------------------------------------------------------------
        */

        $user->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECTION
        |--------------------------------------------------------------------------
        */

        return redirect()

            ->route('users.index')

            ->with(
                'success',
                'Utilisateur supprimé avec succès.'
            );
    }
}
