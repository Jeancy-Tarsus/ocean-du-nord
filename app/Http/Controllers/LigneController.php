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

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:lignes,code',
            ],

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

        $validated['active'] =
            $request->boolean('active');

        Ligne::create($validated);

        return redirect()
            ->route('lignes.index')
            ->with(
                'success',
                'Ligne enregistrée avec succès.'
            );
    }


    /**
     * Modifier une ligne
     */
    public function update(Request $request, Ligne $ligne)
    {
        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',

                Rule::unique('lignes', 'code')
                    ->ignore($ligne->id),
            ],

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

        $validated['active'] =
            $request->boolean('active');

        $ligne->update($validated);

        return redirect()
            ->route('lignes.index')
            ->with(
                'success',
                'Ligne modifiée avec succès.'
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
