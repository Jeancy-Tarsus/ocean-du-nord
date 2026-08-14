@extends('layouts.admin')

@section('title', 'Agences')

@section('page_title', 'Gestion des agences')

@section('content')


{{-- =========================================================
     MESSAGES POUR SWEETALERT
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


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="card-header bg-white">

        <div class="row align-items-center">


            {{-- TITRE --}}

            <div class="col-md-4">

                <h3 class="card-title ocn-title mb-0">

                    <i class="fas fa-building mr-2"></i>

                    Liste des agences

                </h3>

            </div>


            {{-- =================================================
                 RECHERCHE
            ================================================== --}}

            <div class="col-md-5">

                <form action="{{ route('agences.index') }}"
                      method="GET">

                    <div class="input-group">


                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Rechercher par code, nom ou ville..."
                               autocomplete="off">


                        <div class="input-group-append">


                            {{-- BOUTON X --}}

                            @if(!empty($search))

                                <a href="{{ route('agences.index') }}"
                                   class="btn btn-secondary"
                                   title="Réinitialiser la recherche">

                                    <i class="fas fa-times"></i>

                                </a>

                            @endif


                            {{-- RECHERCHER --}}

                            <button type="submit"
                                    class="btn ocn-btn">

                                <i class="fas fa-search mr-1"></i>

                                Rechercher

                            </button>

                        </div>

                    </div>

                </form>

            </div>


            {{-- =================================================
                 NOUVELLE AGENCE
            ================================================== --}}

            <div class="col-md-3 text-right">

                <button type="button"
                        class="btn ocn-btn"
                        data-toggle="modal"
                        data-target="#modalCreateAgence">

                    <i class="fas fa-plus mr-1"></i>

                    Nouvelle agence

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


                {{-- EN-TÊTE --}}

                <thead class="ocn-table-header">

                    <tr>

                        <th>#</th>

                        <th>Code</th>

                        <th>Agence</th>

                        <th>Ville</th>

                        <th>Téléphone</th>

                        <th>Statut</th>

                        <th class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>


                {{-- CORPS --}}

                <tbody>


                    @forelse($agences as $agence)

                        <tr>


                            {{-- ID --}}

                            <td>

                                {{ $agence->id }}

                            </td>


                            {{-- CODE --}}

                            <td>

                                <strong class="ocn-green">

                                    {{ $agence->code }}

                                </strong>

                            </td>


                            {{-- NOM --}}

                            <td>

                                <strong>

                                    {{ $agence->nom }}

                                </strong>

                            </td>


                            {{-- VILLE --}}

                            <td>

                                {{ $agence->ville }}

                            </td>


                            {{-- TELEPHONE --}}

                            <td>

                                {{ $agence->telephone ?? '-' }}

                            </td>


                            {{-- STATUT --}}

                            <td>

                                @if($agence->active)

                                    <span class="badge badge-success">

                                        <i class="fas fa-check mr-1"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge badge-secondary">

                                        <i class="fas fa-times mr-1"></i>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td class="text-center">


                                {{-- VOIR --}}

                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowAgence{{ $agence->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                {{-- MODIFIER --}}

                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditAgence{{ $agence->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>


                                {{-- SUPPRIMER --}}

                                <form action="{{ route('agences.destroy', $agence) }}"
                                      method="POST"
                                      class="d-inline delete-form"
                                      data-delete-message="Cette agence sera définitivement supprimée.">

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

                            <td colspan="7"
                                class="text-center py-5">


                                <i class="fas fa-building fa-3x text-muted mb-3"></i>


                                <p class="text-muted mb-0">


                                    @if(!empty($search))

                                        Aucune agence trouvée pour :

                                        <strong>

                                            {{ $search }}

                                        </strong>

                                    @else

                                        Aucune agence enregistrée.

                                    @endif


                                </p>

                            </td>

                        </tr>

                    @endforelse


                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if($agences->hasPages())

        <div class="card-footer bg-white">

            {{ $agences->links() }}

        </div>

    @endif


</div>


{{-- =========================================================
     MODALS
========================================================= --}}

@include('agences.modal.create')

@include('agences.modal.edit')

@include('agences.modal.show')


@endsection
