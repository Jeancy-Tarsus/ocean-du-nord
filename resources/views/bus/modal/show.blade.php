@foreach($bus as $bu)

<div class="modal fade"
     id="modalShowBus{{ $bu->id }}"
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

                        <i class="fas fa-bus mr-2"></i>

                        Détails du bus

                    </h5>

                    <small class="text-white">

                        Informations du véhicule

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


                {{-- NUMERO --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        N° Bus :

                    </div>

                    <div class="col-md-7">

                        <strong class="ocn-green">

                            {{ $bu->numero }}

                        </strong>

                    </div>

                </div>


                {{-- IMMATRICULATION --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Immatriculation :

                    </div>

                    <div class="col-md-7">

                        {{ $bu->immatriculation }}

                    </div>

                </div>


                {{-- MARQUE --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Marque :

                    </div>

                    <div class="col-md-7">

                        {{ $bu->marque ?? 'Non renseignée' }}

                    </div>

                </div>


                {{-- MODELE --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Modèle :

                    </div>

                    <div class="col-md-7">

                        {{ $bu->modele ?? 'Non renseigné' }}

                    </div>

                </div>


                {{-- CAPACITE --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Capacité :

                    </div>

                    <div class="col-md-7">

                        <span class="badge ocn-badge">

                            {{ $bu->capacite }} places

                        </span>

                    </div>

                </div>


                {{-- ETAT --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        État :

                    </div>

                    <div class="col-md-7">

                        @switch($bu->etat)

                            @case('bon')

                                <span class="badge badge-success">
                                    Bon
                                </span>

                                @break

                            @case('moyen')

                                <span class="badge badge-warning">
                                    Moyen
                                </span>

                                @break

                            @case('mauvais')

                                <span class="badge badge-danger">
                                    Mauvais
                                </span>

                                @break

                        @endswitch

                    </div>

                </div>


                {{-- STATUT --}}

                <div class="row mb-3">

                    <div class="col-md-5 font-weight-bold">

                        Statut :

                    </div>

                    <div class="col-md-7">

                        @switch($bu->statut)

                            @case('disponible')

                                <span class="badge badge-success">
                                    Disponible
                                </span>

                                @break

                            @case('en_voyage')

                                <span class="badge badge-primary">
                                    En voyage
                                </span>

                                @break

                            @case('en_maintenance')

                                <span class="badge badge-warning">
                                    Maintenance
                                </span>

                                @break

                            @case('en_panne')

                                <span class="badge badge-danger">
                                    En panne
                                </span>

                                @break

                            @case('hors_service')

                                <span class="badge badge-dark">
                                    Hors service
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

                        {{ $bu->observation ?? 'Aucune observation.' }}

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
