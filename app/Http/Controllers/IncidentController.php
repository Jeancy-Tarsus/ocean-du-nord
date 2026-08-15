<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Bus;
use App\Models\Incident;
use App\Models\Voyage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IncidentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Vérifier si l'utilisateur est connecté
    |--------------------------------------------------------------------------
    */

    private function connecte(): bool
    {
        return auth()->check();
    }


    /*
    |--------------------------------------------------------------------------
    | Rôles autorisés à accéder au module
    |--------------------------------------------------------------------------
    */

    private function peutAcceder(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return in_array(auth()->user()->role, [
            'admin',
            'directeur_exploitation',
            'chef_parc',
            'chef_agence',
            'chauffeur',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Administration complète
    |--------------------------------------------------------------------------
    */

    private function estAdmin(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'admin';
    }


    /*
    |--------------------------------------------------------------------------
    | Direction exploitation
    |--------------------------------------------------------------------------
    */

    private function estDirecteur(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'directeur_exploitation';
    }


    /*
    |--------------------------------------------------------------------------
    | Chef parc
    |--------------------------------------------------------------------------
    */

    private function estChefParc(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'chef_parc';
    }


    /*
    |--------------------------------------------------------------------------
    | Chef agence
    |--------------------------------------------------------------------------
    */

    private function estChefAgence(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'chef_agence';
    }


    /*
    |--------------------------------------------------------------------------
    | Chauffeur
    |--------------------------------------------------------------------------
    */

    private function estChauffeur(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'chauffeur';
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        if (!$this->connecte()) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté pour accéder aux incidents.'
                );
        }


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
        | Filtres
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');

        $type = $request->input('type');

        $gravite = $request->input('gravite');

        $statut = $request->input('statut');


        /*
        |--------------------------------------------------------------------------
        | Requête incidents
        |--------------------------------------------------------------------------
        */

        $incidents = Incident::with([
            'voyage.ligne',
            'voyage.bus',
            'bus',
            'agence',
            'user',
        ])


        /*
        |--------------------------------------------------------------------------
        | Chef agence
        |--------------------------------------------------------------------------
        |
        | Il ne voit que les incidents de son agence.
        |
        */

        ->when(
            $this->estChefAgence(),
            function ($query) {

                $query->where(
                    'agence_id',
                    auth()->user()->agence_id
                );

            }
        )


        /*
        |--------------------------------------------------------------------------
        | Chauffeur
        |--------------------------------------------------------------------------
        |
        | Pour le moment, on limite aux incidents qu'il a déclarés.
        | Lorsque nous confirmerons la relation chauffeur <-> voyages,
        | on pourra élargir automatiquement aux incidents de ses voyages.
        |
        */

        ->when(
            $this->estChauffeur(),
            function ($query) {

                $query->where(
                    'user_id',
                    auth()->id()
                );

            }
        )


        /*
        |--------------------------------------------------------------------------
        | Recherche
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
        | Type
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
        | Gravité
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
        | Statut
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
        | Voyages disponibles
        |--------------------------------------------------------------------------
        */

        $voyages = Voyage::with([
            'ligne',
            'bus',
            'voyageAgences.agence',
        ])

        ->whereIn('statut', [
            'planifie',
            'en_cours',
        ])


        /*
        |--------------------------------------------------------------------------
        | Chef agence
        |--------------------------------------------------------------------------
        */

        ->when(
            $this->estChefAgence(),
            function ($query) {

                $query->whereHas(
                    'voyageAgences',
                    function ($q) {

                        $q->where(
                            'agence_id',
                            auth()->user()->agence_id
                        );

                    }
                );

            }
        )


        ->latest()

        ->get();


        /*
        |--------------------------------------------------------------------------
        | Agences
        |--------------------------------------------------------------------------
        */

        if ($this->estChefAgence()) {

            $agences = Agence::where(
                'id',
                auth()->user()->agence_id
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
        | Bus
        |--------------------------------------------------------------------------
        |
        | Conservé pour les relations.
        | Le formulaire incident récupérera automatiquement
        | le bus du voyage.
        |
        */

        $buses = Bus::orderBy(
            'numero'
        )->get();


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
    | INFORMATIONS D'UN VOYAGE
    |--------------------------------------------------------------------------
    */

    public function voyageInformations(
        Voyage $voyage
    ) {

        if (!$this->peutAcceder()) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Vous n\'êtes pas autorisé à consulter ce voyage.',
            ], 403);
        }


        $voyage->load([
            'ligne',
            'bus',
            'voyageAgences.agence',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Chef agence
        |--------------------------------------------------------------------------
        */

        if ($this->estChefAgence()) {

            $autorise =
                $voyage->voyageAgences
                    ->contains(
                        'agence_id',
                        auth()->user()->agence_id
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
        | Agences du voyage
        |--------------------------------------------------------------------------
        */

        $agences =
            $voyage->voyageAgences
                ->sortBy(function ($etape) {

                    return $etape->ordre ?? 0;

                })
                ->map(function ($etape) {

                    return [
                        'id' =>
                            $etape->agence_id,

                        'nom' =>
                            $etape->agence
                                ? $etape->agence->nom
                                : 'Agence',

                        'type' =>
                            $etape->type ?? null,

                        'ordre' =>
                            $etape->ordre ?? null,
                    ];

                })
                ->values()
                ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Réponse
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
                    $voyage->bus
                        ? [
                            'id' =>
                                $voyage->bus->id,

                            'numero' =>
                                $voyage->bus->numero,

                            'immatriculation' =>
                                $voyage->bus->immatriculation,
                        ]
                        : null,

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
        if (!$this->peutAcceder()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à déclarer un incident.'
                );
        }


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
        | Charger le voyage
        |--------------------------------------------------------------------------
        */

        $voyage = Voyage::with([
            'bus',
            'voyageAgences',
        ])->find(
            $validated['voyage_id']
        );


        if (!$voyage) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Le voyage sélectionné est introuvable.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'agence
        |--------------------------------------------------------------------------
        */

        $agenceAutorisee =
            $voyage->voyageAgences
                ->contains(
                    'agence_id',
                    $validated['agence_id']
                );


        if (!$agenceAutorisee) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'L\'agence sélectionnée ne fait pas partie du parcours de ce voyage.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Chef agence
        |--------------------------------------------------------------------------
        */

        if (
            $this->estChefAgence()
            && (int) $validated['agence_id']
                !== (int) auth()->user()->agence_id
        ) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Vous ne pouvez déclarer un incident que pour votre agence.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Bus récupéré automatiquement depuis le voyage
        |--------------------------------------------------------------------------
        */

        $busId =
            $voyage->bus_id;


        try {

            DB::transaction(function () use (
                $validated,
                $busId
            ) {

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
                        auth()->id(),

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
                    'L\'incident a été enregistré avec succès.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->back()
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
        if (!$this->peutAcceder()) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter les incidents.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Chef agence
        |--------------------------------------------------------------------------
        */

        if (
            $this->estChefAgence()
            && (int) $incident->agence_id
                !== (int) auth()->user()->agence_id
        ) {

            return redirect()
                ->route('incidents.index')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter cet incident.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Chauffeur
        |--------------------------------------------------------------------------
        */

        if (
            $this->estChauffeur()
            && (int) $incident->user_id
                !== (int) auth()->id()
        ) {

            return redirect()
                ->route('incidents.index')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter cet incident.'
                );
        }


        $incident->load([
            'voyage.ligne',
            'voyage.bus',
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

    public function update(
        Request $request,
        Incident $incident
    ) {

        if (
            !$this->estAdmin()
            && !$this->estDirecteur()
            && !$this->estChefParc()
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à modifier un incident.'
                );
        }


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
                $validated['statut'] === 'resolu'
                    ? now()
                    : null,

            'observation' =>
                $validated['observation'] ?? null,

        ]);


        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'L\'incident a été modifié avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Incident $incident
    ) {

        if (
            !$this->estAdmin()
            && !$this->estDirecteur()
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à supprimer un incident.'
                );
        }


        $incident->delete();


        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'L\'incident a été supprimé avec succès.'
            );
    }
}
