<div class="modal fade"
     id="modalShowAffectation{{ $affectation->id }}"
     tabindex="-1"
     data-backdrop="static"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content border-0 shadow-lg">


            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header ocn-modal-header">

                <div>

                    <h5 class="modal-title text-white mb-1">

                        <i class="fas fa-exchange-alt mr-2"></i>

                        Remplacement de ressources

                    </h5>

                    <small class="text-white">

                        {{ $affectation->voyage->code ?? 'Voyage inconnu' }}

                    </small>

                </div>


                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            {{-- =====================================================
                 BODY
            ====================================================== --}}

            <div class="modal-body p-4">


                {{-- =================================================
                     VOYAGE
                ================================================== --}}

                <div class="card border-0 bg-light mb-4">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-md-4">

                                <small class="text-muted d-block">

                                    VOYAGE

                                </small>

                                <strong class="h5 mb-0">

                                    {{ $affectation->voyage->code ?? '—' }}

                                </strong>

                            </div>


                            <div class="col-md-5">

                                <small class="text-muted d-block">

                                    LIGNE

                                </small>

                                <strong>

                                    {{ $affectation->voyage->ligne->nom ?? '—' }}

                                </strong>

                            </div>


                            <div class="col-md-3 text-md-right">

                                <span class="badge badge-info">

                                    @switch($affectation->type)

                                        @case('remplacement_bus')
                                            Remplacement bus
                                            @break

                                        @case('remplacement_equipe')
                                            Remplacement équipe
                                            @break

                                        @default
                                            Bus + équipe

                                    @endswitch

                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     AVANT / APRÈS
                ================================================== --}}

                <div class="row">


                    {{-- =================================================
                         AVANT
                    ================================================== --}}

                    <div class="col-md-5">

                        <div class="card border h-100">

                            <div class="card-header bg-light">

                                <strong>

                                    <i class="fas fa-history mr-1 text-secondary"></i>

                                    Avant

                                </strong>

                            </div>


                            <div class="card-body">


                                @if(
                                    $affectation->type === 'remplacement_bus'
                                    ||
                                    $affectation->type === 'remplacement_bus_equipe'
                                )

                                    <div class="mb-4">

                                        <small class="text-muted d-block">

                                            BUS

                                        </small>

                                        <div class="d-flex align-items-center mt-1">

                                            <i class="fas fa-bus fa-lg mr-2 text-secondary"></i>

                                            <strong class="h5 mb-0">

                                                {{ $affectation->ancienBus->numero ?? '—' }}

                                            </strong>

                                        </div>

                                    </div>

                                @endif


                                @if(
                                    $affectation->type === 'remplacement_equipe'
                                    ||
                                    $affectation->type === 'remplacement_bus_equipe'
                                )

                                    <div>

                                        <small class="text-muted d-block">

                                            ÉQUIPE

                                        </small>

                                        <div class="d-flex align-items-center mt-1">

                                            <i class="fas fa-users fa-lg mr-2 text-secondary"></i>

                                            <strong>

                                                {{ $affectation->ancienneEquipe->nom ?? '—' }}

                                            </strong>

                                        </div>


                                        @if($affectation->ancienneEquipe)

                                            <small class="text-muted d-block mt-2">

                                                @if($affectation->ancienneEquipe->chauffeurTitulaire)

                                                    Titulaire :

                                                    {{ $affectation->ancienneEquipe->chauffeurTitulaire->nom }}

                                                @endif

                                                @if($affectation->ancienneEquipe->chauffeurSecondaire)

                                                    <br>

                                                    Secondaire :

                                                    {{ $affectation->ancienneEquipe->chauffeurSecondaire->nom }}

                                                @endif

                                            </small>

                                        @endif

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- FLÈCHE --}}

                    <div class="col-md-2 d-flex align-items-center justify-content-center">

                        <div class="text-center">

                            <i class="fas fa-arrow-right fa-2x ocn-green"></i>

                        </div>

                    </div>


                    {{-- =================================================
                         APRÈS
                    ================================================== --}}

                    <div class="col-md-5">

                        <div class="card border h-100">

                            <div class="card-header bg-light">

                                <strong>

                                    <i class="fas fa-random mr-1 ocn-green"></i>

                                    Après

                                </strong>

                            </div>


                            <div class="card-body">


                                @if(
                                    $affectation->type === 'remplacement_bus'
                                    ||
                                    $affectation->type === 'remplacement_bus_equipe'
                                )

                                    <div class="mb-4">

                                        <small class="text-muted d-block">

                                            BUS DE RELAIS

                                        </small>

                                        <div class="d-flex align-items-center mt-1">

                                            <i class="fas fa-bus fa-lg mr-2 ocn-green"></i>

                                            <strong class="h5 mb-0">

                                                {{ $affectation->nouveauBus->numero ?? '—' }}

                                            </strong>

                                        </div>

                                    </div>

                                @endif


                                @if(
                                    $affectation->type === 'remplacement_equipe'
                                    ||
                                    $affectation->type === 'remplacement_bus_equipe'
                                )

                                    <div>

                                        <small class="text-muted d-block">

                                            NOUVELLE ÉQUIPE

                                        </small>

                                        <div class="d-flex align-items-center mt-1">

                                            <i class="fas fa-users fa-lg mr-2 ocn-green"></i>

                                            <strong>

                                                {{ $affectation->nouvelleEquipe->nom ?? '—' }}

                                            </strong>

                                        </div>


                                        @if($affectation->nouvelleEquipe)

                                            <small class="text-muted d-block mt-2">

                                                @if($affectation->nouvelleEquipe->chauffeurTitulaire)

                                                    Titulaire :

                                                    {{ $affectation->nouvelleEquipe->chauffeurTitulaire->nom }}

                                                @endif

                                                @if($affectation->nouvelleEquipe->chauffeurSecondaire)

                                                    <br>

                                                    Secondaire :

                                                    {{ $affectation->nouvelleEquipe->chauffeurSecondaire->nom }}

                                                @endif

                                            </small>

                                        @endif

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     MOTIF
                ================================================== --}}

                <div class="card border-0 bg-light mt-4">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-7">

                                <small class="text-muted d-block">

                                    MOTIF DU REMPLACEMENT

                                </small>

                                <strong>

                                    {{ $affectation->motif }}

                                </strong>

                            </div>


                            <div class="col-md-5">

                                <small class="text-muted d-block">

                                    DATE ET HEURE

                                </small>

                                <strong>

                                    {{ $affectation->date_affectation
                                        ? $affectation->date_affectation->format('d/m/Y')
                                        : '—'
                                    }}

                                    à

                                    {{ $affectation->heure_affectation ?? '—' }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     OBSERVATION
                ================================================== --}}

                @if($affectation->observation)

                    <div class="mt-3">

                        <small class="text-muted d-block">

                            OBSERVATION

                        </small>

                        <div class="p-3 border rounded bg-white">

                            {{ $affectation->observation }}

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     UTILISATEUR
                ================================================== --}}

                <div class="mt-3 text-muted">

                    <small>

                        <i class="fas fa-user mr-1"></i>

                        Enregistré par :

                        <strong>

                            {{ $affectation->user->name ?? '—' }}

                        </strong>

                    </small>

                </div>

            </div>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            <div class="modal-footer bg-light">

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
