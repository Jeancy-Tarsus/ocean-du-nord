@extends('layouts.admin')

@section('title', 'Incidents')

@section('page_title', 'Gestion des Incidents')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
         CARD PRINCIPALE
    ========================================================== --}}

    <div class="card ocn-card shadow-sm">


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="card-header bg-white">

            <div class="row align-items-center">


                {{-- TITRE --}}

                <div class="col-md-6">

                    <h3 class="card-title ocn-title mb-0">

                        <i class="fas fa-exclamation-triangle mr-2"></i>

                        Gestion des incidents

                    </h3>

                    <small class="text-muted">

                        Suivi et traitement des incidents d'exploitation

                    </small>

                </div>


                {{-- BOUTON NOUVEAU --}}

                <div class="col-md-6 text-right">

                    <button type="button"
                            class="btn ocn-btn"
                            data-toggle="modal"
                            data-target="#modalCreateIncident">

                        <i class="fas fa-plus mr-1"></i>

                        Signaler un incident

                    </button>

                </div>

            </div>

        </div>



        {{-- =====================================================
             FILTRES
        ====================================================== --}}

        <div class="card-body border-bottom">

            <form action="{{ route('incidents.index') }}"
                  method="GET">

                <div class="row">


                    {{-- RECHERCHE --}}

                    <div class="col-md-4">

                        <div class="form-group mb-md-0">

                            <label>

                                <i class="fas fa-search ocn-green mr-1"></i>

                                Recherche

                            </label>

                            <input type="text"
                                   name="search"
                                   value="{{ $search ?? '' }}"
                                   class="form-control"
                                   placeholder="Référence, titre ou description..."
                                   autocomplete="off">

                        </div>

                    </div>



                    {{-- TYPE --}}

                    <div class="col-md-2">

                        <div class="form-group mb-md-0">

                            <label>
                                Type
                            </label>

                            <select name="type"
                                    class="form-control">

                                <option value="">
                                    Tous
                                </option>

                                <option value="panne"
                                    {{ ($type ?? '') === 'panne' ? 'selected' : '' }}>
                                    Panne
                                </option>

                                <option value="accident"
                                    {{ ($type ?? '') === 'accident' ? 'selected' : '' }}>
                                    Accident
                                </option>

                                <option value="retard"
                                    {{ ($type ?? '') === 'retard' ? 'selected' : '' }}>
                                    Retard
                                </option>

                                <option value="probleme_chauffeur"
                                    {{ ($type ?? '') === 'probleme_chauffeur' ? 'selected' : '' }}>
                                    Problème chauffeur
                                </option>

                                <option value="probleme_technique"
                                    {{ ($type ?? '') === 'probleme_technique' ? 'selected' : '' }}>
                                    Problème technique
                                </option>

                                <option value="autre"
                                    {{ ($type ?? '') === 'autre' ? 'selected' : '' }}>
                                    Autre
                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- GRAVITÉ --}}

                    <div class="col-md-2">

                        <div class="form-group mb-md-0">

                            <label>
                                Gravité
                            </label>

                            <select name="gravite"
                                    class="form-control">

                                <option value="">
                                    Toutes
                                </option>

                                <option value="faible"
                                    {{ ($gravite ?? '') === 'faible' ? 'selected' : '' }}>
                                    Faible
                                </option>

                                <option value="moyenne"
                                    {{ ($gravite ?? '') === 'moyenne' ? 'selected' : '' }}>
                                    Moyenne
                                </option>

                                <option value="grave"
                                    {{ ($gravite ?? '') === 'grave' ? 'selected' : '' }}>
                                    Grave
                                </option>

                                <option value="critique"
                                    {{ ($gravite ?? '') === 'critique' ? 'selected' : '' }}>
                                    Critique
                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- STATUT --}}

                    <div class="col-md-2">

                        <div class="form-group mb-md-0">

                            <label>
                                Statut
                            </label>

                            <select name="statut"
                                    class="form-control">

                                <option value="">
                                    Tous
                                </option>

                                <option value="ouvert"
                                    {{ ($statut ?? '') === 'ouvert' ? 'selected' : '' }}>
                                    Ouvert
                                </option>

                                <option value="en_cours"
                                    {{ ($statut ?? '') === 'en_cours' ? 'selected' : '' }}>
                                    En cours
                                </option>

                                <option value="resolu"
                                    {{ ($statut ?? '') === 'resolu' ? 'selected' : '' }}>
                                    Résolu
                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- BOUTONS --}}

                    <div class="col-md-2">

                        <div class="form-group mb-md-0">

                            <label class="d-block">
                                &nbsp;
                            </label>

                            <div class="d-flex">

                                <button type="submit"
                                        class="btn ocn-btn mr-2">

                                    <i class="fas fa-search mr-1"></i>

                                    Filtrer

                                </button>


                                <a href="{{ route('incidents.index') }}"
                                   class="btn btn-secondary"
                                   title="Réinitialiser">

                                    <i class="fas fa-redo"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>



        {{-- =====================================================
             TABLEAU
        ====================================================== --}}

        <div class="card-body p-0">


            <div class="ocn-table-wrapper">

                <div class="table-responsive">

                    <table class="table ocn-table mb-0">


                        {{-- EN-TÊTE --}}

                        <thead class="ocn-table-header">

                            <tr>

                                <th>
                                    Référence
                                </th>

                                <th>
                                    Voyage
                                </th>

                                <th>
                                    Bus
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Gravité
                                </th>

                                <th>
                                    Statut
                                </th>

                                <th>
                                    Déclaré par
                                </th>

                                <th class="text-center"
                                    style="width: 140px;">

                                    Actions

                                </th>

                            </tr>

                        </thead>



                        {{-- CORPS --}}

                        <tbody>

                            @forelse($incidents as $incident)

                                <tr>


                                    {{-- RÉFÉRENCE --}}

                                    <td>

                                        <strong class="ocn-green">

                                            {{ $incident->reference }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ \Carbon\Carbon::parse($incident->date_incident)->format('d/m/Y') }}

                                            à

                                            {{ $incident->heure_incident }}

                                        </small>

                                    </td>



                                    {{-- VOYAGE --}}

                                    <td>

                                        @if($incident->voyage)

                                            <strong>

                                                {{ $incident->voyage->code }}

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ $incident->voyage->ligne->nom ?? 'Ligne inconnue' }}

                                            </small>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>



                                    {{-- BUS --}}

                                    <td>

                                        @if($incident->bus)

                                            <i class="fas fa-bus ocn-green mr-1"></i>

                                            {{ $incident->bus->numero }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>



                                    {{-- TYPE --}}

                                    <td>

                                        @switch($incident->type)

                                            @case('panne')

                                                <span class="badge badge-warning">
                                                    Panne
                                                </span>

                                                @break

                                            @case('accident')

                                                <span class="badge badge-danger">
                                                    Accident
                                                </span>

                                                @break

                                            @case('retard')

                                                <span class="badge badge-info">
                                                    Retard
                                                </span>

                                                @break

                                            @case('probleme_chauffeur')

                                                <span class="badge badge-secondary">
                                                    Problème chauffeur
                                                </span>

                                                @break

                                            @case('probleme_technique')

                                                <span class="badge badge-primary">
                                                    Problème technique
                                                </span>

                                                @break

                                            @default

                                                <span class="badge badge-light">
                                                    Autre
                                                </span>

                                        @endswitch

                                    </td>



                                    {{-- GRAVITÉ --}}

                                    <td>

                                        @switch($incident->gravite)

                                            @case('faible')

                                                <span class="badge badge-success">
                                                    Faible
                                                </span>

                                                @break

                                            @case('moyenne')

                                                <span class="badge badge-warning">
                                                    Moyenne
                                                </span>

                                                @break

                                            @case('grave')

                                                <span class="badge badge-orange">
                                                    Grave
                                                </span>

                                                @break

                                            @case('critique')

                                                <span class="badge badge-danger">
                                                    Critique
                                                </span>

                                                @break

                                        @endswitch

                                    </td>



                                    {{-- STATUT --}}

                                    <td>

                                        @switch($incident->statut)

                                            @case('ouvert')

                                                <span class="badge badge-danger">
                                                    Ouvert
                                                </span>

                                                @break

                                            @case('en_cours')

                                                <span class="badge badge-warning">
                                                    En cours
                                                </span>

                                                @break

                                            @case('resolu')

                                                <span class="badge badge-success">
                                                    Résolu
                                                </span>

                                                @break

                                        @endswitch

                                    </td>



                                    {{-- DÉCLARÉ PAR --}}

                                    <td>

                                        @if($incident->user)

                                            {{ $incident->user->name }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>



                                    {{-- ACTIONS --}}

                                    <td class="text-center">

                                        <div class="d-inline-flex align-items-center">


                                            {{-- VOIR --}}

                                            <button type="button"
                                                    class="btn btn-info btn-sm mr-1"
                                                    data-toggle="modal"
                                                    data-target="#modalShowIncident{{ $incident->id }}"
                                                    title="Voir les détails">

                                                <i class="fas fa-eye"></i>

                                            </button>



                                            {{-- =================================================
                                                 DROIT DE MODIFICATION
                                            ================================================== --}}

                                            @php

                                                $canEditIncident = false;

                                                /*
                                                | ADMIN
                                                */

                                                if (
                                                    auth()->user()->role === 'admin'
                                                ) {

                                                    $canEditIncident = true;

                                                }

                                                /*
                                                | DIRECTEUR EXPLOITATION
                                                */

                                                elseif (
                                                    auth()->user()->role ===
                                                    'directeur_exploitation'
                                                ) {

                                                    $canEditIncident = true;

                                                }

                                                /*
                                                | INCIDENT NON RÉSOLU
                                                */

                                                elseif (
                                                    $incident->statut !== 'resolu'
                                                ) {

                                                    /*
                                                    | Chef d'agence
                                                    */

                                                    if (
                                                        auth()->user()->role ===
                                                        'chef_agence'
                                                        &&
                                                        (int) $incident->agence_id ===
                                                        (int) auth()->user()->agence_id
                                                    ) {

                                                        $canEditIncident = true;

                                                    }

                                                    /*
                                                    | Créateur de l'incident
                                                    */

                                                    elseif (
                                                        (int) $incident->user_id ===
                                                        (int) auth()->id()
                                                    ) {

                                                        $canEditIncident = true;

                                                    }

                                                }

                                            @endphp



                                            @if($canEditIncident)

                                                <button type="button"
                                                        class="btn btn-warning btn-sm mr-1"
                                                        data-toggle="modal"
                                                        data-target="#modalEditIncident{{ $incident->id }}"
                                                        title="Modifier">

                                                    <i class="fas fa-edit"></i>

                                                </button>

                                            @endif



                                            {{-- =================================================
                                                 SUPPRIMER
                                            ================================================== --}}

                                            @if(
                                                auth()->user()->role === 'admin'
                                                ||
                                                auth()->user()->role ===
                                                'directeur_exploitation'
                                            )

                                                <form action="{{ route('incidents.destroy', $incident) }}"
                                                      method="POST"
                                                      class="d-inline delete-form">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Supprimer">

                                                        <i class="fas fa-trash"></i>

                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="text-center py-5">

                                        <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>

                                        <h5 class="text-muted">

                                            Aucun incident trouvé

                                        </h5>

                                        <p class="text-muted mb-0">

                                            Aucun incident ne correspond
                                            aux critères sélectionnés.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>



        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($incidents->hasPages())

            <div class="card-footer bg-white">

                {{ $incidents->links() }}

            </div>

        @endif


    </div>

</div>



{{-- =========================================================
     MODAL CRÉATION
========================================================= --}}

@include('incidents.modal.create')



{{-- =========================================================
     MODALS AFFICHAGE
========================================================= --}}

@foreach($incidents as $incident)

    @include(
        'incidents.modal.show',
        ['incident' => $incident]
    )

@endforeach



{{-- =========================================================
     MODALS MODIFICATION
========================================================= --}}

@foreach($incidents as $incident)

    @php

        $canEditIncident = false;

        if (
            auth()->user()->role === 'admin'
        ) {

            $canEditIncident = true;

        }

        elseif (
            auth()->user()->role === 'directeur_exploitation'
        ) {

            $canEditIncident = true;

        }

        elseif (
            $incident->statut !== 'resolu'
        ) {

            if (
                auth()->user()->role === 'chef_agence'
                &&
                (int) $incident->agence_id ===
                (int) auth()->user()->agence_id
            ) {

                $canEditIncident = true;

            }

            elseif (
                (int) $incident->user_id ===
                (int) auth()->id()
            ) {

                $canEditIncident = true;

            }

        }

    @endphp


    @if($canEditIncident)

        @include(
            'incidents.modal.edit',
            ['incident' => $incident]
        )

    @endif

@endforeach



{{-- =========================================================
     SWEETALERT
========================================================= --}}

@push('scripts')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | MESSAGE DE SUCCÈS
    |--------------------------------------------------------------------------
    */

    @if(session('success'))

        Swal.fire({

            icon: 'success',

            title: 'Succès',

            text: @json(session('success')),

            confirmButtonText: 'OK',

            confirmButtonColor: '#28a745',

            timer: 3000,

            timerProgressBar: true

        });

    @endif



    /*
    |--------------------------------------------------------------------------
    | MESSAGE D'ERREUR
    |--------------------------------------------------------------------------
    */

    @if(session('error'))

        Swal.fire({

            icon: 'error',

            title: 'Erreur',

            text: @json(session('error')),

            confirmButtonText: 'OK',

            confirmButtonColor: '#dc3545'

        });

    @endif



    /*
    |--------------------------------------------------------------------------
    | SUPPRESSION
    |--------------------------------------------------------------------------
    */

    $('.delete-form').on('submit', function (e) {

        e.preventDefault();

        const form = this;

        Swal.fire({

            title: 'Supprimer cet incident ?',

            text: 'Cette action est irréversible.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Oui, supprimer',

            cancelButtonText: 'Annuler',

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d',

            reverseButtons: true

        }).then(function (result) {

            if (result.isConfirmed) {

                form.submit();

            }

        });

    });

});

</script>

@endpush


@endsection
