<div class="modal fade"
     id="modalCreateIncident"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalCreateIncidentLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('incidents.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf


                {{-- =====================================================
                     HEADER
                ====================================================== --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white"
                            id="modalCreateIncidentLabel">

                            <i class="fas fa-exclamation-triangle mr-2"></i>

                            Déclarer un incident

                        </h5>

                        <small class="text-white">

                            Enregistrer un incident survenu pendant un voyage

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
                         VOYAGE
                    ================================================== --}}

                    <div class="form-group">

                        <label for="incident_voyage_id">

                            Voyage

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-route ocn-green"></i>

                                </span>

                            </div>


                            <select name="voyage_id"
                                    id="incident_voyage_id"
                                    class="form-control"
                                    required>

                                <option value="">

                                    Sélectionner un voyage

                                </option>


                                @forelse($voyages as $voyage)

                                    <option value="{{ $voyage->id }}">

                                        {{ $voyage->code }}

                                        @if($voyage->ligne)

                                            —
                                            {{ $voyage->ligne->nom }}

                                        @endif

                                    </option>

                                @empty

                                    <option value=""
                                            disabled>

                                        Aucun voyage disponible

                                    </option>

                                @endforelse

                            </select>

                        </div>


                        <small class="text-muted">

                            Sélectionnez le voyage concerné par l'incident.

                        </small>

                    </div>



                    {{-- =================================================
                         INFORMATIONS DU VOYAGE
                    ================================================== --}}

                    <div id="incidentVoyageInfo"
                         class="d-none mb-4">

                        <div class="card border shadow-sm mb-0">


                            {{-- HEADER --}}

                            <div class="card-header ocn-light">

                                <strong>

                                    <i class="fas fa-info-circle ocn-green mr-1"></i>

                                    Informations du voyage

                                </strong>

                            </div>


                            {{-- BODY --}}

                            <div class="card-body">

                                <div class="row">


                                    {{-- CODE VOYAGE --}}

                                    <div class="col-md-4 mb-3">

                                        <small class="text-muted d-block">

                                            Voyage

                                        </small>

                                        <strong id="incidentInfoCode">

                                            —

                                        </strong>

                                    </div>


                                    {{-- LIGNE --}}

                                    <div class="col-md-8 mb-3">

                                        <small class="text-muted d-block">

                                            Ligne

                                        </small>

                                        <strong id="incidentInfoLigne">

                                            —

                                        </strong>

                                    </div>


                                    {{-- BUS --}}

                                    <div class="col-md-6 mb-3">

                                        <small class="text-muted d-block">

                                            <i class="fas fa-bus ocn-green mr-1"></i>

                                            Bus

                                        </small>

                                        <strong id="incidentInfoBus">

                                            —

                                        </strong>

                                    </div>


                                    {{-- ÉQUIPE --}}

                                    <div class="col-md-6 mb-3">

                                        <small class="text-muted d-block">

                                            <i class="fas fa-users ocn-green mr-1"></i>

                                            Équipe

                                        </small>

                                        <strong id="incidentInfoEquipe">

                                            —

                                        </strong>

                                    </div>


                                    {{-- CHAUFFEUR TITULAIRE --}}

                                    <div class="col-md-6">

                                        <small class="text-muted d-block">

                                            <i class="fas fa-user ocn-green mr-1"></i>

                                            Chauffeur titulaire

                                        </small>

                                        <span id="incidentInfoChauffeur1">

                                            —

                                        </span>

                                    </div>


                                    {{-- CHAUFFEUR SECONDAIRE --}}

                                    <div class="col-md-6">

                                        <small class="text-muted d-block">

                                            <i class="fas fa-user ocn-green mr-1"></i>

                                            Chauffeur secondaire

                                        </small>

                                        <span id="incidentInfoChauffeur2">

                                            —

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         AGENCE
                    ================================================== --}}

                    <div class="form-group">

                        <label for="incident_agence_id">

                            Agence concernée

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-building ocn-green"></i>

                                </span>

                            </div>


                            <select name="agence_id"
                                    id="incident_agence_id"
                                    class="form-control"
                                    required
                                    disabled>

                                <option value="">

                                    Sélectionnez d'abord un voyage

                                </option>

                            </select>

                        </div>


                        <small class="text-muted">

                            Les agences proposées correspondent au parcours
                            du voyage.

                        </small>

                    </div>



                    {{-- =================================================
                         TYPE + GRAVITÉ
                    ================================================== --}}

                    <div class="row">


                        {{-- TYPE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="incident_type">

                                    Type d'incident

                                    <span class="text-danger">*</span>

                                </label>


                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text ocn-light">

                                            <i class="fas fa-exclamation-circle ocn-green"></i>

                                        </span>

                                    </div>


                                    <select name="type"
                                            id="incident_type"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner le type

                                        </option>

                                        <option value="panne">
                                            Panne
                                        </option>

                                        <option value="accident">
                                            Accident
                                        </option>

                                        <option value="retard">
                                            Retard
                                        </option>

                                        <option value="probleme_chauffeur">
                                            Problème chauffeur
                                        </option>

                                        <option value="probleme_technique">
                                            Problème technique
                                        </option>

                                        <option value="autre">
                                            Autre
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>


                        {{-- GRAVITÉ --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="incident_gravite">

                                    Gravité

                                    <span class="text-danger">*</span>

                                </label>


                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text ocn-light">

                                            <i class="fas fa-signal ocn-green"></i>

                                        </span>

                                    </div>


                                    <select name="gravite"
                                            id="incident_gravite"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner la gravité

                                        </option>

                                        <option value="faible">
                                            Faible
                                        </option>

                                        <option value="moyenne">
                                            Moyenne
                                        </option>

                                        <option value="grave">
                                            Grave
                                        </option>

                                        <option value="critique">
                                            Critique
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         TITRE
                    ================================================== --}}

                    <div class="form-group">

                        <label for="incident_titre">

                            Titre de l'incident

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-heading ocn-green"></i>

                                </span>

                            </div>


                            <input type="text"
                                   name="titre"
                                   id="incident_titre"
                                   class="form-control"
                                   maxlength="255"
                                   placeholder="Ex : Panne moteur du bus"
                                   value="{{ old('titre') }}"
                                   required>

                        </div>

                    </div>



                    {{-- =================================================
                         DATE + HEURE
                    ================================================== --}}

                    <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="incident_date">

                                    Date de l'incident

                                    <span class="text-danger">*</span>

                                </label>


                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text ocn-light">

                                            <i class="fas fa-calendar-alt ocn-green"></i>

                                        </span>

                                    </div>


                                    <input type="date"
                                           name="date_incident"
                                           id="incident_date"
                                           class="form-control"
                                           value="{{ old('date_incident', now()->format('Y-m-d')) }}"
                                           required>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="incident_heure">

                                    Heure de l'incident

                                    <span class="text-danger">*</span>

                                </label>


                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text ocn-light">

                                            <i class="fas fa-clock ocn-green"></i>

                                        </span>

                                    </div>


                                    <input type="time"
                                           name="heure_incident"
                                           id="incident_heure"
                                           class="form-control"
                                           value="{{ old('heure_incident', now()->format('H:i')) }}"
                                           required>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         DESCRIPTION
                    ================================================== --}}

                    <div class="form-group">

                        <label for="incident_description">

                            Description

                            <span class="text-danger">*</span>

                        </label>


                        <textarea name="description"
                                  id="incident_description"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Décrivez précisément ce qui s'est passé..."
                                  required>{{ old('description') }}</textarea>

                    </div>



                    {{-- =================================================
                         OBSERVATION
                    ================================================== --}}

                    <div class="form-group">

                        <label for="incident_observation">

                            Observation

                        </label>


                        <textarea name="observation"
                                  id="incident_observation"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Informations complémentaires...">{{ old('observation') }}</textarea>

                    </div>



                    {{-- =================================================
                         DÉCLARANT
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Déclaré par

                        </label>


                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-user ocn-green"></i>

                                </span>

                            </div>


                            <input type="text"
                                   class="form-control"
                                   value="{{ auth()->user()->name }}"
                                   readonly>

                        </div>

                    </div>



                    {{-- NOTE --}}

                    <div class="alert alert-light border mb-0">

                        <i class="fas fa-info-circle ocn-green mr-2"></i>

                        Le bus, l'équipe et les chauffeurs sont récupérés
                        automatiquement à partir du voyage sélectionné.

                    </div>


                    <small class="text-muted d-block mt-3">

                        <span class="text-danger">*</span>

                        Champs obligatoires.

                    </small>

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

                        Enregistrer l'incident

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =============================================================
     JAVASCRIPT
============================================================= --}}

