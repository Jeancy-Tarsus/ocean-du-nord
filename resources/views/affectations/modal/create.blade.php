<div class="modal fade"
     id="modalCreateAffectation"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('affectations.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf

                {{-- =====================================================
                     HEADER
                ====================================================== --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-exchange-alt mr-2"></i>

                            Nouvelle affectation

                        </h5>

                        <small class="text-white">

                            Remplacement d'une ressource pendant un voyage

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

                    <div class="form-group">

                        <label>

                            Voyage

                            <span class="text-danger">*</span>

                        </label>

                        <select name="voyage_id"
                                id="affectationVoyage"
                                class="form-control"
                                required>

                            <option value="">

                                Sélectionner un voyage

                            </option>

                            @foreach($voyages as $voyage)

                                <option value="{{ $voyage->id }}"
                                        data-code="{{ $voyage->code }}"
                                        data-ligne="{{ $voyage->ligne->nom ?? 'Ligne inconnue' }}"
                                        data-statut="{{ $voyage->statut }}"
                                        data-bus-id="{{ $voyage->bus_id ?? '' }}"
                                        data-bus="{{ $voyage->bus->numero ?? '—' }}"
                                        data-equipe-id="{{ $voyage->equipe_id ?? '' }}"
                                        data-equipe="{{ $voyage->equipe->nom ?? '—' }}"

                                    @if(
                                        isset($prefillVoyageId)
                                        &&
                                        (int) $prefillVoyageId === (int) $voyage->id
                                    )
                                        selected
                                    @endif
                                >

                                    {{ $voyage->code }}
                                    —
                                    {{ $voyage->ligne->nom ?? 'Ligne inconnue' }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         INFORMATIONS VOYAGE
                    ================================================== --}}

                    <div id="voyageInformations"
                         class="alert alert-light border"
                         style="display:none;">

                        <div class="row">

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Voyage
                                </small>

                                <strong id="infoVoyage">
                                    —
                                </strong>

                            </div>

                            <div class="col-md-5">

                                <small class="text-muted d-block">
                                    Ligne
                                </small>

                                <strong id="infoLigne">
                                    —
                                </strong>

                            </div>

                            <div class="col-md-3">

                                <small class="text-muted d-block">
                                    Statut
                                </small>

                                <strong id="infoStatut">
                                    —
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         TYPE
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Type de remplacement

                            <span class="text-danger">*</span>

                        </label>

                        <select name="type"
                                id="affectationType"
                                class="form-control"
                                required>

                            <option value="">

                                Sélectionner

                            </option>

                            <option value="remplacement_bus">

                                Remplacement du bus

                            </option>

                            <option value="remplacement_equipe">

                                Remplacement de l'équipe

                            </option>

                            <option value="remplacement_bus_equipe">

                                Remplacement bus + équipe

                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                         RESSOURCES ACTUELLES
                    ================================================== --}}

                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-history mr-1 ocn-green"></i>

                                Ressources actuellement prévues

                            </strong>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- BUS ACTUEL --}}

                                <div class="col-md-6">

                                    <div class="form-group mb-md-0">

                                        <label>
                                            Bus actuel
                                        </label>

                                        <input type="text"
                                               id="ancienBusLabel"
                                               class="form-control"
                                               value="Sélectionnez d'abord un voyage"
                                               readonly>

                                        <input type="hidden"
                                               name="ancien_bus_id"
                                               id="ancienBusId">

                                    </div>

                                </div>


                                {{-- ÉQUIPE ACTUELLE --}}

                                <div class="col-md-6">

                                    <div class="form-group mb-md-0">

                                        <label>
                                            Équipe actuelle
                                        </label>

                                        <input type="text"
                                               id="ancienneEquipeLabel"
                                               class="form-control"
                                               value="Sélectionnez d'abord un voyage"
                                               readonly>

                                        <input type="hidden"
                                               name="ancienne_equipe_id"
                                               id="ancienneEquipeId">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         RESSOURCES DE REMPLACEMENT
                    ================================================== --}}

                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-random mr-1 ocn-green"></i>

                                Ressources de remplacement

                            </strong>

                        </div>

                        <div class="card-body">


                            {{-- =================================================
                                 BUS DE REMPLACEMENT
                            ================================================== --}}

                            <div id="blocNouveauBus"
                                 class="form-group"
                                 style="display:none;">

                                <label>

                                    Bus de remplacement

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="nouveau_bus_id"
                                        id="nouveauBus"
                                        class="form-control">

                                    <option value="">

                                        Sélectionner un bus disponible

                                    </option>

                                    @foreach($busesDisponibles as $bus)

                                        <option value="{{ $bus->id }}">

                                            {{ $bus->numero }}

                                            @if($bus->immatriculation)

                                                —
                                                {{ $bus->immatriculation }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">

                                    Seuls les bus disponibles sont proposés.

                                </small>

                            </div>


                            {{-- =================================================
                                 NOUVELLE ÉQUIPE
                            ================================================== --}}

                            <div id="blocNouvelleEquipe"
                                 class="form-group"
                                 style="display:none;">

                                <label>

                                    Nouvelle équipe

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="nouvelle_equipe_id"
                                        id="nouvelleEquipe"
                                        class="form-control">

                                    <option value="">

                                        Sélectionner une équipe

                                    </option>

                                    @foreach($equipes as $equipe)

                                        <option value="{{ $equipe->id }}">

                                            {{ $equipe->nom }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- =================================================
                                 MESSAGE INITIAL
                            ================================================== --}}

                            <div id="messageChoixRemplacement"
                                 class="text-muted text-center py-3">

                                <i class="fas fa-info-circle mr-1"></i>

                                Sélectionnez le type de remplacement.

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         MOTIF
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Motif du remplacement

                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="motif"
                               id="affectationMotif"
                               class="form-control"
                               placeholder="Ex : Panne moteur du bus"
                               value="{{ $prefillMotif ?? old('motif') }}"
                               required>

                    </div>


                    {{-- =================================================
                         DATE / HEURE
                    ================================================== --}}

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Date

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="date"
                                       name="date_affectation"
                                       class="form-control"
                                       value="{{ old('date_affectation', date('Y-m-d')) }}"
                                       required>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Heure

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="time"
                                       name="heure_affectation"
                                       class="form-control"
                                       value="{{ old('heure_affectation', date('H:i')) }}"
                                       required>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         OBSERVATION
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Observation

                        </label>

                        <textarea name="observation"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Ajouter une observation...">{{ old('observation') }}</textarea>

                    </div>


                    {{-- =================================================
                         INFORMATION
                    ================================================== --}}

                    <div class="alert alert-warning mb-0">

                        <i class="fas fa-info-circle mr-1"></i>

                        <strong>Important :</strong>

                        le voyage conserve son bus et son équipe
                        d'origine. Cette opération enregistre le
                        remplacement dans l'historique des affectations.

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

                        Annuler

                    </button>

                    <button type="submit"
                            class="btn ocn-btn">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

