@extends('layouts.admin')

@section('title', 'Voyages')

@section('page_title', 'Gestion des voyages')

@section('content')

@if(session('success'))

    <div id="ocn-success-message"
         data-message="{{ session('success') }}">
    </div>

@endif


@if(session('error'))

    <div id="ocn-error-message"
         data-message="{{ session('error') }}">
    </div>

@endif


@if($errors->any())

    <div id="ocn-validation-errors"
         data-errors='@json($errors->all())'>
    </div>

@endif


<div class="card ocn-card shadow-sm">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="card-header bg-white">

        <div class="row align-items-center">

            <div class="col-md-4">

                <h3 class="card-title ocn-title mb-0">

                    <i class="fas fa-road mr-2"></i>

                    Liste des voyages

                </h3>

            </div>


            <div class="col-md-5">

                <form action="{{ route('voyages.index') }}"
                      method="GET">

                    <div class="input-group">

                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Rechercher par code ou ligne..."
                               autocomplete="off">


                        <div class="input-group-append">

                            @if(!empty($search))

                                <a href="{{ route('voyages.index') }}"
                                   class="btn btn-secondary">

                                    <i class="fas fa-times"></i>

                                </a>

                            @endif


                            <button type="submit"
                                    class="btn ocn-btn">

                                <i class="fas fa-search mr-1"></i>

                                Rechercher

                            </button>

                        </div>

                    </div>

                </form>

            </div>


            <div class="col-md-3 text-right">

                <button type="button"
                        class="btn ocn-btn"
                        data-toggle="modal"
                        data-target="#modalCreateVoyage">

                    <i class="fas fa-plus mr-1"></i>

                    Nouveau voyage

                </button>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TABLEAU
    ========================================================== --}}

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="ocn-table-header">

                    <tr>

                        <th>#</th>

                        <th>Code</th>

                        <th>Ligne</th>

                        <th>Bus</th>

                        <th>Équipe</th>

                        <th>Départ</th>

                        <th>Arrivée prévue</th>

                        <th>Statut</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($voyages as $voyage)

                        <tr>

                            {{-- ID --}}

                            <td>

                                {{ $voyage->id }}

                            </td>


                            {{-- CODE --}}

                            <td>

                                <strong class="ocn-green">

                                    {{ $voyage->code }}

                                </strong>

                            </td>


                            {{-- LIGNE --}}

                            <td>

                                {{ $voyage->ligne->nom ?? '-' }}

                            </td>


                            {{-- BUS --}}

                            <td>

                                @if($voyage->bus)

                                    {{ $voyage->bus->numero }}

                                    —

                                    {{ $voyage->bus->immatriculation }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- EQUIPE --}}

                            <td>

                                {{ $voyage->equipe->nom ?? '-' }}

                            </td>


                            {{-- DEPART --}}

                            <td>

                                @if($voyage->date_depart)

                                    {{ $voyage->date_depart->format('d/m/Y') }}

                                    <br>

                                @endif

                                <small class="text-muted">

                                    {{ $voyage->heure_depart }}

                                </small>

                            </td>


                            {{-- ARRIVEE PREVUE --}}

                            <td>

                                @if($voyage->date_arrivee_prevue)

                                    {{ $voyage->date_arrivee_prevue->format('d/m/Y') }}

                                    @if($voyage->heure_arrivee_prevue)

                                        <br>

                                        <small class="text-muted">

                                            {{ $voyage->heure_arrivee_prevue }}

                                        </small>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            {{-- STATUT --}}

                            <td>

                                @switch($voyage->statut)

                                    @case('planifie')

                                        <span class="badge badge-info">

                                            Planifié

                                        </span>

                                        @break


                                    @case('en_cours')

                                        <span class="badge badge-primary">

                                            En cours

                                        </span>

                                        @break


                                    @case('termine')

                                        <span class="badge badge-success">

                                            Terminé

                                        </span>

                                        @break


                                    @case('annule')

                                        <span class="badge badge-danger">

                                            Annulé

                                        </span>

                                        @break


                                    @default

                                        <span class="badge badge-secondary">

                                            {{ $voyage->statut }}

                                        </span>

                                @endswitch

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-center">


                                {{-- VOIR --}}

                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowVoyage{{ $voyage->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                {{-- MODIFIER --}}

                                @if($voyage->statut === 'planifie')

                                    <button type="button"
                                            class="btn btn-warning btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalEditVoyage{{ $voyage->id }}"
                                            title="Modifier">

                                        <i class="fas fa-edit"></i>

                                    </button>

                                @endif


                                {{-- DEMARRER --}}

                                @if(

                                    auth()->user()->role === 'admin' ||

                                    auth()->user()->role === 'directeur_exploitation' ||

                                    (

                                        auth()->user()->role === 'chef_agence' &&

                                        $voyage->voyageAgences->contains(
                                            'agence_id',
                                            auth()->user()->agence_id
                                        )

                                    )

                                )

                                    @if($voyage->statut === 'planifie')

                                        <form action="{{ route('voyages.start', $voyage) }}"
                                              method="POST"
                                              class="d-inline start-voyage-form">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-success btn-sm"
                                                    title="Démarrer le voyage">

                                                <i class="fas fa-play"></i>

                                            </button>

                                        </form>

                                    @endif

                                @endif


                               


                                {{-- SUPPRIMER --}}

                                @if($voyage->statut === 'termine')

                                    <form action="{{ route('voyages.destroy', $voyage) }}"
                                          method="POST"
                                          class="d-inline delete-form"
                                          data-delete-message="Ce voyage sera définitivement supprimé.">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Supprimer">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <i class="fas fa-road fa-3x text-muted mb-3"></i>

                                <p class="text-muted mb-0">

                                    @if(!empty($search))

                                        Aucun voyage trouvé pour :

                                        <strong>

                                            {{ $search }}

                                        </strong>

                                    @else

                                        Aucun voyage enregistré.

                                    @endif

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         PAGINATION
    ========================================================== --}}

    @if($voyages->hasPages())

        <div class="card-footer bg-white">

            {{ $voyages->links() }}

        </div>

    @endif


</div>


{{-- =============================================================
     MODALS DES VOYAGES

     IMPORTANT :
     Ils sont volontairement EN DEHORS du tableau.
============================================================= --}}

@foreach($voyages as $voyage)

    {{-- MODIFIER --}}

    @include(
        'voyages.modal.edit',
        ['voyage' => $voyage]
    )


    {{-- VOIR --}}

    @include(
        'voyages.modal.show',
        ['voyage' => $voyage]
    )

@endforeach


{{-- =============================================================
     MODAL CREATION
============================================================= --}}

@include('voyages.modal.create')


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | DEMARRER
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.start-voyage-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({

                    title: 'Démarrer le voyage ?',

                    text: 'Le bus, l’équipe et les chauffeurs seront marqués comme en voyage.',

                    icon: 'question',

                    showCancelButton: true,

                    confirmButtonText: 'Oui, démarrer',

                    cancelButtonText: 'Annuler',

                    reverseButtons: true

                }).then(function (result) {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

        });


    /*
    |--------------------------------------------------------------------------
    | TERMINER
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.finish-voyage-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({

                    title: 'Terminer le voyage ?',

                    text: 'Le voyage sera marqué comme terminé et les ressources seront libérées.',

                    icon: 'question',

                    showCancelButton: true,

                    confirmButtonText: 'Oui, terminer',

                    cancelButtonText: 'Annuler',

                    reverseButtons: true

                }).then(function (result) {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

        });

});

</script>

@endpush

@endsection
