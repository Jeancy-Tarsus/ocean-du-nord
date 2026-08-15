<div class="modal fade"
     id="modalCreateVoyage"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('voyages.store') }}"
                  method="POST">

                @csrf


                {{-- =====================================================
                     HEADER
                ====================================================== --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-road mr-2"></i>

                            Nouveau voyage

                        </h5>

                        <small class="text-white">

                            Planifier un nouveau voyage

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
                         INFORMATIONS DU VOYAGE
                    ================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-info-circle mr-2"></i>

                            Informations du voyage

                        </h6>


                        <div class="row mt-3">


                            {{-- LIGNE --}}

                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>

                                        Ligne

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="ligne_id"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner une ligne

                                        </option>

                                        @foreach($lignes as $ligne)

                                            <option value="{{ $ligne->id }}"
                                                {{ old('ligne_id') == $ligne->id ? 'selected' : '' }}>

                                                {{ $ligne->code }}
                                                —
                                                {{ $ligne->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- BUS --}}

                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>

                                        Bus

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="bus_id"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner un bus

                                        </option>

                                        @foreach($buses as $bus)

                                            <option value="{{ $bus->id }}"
                                                {{ old('bus_id') == $bus->id ? 'selected' : '' }}>

                                                {{ $bus->numero }}
                                                —
                                                {{ $bus->immatriculation }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- EQUIPE --}}

                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>

                                        Équipe

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="equipe_id"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner une équipe

                                        </option>

                                        @foreach($equipes as $equipe)

                                            <option value="{{ $equipe->id }}"
                                                {{ old('equipe_id') == $equipe->id ? 'selected' : '' }}>

                                                {{ $equipe->code }}
                                                —
                                                {{ $equipe->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         PARCOURS DU VOYAGE
                    ================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-route mr-2"></i>

                            Parcours du voyage

                        </h6>


                        <p class="text-muted small mt-2 mb-3">

                            Définissez l'ordre des agences parcourues
                            par le bus.

                        </p>


                        {{-- =============================================
                             AGENCE DE DÉPART
                        ============================================== --}}

                        <div class="form-group">

                            <label>

                                <i class="fas fa-map-marker-alt text-success mr-1"></i>

                                Agence de départ

                                <span class="text-danger">*</span>

                            </label>


                            <select name="agence_depart"
                                    class="form-control"
                                    required>

                                <option value="">

                                    Sélectionner l'agence de départ

                                </option>

                                @foreach($agences as $agence)

                                    <option value="{{ $agence->id }}"
                                        {{ old('agence_depart') == $agence->id ? 'selected' : '' }}>

                                        {{ $agence->code }}
                                        —
                                        {{ $agence->nom }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =============================================
                             AGENCES DE PASSAGE
                        ============================================== --}}

                        <div class="form-group mt-4">

                            <div class="d-flex justify-content-between align-items-center">

                                <label class="mb-0">

                                    <i class="fas fa-map-signs text-warning mr-1"></i>

                                    Agences de passage

                                    <span class="text-muted">
                                        (facultatif)
                                    </span>

                                </label>


                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        id="btnAddAgencePassage">

                                    <i class="fas fa-plus mr-1"></i>

                                    Ajouter une agence

                                </button>

                            </div>


                            <div id="agencesPassageContainer"
                                 class="mt-3">

                                {{-- Les agences de passage seront ajoutées ici --}}

                            </div>


                            <small class="text-muted">

                                Ajoutez les agences dans l'ordre réel
                                du parcours.

                            </small>

                        </div>


                        {{-- =============================================
                             AGENCE D'ARRIVÉE
                        ============================================== --}}

                        <div class="form-group mt-4">

                            <label>

                                <i class="fas fa-flag-checkered text-danger mr-1"></i>

                                Agence d'arrivée

                                <span class="text-danger">*</span>

                            </label>


                            <select name="agence_arrivee"
                                    class="form-control"
                                    required>

                                <option value="">

                                    Sélectionner l'agence d'arrivée

                                </option>

                                @foreach($agences as $agence)

                                    <option value="{{ $agence->id }}"
                                        {{ old('agence_arrivee') == $agence->id ? 'selected' : '' }}>

                                        {{ $agence->code }}
                                        —
                                        {{ $agence->nom }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                    </div>


                    {{-- =================================================
                         HORAIRES
                    ================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-clock mr-2"></i>

                            Horaires

                        </h6>


                        <div class="row mt-3">


                            {{-- DATE DEPART --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Date de départ

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="date"
                                           name="date_depart"
                                           value="{{ old('date_depart') }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- HEURE DEPART --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Heure de départ

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="time"
                                           name="heure_depart"
                                           value="{{ old('heure_depart') }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- DATE ARRIVEE --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Date d'arrivée prévue

                                    </label>

                                    <input type="date"
                                           name="date_arrivee_prevue"
                                           value="{{ old('date_arrivee_prevue') }}"
                                           class="form-control">

                                </div>

                            </div>


                            {{-- HEURE ARRIVEE --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Heure d'arrivée prévue

                                    </label>

                                    <input type="time"
                                           name="heure_arrivee_prevue"
                                           value="{{ old('heure_arrivee_prevue') }}"
                                           class="form-control">

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         STATUT
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Statut

                        </label>


                        <input type="text"
                               class="form-control"
                               value="Planifié"
                               readonly>


                        {{-- La valeur réellement envoyée à Laravel --}}

                        <input type="hidden"
                               name="statut"
                               value="planifie">

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
                                  rows="3"
                                  placeholder="Informations supplémentaires...">{{ old('observation') }}</textarea>

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

                        Planifier le voyage

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT : AGENCES DE PASSAGE
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('agencesPassageContainer');
    const btnAdd = document.getElementById('btnAddAgencePassage');

    if (!container || !btnAdd) {
        return;
    }

    const agences = @json($agences->values());

    function ajouterAgencePassage(selectedId = '') {

        const wrapper = document.createElement('div');

        wrapper.className =
            'row align-items-center mb-2 agence-passage-row';


        // Numéro de passage

        const numeroCol = document.createElement('div');

        numeroCol.className =
            'col-md-1 text-center';


        const numero = document.createElement('span');

        numero.className =
            'badge badge-warning passage-numero';


        numeroCol.appendChild(numero);


        // Sélection agence

        const selectCol = document.createElement('div');

        selectCol.className =
            'col-md-10';


        const select = document.createElement('select');

        select.name =
            'agences_passage[]';

        select.className =
            'form-control';

        select.required =
            true;


        let options =
            '<option value="">Sélectionner une agence</option>';


        agences.forEach(function (agence) {

            const selected =
                String(selectedId) === String(agence.id)
                    ? 'selected'
                    : '';


            options +=
                '<option value="' + agence.id + '" ' +
                selected +
                '>' +
                agence.code +
                ' — ' +
                agence.nom +
                '</option>';

        });


        select.innerHTML = options;

        selectCol.appendChild(select);


        // Bouton supprimer

        const deleteCol =
            document.createElement('div');

        deleteCol.className =
            'col-md-1';


        const deleteButton =
            document.createElement('button');

        deleteButton.type =
            'button';

        deleteButton.className =
            'btn btn-outline-danger btn-sm';

        deleteButton.title =
            'Supprimer cette agence';


        deleteButton.innerHTML =
            '<i class="fas fa-trash"></i>';


        deleteButton.addEventListener(
            'click',
            function () {

                wrapper.remove();

                mettreAJourNumeros();

            }
        );


        deleteCol.appendChild(deleteButton);


        // Assemblage

        wrapper.appendChild(numeroCol);

        wrapper.appendChild(selectCol);

        wrapper.appendChild(deleteCol);

        container.appendChild(wrapper);


        mettreAJourNumeros();

    }


    // Mettre à jour les numéros

    function mettreAJourNumeros() {

        const rows =
            container.querySelectorAll(
                '.agence-passage-row'
            );


        rows.forEach(function (row, index) {

            const numero =
                row.querySelector(
                    '.passage-numero'
                );


            if (numero) {

                numero.textContent =
                    index + 2;

            }

        });

    }


    // Ajouter une agence

    btnAdd.addEventListener(
        'click',
        function () {

            ajouterAgencePassage();

        }
    );


    // Restaurer les anciennes valeurs
    // après une erreur de validation

    const anciennesAgences =
        @json(old('agences_passage', []));


    anciennesAgences.forEach(
        function (agenceId) {

            ajouterAgencePassage(
                agenceId
            );

        }
    );

});

</script>
