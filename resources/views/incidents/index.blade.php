@extends('layouts.admin')

@section('title', 'Incidents')

@section('page_title', 'Gestion des Incidents')

@section('content')

<div class="container-fluid">

    <div class="card ocn-card shadow-sm">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="card-header bg-white">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h3 class="card-title ocn-title mb-0">

                        <i class="fas fa-exclamation-triangle mr-2"></i>

                        Gestion des incidents

                    </h3>

                    <small class="text-muted">

                        Suivi et traitement des incidents d'exploitation

                    </small>

                </div>


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

                            <label>Type</label>

                            <select name="type"
                                    class="form-control">

                                <option value="">Tous</option>

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

                            <label>Gravité</label>

                            <select name="gravite"
                                    class="form-control">

                                <option value="">Toutes</option>

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

                            <label>Statut</label>

                            <select name="statut"
                                    class="form-control">

                                <option value="">Tous</option>

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

                            <label class="d-block">&nbsp;</label>

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

                        <thead class="ocn-table-header">

                            <tr>

                                <th>Référence</th>

                                <th>Voyage</th>

                                <th>Bus</th>

                                <th>Type</th>

                                <th>Gravité</th>

                                <th>Statut</th>

                                <th>Déclaré par</th>

                                <th class="text-center"
                                    style="width: 160px;">

                                    Actions

                                </th>

                            </tr>

                        </thead>


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

                                            <span class="text-muted">—</span>

                                        @endif

                                    </td>


                                    {{-- BUS --}}

                                    <td>

                                        @if($incident->bus)

                                            <i class="fas fa-bus ocn-green mr-1"></i>

                                            {{ $incident->bus->numero }}

                                        @else

                                            <span class="text-muted">—</span>

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

                                            <span class="text-muted">—</span>

                                        @endif

                                    </td>


                                    {{-- ACTIONS --}}

                                    <td class="text-center">

                                        @php

                                            $user = auth()->user();

                                            /*
                                            |--------------------------------------------------------------------------
                                            | MODIFICATION
                                            |--------------------------------------------------------------------------
                                            |
                                            | ADMIN :
                                            | toujours
                                            |
                                            | DIRECTEUR :
                                            | toujours
                                            |
                                            | CHEF AGENCE :
                                            | son agence uniquement
                                            | et incident non résolu
                                            |
                                            | AUTRES :
                                            | jamais
                                            |
                                            */

                                            $canEditIncident =

                                                $user->role === 'admin'

                                                ||

                                                $user->role === 'directeur_exploitation'

                                                ||

                                                (
                                                    $user->role === 'chef_agence'

                                                    &&

                                                    $incident->statut !== 'resolu'

                                                    &&

                                                    $incident->agence_id !== null

                                                    &&

                                                    $user->agence_id !== null

                                                    &&

                                                    (int) $incident->agence_id ===
                                                    (int) $user->agence_id
                                                );


                                            /*
                                            |--------------------------------------------------------------------------
                                            | PRISE EN CHARGE
                                            |--------------------------------------------------------------------------
                                            */

                                            $canTakeCharge =

                                                $user->role === 'admin'

                                                ||

                                                $user->role === 'directeur_exploitation'

                                                ||

                                                (
                                                    $user->role === 'chef_agence'

                                                    &&

                                                    $incident->agence_id !== null

                                                    &&

                                                    $user->agence_id !== null

                                                    &&

                                                    (int) $incident->agence_id ===
                                                    (int) $user->agence_id
                                                );


                                            /*
                                            |--------------------------------------------------------------------------
                                            | RÉSOLUTION
                                            |--------------------------------------------------------------------------
                                            */

                                            $canResolve = $canTakeCharge;

                                        @endphp


                                        <div class="d-inline-flex align-items-center">


                                            {{-- VOIR --}}

                                            <button type="button"
                                                    class="btn btn-info btn-sm mr-1"
                                                    data-toggle="modal"
                                                    data-target="#modalShowIncident{{ $incident->id }}"
                                                    title="Voir les détails">

                                                <i class="fas fa-eye"></i>

                                            </button>


                                            {{-- MODIFIER --}}

                                            @if($canEditIncident)

                                                <button type="button"
                                                        class="btn btn-warning btn-sm mr-1"
                                                        data-toggle="modal"
                                                        data-target="#modalEditIncident{{ $incident->id }}"
                                                        title="Modifier">

                                                    <i class="fas fa-edit"></i>

                                                </button>

                                            @endif


                                            {{-- PRENDRE EN CHARGE --}}

                                            @if(
                                                $incident->statut === 'ouvert'
                                                &&
                                                $canTakeCharge
                                            )

                                                <form action="{{ route('incidents.prendreEnCharge', $incident) }}"
                                                      method="POST"
                                                      class="d-inline mr-1">

                                                    @csrf

                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-warning btn-sm"
                                                            title="Prendre en charge">

                                                        <i class="fas fa-hand-paper"></i>

                                                    </button>

                                                </form>

                                            @endif


                                            {{-- RÉSOUDRE --}}

                                            @if(
                                                $incident->statut === 'en_cours'
                                                &&
                                                $canResolve
                                            )

                                                <button type="button"
                                                        class="btn ocn-btn btn-sm mr-1"
                                                        data-toggle="modal"
                                                        data-target="#modalResolveIncident{{ $incident->id }}"
                                                        title="Résoudre">

                                                    <i class="fas fa-check-circle"></i>

                                                </button>

                                            @endif


                                            {{-- SUPPRIMER --}}

                                            @if(
                                                $user->role === 'admin'
                                                ||
                                                $user->role === 'directeur_exploitation'
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


        {{-- PAGINATION --}}

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
     MODALS SHOW
========================================================= --}}

