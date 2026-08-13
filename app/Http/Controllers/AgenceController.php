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
    public function index()
    {
        $agences = Agence::latest()->paginate(10);

        return view('agences.index', compact('agences'));
    }

    /**
     * Enregistrer une nouvelle agence.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:agences,code',
            ],

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

        $validated['active'] = $request->boolean('active');

        Agence::create($validated);

        return redirect()
            ->route('agences.index')
            ->with('success', 'Agence créée avec succès.');
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
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('agences', 'code')->ignore($agence->id),
            ],

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

        $validated['active'] = $request->boolean('active');

        $agence->update($validated);

        return redirect()
            ->route('agences.index')
            ->with('success', 'Agence modifiée avec succès.');
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
