@extends('layouts.admin')

@section('title', 'Équipes')
@section('page_title', 'Gestion des équipes')

@section('content')

@if(session('success'))
    <div id="ocn-success-message" data-message="{{ session('success') }}"></div>
@endif

@if(session('error'))
    <div id="ocn-error-message" data-message="{{ session('error') }}"></div>
@endif

@if($errors->any())
    <div id="ocn-validation-errors"
         data-errors='@json($errors->all())'></div>
@endif

<div class="card ocn-card shadow-sm">

    <div class="card-header bg-white">

        <div class="row align-items-center">

            <div class="col-md-4">
                <h3 class="card-title ocn-title mb-0">
                    <i class="fas fa-users mr-2"></i>
                    Liste des équipes
                </h3>
            </div>

            {{-- RECHERCHE --}}
            <div class="col-md-5">

                <form action="{{ route('equipes.index') }}" method="GET">

                    <div class="input-group">

                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Rechercher une équipe..."
                               autocomplete="off">

                        <div class="input-group-append">

                            @if(!empty($search))

                                <a href="{{ route('equipes.index') }}"
                                   class="btn btn-secondary"
                                   title="Réinitialiser">

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

            {{-- NOUVELLE ÉQUIPE --}}
            <div class="col-md-3 text-right">

                <button type="button"
                        class="btn ocn-btn"
                        data-toggle="modal"
                        data-target="#modalCreateEquipe">

                    <i class="fas fa-plus mr-1"></i>
                    Nouvelle équipe

                </button>

            </div>

        </div>

    </div>



    {{-- TABLEAU --}}

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="ocn-table-header">

                    <tr>

                        <th>#</th>
                        <th>Nom de l'équipe</th>
                        <th>Chauffeur titulaire</th>
                        <th>Chauffeur secondaire</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($equipes as $equipe)

                        <tr>

                            <td>
                                {{ $equipe->id }}
                            </td>

                            <td>

                                <strong class="ocn-green">

                                    {{ $equipe->code }}

                                </strong>

                            </td>

                            <td>

                                @if($equipe->chauffeurTitulaire)

                                    {{ $equipe->chauffeurTitulaire->nom }}
                                    {{ $equipe->chauffeurTitulaire->prenom }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $equipe->chauffeurTitulaire->matricule }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        Non affecté
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($equipe->chauffeurSecondaire)

                                    {{ $equipe->chauffeurSecondaire->nom }}
                                    {{ $equipe->chauffeurSecondaire->prenom }}

                                    <br>

                                    <small class="text-muted">
                                        {{ $equipe->chauffeurSecondaire->matricule }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        Non affecté
                                    </span>

                                @endif

                            </td>

                            <td>

                              @if($equipe->statut === 'disponible')

                                <span class="badge badge-success">
                                    Disponible
                                </span>

                            @elseif($equipe->statut === 'en_voyage')

                                <span class="badge badge-primary">
                                    En voyage
                                </span>

                            @elseif($equipe->statut === 'indisponible')

                                <span class="badge badge-warning">
                                    Indisponible
                                </span>

                            @endif



                            </td>

                            <td class="text-center">

                                {{-- VOIR --}}

                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowEquipe{{ $equipe->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                {{-- MODIFIER --}}

                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditEquipe{{ $equipe->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>


                                {{-- SUPPRIMER --}}

                                <form action="{{ route('equipes.destroy', $equipe) }}"
                                      method="POST"
                                      class="d-inline delete-form"
                                      data-delete-message="Cette équipe sera définitivement supprimée.">

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

                            <td colspan="6"
                                class="text-center py-5">

                                <i class="fas fa-users fa-3x text-muted"></i>

                                <p class="mt-2 mb-0 text-muted">

                                    @if(!empty($search))

                                        Aucune équipe trouvée.

                                    @else

                                        Aucune équipe enregistrée.

                                    @endif

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}

    @if($equipes->hasPages())

        <div class="card-footer bg-white">

            {{ $equipes->links() }}

        </div>

    @endif

</div>


{{-- MODALS --}}

@include('equipes.modal.create')

@include('equipes.modal.edit')

@include('equipes.modal.show')

@endsection