@foreach($incidents as $incident)

    @include(
        'incidents.modal.show',
        ['incident' => $incident]
    )

@endforeach


{{-- =========================================================
     MODALS EDIT
========================================================= --}}

@foreach($incidents as $incident)

    @php

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | RÈGLE MODIFICATION
        |--------------------------------------------------------------------------
        */

        $canEditIncident =

            $user->role === 'admin'

            ||

            $user->role === 'directeur_exploitation'

            ||

            (
                $user->role === 'chef_agence'

                &&

                $incident->statut !== 'resolu'

                &&

                $incident->agence_id !== null

                &&

                $user->agence_id !== null

                &&

                (int) $incident->agence_id ===
                (int) $user->agence_id
            );

    @endphp


    @if($canEditIncident)

        @include(
            'incidents.modal.edit',
            ['incident' => $incident]
        )

    @endif

@endforeach


{{-- =========================================================
     MODALS RÉSOLUTION
========================================================= --}}

@foreach($incidents as $incident)

    @php

        $user = auth()->user();

        $canResolveIncident =

            $user->role === 'admin'

            ||

            $user->role === 'directeur_exploitation'

            ||

            (
                $user->role === 'chef_agence'

                &&

                $incident->agence_id !== null

                &&

                $user->agence_id !== null

                &&

                (int) $incident->agence_id ===
                (int) $user->agence_id
            );

    @endphp


    @if(
        $incident->statut === 'en_cours'
        &&
        $canResolveIncident
    )

        <div class="modal fade"
             id="modalResolveIncident{{ $incident->id }}"
             data-backdrop="static"
             data-keyboard="false"
             tabindex="-1"
             role="dialog"
             aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered"
                 role="document">

                <div class="modal-content shadow-lg border-0">

                    <form action="{{ route('incidents.resoudre', $incident) }}"
                          method="POST">

                        @csrf

                        @method('PATCH')


                        {{-- HEADER --}}

                        <div class="modal-header ocn-modal-header">

                            <div>

                                <h5 class="modal-title text-white">

                                    <i class="fas fa-check-circle mr-2"></i>

                                    Résoudre l'incident

                                </h5>

                                <small class="text-white">

                                    {{ $incident->reference }}

                                    —

                                    {{ $incident->titre }}

                                </small>

                            </div>


                            <button type="button"
                                    class="close text-white"
                                    data-dismiss="modal"
                                    aria-label="Fermer">

                                <span aria-hidden="true">
                                    &times;
                                </span>

                            </button>

                        </div>


                        {{-- BODY --}}

                        <div class="modal-body p-4">

                            <div class="alert alert-light border">

                                <i class="fas fa-info-circle ocn-green mr-1"></i>

                                Vous êtes sur le point de clôturer cet incident.

                            </div>


                            <div class="form-group">

                                <label>

                                    Solution apportée

                                    <span class="text-danger">*</span>

                                </label>

                                <textarea name="resolution"
                                          class="form-control"
                                          rows="5"
                                          placeholder="Décrire la solution apportée..."
                                          required></textarea>

                            </div>


                            <div class="form-group">

                                <label>

                                    Observation finale

                                </label>

                                <textarea name="observation"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Ajouter une observation complémentaire..."></textarea>

                            </div>


                            <small class="text-muted">

                                <span class="text-danger">*</span>

                                La résolution est obligatoire.

                                La date de résolution sera enregistrée automatiquement.

                            </small>

                        </div>


                        {{-- FOOTER --}}

                        <div class="modal-footer ocn-modal-footer">

                            <button type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal">

                                <i class="fas fa-times mr-1"></i>

                                Annuler

                            </button>


                            <button type="submit"
                                    class="btn ocn-btn">

                                <i class="fas fa-check-circle mr-1"></i>

                                Valider la résolution

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

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
    | SUCCÈS
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
    | ERREUR
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
    | ERREURS DE VALIDATION
    |--------------------------------------------------------------------------
    */

    @if($errors->any())

        Swal.fire({

            icon: 'error',

            title: 'Erreur de validation',

            html: @json(
                implode(
                    '<br>',
                    $errors->all()
                )
            ),

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
