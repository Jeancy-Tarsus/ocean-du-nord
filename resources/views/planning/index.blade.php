@extends('layouts.admin')

@section('title', 'Planning')

@section('page_title', 'Planning des voyages')

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


<div class="card ocn-card shadow-sm">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="card-header bg-white">

        <div class="row align-items-center">

            <div class="col-md-4">

                <h3 class="card-title ocn-title mb-0">

                    <i class="fas fa-calendar-alt mr-2"></i>

                    Planning des voyages

                </h3>

            </div>


            <div class="col-md-8">

                <form action="{{ route('planning.index') }}"
                      method="GET">

                    <div class="row">

                        {{-- DATE --}}

                        <div class="col-md-4 mb-2 mb-md-0">

                            <input type="date"
                                   name="date"
                                   value="{{ $date ?? '' }}"
                                   class="form-control">

                        </div>


                        {{-- STATUT --}}

                        <div class="col-md-3 mb-2 mb-md-0">

                            <select name="statut"
                                    class="form-control">

                                <option value="">
                                    Tous les statuts
                                </option>

                                <option value="planifie"
                                    {{ ($statut ?? '') === 'planifie' ? 'selected' : '' }}>
                                    Planifié
                                </option>

                                <option value="en_cours"
                                    {{ ($statut ?? '') === 'en_cours' ? 'selected' : '' }}>
                                    En cours
                                </option>

                                <option value="termine"
                                    {{ ($statut ?? '') === 'termine' ? 'selected' : '' }}>
                                    Terminé
                                </option>

                                <option value="annule"
                                    {{ ($statut ?? '') === 'annule' ? 'selected' : '' }}>
                                    Annulé
                                </option>

                            </select>

                        </div>


                        {{-- LIGNE --}}

                        <div class="col-md-3 mb-2 mb-md-0">

                            <select name="ligne_id"
                                    class="form-control">

                                <option value="">
                                    Toutes les lignes
                                </option>

                                @foreach($lignes as $ligne)

                                    <option value="{{ $ligne->id }}"
                                        {{ (string) ($ligneId ?? '') === (string) $ligne->id ? 'selected' : '' }}>

                                        {{ $ligne->nom }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- BOUTONS --}}

                        <div class="col-md-2">

                            <div class="d-flex">

                                <button type="submit"
                                        class="btn ocn-btn mr-1">

                                    <i class="fas fa-filter"></i>

                                </button>


                                <a href="{{ route('planning.index') }}"
                                   class="btn btn-secondary">

                                    <i class="fas fa-times"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

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

                        <th>Date</th>

                        <th>Heure</th>

                        <th>Voyage</th>

                        <th>Ligne</th>

                        <th>Bus</th>

                        <th>Équipe / Chauffeurs</th>

                        <th>Parcours</th>

                        <th>Arrivée prévue</th>

                        <th>Statut</th>

                        <th class="text-center">
                            Action
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


                            {{-- DATE --}}

                            <td>

                                @if($voyage->date_depart)

                                    {{ $voyage->date_depart->format('d/m/Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- HEURE --}}

                            <td>

                                {{ $voyage->heure_depart ?? '-' }}

                            </td>


                            {{-- VOYAGE --}}

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

                                    <strong>

                                        {{ $voyage->bus->numero }}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ $voyage->bus->immatriculation }}

                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- EQUIPE + CHAUFFEURS --}}

                            <td>

                                @if($voyage->equipe)

                                    <strong>

                                        {{ $voyage->equipe->nom }}

                                    </strong>


                                    @if($voyage->equipe->chauffeurTitulaire)

                                        <br>

                                        <small class="text-muted">

                                            <i class="fas fa-user mr-1"></i>

                                            {{ $voyage->equipe->chauffeurTitulaire->nom }}

                                            {{ $voyage->equipe->chauffeurTitulaire->prenom }}

                                        </small>

                                    @endif


                                    @if($voyage->equipe->chauffeurSecondaire)

                                        <br>

                                        <small class="text-muted">

                                            <i class="fas fa-user mr-1"></i>

                                            {{ $voyage->equipe->chauffeurSecondaire->nom }}

                                            {{ $voyage->equipe->chauffeurSecondaire->prenom }}

                                        </small>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            {{-- PARCOURS --}}

                            <td>

                                @if($voyage->voyageAgences->count())

                                    @foreach(
                                        $voyage->voyageAgences->sortBy('ordre')
                                        as $etape
                                    )

                                        @if($etape->agence)

                                            @if($etape->type === 'depart')

                                                <strong>

                                                    {{ $etape->agence->nom }}

                                                </strong>

                                            @elseif($etape->type === 'arrivee')

                                                <strong>

                                                    → {{ $etape->agence->nom }}

                                                </strong>

                                            @else

                                                <span class="text-muted">

                                                    → {{ $etape->agence->nom }}

                                                </span>

                                            @endif


                                            @if(!$loop->last)

                                                <br>

                                            @endif

                                        @endif

                                    @endforeach

                                @else

                                    -

                                @endif

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


                            {{-- ACTION --}}

                            <td class="text-center">

                                <a href="{{ route('voyages.index') }}"
                                   class="btn btn-info btn-sm"
                                   title="Voir les détails du voyage">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="11"
                                class="text-center py-5">

                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>

                                <p class="text-muted mb-0">

                                    Aucun voyage ne correspond aux critères sélectionnés.

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

    @if($voyages->hasPages())

        <div class="card-footer bg-white">

            {{ $voyages->links() }}

        </div>

    @endif

</div>


@push('styles')

<style>

.ocn-card {
    border: 0;
    border-radius: 8px;
}

.ocn-title {
    color: #20384d;
    font-weight: 600;
}

.ocn-table-header {
    background: #f8fafc;
}

.ocn-table-header th {
    color: #506070;
    font-size: 13px;
    font-weight: 600;
    border-top: 0;
}

.ocn-green {
    color: #1677d2;
}

.table td {
    vertical-align: middle;
    font-size: 14px;
}

</style>

@endpush

@endsection
