<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use App\Models\VoyageAgence;
use App\Models\Ligne;
use App\Models\Bus;
use App\Models\Equipe;
use App\Models\Agence;
use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VoyageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $voyages = Voyage::with([
            'ligne',
            'bus',
            'equipe',
            'voyageAgences.agence',
        ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', '%' . $search . '%')
                        ->orWhereHas('ligne', function ($ligne) use ($search) {
                            $ligne->where('nom', 'like', '%' . $search . '%')
                                ->orWhere('code', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $lignes = Ligne::where('active', true)
            ->orderBy('nom')
            ->get();

        $buses = Bus::where('statut', 'disponible')
            ->orderBy('numero')
            ->get();

        $equipes = Equipe::where('statut', 'disponible')
            ->orderBy('nom')
            ->get();

        $agences = Agence::where('active', true)
            ->orderBy('nom')
            ->get();

        return view('voyages.index', compact(
            'voyages',
            'lignes',
            'buses',
            'equipes',
            'agences',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ligne_id' => [
                'required',
                'exists:lignes,id',
            ],

            'bus_id' => [
                'required',
                'exists:bus,id',
            ],

            'equipe_id' => [
                'required',
                'exists:equipes,id',
            ],

            'date_depart' => [
                'required',
                'date',
            ],

            'heure_depart' => [
                'required',
                'date_format:H:i',
            ],

            'date_arrivee_prevue' => [
                'nullable',
                'date',
                'after_or_equal:date_depart',
            ],

            'heure_arrivee_prevue' => [
                'nullable',
                'date_format:H:i',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'planifie',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],

            'agence_depart' => [
                'required',
                'exists:agences,id',
            ],

            'agences_passage' => [
                'nullable',
                'array',
            ],

            'agences_passage.*' => [
                'exists:agences,id',
            ],

            'agence_arrivee' => [
                'required',
                'exists:agences,id',
            ],
        ]);

        try {
            $bus = Bus::findOrFail(
                $validated['bus_id']
            );

            $equipe = Equipe::findOrFail(
                $validated['equipe_id']
            );

            $chauffeurTitulaire = Chauffeur::findOrFail(
                $equipe->chauffeur_titulaire_id
            );

            $chauffeurSecondaire = Chauffeur::findOrFail(
                $equipe->chauffeur_secondaire_id
            );

            if ($bus->statut !== 'disponible') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        "Le bus {$bus->numero} n'est pas disponible."
                    );
            }

            if ($equipe->statut !== 'disponible') {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        "L'équipe {$equipe->nom} n'est pas disponible."
                    );
            }

            if (
                !$chauffeurTitulaire->disponible ||
                !$chauffeurSecondaire->disponible
            ) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        "Les chauffeurs de l'équipe {$equipe->nom} ne sont pas tous disponibles."
                    );
            }

            if (
                (int) $validated['agence_depart'] ===
                (int) $validated['agence_arrivee']
            ) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        "L'agence de départ et l'agence d'arrivée doivent être différentes."
                    );
            }

            $agencesPassage =
                $validated['agences_passage'] ?? [];

            $toutesLesAgences = array_merge(
                [
                    $validated['agence_depart']
                ],
                $agencesPassage,
                [
                    $validated['agence_arrivee']
                ]
            );

            if (
                count($toutesLesAgences) !==
                count(array_unique($toutesLesAgences))
            ) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        "Une même agence ne peut pas apparaître plusieurs fois dans le même parcours."
                    );
            }

            $dernierVoyage = Voyage::latest('id')->first();

            if ($dernierVoyage) {
                $dernierNumero = (int) preg_replace(
                    '/[^0-9]/',
                    '',
                    $dernierVoyage->code
                );

                $numero = $dernierNumero + 1;
            } else {
                $numero = 1;
            }

            $code = 'VOY-' . str_pad(
                $numero,
                3,
                '0',
                STR_PAD_LEFT
            );

            DB::transaction(function () use (
                $validated,
                $code,
                $agencesPassage
            ) {
                $voyage = Voyage::create([
                    'code' =>
                    $code,

                    'ligne_id' =>
                    $validated['ligne_id'],

                    'bus_id' =>
                    $validated['bus_id'],

                    'equipe_id' =>
                    $validated['equipe_id'],

                    'date_depart' =>
                    $validated['date_depart'],

                    'heure_depart' =>
                    $validated['heure_depart'],

                    'date_arrivee_prevue' =>
                    $validated['date_arrivee_prevue'] ?? null,

                    'heure_arrivee_prevue' =>
                    $validated['heure_arrivee_prevue'] ?? null,

                    'statut' =>
                    'planifie',

                    'observation' =>
                    $validated['observation'] ?? null,
                ]);

                VoyageAgence::create([
                    'voyage_id' =>
                    $voyage->id,

                    'agence_id' =>
                    $validated['agence_depart'],

                    'type' =>
                    'depart',

                    'ordre' =>
                    1,

                    'heure_prevue' =>
                    $validated['heure_depart'],

                    'heure_arrivee_reelle' =>
                    null,

                    'heure_depart_reelle' =>
                    null,

                    'statut' =>
                    'prevu',

                    'observation' =>
                    null,
                ]);

                $ordre = 2;

                foreach ($agencesPassage as $agenceId) {
                    VoyageAgence::create([
                        'voyage_id' =>
                        $voyage->id,

                        'agence_id' =>
                        $agenceId,

                        'type' =>
                        'passage',

                        'ordre' =>
                        $ordre++,

                        'heure_prevue' =>
                        null,

                        'heure_arrivee_reelle' =>
                        null,

                        'heure_depart_reelle' =>
                        null,

                        'statut' =>
                        'prevu',

                        'observation' =>
                        null,
                    ]);
                }

                VoyageAgence::create([
                    'voyage_id' =>
                    $voyage->id,

                    'agence_id' =>
                    $validated['agence_arrivee'],

                    'type' =>
                    'arrivee',

                    'ordre' =>
                    $ordre,

                    'heure_prevue' =>
                    $validated['heure_arrivee_prevue'] ?? null,

                    'heure_arrivee_reelle' =>
                    null,

                    'heure_depart_reelle' =>
                    null,

                    'statut' =>
                    'prevu',

                    'observation' =>
                    null,
                ]);
            });

            return redirect()
                ->route('voyages.index')
                ->with(
                    'success',
                    "Le voyage {$code} a été planifié avec succès."
                );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function start(Voyage $voyage)
    {
        if ($voyage->statut !== 'planifie') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Seul un voyage planifié peut être démarré.'
                );
        }

        try {
            DB::transaction(function () use ($voyage) {
                $bus = Bus::findOrFail(
                    $voyage->bus_id
                );

                $equipe = Equipe::findOrFail(
                    $voyage->equipe_id
                );

                $chauffeurTitulaire = Chauffeur::findOrFail(
                    $equipe->chauffeur_titulaire_id
                );

                $chauffeurSecondaire = Chauffeur::findOrFail(
                    $equipe->chauffeur_secondaire_id
                );

                if ($bus->statut !== 'disponible') {
                    throw new \Exception(
                        "Le bus {$bus->numero} n'est pas disponible."
                    );
                }

                if ($equipe->statut !== 'disponible') {
                    throw new \Exception(
                        "L'équipe {$equipe->nom} n'est pas disponible."
                    );
                }

                if (
                    !$chauffeurTitulaire->disponible ||
                    !$chauffeurSecondaire->disponible
                ) {
                    throw new \Exception(
                        "Les chauffeurs de l'équipe {$equipe->nom} ne sont pas tous disponibles."
                    );
                }

                $voyage->update([
                    'statut' => 'en_cours',
                ]);

                $bus->update([
                    'statut' => 'en_voyage',
                ]);

                $equipe->update([
                    'statut' => 'en_voyage',
                ]);

                $chauffeurTitulaire->update([
                    'disponible' => false,
                    'statut' => 'en_voyage',
                ]);

                $chauffeurSecondaire->update([
                    'disponible' => false,
                    'statut' => 'en_voyage',
                ]);

                $depart = VoyageAgence::where(
                    'voyage_id',
                    $voyage->id
                )
                    ->where('type', 'depart')
                    ->orderBy('ordre')
                    ->first();

                if ($depart) {
                    $depart->update([
                        'statut' => 'reparti',
                        'heure_depart_reelle' => now()->format('H:i:s'),
                    ]);
                }
            });

            return redirect()
                ->route('voyages.index')
                ->with(
                    'success',
                    "Le voyage {$voyage->code} a démarré avec succès."
                );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function update(
        Request $request,
        Voyage $voyage
    ) {
        if ($voyage->statut !== 'planifie') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    "Le voyage {$voyage->code} ne peut plus être modifié car il n'est plus planifié."
                );
        }

        $validated = $request->validate([
            'ligne_id' => [
                'required',
                'exists:lignes,id',
            ],

            'bus_id' => [
                'required',
                'exists:bus,id',
            ],

            'equipe_id' => [
                'required',
                'exists:equipes,id',
            ],

            'date_depart' => [
                'required',
                'date',
            ],

            'heure_depart' => [
                'required',
                'date_format:H:i',
            ],

            'date_arrivee_prevue' => [
                'nullable',
                'date',
                'after_or_equal:date_depart',
            ],

            'heure_arrivee_prevue' => [
                'nullable',
                'date_format:H:i',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

            'agence_depart' => [
                'required',
                'exists:agences,id',
            ],

            'agences_passage' => [
                'nullable',
                'array',
            ],

            'agences_passage.*' => [
                'exists:agences,id',
            ],

            'agence_arrivee' => [
                'required',
                'exists:agences,id',
            ],
        ]);

        try {
            DB::transaction(function () use (
                $validated,
                $voyage
            ) {
                $ancienBus = Bus::find(
                    $voyage->bus_id
                );

                $ancienneEquipe = Equipe::find(
                    $voyage->equipe_id
                );

                $nouveauBus = Bus::findOrFail(
                    $validated['bus_id']
                );

                $nouvelleEquipe = Equipe::findOrFail(
                    $validated['equipe_id']
                );

                if (
                    $nouveauBus->id !==
                    optional($ancienBus)->id
                ) {
                    if ($nouveauBus->statut !== 'disponible') {
                        throw new \Exception(
                            "Le bus {$nouveauBus->numero} n'est pas disponible."
                        );
                    }
                }

                if (
                    $nouvelleEquipe->id !==
                    optional($ancienneEquipe)->id
                ) {
                    if ($nouvelleEquipe->statut !== 'disponible') {
                        throw new \Exception(
                            "L'équipe {$nouvelleEquipe->nom} n'est pas disponible."
                        );
                    }
                }

                $nouveauTitulaire = Chauffeur::findOrFail(
                    $nouvelleEquipe->chauffeur_titulaire_id
                );

                $nouveauSecondaire = Chauffeur::findOrFail(
                    $nouvelleEquipe->chauffeur_secondaire_id
                );

                if (
                    $nouvelleEquipe->id !==
                    optional($ancienneEquipe)->id
                ) {
                    if (
                        !$nouveauTitulaire->disponible ||
                        !$nouveauSecondaire->disponible
                    ) {
                        throw new \Exception(
                            "Les chauffeurs de l'équipe {$nouvelleEquipe->nom} ne sont pas tous disponibles."
                        );
                    }
                }

                if (
                    $ancienBus &&
                    $ancienBus->id !== $nouveauBus->id
                ) {
                    $ancienBus->update([
                        'statut' => 'disponible',
                    ]);
                }

                if (
                    $ancienneEquipe &&
                    $ancienneEquipe->id !== $nouvelleEquipe->id
                ) {
                    $ancienTitulaire =
                        Chauffeur::find(
                            $ancienneEquipe->chauffeur_titulaire_id
                        );

                    $ancienSecondaire =
                        Chauffeur::find(
                            $ancienneEquipe->chauffeur_secondaire_id
                        );

                    $ancienneEquipe->update([
                        'statut' => 'disponible',
                    ]);

                    if ($ancienTitulaire) {
                        $ancienTitulaire->update([
                            'disponible' => true,
                            'statut' => 'actif',
                        ]);
                    }

                    if ($ancienSecondaire) {
                        $ancienSecondaire->update([
                            'disponible' => true,
                            'statut' => 'actif',
                        ]);
                    }
                }

                $nouveauBus->update([
                    'statut' => 'disponible',
                ]);

                $nouvelleEquipe->update([
                    'statut' => 'disponible',
                ]);

                $nouveauTitulaire->update([
                    'disponible' => true,
                    'statut' => 'actif',
                ]);

                $nouveauSecondaire->update([
                    'disponible' => true,
                    'statut' => 'actif',
                ]);

                $voyage->update([
                    'ligne_id' =>
                    $validated['ligne_id'],

                    'bus_id' =>
                    $validated['bus_id'],

                    'equipe_id' =>
                    $validated['equipe_id'],

                    'date_depart' =>
                    $validated['date_depart'],

                    'heure_depart' =>
                    $validated['heure_depart'],

                    'date_arrivee_prevue' =>
                    $validated['date_arrivee_prevue'] ?? null,

                    'heure_arrivee_prevue' =>
                    $validated['heure_arrivee_prevue'] ?? null,

                    'statut' =>
                    'planifie',

                    'observation' =>
                    $validated['observation'] ?? null,
                ]);

                $agencesPassage =
                    $validated['agences_passage'] ?? [];

                if (
                    (int) $validated['agence_depart'] ===
                    (int) $validated['agence_arrivee']
                ) {
                    throw new \Exception(
                        "L'agence de départ et l'agence d'arrivée doivent être différentes."
                    );
                }

                $toutesLesAgences = array_merge(
                    [
                        $validated['agence_depart']
                    ],
                    $agencesPassage,
                    [
                        $validated['agence_arrivee']
                    ]
                );

                if (
                    count($toutesLesAgences) !==
                    count(array_unique($toutesLesAgences))
                ) {
                    throw new \Exception(
                        "Une même agence ne peut pas apparaître plusieurs fois dans le même parcours."
                    );
                }

                VoyageAgence::where(
                    'voyage_id',
                    $voyage->id
                )->delete();

                VoyageAgence::create([
                    'voyage_id' =>
                    $voyage->id,

                    'agence_id' =>
                    $validated['agence_depart'],

                    'type' =>
                    'depart',

                    'ordre' =>
                    1,

                    'heure_prevue' =>
                    $validated['heure_depart'],

                    'heure_arrivee_reelle' =>
                    null,

                    'heure_depart_reelle' =>
                    null,

                    'statut' =>
                    'prevu',

                    'observation' =>
                    null,
                ]);

                $ordre = 2;

                foreach ($agencesPassage as $agenceId) {
                    VoyageAgence::create([
                        'voyage_id' =>
                        $voyage->id,

                        'agence_id' =>
                        $agenceId,

                        'type' =>
                        'passage',

                        'ordre' =>
                        $ordre++,

                        'heure_prevue' =>
                        null,

                        'heure_arrivee_reelle' =>
                        null,

                        'heure_depart_reelle' =>
                        null,

                        'statut' =>
                        'prevu',

                        'observation' =>
                        null,
                    ]);
                }

                VoyageAgence::create([
                    'voyage_id' =>
                    $voyage->id,

                    'agence_id' =>
                    $validated['agence_arrivee'],

                    'type' =>
                    'arrivee',

                    'ordre' =>
                    $ordre,

                    'heure_prevue' =>
                    $validated['heure_arrivee_prevue'] ?? null,

                    'heure_arrivee_reelle' =>
                    null,

                    'heure_depart_reelle' =>
                    null,

                    'statut' =>
                    'prevu',

                    'observation' =>
                    null,
                ]);
            });

            return redirect()
                ->route('voyages.index')
                ->with(
                    'success',
                    "Le voyage {$voyage->code} a été modifié avec succès."
                );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function finish(Voyage $voyage)
    {
        if (!in_array(auth()->user()->role, [
            'admin',
            'directeur_exploitation',
            'chef_agence',
        ])) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à terminer ce voyage.'
                );
        }

        if ($voyage->statut !== 'en_cours') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Seul un voyage en cours peut être terminé.'
                );
        }

        try {
            DB::transaction(function () use ($voyage) {
                $bus = Bus::find(
                    $voyage->bus_id
                );

                $equipe = Equipe::find(
                    $voyage->equipe_id
                );

                $titulaire = null;
                $secondaire = null;

                if ($equipe) {
                    $titulaire = Chauffeur::find(
                        $equipe->chauffeur_titulaire_id
                    );

                    $secondaire = Chauffeur::find(
                        $equipe->chauffeur_secondaire_id
                    );
                }

                $voyage->update([
                    'statut' => 'termine',
                ]);

                if ($bus) {
                    $bus->update([
                        'statut' => 'disponible',
                    ]);
                }

                if ($equipe) {
                    $equipe->update([
                        'statut' => 'disponible',
                    ]);
                }

                if ($titulaire) {
                    $titulaire->update([
                        'disponible' => true,
                        'statut' => 'actif',
                    ]);
                }

                if ($secondaire) {
                    $secondaire->update([
                        'disponible' => true,
                        'statut' => 'actif',
                    ]);
                }
            });

            return redirect()
                ->route('voyages.index')
                ->with(
                    'success',
                    "Le voyage {$voyage->code} est terminé. Le bus, l'équipe et les chauffeurs sont de nouveau disponibles."
                );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function arrive(VoyageAgence $voyageAgence)
    {
        $voyage = $voyageAgence->voyage;

        if ($voyage->statut !== 'en_cours') {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Le voyage {$voyage->code} n'est pas en cours."
                ], 422);
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Le voyage {$voyage->code} n'est pas en cours."
                );
        }

        if ($voyageAgence->statut !== 'prevu') {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cette étape a déjà été traitée."
                ], 422);
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Cette étape a déjà été traitée."
                );
        }

        $etapePrecedente = VoyageAgence::where(
            'voyage_id',
            $voyage->id
        )
            ->where(
                'ordre',
                '<',
                $voyageAgence->ordre
            )
            ->orderByDesc('ordre')
            ->first();

        if (
            $etapePrecedente &&
            $etapePrecedente->statut !== 'reparti'
        ) {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Le bus doit d'abord terminer l'étape précédente."
                ], 422);
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Le bus doit d'abord terminer l'étape précédente."
                );
        }

        $heureArrivee = now()->format('H:i:s');

        $voyageAgence->update([
            'statut' =>
            'arrive',

            'heure_arrivee_reelle' =>
            $heureArrivee,
        ]);

        if ($voyageAgence->type === 'arrivee') {

            DB::transaction(function () use (
                $voyage,
                $heureArrivee
            ) {
                $voyage->update([
                    'statut' =>
                    'termine',

                    'date_arrivee_reelle' =>
                    now()->toDateString(),

                    'heure_arrivee_reelle' =>
                    $heureArrivee,
                ]);

                if ($voyage->bus) {
                    $voyage->bus->update([
                        'statut' =>
                        'disponible',
                    ]);
                }

                if ($voyage->equipe) {

                    $voyage->equipe->update([
                        'statut' =>
                        'disponible',
                    ]);

                    if ($voyage->equipe->chauffeurTitulaire) {

                        $voyage->equipe->chauffeurTitulaire->update([
                            'statut' =>
                            'actif',

                            'disponible' =>
                            true,
                        ]);
                    }

                    if ($voyage->equipe->chauffeurSecondaire) {

                        $voyage->equipe->chauffeurSecondaire->update([
                            'statut' =>
                            'actif',

                            'disponible' =>
                            true,
                        ]);
                    }
                }
            });

            if (request()->expectsJson()) {
                return response()->json([
                    'success' =>
                    true,

                    'final' =>
                    true,

                    'message' =>
                    "Le voyage {$voyage->code} est arrivé à destination et est maintenant terminé.",

                    'statut' =>
                    'termine',

                    'heure_arrivee' =>
                    substr($heureArrivee, 0, 5),
                ]);
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    "Le voyage {$voyage->code} est arrivé à destination et est maintenant terminé."
                );
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' =>
                true,

                'final' =>
                false,

                'message' =>
                "L'arrivée à {$voyageAgence->agence->nom} a été enregistrée.",

                'statut' =>
                'arrive',

                'heure_arrivee' =>
                substr($heureArrivee, 0, 5),
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                "L'arrivée à {$voyageAgence->agence->nom} a été enregistrée."
            );
    }

    public function depart(VoyageAgence $voyageAgence)
    {
        $voyage = $voyageAgence->voyage;

        if ($voyage->statut !== 'en_cours') {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' =>
                    false,

                    'message' =>
                    "Le voyage {$voyage->code} n'est pas en cours."
                ], 422);
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Le voyage {$voyage->code} n'est pas en cours."
                );
        }

        if ($voyageAgence->type === 'arrivee') {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' =>
                    false,

                    'message' =>
                    "Cette agence est la destination finale."
                ], 422);
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Cette agence est la destination finale."
                );
        }

        if ($voyageAgence->statut !== 'arrive') {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' =>
                    false,

                    'message' =>
                    "Vous devez d'abord confirmer l'arrivée du bus."
                ], 422);
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Vous devez d'abord confirmer l'arrivée du bus."
                );
        }

        $heureDepart = now()->format('H:i:s');

        $voyageAgence->update([
            'statut' =>
            'reparti',

            'heure_depart_reelle' =>
            $heureDepart,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' =>
                true,

                'message' =>
                "Le départ de {$voyageAgence->agence->nom} a été enregistré.",

                'statut' =>
                'reparti',

                'heure_depart' =>
                substr($heureDepart, 0, 5),
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                "Le départ de {$voyageAgence->agence->nom} a été enregistré."
            );
    }

    public function destroy(Voyage $voyage)
    {
        if ($voyage->statut !== 'termine') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    "Impossible de supprimer le voyage {$voyage->code}. Seul un voyage terminé peut être supprimé."
                );
        }

        $code = $voyage->code;

        try {
            DB::transaction(function () use ($voyage) {
                $bus = Bus::find(
                    $voyage->bus_id
                );

                $equipe = Equipe::find(
                    $voyage->equipe_id
                );

                $titulaire = null;
                $secondaire = null;

                if ($equipe) {
                    $titulaire = Chauffeur::find(
                        $equipe->chauffeur_titulaire_id
                    );

                    $secondaire = Chauffeur::find(
                        $equipe->chauffeur_secondaire_id
                    );
                }

                $voyage->delete();

                if ($bus) {
                    $bus->update([
                        'statut' =>
                        'disponible',
                    ]);
                }

                if ($equipe) {
                    $equipe->update([
                        'statut' =>
                        'disponible',
                    ]);
                }

                if ($titulaire) {
                    $titulaire->update([
                        'disponible' =>
                        true,

                        'statut' =>
                        'actif',
                    ]);
                }

                if ($secondaire) {
                    $secondaire->update([
                        'disponible' =>
                        true,

                        'statut' =>
                        'actif',
                    ]);
                }
            });

            return redirect()
                ->route('voyages.index')
                ->with(
                    'success',
                    "Le voyage {$code} a été supprimé avec succès."
                );
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    "Impossible de supprimer le voyage {$code} car il est utilisé par d'autres données."
                );
        }
    }
}
