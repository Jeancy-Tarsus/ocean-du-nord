@foreach($agences as $agence)

<div class="modal fade"
     id="modalShowAgence{{ $agence->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-md modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">


            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header ocn-modal-header">

                <div>

                    <h5 class="modal-title text-white">

                        <i class="fas fa-building mr-2"></i>

                        Détails de l'agence

                    </h5>

                    <small class="text-white">

                        Informations de l'agence

                    </small>

                </div>


                <button type="button"
                        class="close text-white"
                        data-dismiss="modal"
                        aria-label="Fermer">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>


            {{-- =====================================================
                 BODY
            ====================================================== --}}

            <div class="modal-body p-4">


                {{-- IDENTIFICATION --}}

                <div class="text-center mb-4">

                    <div class="ocn-light rounded p-4">

                        <div class="mb-2">

                            <i class="fas fa-building fa-3x ocn-green"></i>

                        </div>


                        <h5 class="font-weight-bold mb-1">

                            {{ $agence->nom }}

                        </h5>


                        {{-- CODE AUTOMATIQUE --}}

                        <span class="badge badge-light">

                            {{ $agence->code }}

                        </span>

                    </div>

                </div>


                {{-- =================================================
                     INFORMATIONS
                ================================================== --}}


                {{-- VILLE --}}

                <div class="row mb-3">

                    <div class="col-5 font-weight-bold">

                        <i class="fas fa-map-marker-alt mr-2 ocn-green"></i>

                        Ville

                    </div>

                    <div class="col-7">

                        {{ $agence->ville }}

                    </div>

                </div>


                {{-- ADRESSE --}}

                <div class="row mb-3">

                    <div class="col-5 font-weight-bold">

                        <i class="fas fa-location-arrow mr-2 ocn-green"></i>

                        Adresse

                    </div>

                    <div class="col-7">

                        {{ $agence->adresse ?? 'Non renseignée' }}

                    </div>

                </div>


                {{-- TELEPHONE --}}

                <div class="row mb-3">

                    <div class="col-5 font-weight-bold">

                        <i class="fas fa-phone mr-2 ocn-green"></i>

                        Téléphone

                    </div>

                    <div class="col-7">

                        {{ $agence->telephone ?? 'Non renseigné' }}

                    </div>

                </div>


                {{-- STATUT --}}

                <div class="row mb-3">

                    <div class="col-5 font-weight-bold">

                        <i class="fas fa-toggle-on mr-2 ocn-green"></i>

                        Statut

                    </div>

                    <div class="col-7">

                        @if($agence->active)

                            <span class="badge badge-success">

                                <i class="fas fa-check mr-1"></i>

                                Active

                            </span>

                        @else

                            <span class="badge badge-secondary">

                                <i class="fas fa-times mr-1"></i>

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>


                {{-- DATE DE CRÉATION --}}

                <div class="row mb-3">

                    <div class="col-5 font-weight-bold">

                        <i class="fas fa-calendar-plus mr-2 ocn-green"></i>

                        Créée le

                    </div>

                    <div class="col-7">

                        {{ $agence->created_at?->format('d/m/Y H:i') ?? '-' }}

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

@endforeach
