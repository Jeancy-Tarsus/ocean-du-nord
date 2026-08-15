@extends('layouts.admin')

@section('title', 'Incidents')

@section('page_title', 'Gestion des incidents')

@section('content')

<div class="card ocn-card shadow-sm">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="card-header bg-white">

        <div class="row align-items-center">

            {{-- TITRE --}}

            <div class="col-md-4">

                <h3 class="card-title ocn-title mb-0">

                    <i class="fas fa-exclamation-triangle mr-2"></i>

                    Liste des incidents

                </h3>

            </div>


            {{-- RECHERCHE --}}

            <div class="col-md-5">

                <form action="{{ route('incidents.index') }}"
                      method="GET">

                    <div class="input-group">

                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Rechercher un incident..."
                               autocomplete="off">

                        <div class="input-group-append">

                            @if(!empty($search))

                                <a href="{{ route('incidents.index') }}"
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


            {{-- NOUVEL INCIDENT --}}

            <div class="col-md-3 text-right">

                @auth

                    @if(in_array(auth()->user()->role, [
                        'admin',
                        'directeur_exploitation',
                        'chef_parc',
                        'chef_agence',
                        'chauffeur'
                    ]))

                        <button type="button"
                                class="btn ocn-btn"
                                data-toggle="modal"
                                data-target="#modalCreateIncident">

                            <i class="fas fa-plus mr-1"></i>

                            Nouvel incident

                        </button>

                    @endif

                @endauth

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTRES
    ========================================================== --}}

    <div class="card-body border-bottom">

        <form action="{{ route('incidents.index') }}"
              method="GET">

            @if(!empty($search))

                <input type="hidden"
                       name="search"
                       value="{{ $search }}">

            @endif


            <div class="row">

                {{-- TYPE --}}

                <div class="col-md-4 mb-2">

                    <label class="mb-1">

                        Type

                    </label>

                    <select name="type"
                            class="form-control">

                        <option value="">

                            Tous les types

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


                {{-- GRAVITE --}}

                <div class="col-md-3 mb-2">

                    <label class="mb-1">

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


                {{-- STATUT --}}

                <div class="col-md-3 mb-2">

                    <label class="mb-1">

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


                {{-- BOUTONS --}}

                <div class="col-md-2 mb-2 d-flex align-items-end">

                    <button type="submit"
                            class="btn ocn-btn mr-1"
                            title="Filtrer">

                        <i class="fas fa-filter"></i>

                    </button>


                    <a href="{{ route('incidents.index') }}"
                       class="btn btn-secondary"
                       title="Réinitialiser">

                        <i class="fas fa-times"></i>

                    </a>

                </div>

            </div>

        </form>

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

                        <th>Référence</th>

                        <th>Type</th>

                        <th>Titre</th>

                        <th>Voyage</th>

                        <th>Bus</th>

                        <th>Agence</th>

                        <th>Date</th>

                        <th>Gravité</th>

                        <th>Statut</th>

                        <th class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($incidents as $incident)

                        <tr>

                            {{-- ID --}}

                            <td>

                                {{ $incident->id }}

                            </td>


                            {{-- REFERENCE --}}

                            <td>

                                <strong class="ocn-green">

                                    {{ $incident->reference }}

                                </strong>

                            </td>


                            {{-- TYPE --}}

                            <td>

                                @switch($incident->type)

                                    @case('panne')

                                        Panne

                                        @break

                                    @case('accident')

                                        Accident

                                        @break

                                    @case('retard')

                                        Retard

                                        @break

                                    @case('probleme_chauffeur')

                                        Problème chauffeur

                                        @break

                                    @case('probleme_technique')

                                        Problème technique

                                        @break

                                    @case('autre')

                                        Autre

                                        @break

                                    @default

                                        {{ $incident->type }}

                                @endswitch

                            </td>


                            {{-- TITRE --}}

                            <td>

                                <strong>

                                    {{ $incident->titre }}

                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ \Illuminate\Support\Str::limit(
                                        $incident->description,
                                        60
                                    ) }}

                                </small>

                            </td>


                            {{-- VOYAGE --}}

                            <td>

                                @if($incident->voyage)

                                    <strong>

                                        {{ $incident->voyage->code }}

                                    </strong>

                                    @if($incident->voyage->ligne)

                                        <br>

                                        <small class="text-muted">

                                            {{ $incident->voyage->ligne->nom }}

                                        </small>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            {{-- BUS --}}

                            <td>

                                @if($incident->bus)

                                    {{ $incident->bus->numero }}

                                    <br>

                                    <small class="text-muted">

                                        {{ $incident->bus->immatriculation }}

                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- AGENCE --}}

                            <td>

                                {{ $incident->agence->nom ?? '-' }}

                            </td>


                            {{-- DATE --}}

                            <td>

                                {{ $incident->date_incident
                                    ? $incident->date_incident->format('d/m/Y')
                                    : '-'
                                }}

                                @if($incident->heure_incident)

                                    <br>

                                    <small class="text-muted">

                                        {{ $incident->heure_incident }}

                                    </small>

                                @endif

                            </td>


                            {{-- GRAVITE --}}

                            <td>

                                @switch($incident->gravite)

                                    @case('faible')

                                        <span class="badge badge-success">

                                            Faible

                                        </span>

                                        @break

                                    @case('moyenne')

                                        <span class="badge badge-info">

                                            Moyenne

                                        </span>

                                        @break

                                    @case('grave')

                                        <span class="badge badge-warning">

                                            Grave

                                        </span>

                                        @break

                                    @case('critique')

                                        <span class="badge badge-danger">

                                            Critique

                                        </span>

                                        @break

                                    @default

                                        <span class="badge badge-secondary">

                                            {{ $incident->gravite }}

                                        </span>

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

                                    @default

                                        <span class="badge badge-secondary">

                                            {{ $incident->statut }}

                                        </span>

                                @endswitch

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-center">

                                {{-- VOIR --}}

                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowIncident{{ $incident->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                @auth

                                    {{-- MODIFIER --}}

                                    @if(in_array(auth()->user()->role, [
                                        'admin',
                                        'directeur_exploitation',
                                        'chef_parc'
                                    ]))

                                        <button type="button"
                                                class="btn btn-warning btn-sm"
                                                data-toggle="modal"
                                                data-target="#modalEditIncident{{ $incident->id }}"
                                                title="Modifier">

                                            <i class="fas fa-edit"></i>

                                        </button>

                                    @endif


                                    {{-- SUPPRIMER --}}

                                    @if(in_array(auth()->user()->role, [
                                        'admin',
                                        'directeur_exploitation'
                                    ]))

                                        <form action="{{ route('incidents.destroy', $incident) }}"
                                              method="POST"
                                              class="d-inline delete-incident-form">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Supprimer">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    @endif

                                @endauth

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="11"
                                class="text-center py-5">

                                <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>

                                <p class="text-muted mb-0">

                                    Aucun incident enregistré.

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

    @if($incidents->hasPages())

        <div class="card-footer bg-white">

            {{ $incidents->links() }}

        </div>

    @endif

</div>


{{-- =========================================================
     MODAL CREATE
========================================================== --}}

@auth

    @if(in_array(auth()->user()->role, [
        'admin',
        'directeur_exploitation',
        'chef_parc',
        'chef_agence',
        'chauffeur'
    ]))

        @include('incidents.modal.create')

    @endif

@endauth


{{-- =========================================================
     MODALS SHOW / EDIT
========================================================== --}}

@foreach($incidents as $incident)

    @include(
        'incidents.modal.show',
        ['incident' => $incident]
    )


    @auth

        @if(in_array(auth()->user()->role, [
            'admin',
            'directeur_exploitation',
            'chef_parc'
        ]))

            @include(
                'incidents.modal.edit',
                ['incident' => $incident]
            )

        @endif

    @endauth

@endforeach

@endsection
