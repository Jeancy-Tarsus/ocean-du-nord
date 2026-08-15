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
    /**
     * Afficher la liste des voyages.
     */
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


        /*
        |--------------------------------------------------------------------------
        | LIGNES
        |--------------------------------------------------------------------------
        */

        $lignes = Ligne::where('active', true)
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BUS DISPONIBLES POUR LA CREATION
        |--------------------------------------------------------------------------
        */

        $buses = Bus::where('statut', 'disponible')
            ->orderBy('numero')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | EQUIPES
        |--------------------------------------------------------------------------
        */

        $equipes = Equipe::orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENCES
        |--------------------------------------------------------------------------
        */

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


    /**
     * Enregistrer un nouveau voyage.
     */
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
                    'en_cours',
                    'termine',
                    'annule',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],

            'agences_depart' => [
                'nullable',
                'array',
            ],

            'agences_depart.*' => [
                'integer',
                'exists:agences,id',
            ],

            'agences_arrivee' => [
                'nullable',
                'array',
            ],

            'agences_arrivee.*' => [
                'integer',
                'exists:agences,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | BUS
        |--------------------------------------------------------------------------
        */

        $bus = Bus::findOrFail(
            $validated['bus_id']
        );

        if ($bus->statut !== 'disponible') {

            return back()
                ->withInput()
                ->with(
                    'error',
                    "Le bus {$bus->numero} n'est pas disponible."
                );
        }


        /*
        |--------------------------------------------------------------------------
        | EQUIPE
        |--------------------------------------------------------------------------
        */

        $equipe = Equipe::findOrFail(
            $validated['equipe_id']
        );

        if ($equipe->statut !== 'disponible') {

            return back()
                ->withInput()
                ->with(
                    'error',
                    "L'équipe {$equipe->code} n'est pas disponible."
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHAUFFEURS DE L'EQUIPE
        |--------------------------------------------------------------------------
        */

        $chauffeurTitulaire = Chauffeur::findOrFail(
            $equipe->chauffeur_titulaire_id
        );

        $chauffeurSecondaire = Chauffeur::findOrFail(
            $equipe->chauffeur_secondaire_id
        );


        /*
        |--------------------------------------------------------------------------
        | Vérifier les chauffeurs
        |--------------------------------------------------------------------------
        */

        if (
            !$chauffeurTitulaire->disponible ||
            $chauffeurTitulaire->statut !== 'actif'
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    "Le chauffeur titulaire de l'équipe {$equipe->code} n'est pas disponible."
                );
        }


        if (
            !$chauffeurSecondaire->disponible ||
            $chauffeurSecondaire->statut !== 'actif'
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    "Le chauffeur secondaire de l'équipe {$equipe->code} n'est pas disponible."
                );
        }


        /*
        |--------------------------------------------------------------------------
        | AGENCES
        |--------------------------------------------------------------------------
        */

        $agencesDepart = array_values(
            array_unique(
                $request->input('agences_depart', [])
            )
        );

        $agencesArrivee = array_values(
            array_unique(
                $request->input('agences_arrivee', [])
            )
        );


        /*
        |--------------------------------------------------------------------------
        | GENERATION DU CODE
        |--------------------------------------------------------------------------
        */

        $dernierCode = Voyage::where(
            'code',
            'like',
            'VOY-%'
        )
            ->orderByDesc('id')
            ->value('code');


        if ($dernierCode) {

            $numero = (int) str_replace(
                'VOY-',
                '',
                $dernierCode
            ) + 1;

        } else {

            $numero = 1;
        }


        $code = 'VOY-' . str_pad(
            $numero,
            3,
            '0',
            STR_PAD_LEFT
        );


        /*
        |--------------------------------------------------------------------------
        | CREATION DU VOYAGE
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $code,
            $agencesDepart,
            $agencesArrivee,
            $bus,
            $equipe,
            $chauffeurTitulaire,
            $chauffeurSecondaire
        ) {

            $voyage = Voyage::create([

                'code' => $code,

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
                    $validated['statut'],

                'observation' =>
                    $validated['observation'] ?? null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | AGENCES DE DEPART
            |--------------------------------------------------------------------------
            */

            $ordre = 1;

            foreach ($agencesDepart as $agenceId) {

                VoyageAgence::create([

                    'voyage_id' =>
                        $voyage->id,

                    'agence_id' =>
                        $agenceId,

                    'type' =>
                        'depart',

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


            /*
            |--------------------------------------------------------------------------
            | AGENCES D'ARRIVEE
            |--------------------------------------------------------------------------
            */

            foreach ($agencesArrivee as $agenceId) {

                VoyageAgence::create([

                    'voyage_id' =>
                        $voyage->id,

                    'agence_id' =>
                        $agenceId,

                    'type' =>
                        'arrivee',

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


            /*
            |--------------------------------------------------------------------------
            | BUS → EN VOYAGE
            |--------------------------------------------------------------------------
            */

            $bus->update([
                'statut' => 'en_voyage',
            ]);


            /*
            |--------------------------------------------------------------------------
            | EQUIPE → EN VOYAGE
            |--------------------------------------------------------------------------
            */

            $equipe->update([
                'statut' => 'en_voyage',
            ]);


            /*
            |--------------------------------------------------------------------------
            | CHAUFFEURS → INDISPONIBLES
            |--------------------------------------------------------------------------
            */

            $chauffeurTitulaire->update([
                'disponible' => false,
            ]);

            $chauffeurSecondaire->update([
                'disponible' => false,
            ]);
        });


        return redirect()
            ->route('voyages.index')
            ->with(
                'success',
                "Le voyage {$code} a été créé avec succès."
            );
    }


    /**
     * Modifier un voyage.
     */
    public function update(
        Request $request,
        Voyage $voyage
    ) {

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
                    'en_cours',
                    'termine',
                    'annule',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        try {

            DB::transaction(function () use (
                $validated,
                $voyage
            ) {

                /*
                |--------------------------------------------------------------------------
                | ANCIENS BUS / EQUIPE
                |--------------------------------------------------------------------------
                */

                $ancienBus = Bus::find(
                    $voyage->bus_id
                );

                $ancienneEquipe = Equipe::find(
                    $voyage->equipe_id
                );


                /*
                |--------------------------------------------------------------------------
                | NOUVEAUX BUS / EQUIPE
                |--------------------------------------------------------------------------
                */

                $nouveauBus = Bus::findOrFail(
                    $validated['bus_id']
                );

                $nouvelleEquipe = Equipe::findOrFail(
                    $validated['equipe_id']
                );


                /*
                |--------------------------------------------------------------------------
                | ANCIENS CHAUFFEURS
                |--------------------------------------------------------------------------
                */

                $anciensChauffeurs = [];

                if ($ancienneEquipe) {

                    $anciensChauffeurs = [
                        $ancienneEquipe->chauffeur_titulaire_id,
                        $ancienneEquipe->chauffeur_secondaire_id,
                    ];
                }


                /*
                |--------------------------------------------------------------------------
                | NOUVEAUX CHAUFFEURS
                |--------------------------------------------------------------------------
                */

                $nouveauTitulaire = Chauffeur::findOrFail(
                    $nouvelleEquipe->chauffeur_titulaire_id
                );

                $nouveauSecondaire = Chauffeur::findOrFail(
                    $nouvelleEquipe->chauffeur_secondaire_id
                );


                /*
                |--------------------------------------------------------------------------
                | GESTION DU BUS
                |--------------------------------------------------------------------------
                */

                if (
                    $ancienBus &&
                    $ancienBus->id !== $nouveauBus->id
                ) {

                    /*
                    | Ancien bus → disponible
                    */

                    $ancienBus->update([
                        'statut' => 'disponible',
                    ]);


                    /*
                    | Nouveau bus disponible ?
                    */

                    if (
                        $nouveauBus->statut !== 'disponible'
                    ) {

                        throw new \Exception(
                            "Le bus {$nouveauBus->numero} n'est pas disponible."
                        );
                    }


                    /*
                    | Nouveau bus → en voyage
                    */

                    $nouveauBus->update([
                        'statut' => 'en_voyage',
                    ]);

                } else {

                    /*
                    | Même bus
                    */

                    if (
                        in_array(
                            $validated['statut'],
                            [
                                'termine',
                                'annule'
                            ]
                        )
                    ) {

                        $nouveauBus->update([
                            'statut' => 'disponible',
                        ]);

                    } else {

                        $nouveauBus->update([
                            'statut' => 'en_voyage',
                        ]);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | GESTION DE L'EQUIPE
                |--------------------------------------------------------------------------
                */

                if (
                    $ancienneEquipe &&
                    $ancienneEquipe->id !== $nouvelleEquipe->id
                ) {

                    /*
                    | Ancienne équipe → disponible
                    */

                    $ancienneEquipe->update([
                        'statut' => 'disponible',
                    ]);


                    /*
                    | Libérer les anciens chauffeurs
                    */

                    $ancienTitulaire = Chauffeur::find(
                        $ancienneEquipe->chauffeur_titulaire_id
                    );

                    $ancienSecondaire = Chauffeur::find(
                        $ancienneEquipe->chauffeur_secondaire_id
                    );


                    if ($ancienTitulaire) {

                        $ancienTitulaire->update([
                            'disponible' => true,
                        ]);
                    }


                    if ($ancienSecondaire) {

                        $ancienSecondaire->update([
                            'disponible' => true,
                        ]);
                    }


                    /*
                    | Vérifier la nouvelle équipe
                    */

                    if (
                        $nouvelleEquipe->statut !== 'disponible'
                    ) {

                        throw new \Exception(
                            "L'équipe {$nouvelleEquipe->code} n'est pas disponible."
                        );
                    }


                    /*
                    | Vérifier les nouveaux chauffeurs
                    */

                    if (
                        !$nouveauTitulaire->disponible ||
                        $nouveauTitulaire->statut !== 'actif'
                    ) {

                        throw new \Exception(
                            "Le chauffeur titulaire de l'équipe {$nouvelleEquipe->code} n'est pas disponible."
                        );
                    }


                    if (
                        !$nouveauSecondaire->disponible ||
                        $nouveauSecondaire->statut !== 'actif'
                    ) {

                        throw new \Exception(
                            "Le chauffeur secondaire de l'équipe {$nouvelleEquipe->code} n'est pas disponible."
                        );
                    }


                    /*
                    | Nouvelle équipe → en voyage
                    */

                    $nouvelleEquipe->update([
                        'statut' => 'en_voyage',
                    ]);


                    /*
                    | Nouveaux chauffeurs → indisponibles
                    */

                    $nouveauTitulaire->update([
                        'disponible' => false,
                    ]);

                    $nouveauSecondaire->update([
                        'disponible' => false,
                    ]);

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | MÊME ÉQUIPE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        in_array(
                            $validated['statut'],
                            [
                                'termine',
                                'annule'
                            ]
                        )
                    ) {

                        /*
                        | Équipe → disponible
                        */

                        $nouvelleEquipe->update([
                            'statut' => 'disponible',
                        ]);


                        /*
                        | Chauffeur titulaire → disponible
                        */

                        $nouveauTitulaire->update([
                            'disponible' => true,
                        ]);


                        /*
                        | Chauffeur secondaire → disponible
                        */

                        $nouveauSecondaire->update([
                            'disponible' => true,
                        ]);

                    } else {

                        /*
                        | Équipe → en voyage
                        */

                        $nouvelleEquipe->update([
                            'statut' => 'en_voyage',
                        ]);


                        /*
                        | Chauffeurs → indisponibles
                        */

                        $nouveauTitulaire->update([
                            'disponible' => false,
                        ]);

                        $nouveauSecondaire->update([
                            'disponible' => false,
                        ]);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | MISE A JOUR DU VOYAGE
                |--------------------------------------------------------------------------
                */

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
                        $validated['statut'],

                    'observation' =>
                        $validated['observation'] ?? null,
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


    /**
     * Supprimer un voyage.
     */
    public function destroy(Voyage $voyage)
    {
        $code = $voyage->code;


        try {

            DB::transaction(function () use (
                $voyage
            ) {

                /*
                |--------------------------------------------------------------------------
                | BUS
                |--------------------------------------------------------------------------
                */

                $bus = Bus::find(
                    $voyage->bus_id
                );


                /*
                |--------------------------------------------------------------------------
                | EQUIPE
                |--------------------------------------------------------------------------
                */

                $equipe = Equipe::find(
                    $voyage->equipe_id
                );


                /*
                |--------------------------------------------------------------------------
                | CHAUFFEURS
                |--------------------------------------------------------------------------
                */

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


                /*
                |--------------------------------------------------------------------------
                | SUPPRESSION DU VOYAGE
                |--------------------------------------------------------------------------
                */

                $voyage->delete();


                /*
                |--------------------------------------------------------------------------
                | BUS → DISPONIBLE
                |--------------------------------------------------------------------------
                */

                if ($bus) {

                    $bus->update([
                        'statut' => 'disponible',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | EQUIPE → DISPONIBLE
                |--------------------------------------------------------------------------
                */

                if ($equipe) {

                    $equipe->update([
                        'statut' => 'disponible',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | CHAUFFEUR TITULAIRE → DISPONIBLE
                |--------------------------------------------------------------------------
                */

                if ($titulaire) {

                    $titulaire->update([
                        'disponible' => true,
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | CHAUFFEUR SECONDAIRE → DISPONIBLE
                |--------------------------------------------------------------------------
                */

                if ($secondaire) {

                    $secondaire->update([
                        'disponible' => true,
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