(function () {

    /*
    |--------------------------------------------------------------------------
    | FONCTION : AFFICHER LES CHAMPS SELON LE TYPE
    |--------------------------------------------------------------------------
    */

    function gererTypeAffectation() {

        var typeElement =
            document.getElementById('affectationType');

        var blocBus =
            document.getElementById('blocNouveauBus');

        var blocEquipe =
            document.getElementById('blocNouvelleEquipe');

        var selectBus =
            document.getElementById('nouveauBus');

        var selectEquipe =
            document.getElementById('nouvelleEquipe');

        var message =
            document.getElementById('messageChoixRemplacement');


        if (!typeElement) {
            return;
        }


        var type =
            typeElement.value;


        /*
        |--------------------------------------------------------------------------
        | TOUT CACHER D'ABORD
        |--------------------------------------------------------------------------
        */

        if (blocBus) {

            blocBus.style.display = 'none';

        }


        if (blocEquipe) {

            blocEquipe.style.display = 'none';

        }


        if (selectBus) {

            selectBus.required = false;

        }


        if (selectEquipe) {

            selectEquipe.required = false;

        }


        if (message) {

            message.style.display = 'none';

        }


        /*
        |--------------------------------------------------------------------------
        | REMPLACEMENT BUS
        |--------------------------------------------------------------------------
        */

        if (
            type === 'remplacement_bus'
            ||
            type === 'remplacement_bus_equipe'
        ) {

            if (blocBus) {

                blocBus.style.display = 'block';

            }


            if (selectBus) {

                selectBus.required = true;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | REMPLACEMENT ÉQUIPE
        |--------------------------------------------------------------------------
        */

        if (
            type === 'remplacement_equipe'
            ||
            type === 'remplacement_bus_equipe'
        ) {

            if (blocEquipe) {

                blocEquipe.style.display = 'block';

            }


            if (selectEquipe) {

                selectEquipe.required = true;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | AUCUN TYPE
        |--------------------------------------------------------------------------
        */

        if (!type) {

            if (message) {

                message.style.display = 'block';

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CHARGER LE VOYAGE
    |--------------------------------------------------------------------------
    */

    function chargerVoyageAffectation() {

        var select =
            document.getElementById('affectationVoyage');


        if (!select) {
            return;
        }


        var option =
            select.options[select.selectedIndex];


        if (
            !option
            ||
            !option.value
        ) {

            document.getElementById(
                'voyageInformations'
            ).style.display = 'none';


            document.getElementById(
                'ancienBusLabel'
            ).value =
                'Sélectionnez d\'abord un voyage';


            document.getElementById(
                'ancienneEquipeLabel'
            ).value =
                'Sélectionnez d\'abord un voyage';


            document.getElementById(
                'ancienBusId'
            ).value = '';


            document.getElementById(
                'ancienneEquipeId'
            ).value = '';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRATION DES DONNÉES
        |--------------------------------------------------------------------------
        */

        var code =
            option.getAttribute('data-code') || '—';

        var ligne =
            option.getAttribute('data-ligne') || '—';

        var statut =
            option.getAttribute('data-statut') || '—';

        var busId =
            option.getAttribute('data-bus-id') || '';

        var bus =
            option.getAttribute('data-bus') || '—';

        var equipeId =
            option.getAttribute('data-equipe-id') || '';

        var equipe =
            option.getAttribute('data-equipe') || '—';


        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS DU VOYAGE
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'voyageInformations'
        ).style.display = 'block';


        document.getElementById(
            'infoVoyage'
        ).textContent = code;


        document.getElementById(
            'infoLigne'
        ).textContent = ligne;


        document.getElementById(
            'infoStatut'
        ).textContent = statut;


        /*
        |--------------------------------------------------------------------------
        | BUS ACTUEL
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'ancienBusLabel'
        ).value = bus;


        document.getElementById(
            'ancienBusId'
        ).value = busId;


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPE ACTUELLE
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'ancienneEquipeLabel'
        ).value = equipe;


        document.getElementById(
            'ancienneEquipeId'
        ).value = equipeId;


        /*
        |--------------------------------------------------------------------------
        | EMPÊCHER DE CHOISIR LE BUS ACTUEL
        |--------------------------------------------------------------------------
        */

        var optionsBus =
            document.querySelectorAll(
                '#nouveauBus option'
            );


        optionsBus.forEach(function (busOption) {

            busOption.disabled = false;

        });


        if (busId) {

            var busActuel =
                document.querySelector(
                    '#nouveauBus option[value="' + busId + '"]'
                );


            if (busActuel) {

                busActuel.disabled = true;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESET DU NOUVEAU BUS
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'nouveauBus'
        ).value = '';


        /*
        |--------------------------------------------------------------------------
        | RESET DE LA NOUVELLE ÉQUIPE
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'nouvelleEquipe'
        ).value = '';

    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDRE QUE LE DOM SOIT CHARGÉ
    |--------------------------------------------------------------------------
    */

    function initialiserAffectation() {

        var selectVoyage =
            document.getElementById(
                'affectationVoyage'
            );


        var selectType =
            document.getElementById(
                'affectationType'
            );


        /*
        |--------------------------------------------------------------------------
        | VOYAGE
        |--------------------------------------------------------------------------
        */

        if (selectVoyage) {

            selectVoyage.addEventListener(
                'change',
                function () {

                    chargerVoyageAffectation();

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TYPE
        |--------------------------------------------------------------------------
        */

        if (selectType) {

            selectType.addEventListener(
                'change',
                function () {

                    gererTypeAffectation();

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PRÉREMPLISSAGE
        |--------------------------------------------------------------------------
        */

        var prefillVoyageId =
            @json($prefillVoyageId ?? null);


        var prefillMotif =
            @json($prefillMotif ?? null);


        if (prefillVoyageId) {

            if (selectVoyage) {

                selectVoyage.value =
                    String(prefillVoyageId);


                chargerVoyageAffectation();

            }


            /*
            |--------------------------------------------------------------------------
            | TYPE PAR DÉFAUT DEPUIS INCIDENT
            |--------------------------------------------------------------------------
            */

            if (selectType) {

                selectType.value =
                    'remplacement_bus';


                gererTypeAffectation();

            }


            /*
            |--------------------------------------------------------------------------
            | MOTIF INCIDENT
            |--------------------------------------------------------------------------
            */

            if (
                prefillMotif
                &&
                document.getElementById(
                    'affectationMotif'
                )
            ) {

                document.getElementById(
                    'affectationMotif'
                ).value =
                    prefillMotif;

            }

        }

        else {

            /*
            |--------------------------------------------------------------------------
            | FORMULAIRE NORMAL
            |--------------------------------------------------------------------------
            */

            gererTypeAffectation();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SI JQUERY EST DISPONIBLE
    |--------------------------------------------------------------------------
    |
    | On attend que le modal soit réellement chargé.
    |
    */

    if (
        typeof jQuery !== 'undefined'
    ) {

        jQuery(function () {

            initialiserAffectation();

        });

    }

    else {

        if (
            document.readyState === 'loading'
        ) {

            document.addEventListener(
                'DOMContentLoaded',
                initialiserAffectation
            );

        }

        else {

            initialiserAffectation();

        }

    }

})();

</script>
