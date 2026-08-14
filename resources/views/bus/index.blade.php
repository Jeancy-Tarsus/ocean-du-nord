@extends('layouts.admin')

@section('title', 'Bus')

@section('page_title', 'Gestion du parc automobile')

@section('content')

{{-- =========================================================
     MESSAGES
========================================================= --}}

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


{{-- =========================================================
     CARD PRINCIPALE
========================================================= --}}

<div class="card ocn-card shadow-sm">

    {{-- <div class="card-header bg-white">

        <h3 class="card-title ocn-title">

            <i class="fas fa-bus mr-2"></i>

            Liste des bus

        </h3>


        <div class="card-tools">

            <button type="button"
                    class="btn ocn-btn btn-sm"
                    data-toggle="modal"
                    data-target="#modalCreateBus">

                <i class="fas fa-plus mr-1"></i>

                Nouveau bus

            </button>

        </div>

    </div> --}}
    <div class="card-header bg-white">

    <div class="row align-items-center">

        {{-- TITRE --}}
        <div class="col-md-4">

            <h3 class="card-title ocn-title mb-0">

                <i class="fas fa-bus mr-2"></i>

                Liste des bus

            </h3>

        </div>


       {{-- RECHERCHE --}}
        <div class="col-md-5">

            <form action="{{ route('bus.index') }}"
                method="GET">

                <div class="input-group">

                    <input type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        class="form-control"
                        placeholder="Rechercher un bus..."
                        autocomplete="off">

                    <div class="input-group-append">

                        @if(!empty($search))

                            {{-- BOUTON X --}}

                            <a href="{{ route('bus.index') }}"
                            class="btn btn-secondary"
                            title="Réinitialiser la recherche">

                                <i class="fas fa-times"></i>

                            </a>

                        @endif


                        {{-- BOUTON RECHERCHE --}}

                        <button type="submit"
                                class="btn ocn-btn">

                            <i class="fas fa-search mr-1"></i>

                            Rechercher

                        </button>

                    </div>

                </div>

            </form>

        </div>


        {{-- NOUVEAU --}}
        <div class="col-md-3 text-right">

            <button type="button"
                    class="btn ocn-btn"
                    data-toggle="modal"
                    data-target="#modalCreateBus">

                <i class="fas fa-plus mr-1"></i>

                Nouveau bus

            </button>

        </div>

    </div>

</div>


    {{-- =====================================================
         TABLEAU
    ====================================================== --}}

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="ocn-table-header">

                    <tr>

                        <th>#</th>

                        <th>N° Bus</th>

                        <th>Immatriculation</th>

                        <th>Marque / Modèle</th>

                        <th>Capacité</th>

                        <th>État</th>

                        <th>Statut</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($bus as $bu)

                        <tr>

                            <td>
                                {{ $bu->id }}
                            </td>


                            <td>

                                <strong class="ocn-green">
                                    {{ $bu->numero }}
                                </strong>

                            </td>


                            <td>
                                {{ $bu->immatriculation }}
                            </td>


                            <td>

                                {{ $bu->marque ?? '-' }}

                                @if($bu->modele)

                                    <br>

                                    <small class="text-muted">
                                        {{ $bu->modele }}
                                    </small>

                                @endif

                            </td>


                            <td>

                                <span class="badge ocn-badge">

                                    {{ $bu->capacite }} places

                                </span>

                            </td>


                            {{-- ÉTAT --}}

                            <td>

                                @switch($bu->etat)

                                    @case('bon')

                                        <span class="badge badge-success">
                                            Bon
                                        </span>

                                        @break

                                    @case('moyen')

                                        <span class="badge badge-warning">
                                            Moyen
                                        </span>

                                        @break

                                    @case('mauvais')

                                        <span class="badge badge-danger">
                                            Mauvais
                                        </span>

                                        @break

                                @endswitch

                            </td>


                            {{-- STATUT --}}

                            <td>

                                @switch($bu->statut)

                                    @case('disponible')

                                        <span class="badge badge-success">
                                            Disponible
                                        </span>

                                        @break

                                    @case('en_voyage')

                                        <span class="badge badge-primary">
                                            En voyage
                                        </span>

                                        @break

                                    @case('en_maintenance')

                                        <span class="badge badge-warning">
                                            Maintenance
                                        </span>

                                        @break

                                    @case('en_panne')

                                        <span class="badge badge-danger">
                                            En panne
                                        </span>

                                        @break

                                    @case('hors_service')

                                        <span class="badge badge-dark">
                                            Hors service
                                        </span>

                                        @break

                                @endswitch

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-center">

                                {{-- VOIR --}}

                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowBus{{ $bu->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                {{-- MODIFIER --}}

                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditBus{{ $bu->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>


                                {{-- SUPPRIMER --}}

                               <form action="{{ route('bus.destroy', $bu) }}"
                                    method="POST"
                                    class="d-inline delete-form"
                                    data-delete-message="Ce bus sera définitivement supprimé.">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Supprimer">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <i class="fas fa-bus fa-2x text-muted"></i>

                                <p class="mt-2 mb-0">

                                    Aucun bus enregistré.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}

    @if($bus->hasPages())

        <div class="card-footer bg-white">

            {{ $bus->links() }}

        </div>

    @endif

</div>


{{-- =========================================================
     MODALS
========================================================= --}}

@include('bus.modal.create')

@include('bus.modal.edit')

@include('bus.modal.show')

@endsection
