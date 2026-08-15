<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Voyage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    /**
     * Afficher la liste des bus.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $bus = Bus::query()

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('numero', 'like', '%' . $search . '%')
                        ->orWhere('immatriculation', 'like', '%' . $search . '%')
                        ->orWhere('marque', 'like', '%' . $search . '%')
                        ->orWhere('modele', 'like', '%' . $search . '%')
                        ->orWhere('etat', 'like', '%' . $search . '%')
                        ->orWhere('statut', 'like', '%' . $search . '%');
                });
            })

            ->latest()

            ->paginate(10)

            ->withQueryString();


        return view('bus.index', compact(
            'bus',
            'search'
        ));
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
            ->with(
                'success',
                'Bus enregistré avec succès.'
            );
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
                Rule::unique('bus', 'numero')
                    ->ignore($bu->id),
            ],

            'immatriculation' => [
                'required',
                'string',
                'max:50',
                Rule::unique('bus', 'immatriculation')
                    ->ignore($bu->id),
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
            ->with(
                'success',
                'Bus modifié avec succès.'
            );
    }


    /**
     * Supprimer un bus.
     */
    public function destroy($id)
    {
        $bus = Bus::find($id);

        // Bus introuvable
        if (!$bus) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Le bus demandé est introuvable.'
                );
        }

        $numero = $bus->numero;


        /*
    |--------------------------------------------------------------------------
    | Vérifier si le bus est utilisé dans un voyage
    |--------------------------------------------------------------------------
    */

        $utiliseDansVoyage = Voyage::where(
            'bus_id',
            $bus->id
        )->exists();


        if ($utiliseDansVoyage) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Impossible de supprimer le bus {$numero} : il est actuellement utilisé dans un voyage."
                );
        }


        /*
    |--------------------------------------------------------------------------
    | Suppression
    |--------------------------------------------------------------------------
    */

        try {

            $bus->delete();


            /*
        |--------------------------------------------------------------------------
        | Vérification
        |--------------------------------------------------------------------------
        */

            if (Bus::find($bus->id)) {

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        "Le bus {$numero} n'a pas pu être supprimé."
                    );
            }


            return redirect()
                ->route('bus.index')
                ->with(
                    'success',
                    "Le bus {$numero} a été supprimé avec succès."
                );
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Impossible de supprimer le bus {$numero} : il est utilisé par d'autres données."
                );
        }
    }
}
