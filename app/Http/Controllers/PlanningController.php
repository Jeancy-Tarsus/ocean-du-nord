<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use App\Models\Ligne;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vous devez être connecté pour accéder au planning.'
                );
        }

        if (
            !in_array(auth()->user()->role, [
                'admin',
                'directeur_exploitation',
                'chef_agence',
            ])
        ) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Vous n\'êtes pas autorisé à consulter le planning.'
                );
        }

        $date = $request->input('date');
        $statut = $request->input('statut');
        $ligneId = $request->input('ligne_id');

        $voyages = Voyage::with([
            'ligne',
            'bus',
            'equipe.chauffeurTitulaire',
            'equipe.chauffeurSecondaire',
            'voyageAgences.agence',
        ])
            ->when(
                auth()->user()->role === 'chef_agence',
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
            ->when(
                $date,
                function ($query) use ($date) {
                    $query->whereDate(
                        'date_depart',
                        $date
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
            ->when(
                $ligneId,
                function ($query) use ($ligneId) {
                    $query->where(
                        'ligne_id',
                        $ligneId
                    );
                }
            )
            ->orderBy('date_depart')
            ->orderBy('heure_depart')
            ->paginate(15)
            ->withQueryString();

        $lignes = Ligne::where('active', true)
            ->orderBy('nom')
            ->get();

        return view(
            'planning.index',
            compact(
                'voyages',
                'lignes',
                'date',
                'statut',
                'ligneId'
            )
        );
    }
}
