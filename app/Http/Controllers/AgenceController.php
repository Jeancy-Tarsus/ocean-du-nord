<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgenceController extends Controller
{
    /**
     * Afficher la liste des agences.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $agences = Agence::query()

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('code', 'like', '%' . $search . '%')
                        ->orWhere('nom', 'like', '%' . $search . '%')
                        ->orWhere('ville', 'like', '%' . $search . '%');
                });
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view('agences.index', compact(
            'agences',
            'search'
        ));
    }

    /**
     * Enregistrer une nouvelle agence.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'ville' => [
                'required',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
    |--------------------------------------------------------------------------
    | Génération automatique du code agence
    |--------------------------------------------------------------------------
    |
    | Exemple :
    | AG-001
    | AG-002
    | AG-003
    |
    */

        $dernierCode = Agence::where('code', 'like', 'AG-%')
            ->orderByDesc('id')
            ->value('code');


        if ($dernierCode) {

            $numero = (int) str_replace('AG-', '', $dernierCode) + 1;
        } else {

            $numero = 1;
        }


        $code = 'AG-' . str_pad(
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
    | Création de l'agence
    |--------------------------------------------------------------------------
    */

        Agence::create($validated);


        return redirect()
            ->route('agences.index')
            ->with(
                'success',
                "L'agence {$code} a été créée avec succès."
            );
    }

    /**
     * Afficher une agence.
     */
    public function show(Agence $agence)
    {
        return view('agences.show', compact('agence'));
    }

    /**
     * Modifier une agence.
     */
    public function update(Request $request, Agence $agence)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'ville' => [
                'required',
                'string',
                'max:255',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
    |--------------------------------------------------------------------------
    | Statut
    |--------------------------------------------------------------------------
    */

        $validated['active'] = $request->boolean('active');


        /*
    |--------------------------------------------------------------------------
    | Modification
    |--------------------------------------------------------------------------
    |
    | Le code de l'agence n'est volontairement pas inclus.
    | Il reste donc inchangé.
    |
    */

        $agence->update($validated);


        return redirect()
            ->route('agences.index')
            ->with(
                'success',
                "L'agence {$agence->code} a été modifiée avec succès."
            );
    }
    /**
     * Supprimer une agence.
     */
    public function destroy(Agence $agence)
    {
        $agence->delete();

        return redirect()
            ->route('agences.index')
            ->with('success', 'Agence supprimée avec succès.');
    }
}
