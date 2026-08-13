<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    /**
     * Afficher la liste des bus.
     */
    public function index()
    {
        $bus = Bus::latest()->paginate(10);

        return view('bus.index', compact('bus'));
    }

    /**
     * Enregistrer un nouveau bus.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => [
                'required',
                'string',
                'max:50',
                'unique:bus,numero',
            ],

            'immatriculation' => [
                'required',
                'string',
                'max:50',
                'unique:bus,immatriculation',
            ],

            'marque' => [
                'nullable',
                'string',
                'max:100',
            ],

            'modele' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacite' => [
                'required',
                'integer',
                'min:1',
            ],

            'etat' => [
                'required',
                Rule::in([
                    'bon',
                    'moyen',
                    'mauvais',
                ]),
            ],

            'statut' => [
                'required',
                Rule::in([
                    'disponible',
                    'en_voyage',
                    'en_maintenance',
                    'en_panne',
                    'hors_service',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);

        Bus::create($validated);

        return redirect()
            ->route('bus.index')
            ->with('success', 'Bus enregistré avec succès.');
    }

    /**
     * Modifier un bus.
     */
    public function update(Request $request, Bus $bu)
    {
        $validated = $request->validate([
            'numero' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bus', 'numero')->ignore($bu->id),
            ],

            'immatriculation' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bus', 'immatriculation')->ignore($bu->id),
            ],

            'marque' => [
                'nullable',
                'string',
                'max:100',
            ],

            'modele' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacite' => [
                'required',
                'integer',
                'min:1',
            ],

            'etat' => [
                'required',
                Rule::in([
                    'bon',
                    'moyen',
                    'mauvais',
                ]),
            ],

            'statut' => [
                'required',
                Rule::in([
                    'disponible',
                    'en_voyage',
                    'en_maintenance',
                    'en_panne',
                    'hors_service',
                ]),
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);

        $bu->update($validated);

        return redirect()
            ->route('bus.index')
            ->with('success', 'Bus modifié avec succès.');
    }

    /**
     * Supprimer un bus.
     */
    public function destroy(Bus $bu)
    {
        $bu->delete();

        return redirect()
            ->route('bus.index')
            ->with('success', 'Bus supprimé avec succès.');
    }
}
