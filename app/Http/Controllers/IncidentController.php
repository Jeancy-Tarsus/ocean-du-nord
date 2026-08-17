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
    | ACCÈS GÉNÉRAL
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


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'Vous devez être connecté.');
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

            ->when(
                $user->role === 'chef_agence',
                function ($query) use ($user) {

                    $query->where(
                        'agence_id',
                        $user->agence_id
                    );
                }
            )

            ->when(
                $user->role === 'chauffeur',
                function ($query) use ($user) {

                    $query->where(
                        'user_id',
                        $user->id
                    );
                }
            )

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

            ->when(
                $type,
                function ($query) use ($type) {

                    $query->where(
                        'type',
                        $type
                    );
                }
            )

            ->when(
                $gravite,
                function ($query) use ($gravite) {

                    $query->where(
                        'gravite',
                        $gravite
                    );
                }
            )

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
        | VOYAGES
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
    | INFORMATIONS VOYAGE
    |--------------------------------------------------------------------------
    */

    public function voyageInformations(Voyage $voyage)
    {
        $user = Auth::user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté.',
            ], 401);
        }


        if (!$this->peutAcceder()) {

            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }


        $voyage->load([
            'ligne',
            'bus',
            'equipe.chauffeurTitulaire',
            'equipe.chauffeurSecondaire',
            'voyageAgences.agence',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CHEF AGENCE
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
        | AGENCES
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
        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


        if (!$this->peutAcceder()) {

            return back()
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
        | AGENCE DU VOYAGE
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
        | CHEF AGENCE
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


        $busId = $voyage->bus_id;


        try {

            DB::transaction(function () use (
                $validated,
                $busId,
                $user
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
                    'Une erreur est survenue lors de l\'enregistrement de l\'incident : '
                    . $e->getMessage()
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
        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


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
        | CHEF AGENCE
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
        | RÈGLE DE MODIFICATION
        |--------------------------------------------------------------------------
        |
        | ADMIN
        | -> peut toujours modifier.
        |
        | DIRECTEUR EXPLOITATION
        | -> peut toujours modifier.
        |
        | CHEF AGENCE
        | -> peut modifier uniquement les incidents
        |    de son agence ET non résolus.
        |
        | CHAUFFEUR
        | -> ne peut pas modifier.
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
        */

        elseif ($user->role === 'directeur_exploitation') {

            $canEdit = true;
        }


        /*
        |--------------------------------------------------------------------------
        | CHEF AGENCE
        |--------------------------------------------------------------------------
        */

        elseif (
            $user->role === 'chef_agence'
            &&
            $incident->statut !== 'resolu'
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
                'date_format:H:i:s',
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
        | RÉSOLUTION
        |--------------------------------------------------------------------------
        */

        $resolution =
            $validated['resolution'] ?? null;


        $dateResolution =
            $incident->date_resolution;


        /*
        |--------------------------------------------------------------------------
        | SI LE STATUT EST RÉSOLU
        |--------------------------------------------------------------------------
        */

        if ($validated['statut'] === 'resolu') {

            /*
            | Pour un incident déjà résolu, on conserve
            | sa date de résolution.
            */

            if (!$dateResolution) {

                $dateResolution = now();
            }


            /*
            | Une résolution est obligatoire
            | lorsqu'on passe directement à résolu.
            */

            if (
                empty(trim((string) $resolution))
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Veuillez renseigner la résolution de l\'incident.'
                    );
            }

        } else {

            /*
            | Si l'incident n'est plus résolu,
            | on efface les informations de résolution.
            */

            $resolution = null;

            $dateResolution = null;
        }


        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        try {

            $incident->type =
                $validated['type'];

            $incident->titre =
                $validated['titre'];

            $incident->description =
                $validated['description'];

            $incident->date_incident =
                $validated['date_incident'];

            $incident->heure_incident =
                $validated['heure_incident'];

            $incident->gravite =
                $validated['gravite'];

            $incident->statut =
                $validated['statut'];

            $incident->resolution =
                $resolution;

            $incident->date_resolution =
                $dateResolution;

            $incident->observation =
                $validated['observation'] ?? null;


            /*
            |--------------------------------------------------------------------------
            | SAUVEGARDE
            |--------------------------------------------------------------------------
            */

            $incident->save();


            /*
            |--------------------------------------------------------------------------
            | REDIRECTION
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('incidents.index')
                ->with(
                    'success',
                    'Incident modifié avec succès.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de modifier l\'incident : '
                    . $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PRENDRE EN CHARGE
    |--------------------------------------------------------------------------
    */

    public function prendreEnCharge(Incident $incident)
    {
        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


        $canTakeCharge = false;


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $canTakeCharge = true;
        }


        /*
        |--------------------------------------------------------------------------
        | DIRECTEUR
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'directeur_exploitation') {

            $canTakeCharge = true;
        }


        /*
        |--------------------------------------------------------------------------
        | CHEF AGENCE
        |--------------------------------------------------------------------------
        */

        elseif (
            $user->role === 'chef_agence'
            &&
            $incident->agence_id !== null
            &&
            $user->agence_id !== null
            &&
            (int) $incident->agence_id ===
            (int) $user->agence_id
        ) {

            $canTakeCharge = true;
        }


        if (!$canTakeCharge) {

            return back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à prendre en charge cet incident.'
                );
        }


        if ($incident->statut !== 'ouvert') {

            return back()
                ->with(
                    'error',
                    'Seul un incident ouvert peut être pris en charge.'
                );
        }


        $incident->update([

            'statut' =>
                'en_cours',

        ]);


        return back()
            ->with(
                'success',
                'Incident pris en charge avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RÉSOUDRE
    |--------------------------------------------------------------------------
    */

    public function resoudre(
        Request $request,
        Incident $incident
    ) {

        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté.'
                );
        }


        $canResolve = false;


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            $canResolve = true;
        }


        /*
        |--------------------------------------------------------------------------
        | DIRECTEUR
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'directeur_exploitation') {

            $canResolve = true;
        }


        /*
        |--------------------------------------------------------------------------
        | CHEF AGENCE
        |--------------------------------------------------------------------------
        */

        elseif (
            $user->role === 'chef_agence'
            &&
            $incident->agence_id !== null
            &&
            $user->agence_id !== null
            &&
            (int) $incident->agence_id ===
            (int) $user->agence_id
        ) {

            $canResolve = true;
        }


        if (!$canResolve) {

            return back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à résoudre cet incident.'
                );
        }


        if ($incident->statut !== 'en_cours') {

            return back()
                ->with(
                    'error',
                    'Seul un incident en cours peut être résolu.'
                );
        }


        $validated = $request->validate([

            'resolution' => [
                'required',
                'string',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

        ], [

            'resolution.required' =>
                'Veuillez renseigner la résolution de l\'incident.',

        ]);


        $incident->update([

            'statut' =>
                'resolu',

            'resolution' =>
                $validated['resolution'],

            'observation' =>
                $validated['observation']
                ?? $incident->observation,

            'date_resolution' =>
                now(),

        ]);


        return back()
            ->with(
                'success',
                'Incident résolu avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Incident $incident)
    {
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
        | SEUL ADMIN + DIRECTEUR
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


        $incident->delete();


        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'Incident supprimé avec succès.'
            );
    }
}
