<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Bus;
use App\Models\Equipe;
use App\Models\Voyage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AffectationController extends Controller
{
    /**
     * Vérifie si l'utilisateur peut accéder aux affectations.
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
        ]);
    }


    /**
     * Liste des affectations.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Vous devez être connecté.');
        }


        if (!$this->peutAcceder()) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à accéder aux affectations.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRES
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');

        $type = $request->input('type');


        /*
        |--------------------------------------------------------------------------
        | PRÉREMPLISSAGE DEPUIS INCIDENT
        |--------------------------------------------------------------------------
        */

        $prefillVoyageId = $request->input('voyage_id');

        $prefillMotif = $request->input('motif');


        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS
        |--------------------------------------------------------------------------
        */

        $affectations = Affectation::with([
            'voyage.ligne',
            'ancienBus',
            'nouveauBus',
            'ancienneEquipe',
            'nouvelleEquipe',
            'user',
        ])

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'motif',
                        'like',
                        '%' . $search . '%'
                    )

                    ->orWhereHas('voyage', function ($voyage) use ($search) {

                        $voyage->where(
                            'code',
                            'like',
                            '%' . $search . '%'
                        );

                    })

                    ->orWhereHas('ancienBus', function ($bus) use ($search) {

                        $bus->where(
                            'numero',
                            'like',
                            '%' . $search . '%'
                        );

                    })

                    ->orWhereHas('nouveauBus', function ($bus) use ($search) {

                        $bus->where(
                            'numero',
                            'like',
                            '%' . $search . '%'
                        );

                    });

                });

            })

            ->when($type, function ($query) use ($type) {

                $query->where(
                    'type',
                    $type
                );

            })

            ->latest()
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | VOYAGES
        |--------------------------------------------------------------------------
        |
        | On récupère les voyages normalement utilisables.
        |
        */

        $voyagesQuery = Voyage::with([
            'ligne',
            'bus',
            'equipe',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VOYAGES UTILISABLES
        |--------------------------------------------------------------------------
        */

        $voyagesQuery->whereIn(
            'statut',
            [
                'planifie',
                'en_cours',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | SI ON ARRIVE DEPUIS UN INCIDENT
        |--------------------------------------------------------------------------
        |
        | On force également l'ajout du voyage demandé,
        | même si son statut n'est pas dans la liste ci-dessus.
        |
        */

        if ($prefillVoyageId) {

            $voyagePrefill = Voyage::with([
                'ligne',
                'bus',
                'equipe',
            ])->find($prefillVoyageId);


            if ($voyagePrefill) {

                $voyagesQuery->orWhere(
                    'id',
                    $voyagePrefill->id
                );

            }
        }


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRATION DES VOYAGES
        |--------------------------------------------------------------------------
        */

        $voyages = $voyagesQuery

            ->orderByDesc('date_depart')

            ->orderByDesc('heure_depart')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | BUS DISPONIBLES
        |--------------------------------------------------------------------------
        */

        $busesDisponibles = Bus::where(
            'statut',
            'disponible'
        )

            ->orderBy('numero')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPES
        |--------------------------------------------------------------------------
        */

        $equipes = Equipe::orderBy(
            'nom'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'affectations.index',
            compact(
                'affectations',
                'voyages',
                'busesDisponibles',
                'equipes',
                'search',
                'type',
                'prefillVoyageId',
                'prefillMotif'
            )
        );
    }


    /**
     * Enregistrer une affectation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'voyage_id' => [
                'required',
                'exists:voyages,id',
            ],

            'type' => [
                'required',
                'in:remplacement_bus,remplacement_equipe,remplacement_bus_equipe',
            ],

            'ancien_bus_id' => [
                'nullable',
                'exists:bus,id',
            ],

            'nouveau_bus_id' => [
                'nullable',
                'exists:bus,id',
            ],

            'ancienne_equipe_id' => [
                'nullable',
                'exists:equipes,id',
            ],

            'nouvelle_equipe_id' => [
                'nullable',
                'exists:equipes,id',
            ],

            'motif' => [
                'required',
                'string',
                'max:255',
            ],

            'date_affectation' => [
                'required',
                'date',
            ],

            'heure_affectation' => [
                'required',
                'date_format:H:i',
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
            'equipe',
        ])->findOrFail(
            $validated['voyage_id']
        );


        /*
        |--------------------------------------------------------------------------
        | RESSOURCES ACTUELLES
        |--------------------------------------------------------------------------
        */

        $ancienBusId = $voyage->bus_id;

        $ancienneEquipeId = $voyage->equipe_id;


        /*
        |--------------------------------------------------------------------------
        | REMPLACEMENT BUS
        |--------------------------------------------------------------------------
        */

        if (
            $validated['type'] === 'remplacement_bus'
            ||
            $validated['type'] === 'remplacement_bus_equipe'
        ) {

            if (
                empty($validated['nouveau_bus_id'])
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouveau_bus_id' =>
                            'Veuillez sélectionner un bus de remplacement.',
                    ]);
            }


            $nouveauBus = Bus::where(
                'id',
                $validated['nouveau_bus_id']
            )
                ->where(
                    'statut',
                    'disponible'
                )
                ->first();


            if (!$nouveauBus) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouveau_bus_id' =>
                            'Le bus sélectionné n’est pas disponible.',
                    ]);
            }


            if (
                $ancienBusId !== null
                &&
                (int) $ancienBusId ===
                (int) $validated['nouveau_bus_id']
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouveau_bus_id' =>
                            'Le nouveau bus doit être différent du bus actuel.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | REMPLACEMENT ÉQUIPE
        |--------------------------------------------------------------------------
        */

        if (
            $validated['type'] === 'remplacement_equipe'
            ||
            $validated['type'] === 'remplacement_bus_equipe'
        ) {

            if (
                empty($validated['nouvelle_equipe_id'])
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouvelle_equipe_id' =>
                            'Veuillez sélectionner une nouvelle équipe.',
                    ]);
            }


            if (
                $ancienneEquipeId !== null
                &&
                (int) $ancienneEquipeId ===
                (int) $validated['nouvelle_equipe_id']
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouvelle_equipe_id' =>
                            'La nouvelle équipe doit être différente de l’équipe actuelle.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        |
        | Le voyage conserve son bus et son équipe.
        | L'affectation garde uniquement la trace du remplacement.
        |
        */

        DB::transaction(function () use (
            $validated,
            $ancienBusId,
            $ancienneEquipeId
        ) {

            Affectation::create([

                'voyage_id' =>
                    $validated['voyage_id'],

                'ancien_bus_id' =>
                    $ancienBusId,

                'nouveau_bus_id' =>
                    $validated['nouveau_bus_id'] ?? null,

                'ancienne_equipe_id' =>
                    $ancienneEquipeId,

                'nouvelle_equipe_id' =>
                    $validated['nouvelle_equipe_id'] ?? null,

                'type' =>
                    $validated['type'],

                'motif' =>
                    $validated['motif'],

                'date_affectation' =>
                    $validated['date_affectation'],

                'heure_affectation' =>
                    $validated['heure_affectation'],

                'observation' =>
                    $validated['observation'] ?? null,

                'user_id' =>
                    Auth::id(),

            ]);
        });


        return redirect()
            ->route('affectations.index')
            ->with(
                'success',
                'L’affectation a été enregistrée avec succès.'
            );
    }


    /**
     * Afficher une affectation.
     */
    public function show(Affectation $affectation)
    {
        $affectation->load([
            'voyage.ligne',
            'ancienBus',
            'nouveauBus',
            'ancienneEquipe',
            'nouvelleEquipe',
            'user',
        ]);


        return view(
            'affectations.show',
            compact('affectation')
        );
    }


    /**
     * Modifier une affectation.
     */
    public function update(
        Request $request,
        Affectation $affectation
    ) {

        $validated = $request->validate([

            'type' => [
                'required',
                'in:remplacement_bus,remplacement_equipe,remplacement_bus_equipe',
            ],

            'nouveau_bus_id' => [
                'nullable',
                'exists:bus,id',
            ],

            'nouvelle_equipe_id' => [
                'nullable',
                'exists:equipes,id',
            ],

            'motif' => [
                'required',
                'string',
                'max:255',
            ],

            'date_affectation' => [
                'required',
                'date',
            ],

            'heure_affectation' => [
                'required',
                'date_format:H:i:s',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

        ]);


        if (
            $validated['type'] === 'remplacement_bus'
            ||
            $validated['type'] === 'remplacement_bus_equipe'
        ) {

            if (
                empty($validated['nouveau_bus_id'])
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouveau_bus_id' =>
                            'Veuillez sélectionner un bus de remplacement.',
                    ]);
            }


            $nouveauBus = Bus::where(
                'id',
                $validated['nouveau_bus_id']
            )
                ->where(
                    'statut',
                    'disponible'
                )
                ->first();


            if (!$nouveauBus) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouveau_bus_id' =>
                            'Le bus sélectionné n’est pas disponible.',
                    ]);
            }


            if (
                $affectation->ancien_bus_id !== null
                &&
                (int) $affectation->ancien_bus_id ===
                (int) $validated['nouveau_bus_id']
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouveau_bus_id' =>
                            'Le nouveau bus doit être différent du bus actuel.',
                    ]);
            }
        }


        if (
            $validated['type'] === 'remplacement_equipe'
            ||
            $validated['type'] === 'remplacement_bus_equipe'
        ) {

            if (
                empty($validated['nouvelle_equipe_id'])
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouvelle_equipe_id' =>
                            'Veuillez sélectionner une nouvelle équipe.',
                    ]);
            }


            if (
                $affectation->ancienne_equipe_id !== null
                &&
                (int) $affectation->ancienne_equipe_id ===
                (int) $validated['nouvelle_equipe_id']
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'nouvelle_equipe_id' =>
                            'La nouvelle équipe doit être différente de l’équipe actuelle.',
                    ]);
            }
        }


        $affectation->update([

            'type' =>
                $validated['type'],

            'nouveau_bus_id' =>
                $validated['nouveau_bus_id'] ?? null,

            'nouvelle_equipe_id' =>
                $validated['nouvelle_equipe_id'] ?? null,

            'motif' =>
                $validated['motif'],

            'date_affectation' =>
                $validated['date_affectation'],

            'heure_affectation' =>
                $validated['heure_affectation'],

            'observation' =>
                $validated['observation'] ?? null,

        ]);


        return redirect()
            ->route('affectations.index')
            ->with(
                'success',
                'L’affectation a été modifiée avec succès.'
            );
    }


    /**
     * Supprimer une affectation.
     */
    public function destroy(
        Affectation $affectation
    ) {

        $affectation->delete();


        return redirect()
            ->route('affectations.index')
            ->with(
                'success',
                'L’affectation a été supprimée avec succès.'
            );
    }
}
