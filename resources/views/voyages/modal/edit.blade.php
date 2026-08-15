<div class="modal fade"
     id="modalEditVoyage{{ $voyage->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('voyages.update', $voyage) }}"
                  method="POST">

                @csrf

                @method('PUT')


                {{-- =====================================================
                     HEADER
                ====================================================== --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-edit mr-2"></i>

                            Modifier le voyage

                        </h5>

                        <small class="text-white">

                            {{ $voyage->code }}

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
                                                {{ old(
                                                    'ligne_id',
                                                    $voyage->ligne_id
                                                ) == $ligne->id ? 'selected' : '' }}>

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
                                                {{ old(
                                                    'bus_id',
                                                    $voyage->bus_id
                                                ) == $bus->id ? 'selected' : '' }}>

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
                                                {{ old(
                                                    'equipe_id',
                                                    $voyage->equipe_id
                                                ) == $equipe->id ? 'selected' : '' }}>

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
                         PARCOURS
                    ================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-route mr-2"></i>

                            Parcours du voyage

                        </h6>


                        <p class="text-muted small mt-2 mb-3">

                            Modifiez l'ordre des agences parcourues
                            par le bus.

                        </p>


                        {{-- =================================================
                             AGENCE DE DÉPART
                        ================================================== --}}

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


                                @php

                                    $agenceDepart = $voyage->voyageAgences
                                        ->where('type', 'depart')
                                        ->sortBy('ordre')
                                        ->first();

                                @endphp


                                @foreach($agences as $agence)

                                    <option value="{{ $agence->id }}"
                                        {{ old(
                                            'agence_depart',
                                            $agenceDepart?->agence_id
                                        ) == $agence->id ? 'selected' : '' }}>

                                        {{ $agence->code }}
                                        —
                                        {{ $agence->nom }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             AGENCES DE PASSAGE
                        ================================================== --}}

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
                                        id="btnAddAgencePassage{{ $voyage->id }}">

                                    <i class="fas fa-plus mr-1"></i>

                                    Ajouter une agence

                                </button>

                            </div>


                            <div id="agencesPassageContainer{{ $voyage->id }}"
                                 class="mt-3">

                                @php

                                    $agencesPassageExistantes =
                                        $voyage->voyageAgences
                                            ->where('type', 'passage')
                                            ->sortBy('ordre');

                                @endphp


                                @foreach($agencesPassageExistantes as $passage)

                                    <div class="row align-items-center mb-2 agence-passage-row">

                                        <div class="col-md-1 text-center">

                                            <span class="badge badge-warning passage-numero">

                                                {{ $passage->ordre }}

                                            </span>

                                        </div>


                                        <div class="col-md-10">

                                            <select name="agences_passage[]"
                                                    class="form-control"
                                                    required>

                                                <option value="">

                                                    Sélectionner une agence

                                                </option>


                                                @foreach($agences as $agence)

                                                    <option value="{{ $agence->id }}"
                                                        {{ $passage->agence_id == $agence->id ? 'selected' : '' }}>

                                                        {{ $agence->code }}
                                                        —
                                                        {{ $agence->nom }}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>


                                        <div class="col-md-1">

                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm btn-remove-passage">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </div>

                                    </div>

                                @endforeach

                            </div>


                            <small class="text-muted">

                                Ajoutez les agences dans l'ordre réel
                                du parcours.

                            </small>

                        </div>


                        {{-- =================================================
                             AGENCE D'ARRIVÉE
                        ================================================== --}}

                        <div class="form-group mt-4">

                            <label>

                                <i class="fas fa-flag-checkered text-danger mr-1"></i>

                                Agence d'arrivée

                                <span class="text-danger">*</span>

                            </label>


                            @php

                                $agenceArrivee = $voyage->voyageAgences
                                    ->where('type', 'arrivee')
                                    ->sortBy('ordre')
                                    ->first();

                            @endphp


                            <select name="agence_arrivee"
                                    class="form-control"
                                    required>

                                <option value="">

                                    Sélectionner l'agence d'arrivée

                                </option>


                                @foreach($agences as $agence)

                                    <option value="{{ $agence->id }}"
                                        {{ old(
                                            'agence_arrivee',
                                            $agenceArrivee?->agence_id
                                        ) == $agence->id ? 'selected' : '' }}>

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


                            {{-- DATE DÉPART --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Date de départ

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="date"
                                           name="date_depart"
                                           value="{{ old(
                                               'date_depart',
                                               optional($voyage->date_depart)->format('Y-m-d')
                                           ) }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- HEURE DÉPART --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Heure de départ

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="time"
                                           name="heure_depart"
                                           value="{{ old(
                                               'heure_depart',
                                               substr($voyage->heure_depart, 0, 5)
                                           ) }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- DATE ARRIVÉE --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Date d'arrivée prévue

                                    </label>

                                    <input type="date"
                                           name="date_arrivee_prevue"
                                           value="{{ old(
                                               'date_arrivee_prevue',
                                               $voyage->date_arrivee_prevue
                                           ) }}"
                                           class="form-control">

                                </div>

                            </div>


                            {{-- HEURE ARRIVÉE --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Heure d'arrivée prévue

                                    </label>

                                    <input type="time"
                                           name="heure_arrivee_prevue"
                                           value="{{ old(
                                               'heure_arrivee_prevue',
                                               $voyage->heure_arrivee_prevue
                                                   ? substr($voyage->heure_arrivee_prevue, 0, 5)
                                                   : ''
                                           ) }}"
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
                                  placeholder="Informations supplémentaires...">{{ old(
                                      'observation',
                                      $voyage->observation
                                  ) }}</textarea>

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

                        Enregistrer les modifications

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

    const container =
        document.getElementById(
            'agencesPassageContainer{{ $voyage->id }}'
        );

    const btnAdd =
        document.getElementById(
            'btnAddAgencePassage{{ $voyage->id }}'
        );


    if (!container || !btnAdd) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Liste des agences
    |--------------------------------------------------------------------------
    */

    const agences = @json($agences->values());


    /*
    |--------------------------------------------------------------------------
    | Ajouter une agence de passage
    |--------------------------------------------------------------------------
    */

    function ajouterAgencePassage(selectedId = '') {

        const wrapper =
            document.createElement('div');

        wrapper.className =
            'row align-items-center mb-2 agence-passage-row';


        /*
        | Numéro
        */

        const numeroCol =
            document.createElement('div');

        numeroCol.className =
            'col-md-1 text-center';


        const numero =
            document.createElement('span');

        numero.className =
            'badge badge-warning passage-numero';


        numeroCol.appendChild(numero);


        /*
        | Select
        */

        const selectCol =
            document.createElement('div');

        selectCol.className =
            'col-md-10';


        const select =
            document.createElement('select');

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
                '<option value="' +
                agence.id +
                '" ' +
                selected +
                '>' +
                agence.code +
                ' — ' +
                agence.nom +
                '</option>';

        });


        select.innerHTML =
            options;


        selectCol.appendChild(
            select
        );


        /*
        | Bouton supprimer
        */

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


        deleteCol.appendChild(
            deleteButton
        );


        /*
        | Assemblage
        */

        wrapper.appendChild(
            numeroCol
        );

        wrapper.appendChild(
            selectCol
        );

        wrapper.appendChild(
            deleteCol
        );


        container.appendChild(
            wrapper
        );


        mettreAJourNumeros();

    }


    /*
    |--------------------------------------------------------------------------
    | Mettre à jour les numéros
    |--------------------------------------------------------------------------
    */

    function mettreAJourNumeros() {

        const rows =
            container.querySelectorAll(
                '.agence-passage-row'
            );


        rows.forEach(
            function (row, index) {

                const numero =
                    row.querySelector(
                        '.passage-numero'
                    );


                if (numero) {

                    /*
                    | Le départ est 1.
                    | Les passages commencent donc à 2.
                    */

                    numero.textContent =
                        index + 2;

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Ajouter
    |--------------------------------------------------------------------------
    */

    btnAdd.addEventListener(
        'click',
        function () {

            ajouterAgencePassage();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Supprimer les passages existants
    |--------------------------------------------------------------------------
    */

    container
        .querySelectorAll('.btn-remove-passage')
        .forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        button
                            .closest(
                                '.agence-passage-row'
                            )
                            .remove();

                        mettreAJourNumeros();

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Anciennes valeurs après erreur de validation
    |--------------------------------------------------------------------------
    */

    const anciennesAgences =
        @json(old('agences_passage', []));


    /*
    | S'il y a eu une erreur de validation,
    | on reconstruit les anciennes valeurs.
    */

    if (anciennesAgences.length > 0) {

        container.innerHTML = '';


        anciennesAgences.forEach(
            function (agenceId) {

                ajouterAgencePassage(
                    agenceId
                );

            }
        );

    }


    mettreAJourNumeros();

});

</script>
