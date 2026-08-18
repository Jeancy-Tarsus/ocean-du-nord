<div class="modal fade"
     id="modalShowIncident{{ $incident->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">


            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header ocn-modal-header">

                <div>

                    <h5 class="modal-title text-white">

                        <i class="fas fa-exclamation-triangle mr-2"></i>

                        Détails de l'incident

                    </h5>

                    <small class="text-white">

                        {{ $incident->reference }}

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


            {{-- =====================================================
                 BODY
            ====================================================== --}}

            <div class="modal-body p-4">


                {{-- =================================================
                     INFORMATIONS PRINCIPALES
                ================================================== --}}

                <div class="card border mb-4">

                    <div class="card-header bg-light">

                        <strong>

                            <i class="fas fa-info-circle mr-1 ocn-green"></i>

                            Informations de l'incident

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- RÉFÉRENCE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Référence

                                </small>

                                <strong class="ocn-green">

                                    {{ $incident->reference }}

                                </strong>

                            </div>


                            {{-- TYPE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Type

                                </small>

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

                            </div>


                            {{-- GRAVITÉ --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Gravité

                                </small>

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

                            </div>


                            {{-- TITRE --}}

                            <div class="col-md-12 mb-3">

                                <small class="text-muted d-block">

                                    Titre

                                </small>

                                <strong>

                                    {{ $incident->titre }}

                                </strong>

                            </div>


                            {{-- DESCRIPTION --}}

                            <div class="col-md-12">

                                <small class="text-muted d-block">

                                    Description

                                </small>

                                <div class="p-3 bg-light rounded">

                                    {{ $incident->description }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     VOYAGE / BUS / ÉQUIPE
                ================================================== --}}

                <div class="card border mb-4">

                    <div class="card-header bg-light">

                        <strong>

                            <i class="fas fa-bus mr-1 ocn-green"></i>

                            Ressources concernées

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- VOYAGE --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    <i class="fas fa-route ocn-green mr-1"></i>

                                    Voyage

                                </small>

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

                            </div>


                            {{-- BUS --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    <i class="fas fa-bus ocn-green mr-1"></i>

                                    Bus

                                </small>

                                <strong>

                                    @if($incident->bus)

                                        {{ $incident->bus->numero }}

                                        @if($incident->bus->immatriculation)

                                            —
                                            {{ $incident->bus->immatriculation }}

                                        @endif

                                    @else

                                        —

                                    @endif

                                </strong>

                            </div>


                            {{-- AGENCE --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    <i class="fas fa-building ocn-green mr-1"></i>

                                    Agence concernée

                                </small>

                                <strong>

                                    {{ $incident->agence->nom ?? '—' }}

                                </strong>

                            </div>


                            {{-- ÉQUIPE --}}

                            @if($incident->voyage && $incident->voyage->equipe)

                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">

                                        <i class="fas fa-users ocn-green mr-1"></i>

                                        Équipe

                                    </small>

                                    <strong>

                                        {{ $incident->voyage->equipe->nom }}

                                    </strong>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DATE / HEURE / STATUT
                ================================================== --}}

                <div class="card border mb-4">

                    <div class="card-header bg-light">

                        <strong>

                            <i class="fas fa-clock mr-1 ocn-green"></i>

                            Suivi

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- DATE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Date de l'incident

                                </small>

                                <strong>

                                    {{ $incident->date_incident
                                        ? $incident->date_incident->format('d/m/Y')
                                        : '—'
                                    }}

                                </strong>

                            </div>


                            {{-- HEURE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Heure

                                </small>

                                <strong>

                                    {{ $incident->heure_incident }}

                                </strong>

                            </div>


                            {{-- STATUT --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Statut

                                </small>


                                @switch($incident->statut)

                                    @case('ouvert')

                                        <span class="badge badge-danger">

                                            <i class="fas fa-folder-open mr-1"></i>

                                            Ouvert

                                        </span>

                                        @break

                                    @case('en_cours')

                                        <span class="badge badge-warning">

                                            <i class="fas fa-spinner mr-1"></i>

                                            En cours

                                        </span>

                                        @break

                                    @case('resolu')

                                        <span class="badge badge-success">

                                            <i class="fas fa-check-circle mr-1"></i>

                                            Résolu

                                        </span>

                                        @break

                                @endswitch

                            </div>


                            {{-- DÉCLARÉ PAR --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Déclaré par

                                </small>

                                <strong>

                                    {{ $incident->user->name ?? '—' }}

                                </strong>

                            </div>


                            {{-- DATE ENREGISTREMENT --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Date d'enregistrement

                                </small>

                                <strong>

                                    {{ $incident->created_at->format('d/m/Y H:i') }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     RÉSOLUTION
                ================================================== --}}

                @if($incident->statut === 'resolu')

                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-check-circle mr-1 text-success"></i>

                                Résolution

                            </strong>

                        </div>


                        <div class="card-body">

                            <div class="mb-3">

                                <small class="text-muted d-block">

                                    Solution apportée

                                </small>

                                <div class="p-3 bg-light rounded">

                                    {{ $incident->resolution ?? '—' }}

                                </div>

                            </div>


                            @if($incident->date_resolution)

                                <small class="text-muted">

                                    Résolu le :

                                    <strong>

                                        {{ \Carbon\Carbon::parse($incident->date_resolution)->format('d/m/Y H:i') }}

                                    </strong>

                                </small>

                            @endif

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     OBSERVATION
                ================================================== --}}

                @if($incident->observation)

                    <div class="card border">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-comment-alt mr-1 ocn-green"></i>

                                Observation

                            </strong>

                        </div>

                        <div class="card-body">

                            {{ $incident->observation }}

                        </div>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            <div class="modal-footer ocn-modal-footer">

                @php

                    $user = auth()->user();

                    $canTakeCharge = false;

                    $canResolve = false;


                    /*
                    |--------------------------------------------------------------------------
                    | ADMIN
                    |--------------------------------------------------------------------------
                    */

                    if ($user->role === 'admin') {

                        $canTakeCharge = true;

                        $canResolve = true;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DIRECTEUR EXPLOITATION
                    |--------------------------------------------------------------------------
                    */

                    elseif ($user->role === 'directeur_exploitation') {

                        $canTakeCharge = true;

                        $canResolve = true;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CHEF AGENCE
                    |--------------------------------------------------------------------------
                    */

                    elseif (
                        $user->role === 'chef_agence'
                        &&
                        $incident->agence_id !== null
                        &&
                        $user->agence_id !== null
                        &&
                        (int) $incident->agence_id ===
                        (int) $user->agence_id
                    ) {

                        $canTakeCharge = true;

                        $canResolve = true;

                    }

                @endphp


                {{-- =================================================
                     INCIDENT OUVERT
                     → UNIQUEMENT PRENDRE EN CHARGE
                ================================================== --}}

                @if(
                    $incident->statut === 'ouvert'
                    &&
                    $canTakeCharge
                )

                    <form action="{{ route('incidents.prendreEnCharge', $incident) }}"
                          method="POST"
                          class="d-inline">

                        @csrf

                        @method('PATCH')

                        <button type="submit"
                                class="btn btn-warning">

                            <i class="fas fa-hand-paper mr-1"></i>

                            Prendre en charge

                        </button>

                    </form>

                @endif


                {{-- =================================================
                     INCIDENT EN COURS
                     → AFFECTER UN BUS
                ================================================== --}}

                @if(
                    $incident->statut === 'en_cours'
                    &&
                    $incident->voyage_id
                    &&
                    $incident->bus_id
                    &&
                    in_array($user->role, [
                        'admin',
                        'directeur_exploitation',
                        'chef_parc',
                        'chef_agence'
                    ])
                )

                    <a href="{{ route('affectations.index', [
                        'voyage_id' => $incident->voyage_id,
                        'motif' => 'Incident ' . $incident->reference . ' : ' . $incident->titre
                    ]) }}"
                       class="btn btn-primary">

                        <i class="fas fa-bus mr-1"></i>

                        Affecter un bus de remplacement

                    </a>

                @endif


                {{-- =================================================
                     INCIDENT EN COURS
                     → RÉSOUDRE
                ================================================== --}}

                @if(
                    $incident->statut === 'en_cours'
                    &&
                    $canResolve
                )

                    <button type="button"
                            class="btn ocn-btn"
                            data-dismiss="modal"
                            data-toggle="modal"
                            data-target="#modalResolveIncident{{ $incident->id }}">

                        <i class="fas fa-check-circle mr-1"></i>

                        Résoudre

                    </button>

                @endif


                {{-- =================================================
                     INCIDENT RÉSOLU
                     → AUCUNE ACTION SUPPLÉMENTAIRE
                ================================================== --}}


                {{-- =================================================
                     FERMER
                ================================================== --}}

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>

                    Fermer

                </button>

            </div>

        </div>

    </div>

</div>
