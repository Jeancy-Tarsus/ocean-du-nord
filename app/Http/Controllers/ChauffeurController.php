<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class ChauffeurController extends Controller
{
    /**
     * Afficher la liste des chauffeurs.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $chauffeurs = Chauffeur::query()

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('matricule', 'like', '%' . $search . '%')
                        ->orWhere('nom', 'like', '%' . $search . '%')
                        ->orWhere('prenom', 'like', '%' . $search . '%')
                        ->orWhere('telephone', 'like', '%' . $search . '%')
                        ->orWhere('numero_permis', 'like', '%' . $search . '%')
                        ->orWhere('statut', 'like', '%' . $search . '%');
                });
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();


        return view('chauffeurs.index', compact(
            'chauffeurs',
            'search'
        ));
    }

    /**
     * Enregistrer un nouveau chauffeur.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'prenom' => [
                'required',
                'string',
                'max:100',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'numero_permis' => [
                'required',
                'string',
                'max:100',
                'unique:chauffeurs,numero_permis',
            ],

            'date_expiration_permis' => [
                'nullable',
                'date',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'actif',
                    'en_voyage',
                    'indisponible',
                    'suspendu',
                    'inactif',
                ]),
            ],

            'disponible' => [
                'nullable',
                'boolean',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);

        /*
    |--------------------------------------------------------------------------
    | Génération automatique du matricule
    |--------------------------------------------------------------------------
    */

        $dernierChauffeur = Chauffeur::latest('id')->first();

        $numero = $dernierChauffeur
            ? $dernierChauffeur->id + 1
            : 1;

        $validated['matricule'] =
            'CH-ON-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

        $validated['disponible'] =
            $request->boolean('disponible');

        Chauffeur::create($validated);

        return redirect()
            ->route('chauffeurs.index')
            ->with(
                'success',
                'Chauffeur ' . $validated['matricule'] . ' enregistré avec succès.'
            );
    }

    /**
     * Modifier un chauffeur.
     */
    public function update(Request $request, Chauffeur $chauffeur)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'prenom' => [
                'required',
                'string',
                'max:100',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'numero_permis' => [
                'required',
                'string',
                'max:100',
                Rule::unique('chauffeurs', 'numero_permis')
                    ->ignore($chauffeur->id),
            ],

            'date_expiration_permis' => [
                'nullable',
                'date',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'actif',
                    'en_voyage',
                    'indisponible',
                    'suspendu',
                    'inactif',
                ]),
            ],

            'disponible' => [
                'nullable',
                'boolean',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

        ]);

        $validated['disponible'] =
            $request->boolean('disponible');

        /*
    |--------------------------------------------------------------------------
    | Le matricule n'est volontairement pas modifié
    |--------------------------------------------------------------------------
    */

        $chauffeur->update($validated);

        return redirect()
            ->route('chauffeurs.index')
            ->with(
                'success',
                'Chauffeur ' . $chauffeur->matricule .
                    ' modifié avec succès.'
            );
    }

    /**
     * Supprimer un chauffeur.
     */
    public function destroy(Chauffeur $chauffeur)
    {
        $nom = $chauffeur->nom . ' ' . $chauffeur->prenom;

        try {

            $chauffeur->delete();

            return redirect()
                ->route('chauffeurs.index')
                ->with(
                    'success',
                    "Le chauffeur {$nom} a été supprimé avec succès."
                );
        } catch (QueryException $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Impossible de supprimer le chauffeur {$nom} : il est actuellement affecté à une équipe."
                );
        }
    }
}
