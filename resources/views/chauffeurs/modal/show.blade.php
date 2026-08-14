@foreach($chauffeurs as $chauffeur)

<div class="modal fade"
     id="modalShowChauffeur{{ $chauffeur->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-md modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">


            {{-- HEADER --}}

            <div class="modal-header ocn-modal-header">

                <div>

                    <h5 class="modal-title text-white">

                        <i class="fas fa-user-tie mr-2"></i>

                        Détails du chauffeur

                    </h5>

                    <small class="text-white">

                        {{ $chauffeur->matricule }}

                    </small>

                </div>


                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            {{-- BODY --}}

            <div class="modal-body p-4">


                <div class="text-center mb-4">

                    <div class="ocn-light rounded p-3">

                        <i class="fas fa-user-tie fa-3x ocn-green mb-2"></i>

                        <h5 class="mb-0">

                            {{ $chauffeur->nom }}
                            {{ $chauffeur->prenom }}

                        </h5>

                        <small class="text-muted">

                            {{ $chauffeur->matricule }}

                        </small>

                    </div>

                </div>


                {{-- TELEPHONE --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Téléphone :

                    </div>

                    <div class="col-md-7">

                        {{ $chauffeur->telephone ?? 'Non renseigné' }}

                    </div>

                </div>


                {{-- PERMIS --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        N° permis :

                    </div>

                    <div class="col-md-7">

                        {{ $chauffeur->numero_permis }}

                    </div>

                </div>


                {{-- EXPIRATION --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Expiration :

                    </div>

                    <div class="col-md-7">

                        @if($chauffeur->date_expiration_permis)

                            {{ $chauffeur->date_expiration_permis->format('d/m/Y') }}

                        @else

                            Non renseignée

                        @endif

                    </div>

                </div>


                {{-- DISPONIBILITÉ --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Disponibilité :

                    </div>

                    <div class="col-md-7">

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

                    </div>

                </div>


                {{-- STATUT --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Statut :

                    </div>

                    <div class="col-md-7">

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

                    </div>

                </div>


                {{-- OBSERVATION --}}

                <div class="mt-4">

                    <strong>

                        <i class="fas fa-comment-alt mr-1 ocn-green"></i>

                        Observation

                    </strong>

                    <div class="ocn-light rounded p-3 mt-2">

                        {{ $chauffeur->observation ?? 'Aucune observation.' }}

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}

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
