<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Bus;
use App\Models\Equipe;
use App\Models\Voyage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AffectationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VÉRIFICATION DES DROITS
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
                    'Vous n\'êtes pas autorisé à accéder aux affectations.'
                );
        }


        $search = $request->input('search');
        $type = $request->input('type');


        /*
        |--------------------------------------------------------------------------
        | AFFECTATIONS
        |--------------------------------------------------------------------------
        */

        $affectations = Affectation::with([
            'voyage.ligne',
            'ancienBus',
            'nouveauBus',
            'ancienneEquipe.chauffeurTitulaire',
            'ancienneEquipe.chauffeurSecondaire',
            'nouvelleEquipe.chauffeurTitulaire',
            'nouvelleEquipe.chauffeurSecondaire',
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
        | On récupère les voyages qui peuvent encore être concernés
        | par une affectation.
        |
        */

        $voyages = Voyage::with([
            'ligne',
            'bus',
            'equipe.chauffeurTitulaire',
            'equipe.chauffeurSecondaire',
        ])

        ->whereIn('statut', [
            'planifie',
            'en_cours',
        ])

        ->latest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | BUS DISPONIBLES
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | uniquement les bus dont le statut est "disponible".
        |
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

        $equipes = Equipe::orderBy('nom')->get();


        return view(
            'affectations.index',
            compact(
                'affectations',
                'voyages',
                'busesDisponibles',
                'equipes',
                'search',
                'type'
            )
        );
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
                ->with('error', 'Vous devez être connecté.');
        }

        if (!$this->peutAcceder()) {
            return back()
                ->with('error', 'Vous n\'êtes pas autorisé à créer une affectation.');
        }


        $validated = $request->validate([

            'voyage_id' => [
                'required',
                'exists:voyages,id',
            ],

            'nouveau_bus_id' => [
                'nullable',
                'exists:bus,id',
            ],

            'nouvelle_equipe_id' => [
                'nullable',
                'exists:equipes,id',
            ],

            'type' => [
                'required',
                Rule::in([
                    'remplacement_bus',
                    'remplacement_equipe',
                    'remplacement_bus_equipe',
                ]),
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
                'date_format:H:i,H:i:s',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRATION DU VOYAGE
        |--------------------------------------------------------------------------
        */

        $voyage = Voyage::with([
            'bus',
            'equipe',
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
        | ANCIEN BUS / ANCIENNE ÉQUIPE
        |--------------------------------------------------------------------------
        |
        | Ils viennent TOUJOURS du voyage.
        |
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

            if (empty($validated['nouveau_bus_id'])) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Veuillez sélectionner le bus de remplacement.'
                    );
            }


            /*
            | Le nouveau bus doit être différent de l'ancien.
            */

            if (
                (int) $validated['nouveau_bus_id']
                ===
                (int) $ancienBusId
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le bus de remplacement doit être différent du bus actuel.'
                    );
            }


            /*
            | Le nouveau bus doit être disponible.
            */

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
                    ->with(
                        'error',
                        'Le bus sélectionné n\'est pas disponible.'
                    );
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

            if (empty($validated['nouvelle_equipe_id'])) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Veuillez sélectionner la nouvelle équipe.'
                    );
            }


            if (
                (int) $validated['nouvelle_equipe_id']
                ===
                (int) $ancienneEquipeId
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'La nouvelle équipe doit être différente de l\'équipe actuelle.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION DE L'HISTORIQUE
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (
                $validated,
                $ancienBusId,
                $ancienneEquipeId,
                $user
            ) {

                Affectation::create([

                    'voyage_id' =>
                        $validated['voyage_id'],

                    /*
                    | Anciennes ressources :
                    | elles viennent du voyage.
                    */

                    'ancien_bus_id' =>
                        $ancienBusId,

                    'ancienne_equipe_id' =>
                        $ancienneEquipeId,


                    /*
                    | Nouvelles ressources :
                    | elles représentent le relais.
                    */

                    'nouveau_bus_id' =>
                        $validated['nouveau_bus_id'] ?? null,

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
                        $user->id,
                ]);


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | ON NE MODIFIE PAS :
                |
                | $voyage->bus_id
                | $voyage->equipe_id
                |
                | Le voyage conserve ses ressources initiales.
                |
                */
            });


            return redirect()
                ->route('affectations.index')
                ->with(
                    'success',
                    'Affectation enregistrée avec succès.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible d\'enregistrer l\'affectation : '
                    . $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Affectation $affectation)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('login');
        }

        if (!$this->peutAcceder()) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter cette affectation.'
                );
        }


        $affectation->load([
            'voyage.ligne',
            'ancienBus',
            'nouveauBus',
            'ancienneEquipe.chauffeurTitulaire',
            'ancienneEquipe.chauffeurSecondaire',
            'nouvelleEquipe.chauffeurTitulaire',
            'nouvelleEquipe.chauffeurSecondaire',
            'user',
        ]);


        return view(
            'affectations.modal.show',
            compact('affectation')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Affectation $affectation
    ) {

        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login');
        }


        if (
            $user->role !== 'admin'
            &&
            $user->role !== 'directeur_exploitation'
        ) {

            return back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à modifier une affectation.'
                );
        }


        $validated = $request->validate([

            'type' => [
                'required',
                Rule::in([
                    'remplacement_bus',
                    'remplacement_equipe',
                    'remplacement_bus_equipe',
                ]),
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
                'date_format:H:i,H:i:s',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | BUS
        |--------------------------------------------------------------------------
        */

        if (
            $validated['type'] === 'remplacement_bus'
            ||
            $validated['type'] === 'remplacement_bus_equipe'
        ) {

            if (empty($validated['nouveau_bus_id'])) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Veuillez sélectionner le bus de remplacement.'
                    );
            }


            if (
                (int) $validated['nouveau_bus_id']
                ===
                (int) $affectation->ancien_bus_id
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le nouveau bus doit être différent de l\'ancien bus.'
                    );
            }


            $busDisponible = Bus::where(
                'id',
                $validated['nouveau_bus_id']
            )
            ->where(
                'statut',
                'disponible'
            )
            ->exists();


            if (!$busDisponible) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Le bus sélectionné n\'est plus disponible.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPE
        |--------------------------------------------------------------------------
        */

        if (
            $validated['type'] === 'remplacement_equipe'
            ||
            $validated['type'] === 'remplacement_bus_equipe'
        ) {

            if (empty($validated['nouvelle_equipe_id'])) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Veuillez sélectionner la nouvelle équipe.'
                    );
            }


            if (
                (int) $validated['nouvelle_equipe_id']
                ===
                (int) $affectation->ancienne_equipe_id
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'La nouvelle équipe doit être différente de l\'ancienne équipe.'
                    );
            }
        }


        $affectation->update([

            'type' =>
                $validated['type'],

            /*
            | On conserve toujours les anciennes valeurs
            | déjà enregistrées dans l'historique.
            */

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
                'Affectation modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Affectation $affectation)
    {
        $user = Auth::user();

        if (!$user) {

            return redirect()
                ->route('login');
        }


        if (
            $user->role !== 'admin'
            &&
            $user->role !== 'directeur_exploitation'
        ) {

            return back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à supprimer une affectation.'
                );
        }


        $affectation->delete();


        return redirect()
            ->route('affectations.index')
            ->with(
                'success',
                'Affectation supprimée avec succès.'
            );
    }
}
