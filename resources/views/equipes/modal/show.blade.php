@foreach($equipes as $equipe)

<div class="modal fade"
     id="modalShowEquipe{{ $equipe->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-md modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            <div class="modal-header ocn-modal-header">

                <div>

                    <h5 class="modal-title text-white">

                        <i class="fas fa-users mr-2"></i>

                        Détails de l'équipe

                    </h5>

                    <small class="text-white">

                        {{ $equipe->code }}

                    </small>

                </div>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <div class="modal-body p-4">

                <div class="text-center mb-4">

                    <div class="ocn-light rounded p-3">

                        <i class="fas fa-users fa-3x ocn-green mb-2"></i>

                        <h5 class="mb-0">
                            {{ $equipe->nom }}
                        </h5>

                    </div>

                </div>


                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">
                        Titulaire :
                    </div>

                    <div class="col-md-7">

                        @if($equipe->chauffeurTitulaire)

                            {{ $equipe->chauffeurTitulaire->nom }}
                            {{ $equipe->chauffeurTitulaire->prenom }}

                            <br>

                            <small class="text-muted">

                                {{ $equipe->chauffeurTitulaire->matricule }}

                            </small>

                        @else

                            Non affecté

                        @endif

                    </div>

                </div>


                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">
                        Secondaire :
                    </div>

                    <div class="col-md-7">

                        @if($equipe->chauffeurSecondaire)

                            {{ $equipe->chauffeurSecondaire->nom }}
                            {{ $equipe->chauffeurSecondaire->prenom }}

                            <br>

                            <small class="text-muted">

                                {{ $equipe->chauffeurSecondaire->matricule }}

                            </small>

                        @else

                            Non affecté

                        @endif

                    </div>

                </div>


                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">
                        Statut :
                    </div>

                    <div class="col-md-7">
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

                    </div>

                </div>


                <div class="mt-4">

                    <strong>

                        <i class="fas fa-comment-alt mr-1 ocn-green"></i>

                        Observation

                    </strong>

                    <div class="ocn-light rounded p-3 mt-2">

                        {{ $equipe->observation ?? 'Aucune observation.' }}

                    </div>

                </div>

            </div>


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

@endforeach
