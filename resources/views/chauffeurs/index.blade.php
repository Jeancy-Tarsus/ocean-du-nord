@extends('layouts.admin')

@section('title', 'Chauffeurs')

@section('page_title', 'Gestion des chauffeurs')

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

                    <i class="fas fa-id-card mr-2"></i>

                    Liste des chauffeurs

                </h3>

            </div>



            {{-- =================================================
                 RECHERCHE
            ================================================== --}}

            <div class="col-md-5">

                <form action="{{ route('chauffeurs.index') }}"
                      method="GET">

                    <div class="input-group">


                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Rechercher un chauffeur..."
                               autocomplete="off">


                        <div class="input-group-append">


                            {{-- BOUTON X --}}

                            @if(!empty($search))

                                <a href="{{ route('chauffeurs.index') }}"
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
                 NOUVEAU CHAUFFEUR
            ================================================== --}}

            <div class="col-md-3 text-right">

                <button type="button"
                        class="btn ocn-btn"
                        data-toggle="modal"
                        data-target="#modalCreateChauffeur">

                    <i class="fas fa-plus mr-1"></i>

                    Nouveau chauffeur

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


                {{-- =================================================
                     EN-TÊTE
                ================================================== --}}

                <thead class="ocn-table-header">

                    <tr>

                        <th>#</th>

                        <th>Matricule</th>

                        <th>Nom complet</th>

                        <th>Téléphone</th>

                        <th>Permis</th>

                        <th>Disponibilité</th>

                        <th>Statut</th>

                        <th class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>



                {{-- =================================================
                     CORPS
                ================================================== --}}

                <tbody>


                    @forelse($chauffeurs as $chauffeur)


                        <tr>


                            {{-- ID --}}

                            <td>

                                {{ $chauffeur->id }}

                            </td>



                            {{-- MATRICULE --}}

                            <td>

                                <strong class="ocn-green">

                                    {{ $chauffeur->matricule }}

                                </strong>

                            </td>



                            {{-- NOM COMPLET --}}

                            <td>

                                <strong>

                                    {{ $chauffeur->nom }}

                                    {{ $chauffeur->prenom }}

                                </strong>

                            </td>



                            {{-- TELEPHONE --}}

                            <td>

                                {{ $chauffeur->telephone ?? '-' }}

                            </td>



                            {{-- PERMIS --}}

                            <td>

                                {{ $chauffeur->numero_permis }}

                                @if($chauffeur->date_expiration_permis)

                                    <br>

                                    <small class="text-muted">

                                        Expire le

                                        {{ $chauffeur->date_expiration_permis->format('d/m/Y') }}

                                    </small>

                                @endif

                            </td>



                            {{-- DISPONIBILITÉ --}}

                            <td>

                                @if($chauffeur->disponible)

                                    <span class="badge badge-success">

                                        <i class="fas fa-check mr-1"></i>

                                        Disponible

                                    </span>

                                @else

                                    <span class="badge badge-danger">

                                        <i class="fas fa-times mr-1"></i>

                                        Indisponible

                                    </span>

                                @endif

                            </td>



                            {{-- STATUT --}}

                            <td>

                                @switch($chauffeur->statut)


                                    @case('actif')

                                        <span class="badge badge-success">

                                            Actif

                                        </span>

                                        @break


                                    @case('en_voyage')

                                        <span class="badge badge-primary">

                                            En voyage

                                        </span>

                                        @break


                                    @case('indisponible')

                                        <span class="badge badge-warning">

                                            Indisponible

                                        </span>

                                        @break


                                    @case('suspendu')

                                        <span class="badge badge-danger">

                                            Suspendu

                                        </span>

                                        @break


                                    @case('inactif')

                                        <span class="badge badge-secondary">

                                            Inactif

                                        </span>

                                        @break

                                @endswitch

                            </td>



                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                            <td class="text-center">


                                {{-- VOIR --}}

                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowChauffeur{{ $chauffeur->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>



                                {{-- MODIFIER --}}

                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditChauffeur{{ $chauffeur->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>



                                {{-- SUPPRIMER --}}

                                <form action="{{ route('chauffeurs.destroy', $chauffeur) }}"
                                      method="POST"
                                      class="d-inline delete-form"
                                      data-delete-message="Ce chauffeur sera définitivement supprimé.">

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


                        {{-- =================================================
                             AUCUN RÉSULTAT
                        ================================================== --}}

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">


                                <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>


                                <p class="text-muted mb-0">


                                    @if(!empty($search))

                                        Aucun chauffeur trouvé pour :

                                        <strong>

                                            {{ $search }}

                                        </strong>


                                    @else

                                        Aucun chauffeur enregistré.

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

    @if($chauffeurs->hasPages())

        <div class="card-footer bg-white">

            {{ $chauffeurs->links() }}

        </div>

    @endif


</div>



{{-- =========================================================
     MODALS
========================================================= --}}

@include('chauffeurs.modal.create')

@include('chauffeurs.modal.edit')

@include('chauffeurs.modal.show')


@endsection
