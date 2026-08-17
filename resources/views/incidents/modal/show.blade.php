<div class="modal fade"
     id="modalShowIncident{{ $incident->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
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
                     IDENTIFICATION
                ================================================== --}}

                <div class="card border shadow-sm mb-4">

                    <div class="card-header ocn-light">

                        <strong>

                            <i class="fas fa-info-circle ocn-green mr-1"></i>

                            Identification

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

                            <div class="col-md-12">

                                <small class="text-muted d-block">

                                    Titre

                                </small>

                                <strong>

                                    {{ $incident->titre }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     VOYAGE
                ================================================== --}}

                <div class="card border shadow-sm mb-4">

                    <div class="card-header ocn-light">

                        <strong>

                            <i class="fas fa-route ocn-green mr-1"></i>

                            Informations du voyage

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- VOYAGE --}}

                            <div class="col-md-4 mb-3">

                                <small class="text-muted d-block">

                                    Voyage

                                </small>

                                <strong>

                                    {{ $incident->voyage->code ?? '—' }}

                                </strong>

                            </div>


                            {{-- LIGNE --}}

                            <div class="col-md-8 mb-3">

                                <small class="text-muted d-block">

                                    Ligne

                                </small>

                                <strong>

                                    {{ $incident->voyage->ligne->nom ?? '—' }}

                                </strong>

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

                                <div class="col-md-4">

                                    <small class="text-muted d-block">

                                        <i class="fas fa-users ocn-green mr-1"></i>

                                        Équipe

                                    </small>

                                    <strong>

                                        {{ $incident->voyage->equipe->nom }}

                                    </strong>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted d-block">

                                        Chauffeur titulaire

                                    </small>

                                    <span>

                                        {{ $incident->voyage->equipe->chauffeurTitulaire->nom ?? '—' }}

                                    </span>

                                </div>


                                <div class="col-md-4">

                                    <small class="text-muted d-block">

                                        Chauffeur secondaire

                                    </small>

                                    <span>

                                        {{ $incident->voyage->equipe->chauffeurSecondaire->nom ?? '—' }}

                                    </span>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     DESCRIPTION
                ================================================== --}}

                <div class="card border shadow-sm mb-4">

                    <div class="card-header ocn-light">

                        <strong>

                            <i class="fas fa-align-left ocn-green mr-1"></i>

                            Description de l'incident

                        </strong>

                    </div>


                    <div class="card-body">

                        <p class="mb-0"
                           style="white-space: pre-line;">

                            {{ $incident->description }}

                        </p>

                    </div>

                </div>



                {{-- =================================================
                     DATE / HEURE / STATUT
                ================================================== --}}

                <div class="card border shadow-sm mb-4">

                    <div class="card-header ocn-light">

                        <strong>

                            <i class="fas fa-clock ocn-green mr-1"></i>

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

                                    {{ \Carbon\Carbon::parse($incident->date_incident)->format('d/m/Y') }}

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


                            {{-- RÉSOLUTION --}}

                            @if($incident->resolution)

                                <div class="col-md-12 mb-3">

                                    <small class="text-muted d-block">

                                        Résolution

                                    </small>

                                    <div class="mt-1"
                                         style="white-space: pre-line;">

                                        {{ $incident->resolution }}

                                    </div>

                                </div>

                            @endif


                            {{-- DATE RÉSOLUTION --}}

                            @if($incident->date_resolution)

                                <div class="col-md-6">

                                    <small class="text-muted d-block">

                                        Date de résolution

                                    </small>

                                    <strong>

                                        {{ \Carbon\Carbon::parse($incident->date_resolution)->format('d/m/Y H:i') }}

                                    </strong>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     OBSERVATION
                ================================================== --}}

                @if($incident->observation)

                    <div class="card border shadow-sm mb-4">

                        <div class="card-header ocn-light">

                            <strong>

                                <i class="fas fa-comment-alt ocn-green mr-1"></i>

                                Observation

                            </strong>

                        </div>


                        <div class="card-body">

                            <p class="mb-0"
                               style="white-space: pre-line;">

                                {{ $incident->observation }}

                            </p>

                        </div>

                    </div>

                @endif



                {{-- =================================================
                     DÉCLARANT
                ================================================== --}}

                <div class="card border shadow-sm">

                    <div class="card-header ocn-light">

                        <strong>

                            <i class="fas fa-user ocn-green mr-1"></i>

                            Déclaration

                        </strong>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <small class="text-muted d-block">

                                    Déclaré par

                                </small>

                                <strong>

                                    {{ $incident->user->name ?? 'Utilisateur inconnu' }}

                                </strong>

                            </div>


                            <div class="col-md-6">

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

            </div>



            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            <div class="modal-footer ocn-modal-footer">

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
