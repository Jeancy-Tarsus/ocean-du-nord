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

                {{-- HEADER --}}
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


                {{-- BODY --}}
                <div class="modal-body p-4">


                    {{-- VOYAGE --}}
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
                                        data-bus-id="{{ $voyage->bus_id }}"
                                        data-bus="{{ $voyage->bus->numero ?? '—' }}"
                                        data-equipe-id="{{ $voyage->equipe_id }}"
                                        data-equipe="{{ $voyage->equipe->nom ?? '—' }}">

                                    {{ $voyage->code }}
                                    —
                                    {{ $voyage->ligne->nom ?? 'Ligne inconnue' }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- INFORMATIONS VOYAGE --}}
                    <div id="voyageInformations"
                         class="alert alert-light border d-none">

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


                    {{-- TYPE --}}
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


                    {{-- RESSOURCES ACTUELLES --}}
                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-history mr-1 ocn-green"></i>

                                Ressources actuellement prévues

                            </strong>

                        </div>


                        <div class="card-body">

                            <div class="row">

                                {{-- ANCIEN BUS --}}
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


                                {{-- ANCIENNE ÉQUIPE --}}
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


                    {{-- RESSOURCES DE REMPLACEMENT --}}
                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-random mr-1 ocn-green"></i>

                                Ressources de remplacement

                            </strong>

                        </div>


                        <div class="card-body">


                            {{-- NOUVEAU BUS --}}
                            <div id="blocNouveauBus"
                                 class="form-group d-none">

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

                                            @if(isset($bus->immatriculation))

                                                —
                                                {{ $bus->immatriculation }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">

                                    Seuls les bus ayant le statut
                                    <strong>disponible</strong>
                                    sont proposés.

                                </small>

                            </div>


                            {{-- NOUVELLE ÉQUIPE --}}
                            <div id="blocNouvelleEquipe"
                                 class="form-group d-none">

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

                        </div>

                    </div>


                    {{-- MOTIF --}}
                    <div class="form-group">

                        <label>

                            Motif du remplacement

                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="motif"
                               class="form-control"
                               placeholder="Ex : Panne moteur du bus"
                               value="{{ old('motif') }}"
                               required>

                    </div>


                    {{-- DATE / HEURE --}}
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


                    {{-- OBSERVATION --}}
                    <div class="form-group">

                        <label>

                            Observation

                        </label>

                        <textarea name="observation"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Ajouter une observation...">{{ old('observation') }}</textarea>

                    </div>


                    {{-- INFORMATION --}}
                    <div class="alert alert-warning mb-0">

                        <i class="fas fa-info-circle mr-1"></i>

                        <strong>Important :</strong>

                        cette opération enregistre le remplacement
                        dans l'historique. Les informations initiales
                        du voyage ne sont pas modifiées.

                    </div>

                </div>


                {{-- FOOTER --}}
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


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | QUAND ON CHOISIT UN VOYAGE
    |--------------------------------------------------------------------------
    */

    $('#affectationVoyage').on('change', function () {

        const option = $(this).find('option:selected');


        /*
        | Aucun voyage sélectionné
        */

        if (!option.val()) {

            $('#voyageInformations')
                .addClass('d-none');

            $('#ancienBusLabel')
                .val('Sélectionnez d\'abord un voyage');

            $('#ancienneEquipeLabel')
                .val('Sélectionnez d\'abord un voyage');

            $('#ancienBusId')
                .val('');

            $('#ancienneEquipeId')
                .val('');

            $('#nouveauBus')
                .val('');

            $('#nouvelleEquipe')
                .val('');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS DU VOYAGE
        |--------------------------------------------------------------------------
        */

        const code = option.attr('data-code') || '—';

        const ligne = option.attr('data-ligne') || '—';

        const statut = option.attr('data-statut') || '—';

        const busId = option.attr('data-bus-id') || '';

        const bus = option.attr('data-bus') || '—';

        const equipeId = option.attr('data-equipe-id') || '';

        const equipe = option.attr('data-equipe') || '—';


        /*
        |--------------------------------------------------------------------------
        | AFFICHAGE DU RÉSUMÉ
        |--------------------------------------------------------------------------
        */

        $('#voyageInformations')
            .removeClass('d-none');

        $('#infoVoyage')
            .text(code);

        $('#infoLigne')
            .text(ligne);

        $('#infoStatut')
            .text(statut);


        /*
        |--------------------------------------------------------------------------
        | ANCIEN BUS
        |--------------------------------------------------------------------------
        */

        $('#ancienBusLabel')
            .val(bus);

        $('#ancienBusId')
            .val(busId);


        /*
        |--------------------------------------------------------------------------
        | ANCIENNE ÉQUIPE
        |--------------------------------------------------------------------------
        */

        $('#ancienneEquipeLabel')
            .val(equipe);

        $('#ancienneEquipeId')
            .val(equipeId);


        /*
        |--------------------------------------------------------------------------
        | RÉINITIALISATION DU BUS DE REMPLACEMENT
        |--------------------------------------------------------------------------
        */

        $('#nouveauBus')
            .val('');


        /*
        |--------------------------------------------------------------------------
        | LE BUS ACTUEL NE PEUT PAS ÊTRE SÉLECTIONNÉ
        |--------------------------------------------------------------------------
        */

        $('#nouveauBus option')
            .prop('disabled', false);

        if (busId) {

            $('#nouveauBus option[value="' + busId + '"]')
                .prop('disabled', true);

        }


        /*
        |--------------------------------------------------------------------------
        | RÉINITIALISATION DE L'ÉQUIPE
        |--------------------------------------------------------------------------
        */

        $('#nouvelleEquipe')
            .val('');

    });


    /*
    |--------------------------------------------------------------------------
    | TYPE DE REMPLACEMENT
    |--------------------------------------------------------------------------
    */

    $('#affectationType').on('change', function () {

        const type = $(this).val();


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

            $('#blocNouveauBus')
                .removeClass('d-none');

            $('#nouveauBus')
                .prop('required', true);

        } else {

            $('#blocNouveauBus')
                .addClass('d-none');

            $('#nouveauBus')
                .prop('required', false)
                .val('');

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

            $('#blocNouvelleEquipe')
                .removeClass('d-none');

            $('#nouvelleEquipe')
                .prop('required', true);

        } else {

            $('#blocNouvelleEquipe')
                .addClass('d-none');

            $('#nouvelleEquipe')
                .prop('required', false)
                .val('');

        }

    });

});

</script>
