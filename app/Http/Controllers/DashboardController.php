<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Chauffeur;
use App\Models\Equipe;
use App\Models\Ligne;
use App\Models\Voyage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tableau de bord
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES PRINCIPALES
        |--------------------------------------------------------------------------
        */

        $voyagesAujourdHui = Voyage::whereDate(
            'date_depart',
            today()
        )->count();


        $busDisponibles = Bus::where(
            'statut',
            'disponible'
        )->count();


        $equipesDisponibles = Equipe::where(
            'statut',
            'disponible'
        )->count();


        $chauffeursDisponibles = Chauffeur::where(
            'disponible',
            true
        )
            ->where(
                'statut',
                'actif'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | BUS PAR STATUT
        |--------------------------------------------------------------------------
        */

        $busParStatut = [
            'disponible' => Bus::where('statut', 'disponible')->count(),

            'en_voyage' => Bus::where('statut', 'en_voyage')->count(),

            'en_maintenance' => Bus::where(
                'statut',
                'en_maintenance'
            )->count(),

            'en_panne' => Bus::where(
                'statut',
                'en_panne'
            )->count(),

            'hors_service' => Bus::where(
                'statut',
                'hors_service'
            )->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPES PAR STATUT
        |--------------------------------------------------------------------------
        */

        $equipesParStatut = [
            'disponible' => Equipe::where(
                'statut',
                'disponible'
            )->count(),

            'en_voyage' => Equipe::where(
                'statut',
                'en_voyage'
            )->count(),

            'indisponible' => Equipe::where(
                'statut',
                'indisponible'
            )->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | VOYAGES DES 7 DERNIERS JOURS
        |--------------------------------------------------------------------------
        */

        $dates = collect();

        $voyagesEffectues = collect();

        $voyagesPrevus = collect();


        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $dates->push(
                $date->translatedFormat('d M')
            );


            /*
            | Voyages terminés
            */

            $voyagesEffectues->push(
                Voyage::whereDate(
                    'date_depart',
                    $date
                )
                    ->where(
                        'statut',
                        'termine'
                    )
                    ->count()
            );


            /*
            | Voyages prévus
            */

            $voyagesPrevus->push(
                Voyage::whereDate(
                    'date_depart',
                    $date
                )
                    ->whereIn(
                        'statut',
                        [
                            'planifie',
                            'confirme',
                            'en_cours',
                        ]
                    )
                    ->count()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RÉPARTITION DES VOYAGES PAR LIGNE
        |--------------------------------------------------------------------------
        */

        $voyagesParLigne = Voyage::select(
            'ligne_id',
            DB::raw('COUNT(*) as total')
        )
            ->with('ligne')
            ->groupBy('ligne_id')
            ->orderByDesc('total')
            ->get();


        $lignesLabels = $voyagesParLigne
            ->map(function ($voyage) {

                return $voyage->ligne
                    ? $voyage->ligne->nom
                    : 'Ligne inconnue';

            })
            ->values();


        $lignesData = $voyagesParLigne
            ->pluck('total')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PROCHAINS VOYAGES
        |--------------------------------------------------------------------------
        */

        $prochainsVoyages = Voyage::with([
            'ligne',
            'bus',
            'equipe',
        ])
            ->where(function ($query) {

                $query->whereDate(
                    'date_depart',
                    '>=',
                    today()
                );

            })
            ->whereIn(
                'statut',
                [
                    'planifie',
                    'confirme',
                ]
            )
            ->orderBy(
                'date_depart'
            )
            ->orderBy(
                'heure_depart'
            )
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTALS
        |--------------------------------------------------------------------------
        */

        $totalBus = Bus::count();

        $totalChauffeurs = Chauffeur::count();

        $totalEquipes = Equipe::count();

        $totalVoyages = Voyage::count();

        $totalLignes = Ligne::count();


        /*
        |--------------------------------------------------------------------------
        | RETOUR DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.index',
            compact(
                'voyagesAujourdHui',
                'busDisponibles',
                'equipesDisponibles',
                'chauffeursDisponibles',

                'busParStatut',
                'equipesParStatut',

                'dates',
                'voyagesEffectues',
                'voyagesPrevus',

                'lignesLabels',
                'lignesData',

                'prochainsVoyages',

                'totalBus',
                'totalChauffeurs',
                'totalEquipes',
                'totalVoyages',
                'totalLignes'
            )
        );
    }
}