@push('scripts')

<script>

$(document).ready(function () {

    $('#incident_voyage_id').on('change', function () {

        const voyageId = $(this).val();

        const info = $('#incidentVoyageInfo');

        const agence = $('#incident_agence_id');


        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        info.addClass('d-none');

        $('#incidentInfoCode').text('—');
        $('#incidentInfoLigne').text('—');
        $('#incidentInfoBus').text('—');
        $('#incidentInfoEquipe').text('—');
        $('#incidentInfoChauffeur1').text('—');
        $('#incidentInfoChauffeur2').text('—');


        agence
            .prop('disabled', true)
            .empty()
            .append(
                '<option value="">Sélectionnez d\'abord un voyage</option>'
            );


        if (!voyageId) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CHARGEMENT
        |--------------------------------------------------------------------------
        */

        agence
            .empty()
            .append(
                '<option value="">Chargement...</option>'
            );


        /*
        |--------------------------------------------------------------------------
        | URL DE LA ROUTE
        |--------------------------------------------------------------------------
        */

        let url =
            "{{ route('incidents.voyage.informations', ':voyage') }}";

        url = url.replace(
            ':voyage',
            voyageId
        );


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: url,

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                console.log(
                    'Informations voyage :',
                    response
                );


                if (
                    !response.success
                    ||
                    !response.voyage
                ) {

                    alert(
                        response.message
                        ||
                        'Impossible de récupérer les informations du voyage.'
                    );

                    return;
                }


                const voyage =
                    response.voyage;


                /*
                |--------------------------------------------------------------------------
                | VOYAGE
                |--------------------------------------------------------------------------
                */

                $('#incidentInfoCode')
                    .text(
                        voyage.code
                        ||
                        '—'
                    );


                /*
                |--------------------------------------------------------------------------
                | LIGNE
                |--------------------------------------------------------------------------
                */

                $('#incidentInfoLigne')
                    .text(
                        voyage.ligne
                        ||
                        'Aucune ligne'
                    );


                /*
                |--------------------------------------------------------------------------
                | BUS
                |--------------------------------------------------------------------------
                */

                if (voyage.bus) {

                    let bus = '';


                    if (voyage.bus.numero) {

                        bus =
                            voyage.bus.numero;

                    }


                    if (
                        voyage.bus.immatriculation
                    ) {

                        if (bus !== '') {

                            bus +=
                                ' — ';

                        }

                        bus +=
                            voyage.bus.immatriculation;
                    }


                    $('#incidentInfoBus')
                        .text(
                            bus
                            ||
                            'Bus non renseigné'
                        );

                } else {

                    $('#incidentInfoBus')
                        .text(
                            'Aucun bus'
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | ÉQUIPE
                |--------------------------------------------------------------------------
                */

                if (voyage.equipe) {

                    let equipe =
                        voyage.equipe.nom
                        ||
                        voyage.equipe.code
                        ||
                        'Équipe';

                    $('#incidentInfoEquipe')
                        .text(equipe);


                    /*
                    |--------------------------------------------------------------------------
                    | CHAUFFEUR TITULAIRE
                    |--------------------------------------------------------------------------
                    */

                    $('#incidentInfoChauffeur1')
                        .text(
                            voyage.equipe.chauffeur_titulaire
                            ||
                            'Aucun chauffeur'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | CHAUFFEUR SECONDAIRE
                    |--------------------------------------------------------------------------
                    */

                    $('#incidentInfoChauffeur2')
                        .text(
                            voyage.equipe.chauffeur_secondaire
                            ||
                            'Aucun chauffeur'
                        );

                } else {

                    $('#incidentInfoEquipe')
                        .text(
                            'Aucune équipe'
                        );

                    $('#incidentInfoChauffeur1')
                        .text(
                            'Aucun chauffeur'
                        );

                    $('#incidentInfoChauffeur2')
                        .text(
                            'Aucun chauffeur'
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | AGENCES
                |--------------------------------------------------------------------------
                */

                const agences =
                    voyage.agences
                    ||
                    [];


                agence.empty();


                if (agences.length > 0) {

                    agence.append(
                        '<option value="">'
                        +
                        'Sélectionner une agence'
                        +
                        '</option>'
                    );


                    agences.forEach(function (item) {

                        let texte =
                            item.nom
                            ||
                            'Agence';


                        if (item.type) {

                            texte +=
                                ' — '
                                +
                                item.type;
                        }


                        agence.append(

                            $('<option>', {

                                value:
                                    item.id,

                                text:
                                    texte

                            })

                        );

                    });


                    agence.prop(
                        'disabled',
                        false
                    );

                } else {

                    agence.append(
                        '<option value="">'
                        +
                        'Aucune agence disponible'
                        +
                        '</option>'
                    );

                    agence.prop(
                        'disabled',
                        true
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | AFFICHER LES INFORMATIONS
                |--------------------------------------------------------------------------
                */

                info.removeClass(
                    'd-none'
                );

            },


            error: function (xhr) {

                console.error(
                    'Erreur AJAX :',
                    xhr
                );


                agence
                    .empty()
                    .append(
                        '<option value="">'
                        +
                        'Erreur de chargement'
                        +
                        '</option>'
                    )
                    .prop(
                        'disabled',
                        true
                    );


                let message =
                    'Impossible de récupérer les informations du voyage.';


                if (
                    xhr.responseJSON
                    &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;
                }


                alert(message);
            }

        });

    });

});

</script>

@endpush
