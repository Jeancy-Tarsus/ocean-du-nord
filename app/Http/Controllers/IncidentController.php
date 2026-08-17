<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Bus;
use App\Models\Incident;
use App\Models\User;
use App\Models\Voyage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IncidentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Vérification des rôles
    |--------------------------------------------------------------------------
    */

    private function peutAcceder(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return in_array($user->role, [
            'admin',
            'directeur_exploitation',
            'chef_parc',
            'chef_agence',
            'chauffeur',
        ]);
    }


    private function estAdmin(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === 'admin';
    }


    private function estDirecteur(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === 'directeur_exploitation';
    }


    private function estChefParc(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === 'chef_parc';
    }


    private function estChefAgence(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === 'chef_agence';
    }


    private function estChauffeur(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === 'chauffeur';
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR CONNECTÉ
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté pour accéder aux incidents.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (!$this->peutAcceder()) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à accéder aux incidents.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRES
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');
        $type = $request->input('type');
        $gravite = $request->input('gravite');
        $statut = $request->input('statut');


        /*
        |--------------------------------------------------------------------------
        | INCIDENTS
        |--------------------------------------------------------------------------
        */

        $incidents = Incident::with([
            'voyage.ligne',
            'voyage.bus',
            'voyage.equipe.chauffeurTitulaire',
            'voyage.equipe.chauffeurSecondaire',
            'bus',
            'agence',
            'user',
        ])

            /*
            |--------------------------------------------------------------------------
            | CHEF D'AGENCE
            |--------------------------------------------------------------------------
            */

            ->when(
                $user->role === 'chef_agence',
                function ($query) use ($user) {

                    $query->where(
                        'agence_id',
                        $user->agence_id
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | CHAUFFEUR
            |--------------------------------------------------------------------------
            */

            ->when(
                $user->role === 'chauffeur',
                function ($query) use ($user) {

                    $query->where(
                        'user_id',
                        $user->id
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | RECHERCHE
            |--------------------------------------------------------------------------
            */

            ->when(
                $search,
                function ($query) use ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'reference',
                            'like',
                            '%' . $search . '%'
                        )

                            ->orWhere(
                                'titre',
                                'like',
                                '%' . $search . '%'
                            )

                            ->orWhere(
                                'description',
                                'like',
                                '%' . $search . '%'
                            );
                    });
                }
            )


            /*
            |--------------------------------------------------------------------------
            | TYPE
            |--------------------------------------------------------------------------
            */

            ->when(
                $type,
                function ($query) use ($type) {

                    $query->where(
                        'type',
                        $type
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | GRAVITÉ
            |--------------------------------------------------------------------------
            */

            ->when(
                $gravite,
                function ($query) use ($gravite) {

                    $query->where(
                        'gravite',
                        $gravite
                    );
                }
            )


            /*
            |--------------------------------------------------------------------------
            | STATUT
            |--------------------------------------------------------------------------
            */

            ->when(
                $statut,
                function ($query) use ($statut) {

                    $query->where(
                        'statut',
                        $statut
                    );
                }
            )

            ->latest()
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | VOYAGES DISPONIBLES POUR LE MODAL
        |--------------------------------------------------------------------------
        */

        $voyages = Voyage::with([
            'ligne',
            'bus',
            'equipe.chauffeurTitulaire',
            'equipe.chauffeurSecondaire',
            'voyageAgences.agence',
        ])

            ->whereIn('statut', [
                'planifie',
                'en_cours',
            ])

            ->when(
                $user->role === 'chef_agence',
                function ($query) use ($user) {

                    $query->whereHas(
                        'voyageAgences',
                        function ($q) use ($user) {

                            $q->where(
                                'agence_id',
                                $user->agence_id
                            );
                        }
                    );
                }
            )

            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENCES
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'chef_agence') {

            $agences = Agence::where(
                'id',
                $user->agence_id
            )
                ->where('active', true)
                ->orderBy('nom')
                ->get();
        } else {

            $agences = Agence::where(
                'active',
                true
            )
                ->orderBy('nom')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | BUS
        |--------------------------------------------------------------------------
        */

        $buses = Bus::orderBy('numero')->get();


        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        return view(
            'incidents.index',
            compact(
                'incidents',
                'voyages',
                'agences',
                'buses',
                'search',
                'type',
                'gravite',
                'statut'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMATIONS DU VOYAGE
    |--------------------------------------------------------------------------
    */

    public function voyageInformations(Voyage $voyage)
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté.',
            ], 401);
        }


        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (!$this->peutAcceder()) {

            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT COMPLET DU VOYAGE
        |--------------------------------------------------------------------------
        */

        $voyage->load([
            'ligne',
            'bus',
            'equipe.chauffeurTitulaire',
            'equipe.chauffeurSecondaire',
            'voyageAgences.agence',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION CHEF AGENCE
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'chef_agence') {

            $autorise = $voyage->voyageAgences->contains(
                'agence_id',
                $user->agence_id
            );

            if (!$autorise) {

                return response()->json([
                    'success' => false,
                    'message' =>
                    'Ce voyage ne concerne pas votre agence.',
                ], 403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | AGENCES DU VOYAGE
        |--------------------------------------------------------------------------
        */

        $agences = $voyage->voyageAgences
            ->sortBy(function ($voyageAgence) {

                return $voyageAgence->ordre ?? 0;
            })
            ->map(function ($voyageAgence) {

                return [

                    'id' =>
                    $voyageAgence->agence_id,

                    'nom' =>
                    $voyageAgence->agence
                        ? $voyageAgence->agence->nom
                        : 'Agence',

                    'type' =>
                    $voyageAgence->type,

                    'ordre' =>
                    $voyageAgence->ordre,

                ];
            })
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPE
        |--------------------------------------------------------------------------
        */

        $equipe = null;

        if ($voyage->equipe) {

            $equipe = [

                'id' =>
                $voyage->equipe->id,

                'code' =>
                $voyage->equipe->code,

                'nom' =>
                $voyage->equipe->nom,

                'chauffeur_titulaire' =>
                $voyage->equipe->chauffeurTitulaire
                    ? $voyage->equipe->chauffeurTitulaire->nom
                    : null,

                'chauffeur_secondaire' =>
                $voyage->equipe->chauffeurSecondaire
                    ? $voyage->equipe->chauffeurSecondaire->nom
                    : null,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | BUS
        |--------------------------------------------------------------------------
        */

        $bus = null;

        if ($voyage->bus) {

            $bus = [

                'id' =>
                $voyage->bus->id,

                'numero' =>
                $voyage->bus->numero,

                'immatriculation' =>
                $voyage->bus->immatriculation,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RÉPONSE JSON
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'voyage' => [

                'id' =>
                $voyage->id,

                'code' =>
                $voyage->code,

                'ligne' =>
                $voyage->ligne
                    ? $voyage->ligne->nom
                    : null,

                'bus' =>
                $bus,

                'equipe' =>
                $equipe,

                'agences' =>
                $agences,
            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (!$this->peutAcceder()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à déclarer un incident.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'voyage_id' => [
                'required',
                'exists:voyages,id',
            ],

            'agence_id' => [
                'required',
                'exists:agences,id',
            ],

            'type' => [
                'required',
                Rule::in([
                    'panne',
                    'accident',
                    'retard',
                    'probleme_chauffeur',
                    'probleme_technique',
                    'autre',
                ]),
            ],

            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'date_incident' => [
                'required',
                'date',
            ],

            'heure_incident' => [
                'required',
                'date_format:H:i',
            ],

            'gravite' => [
                'required',
                Rule::in([
                    'faible',
                    'moyenne',
                    'grave',
                    'critique',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VOYAGE
        |--------------------------------------------------------------------------
        */

        $voyage = Voyage::with([
            'bus',
            'voyageAgences',
        ])->find(
            $validated['voyage_id']
        );


        if (!$voyage) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Le voyage sélectionné est introuvable.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION AGENCE
        |--------------------------------------------------------------------------
        */

        $agenceAutorisee =
            $voyage->voyageAgences->contains(
                'agence_id',
                $validated['agence_id']
            );


        if (!$agenceAutorisee) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cette agence ne fait pas partie du parcours du voyage.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHEF D'AGENCE
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'chef_agence'
            &&
            (int) $validated['agence_id']
            !==
            (int) $user->agence_id
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Vous ne pouvez déclarer un incident que pour votre agence.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | BUS DU VOYAGE
        |--------------------------------------------------------------------------
        */

        $busId = $voyage->bus_id;


        try {

            DB::transaction(function () use (
                $validated,
                $busId,
                $user
            ) {

                /*
                |--------------------------------------------------------------------------
                | RÉFÉRENCE
                |--------------------------------------------------------------------------
                */

                $dernierIncident =
                    Incident::latest('id')->first();

                $numero =
                    $dernierIncident
                    ? $dernierIncident->id + 1
                    : 1;

                $reference =
                    'INC-' .
                    str_pad(
                        $numero,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );


                /*
                |--------------------------------------------------------------------------
                | CRÉATION
                |--------------------------------------------------------------------------
                */

                Incident::create([

                    'reference' =>
                    $reference,

                    'voyage_id' =>
                    $validated['voyage_id'],

                    'bus_id' =>
                    $busId,

                    'agence_id' =>
                    $validated['agence_id'],

                    'user_id' =>
                    $user->id,

                    'type' =>
                    $validated['type'],

                    'titre' =>
                    $validated['titre'],

                    'description' =>
                    $validated['description'],

                    'date_incident' =>
                    $validated['date_incident'],

                    'heure_incident' =>
                    $validated['heure_incident'],

                    'gravite' =>
                    $validated['gravite'],

                    'statut' =>
                    'ouvert',

                    'resolution' =>
                    null,

                    'date_resolution' =>
                    null,

                    'observation' =>
                    $validated['observation'] ?? null,
                ]);
            });


            return redirect()
                ->route('incidents.index')
                ->with(
                    'success',
                    'Incident enregistré avec succès.'
                );
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Une erreur est survenue lors de l\'enregistrement de l\'incident.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Incident $incident)
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (!$this->peutAcceder()) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter cet incident.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHEF D'AGENCE
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'chef_agence'
            &&
            (int) $incident->agence_id
            !==
            (int) $user->agence_id
        ) {

            return back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter cet incident.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHAUFFEUR
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'chauffeur'
            &&
            (int) $incident->user_id
            !==
            (int) $user->id
        ) {

            return back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter cet incident.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT
        |--------------------------------------------------------------------------
        */

        $incident->load([
            'voyage.ligne',
            'voyage.bus',
            'voyage.equipe.chauffeurTitulaire',
            'voyage.equipe.chauffeurSecondaire',
            'bus',
            'agence',
            'user',
        ]);


        return view(
            'incidents.show',
            compact('incident')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Incident $incident)
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR CONNECTÉ
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION DES DROITS
        |--------------------------------------------------------------------------
        |
        | ADMIN :
        | Peut toujours modifier.
        |
        | DIRECTEUR EXPLOITATION :
        | Peut toujours modifier.
        |
        | CHEF D'AGENCE :
        | Peut modifier les incidents de son agence
        | tant qu'ils ne sont pas résolus.
        |
        | CRÉATEUR :
        | Peut modifier son propre incident
        | tant qu'il n'est pas résolu.
        |
        | AUTRES :
        | Ne peuvent pas modifier.
        |
        */

        $canEdit = false;


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $canEdit = true;
        }


        /*
        |--------------------------------------------------------------------------
        | DIRECTEUR EXPLOITATION
        |--------------------------------------------------------------------------
        */ elseif ($user->role === 'directeur_exploitation') {

            $canEdit = true;
        }


        /*
        |--------------------------------------------------------------------------
        | INCIDENT NON RÉSOLU
        |--------------------------------------------------------------------------
        */ elseif ($incident->statut !== 'resolu') {


            /*
            |--------------------------------------------------------------------------
            | CHEF D'AGENCE
            |--------------------------------------------------------------------------
            */

            if (
                $user->role === 'chef_agence'
                &&
                $incident->agence_id !== null
                &&
                $user->agence_id !== null
                &&
                (int) $incident->agence_id ===
                (int) $user->agence_id
            ) {

                $canEdit = true;
            }


            /*
            |--------------------------------------------------------------------------
            | CRÉATEUR DE L'INCIDENT
            |--------------------------------------------------------------------------
            */ elseif (
                $incident->user_id !== null
                &&
                (int) $incident->user_id ===
                (int) $user->id
            ) {

                $canEdit = true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | REFUS
        |--------------------------------------------------------------------------
        */

        if (!$canEdit) {

            return redirect()
                ->route('incidents.index')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à modifier cet incident.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'type' => [
                'required',
                Rule::in([
                    'panne',
                    'accident',
                    'retard',
                    'probleme_chauffeur',
                    'probleme_technique',
                    'autre',
                ]),
            ],

            'titre' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'date_incident' => [
                'required',
                'date',
            ],

            'heure_incident' => [
                'required',
                'date_format:H:i',
            ],

            'gravite' => [
                'required',
                Rule::in([
                    'faible',
                    'moyenne',
                    'grave',
                    'critique',
                ]),
            ],

            'statut' => [
                'required',
                Rule::in([
                    'ouvert',
                    'en_cours',
                    'resolu',
                ]),
            ],

            'resolution' => [
                'nullable',
                'string',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | DATE DE RÉSOLUTION
        |--------------------------------------------------------------------------
        */

        if ($validated['statut'] === 'resolu') {

            $dateResolution =
                $incident->date_resolution ?? now();
        } else {

            $dateResolution = null;
        }


        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        $incident->update([

            'type' =>
            $validated['type'],

            'titre' =>
            $validated['titre'],

            'description' =>
            $validated['description'],

            'date_incident' =>
            $validated['date_incident'],

            'heure_incident' =>
            $validated['heure_incident'],

            'gravite' =>
            $validated['gravite'],

            'statut' =>
            $validated['statut'],

            'resolution' =>
            $validated['resolution'] ?? null,

            'date_resolution' =>
            $dateResolution,

            'observation' =>
            $validated['observation'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUCCÈS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'Incident modifié avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Incident $incident)
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | AUTORISATION
        |--------------------------------------------------------------------------
        */

        if (
            $user->role !== 'admin'
            &&
            $user->role !== 'directeur_exploitation'
        ) {

            return back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à supprimer un incident.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION
        |--------------------------------------------------------------------------
        */

        $incident->delete();


        /*
        |--------------------------------------------------------------------------
        | SUCCÈS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'Incident supprimé avec succès.'
            );
    }
}
