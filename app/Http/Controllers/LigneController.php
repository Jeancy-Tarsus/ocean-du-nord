<?php

namespace App\Http\Controllers;

use App\Models\Ligne;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LigneController extends Controller
{
    /**
     * Liste des lignes
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lignes = Ligne::query()
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('lignes.index', compact('lignes', 'search'));
    }


    /**
     * Enregistrer une ligne
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
    |--------------------------------------------------------------------------
    | Génération automatique du code
    |--------------------------------------------------------------------------
    */

        $dernierCode = Ligne::where('code', 'like', 'LIG-%')
            ->orderByDesc('id')
            ->value('code');


        if ($dernierCode) {

            $numero = (int) str_replace('LIG-', '', $dernierCode) + 1;
        } else {

            $numero = 1;
        }


        $code = 'LIG-' . str_pad(
            $numero,
            3,
            '0',
            STR_PAD_LEFT
        );


        /*
    |--------------------------------------------------------------------------
    | Ajouter le code généré
    |--------------------------------------------------------------------------
    */

        $validated['code'] = $code;


        /*
    |--------------------------------------------------------------------------
    | Statut
    |--------------------------------------------------------------------------
    */

        $validated['active'] = $request->boolean('active');


        /*
    |--------------------------------------------------------------------------
    | Création
    |--------------------------------------------------------------------------
    */

        Ligne::create($validated);


        return redirect()
            ->route('lignes.index')
            ->with(
                'success',
                "La ligne {$code} a été créée avec succès."
            );
    }

    /**
     * Modifier une ligne
     */
    public function update(Request $request, Ligne $ligne)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],

        ]);


        $validated['active'] = $request->boolean('active');


        $ligne->update($validated);


        return redirect()
            ->route('lignes.index')
            ->with(
                'success',
                "La ligne {$ligne->code} a été modifiée avec succès."
            );
    }


    /**
     * Supprimer une ligne
     */
    public function destroy(Ligne $ligne)
    {
        $ligne->delete();

        return redirect()
            ->route('lignes.index')
            ->with(
                'success',
                'Ligne supprimée avec succès.'
            );
    }
}
