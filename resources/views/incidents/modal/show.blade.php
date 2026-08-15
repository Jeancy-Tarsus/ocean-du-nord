<div class="modal fade"
     id="modalShowIncident{{ $incident->id }}"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalShowIncidentLabel{{ $incident->id }}"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">

            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header">

                <h5 class="modal-title"
                    id="modalShowIncidentLabel{{ $incident->id }}">

                    <i class="fas fa-exclamation-triangle mr-2"></i>

                    Détails de l'incident

                    <span class="text-muted">

                        — {{ $incident->reference }}

                    </span>

                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Fermer">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>


            {{-- =====================================================
                 CONTENU
            ====================================================== --}}

            <div class="modal-body">


                {{-- =================================================
                     INFORMATIONS DU VOYAGE
                ================================================== --}}

                <div class="card border mb-3">

                    <div class="card-header bg-light">

                        <strong>

                            <i class="fas fa-route mr-1"></i>

                            Voyage concerné

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- REFERENCE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Référence

                                </small>

                                <strong>

                                    {{ $incident->reference }}

                                </strong>

                            </div>


                            {{-- VOYAGE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Voyage

                                </small>

                                <strong>

                                    {{ $incident->voyage->code ?? '-' }}

                                </strong>

                            </div>


                            {{-- LIGNE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Ligne

                                </small>

                                <strong>

                                    {{ $incident->voyage->ligne->nom ?? '-' }}

                                </strong>

                            </div>


                            {{-- BUS --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Bus

                                </small>

                                @if($incident->bus)

                                    <strong>

                                        {{ $incident->bus->numero }}

                                    </strong>

                                    @if($incident->bus->immatriculation)

                                        <br>

                                        <small class="text-muted">

                                            {{ $incident->bus->immatriculation }}

                                        </small>

                                    @endif

                                @else

                                    <strong>-</strong>

                                @endif

                            </div>


                            {{-- AGENCE --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Agence concernée

                                </small>

                                <strong>

                                    {{ $incident->agence->nom ?? '-' }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     INFORMATIONS INCIDENT
                ================================================== --}}

                <div class="card border mb-3">

                    <div class="card-header bg-light">

                        <strong>

                            <i class="fas fa-info-circle mr-1"></i>

                            Informations de l'incident

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- TYPE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Type

                                </small>

                                @switch($incident->type)

                                    @case('panne')

                                        <span class="badge badge-danger">

                                            Panne

                                        </span>

                                        @break

                                    @case('accident')

                                        <span class="badge badge-danger">

                                            Accident

                                        </span>

                                        @break

                                    @case('retard')

                                        <span class="badge badge-warning">

                                            Retard

                                        </span>

                                        @break

                                    @case('probleme_chauffeur')

                                        <span class="badge badge-warning">

                                            Problème chauffeur

                                        </span>

                                        @break

                                    @case('probleme_technique')

                                        <span class="badge badge-warning">

                                            Problème technique

                                        </span>

                                        @break

                                    @case('autre')

                                        <span class="badge badge-secondary">

                                            Autre

                                        </span>

                                        @break

                                    @default

                                        <span class="badge badge-secondary">

                                            {{ $incident->type }}

                                        </span>

                                @endswitch

                            </div>


                            {{-- GRAVITE --}}

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

                            </div>


                            {{-- STATUT --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Statut

                                </small>

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

                            </div>

                        </div>


                        {{-- TITRE --}}

                        <div class="mb-3">

                            <small class="text-muted d-block">

                                Titre

                            </small>

                            <strong>

                                {{ $incident->titre }}

                            </strong>

                        </div>


                        {{-- DATE + HEURE --}}

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Date de l'incident

                                </small>

                                <strong>

                                    {{ $incident->date_incident
                                        ? $incident->date_incident->format('d/m/Y')
                                        : '-'
                                    }}

                                </strong>

                            </div>


                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Heure de l'incident

                                </small>

                                <strong>

                                    {{ $incident->heure_incident ?? '-' }}

                                </strong>

                            </div>

                        </div>


                        {{-- DESCRIPTION --}}

                        <div class="mb-3">

                            <small class="text-muted d-block mb-1">

                                Description

                            </small>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(e($incident->description)) !!}

                            </div>

                        </div>


                        {{-- RESOLUTION --}}

                        @if($incident->resolution)

                            <div class="mb-3">

                                <small class="text-muted d-block mb-1">

                                    Résolution

                                </small>

                                <div class="border rounded p-3 bg-light">

                                    {!! nl2br(e($incident->resolution)) !!}

                                </div>

                            </div>

                        @endif


                        {{-- OBSERVATION --}}

                        @if($incident->observation)

                            <div class="mb-3">

                                <small class="text-muted d-block mb-1">

                                    Observation

                                </small>

                                <div class="border rounded p-3 bg-light">

                                    {!! nl2br(e($incident->observation)) !!}

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     DÉCLARATION / RÉSOLUTION
                ================================================== --}}

                <div class="card border">

                    <div class="card-header bg-light">

                        <strong>

                            <i class="fas fa-user mr-1"></i>

                            Suivi

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- DÉCLARÉ PAR --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Déclaré par

                                </small>

                                <strong>

                                    {{ $incident->user->name ?? '-' }}

                                </strong>

                            </div>


                            {{-- DATE CRÉATION --}}

                            <div class="col-md-6 mb-3">

                                <small class="text-muted d-block">

                                    Déclaré le

                                </small>

                                <strong>

                                    {{ $incident->created_at
                                        ? $incident->created_at->format('d/m/Y H:i')
                                        : '-'
                                    }}

                                </strong>

                            </div>


                            {{-- DATE RÉSOLUTION --}}

                            @if($incident->date_resolution)

                                <div class="col-md-6">

                                    <small class="text-muted d-block">

                                        Résolu le

                                    </small>

                                    <strong>

                                        {{ $incident->date_resolution->format('d/m/Y H:i') }}

                                    </strong>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>

                    Fermer

                </button>


                @auth

                    @if(in_array(auth()->user()->role, [
                        'admin',
                        'directeur_exploitation',
                        'chef_parc'
                    ]))

                        <button type="button"
                                class="btn btn-warning"
                                data-dismiss="modal"
                                data-toggle="modal"
                                data-target="#modalEditIncident{{ $incident->id }}">

                            <i class="fas fa-edit mr-1"></i>

                            Modifier

                        </button>

                    @endif

                @endauth

            </div>

        </div>

    </div>

</div>
