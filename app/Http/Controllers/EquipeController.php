<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipeController extends Controller
{
    /**
     * Afficher la liste des équipes.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $equipes = Equipe::with([
            'chauffeurTitulaire',
            'chauffeurSecondaire'
        ])
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('nom', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();


        /*
    |--------------------------------------------------------------------------
    | Chauffeurs disponibles pour la création
    |--------------------------------------------------------------------------
    |
    | Un chauffeur déjà affecté à une équipe n'apparaît pas
    | dans la liste de création.
    |
    */

        $chauffeursDisponibles = Chauffeur::where('disponible', true)
            ->where('statut', 'actif')
            ->whereNotIn('id', function ($query) {

                $query->select('chauffeur_titulaire_id')
                    ->from('equipes')
                    ->whereNotNull('chauffeur_titulaire_id');
            })
            ->whereNotIn('id', function ($query) {

                $query->select('chauffeur_secondaire_id')
                    ->from('equipes')
                    ->whereNotNull('chauffeur_secondaire_id');
            })
            ->orderBy('nom')
            ->get();


        /*
    |--------------------------------------------------------------------------
    | Chauffeurs disponibles pour la modification
    |--------------------------------------------------------------------------
    |
    | Pour chaque équipe, on récupère :
    | - les chauffeurs libres
    | - ET ses propres chauffeurs actuels
    |
    | Les chauffeurs appartenant à une autre équipe restent exclus.
    |
    */

        foreach ($equipes as $equipe) {

            $equipe->chauffeursEdit = Chauffeur::where('disponible', true)
                ->where('statut', 'actif')
                ->where(function ($query) use ($equipe) {

                    $query->whereNotIn('id', function ($subQuery) use ($equipe) {

                        $subQuery->select('chauffeur_titulaire_id')
                            ->from('equipes')
                            ->whereNotNull('chauffeur_titulaire_id')
                            ->where('id', '!=', $equipe->id);
                    })
                        ->whereNotIn('id', function ($subQuery) use ($equipe) {

                            $subQuery->select('chauffeur_secondaire_id')
                                ->from('equipes')
                                ->whereNotNull('chauffeur_secondaire_id')
                                ->where('id', '!=', $equipe->id);
                        });
                })
                ->orderBy('nom')
                ->get();
        }


        return view('equipes.index', compact(
            'equipes',
            'chauffeursDisponibles',
            'search'
        ));
    }

    /**
     * Enregistrer une nouvelle équipe.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'chauffeur_titulaire_id' => [
                'required',
                'exists:chauffeurs,id',
            ],

            'chauffeur_secondaire_id' => [
                'required',
                'exists:chauffeurs,id',
                'different:chauffeur_titulaire_id',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'disponible',
                    'en_voyage',
                    'indisponible',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier que les deux chauffeurs sont disponibles
        |--------------------------------------------------------------------------
        */

        $titulaire = Chauffeur::findOrFail(
            $validated['chauffeur_titulaire_id']
        );

        $secondaire = Chauffeur::findOrFail(
            $validated['chauffeur_secondaire_id']
        );


        if (
            !$titulaire->disponible ||
            $titulaire->statut !== 'actif'
        ) {

            return back()
                ->withInput()
                ->with('error', 'Le chauffeur titulaire sélectionné n\'est pas disponible.');
        }


        if (
            !$secondaire->disponible ||
            $secondaire->statut !== 'actif'
        ) {

            return back()
                ->withInput()
                ->with('error', 'Le chauffeur secondaire sélectionné n\'est pas disponible.');
        }


        /*
        |--------------------------------------------------------------------------
        | Génération automatique du code de l'équipe
        |--------------------------------------------------------------------------
        */

        $dernierCode = Equipe::where('code', 'like', 'EQ-%')
            ->orderByDesc('id')
            ->value('code');


        if ($dernierCode) {

            $numero = (int) str_replace('EQ-', '', $dernierCode) + 1;
        } else {

            $numero = 1;
        }


        $code = 'EQ-' . str_pad(
            $numero,
            3,
            '0',
            STR_PAD_LEFT
        );


        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        $validated['code'] = $code;

        Equipe::create($validated);


        return redirect()
            ->route('equipes.index')
            ->with(
                'success',
                "L'équipe {$code} a été créée avec succès."
            );
    }


    /**
     * Modifier une équipe.
     */
    public function update(Request $request, Equipe $equipe)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'chauffeur_titulaire_id' => [
                'required',
                'exists:chauffeurs,id',
            ],

            'chauffeur_secondaire_id' => [
                'required',
                'exists:chauffeurs,id',
                'different:chauffeur_titulaire_id',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'disponible',
                    'en_voyage',
                    'indisponible',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier les chauffeurs
        |--------------------------------------------------------------------------
        */

        $titulaire = Chauffeur::findOrFail(
            $validated['chauffeur_titulaire_id']
        );

        $secondaire = Chauffeur::findOrFail(
            $validated['chauffeur_secondaire_id']
        );


        if (
            !$titulaire->disponible &&
            $titulaire->id !== $equipe->chauffeur_titulaire_id
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Le chauffeur titulaire sélectionné n\'est pas disponible.'
                );
        }


        if (
            !$secondaire->disponible &&
            $secondaire->id !== $equipe->chauffeur_secondaire_id
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Le chauffeur secondaire sélectionné n\'est pas disponible.'
                );
        }


        $equipe->update($validated);


        return redirect()
            ->route('equipes.index')
            ->with(
                'success',
                "L'équipe {$equipe->code} a été modifiée avec succès."
            );
    }


    /**
     * Supprimer une équipe.
     */
    public function destroy(Equipe $equipe)
    {
        $code = $equipe->code;

        $equipe->delete();


        return redirect()
            ->route('equipes.index')
            ->with(
                'success',
                "L'équipe {$code} a été supprimée avec succès."
            );
    }
}
