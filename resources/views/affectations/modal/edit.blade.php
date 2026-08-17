<div class="modal fade"
     id="modalEditAffectation{{ $affectation->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('affectations.update', $affectation) }}"
                  method="POST"
                  autocomplete="off">

                @csrf

                @method('PUT')


                {{-- =====================================================
                     HEADER
                ====================================================== --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-edit mr-2"></i>

                            Modifier l'affectation

                        </h5>

                        <small class="text-white">

                            Voyage :
                            {{ $affectation->voyage->code ?? '—' }}

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


                    {{-- VOYAGE --}}

                    <div class="card border-0 bg-light mb-4">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        VOYAGE
                                    </small>

                                    <strong class="h5">
                                        {{ $affectation->voyage->code ?? '—' }}
                                    </strong>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted d-block">
                                        LIGNE
                                    </small>

                                    <strong>
                                        {{ $affectation->voyage->ligne->nom ?? '—' }}
                                    </strong>

                                </div>

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
                                id="editType{{ $affectation->id }}"
                                class="form-control"
                                required>

                            <option value="remplacement_bus"
                                {{ $affectation->type === 'remplacement_bus' ? 'selected' : '' }}>

                                Remplacement du bus

                            </option>

                            <option value="remplacement_equipe"
                                {{ $affectation->type === 'remplacement_equipe' ? 'selected' : '' }}>

                                Remplacement de l'équipe

                            </option>

                            <option value="remplacement_bus_equipe"
                                {{ $affectation->type === 'remplacement_bus_equipe' ? 'selected' : '' }}>

                                Remplacement bus + équipe

                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                         ANCIENNES RESSOURCES
                    ================================================== --}}

                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-history mr-1 text-secondary"></i>

                                Ressources avant remplacement

                            </strong>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                {{-- ANCIEN BUS --}}

                                <div class="col-md-6">

                                    <div class="form-group mb-md-0">

                                        <label>

                                            Ancien bus

                                        </label>

                                        <input type="text"
                                               class="form-control"
                                               value="{{ $affectation->ancienBus->numero ?? '—' }}"
                                               readonly>

                                        <input type="hidden"
                                               name="ancien_bus_id"
                                               value="{{ $affectation->ancien_bus_id }}">

                                    </div>

                                </div>


                                {{-- ANCIENNE ÉQUIPE --}}

                                <div class="col-md-6">

                                    <div class="form-group mb-md-0">

                                        <label>

                                            Ancienne équipe

                                        </label>

                                        <input type="text"
                                               class="form-control"
                                               value="{{ $affectation->ancienneEquipe->nom ?? '—' }}"
                                               readonly>

                                        <input type="hidden"
                                               name="ancienne_equipe_id"
                                               value="{{ $affectation->ancienne_equipe_id }}">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         NOUVELLES RESSOURCES
                    ================================================== --}}

                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-random mr-1 ocn-green"></i>

                                Ressources de remplacement

                            </strong>

                        </div>


                        <div class="card-body">


                            {{-- NOUVEAU BUS --}}

                            <div id="editBlocBus{{ $affectation->id }}"
                                 class="form-group">

                                <label>

                                    Nouveau bus

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="nouveau_bus_id"
                                        id="editBus{{ $affectation->id }}"
                                        class="form-control">

                                    <option value="">

                                        Sélectionner un bus disponible

                                    </option>


                                    {{-- L'ancien bus est affiché séparément.
                                         Ici on ne propose que les bus disponibles. --}}

                                    @foreach($busesDisponibles as $bus)

                                        <option value="{{ $bus->id }}"
                                            {{ (int) $affectation->nouveau_bus_id === (int) $bus->id ? 'selected' : '' }}>

                                            {{ $bus->numero }}

                                            @if(isset($bus->immatriculation))

                                                —
                                                {{ $bus->immatriculation }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">

                                    Seuls les bus actuellement
                                    <strong>disponibles</strong>
                                    sont proposés.

                                </small>

                            </div>


                            {{-- NOUVELLE ÉQUIPE --}}

                            <div id="editBlocEquipe{{ $affectation->id }}"
                                 class="form-group">

                                <label>

                                    Nouvelle équipe

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="nouvelle_equipe_id"
                                        id="editEquipe{{ $affectation->id }}"
                                        class="form-control">

                                    <option value="">

                                        Sélectionner une équipe

                                    </option>

                                    @foreach($equipes as $equipe)

                                        <option value="{{ $equipe->id }}"
                                            {{ (int) $affectation->nouvelle_equipe_id === (int) $equipe->id ? 'selected' : '' }}>

                                            {{ $equipe->nom }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         MOTIF
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Motif

                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="motif"
                               class="form-control"
                               value="{{ $affectation->motif }}"
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
                                       value="{{ $affectation->date_affectation
                                           ? $affectation->date_affectation->format('Y-m-d')
                                           : ''
                                       }}"
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
                                       value="{{ $affectation->heure_affectation }}"
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
                                  rows="4">{{ $affectation->observation }}</textarea>

                    </div>


                    <div class="alert alert-warning mb-0">

                        <i class="fas fa-info-circle mr-1"></i>

                        La modification concerne uniquement
                        l'enregistrement de l'affectation.
                        Le voyage initial n'est pas modifié.

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
     JAVASCRIPT DU MODAL
========================================================= --}}

<script>

$(document).ready(function () {

    function gererTypeAffectation{{ $affectation->id }}() {

        const type = $('#editType{{ $affectation->id }}').val();


        /*
        |--------------------------------------------------------------------------
        | BUS
        |--------------------------------------------------------------------------
        */

        if (
            type === 'remplacement_bus'
            ||
            type === 'remplacement_bus_equipe'
        ) {

            $('#editBlocBus{{ $affectation->id }}')
                .removeClass('d-none');

            $('#editBus{{ $affectation->id }}')
                .prop('required', true);

        } else {

            $('#editBlocBus{{ $affectation->id }}')
                .addClass('d-none');

            $('#editBus{{ $affectation->id }}')
                .prop('required', false);

        }


        /*
        |--------------------------------------------------------------------------
        | ÉQUIPE
        |--------------------------------------------------------------------------
        */

        if (
            type === 'remplacement_equipe'
            ||
            type === 'remplacement_bus_equipe'
        ) {

            $('#editBlocEquipe{{ $affectation->id }}')
                .removeClass('d-none');

            $('#editEquipe{{ $affectation->id }}')
                .prop('required', true);

        } else {

            $('#editBlocEquipe{{ $affectation->id }}')
                .addClass('d-none');

            $('#editEquipe{{ $affectation->id }}')
                .prop('required', false);

        }

    }


    $('#editType{{ $affectation->id }}').on(
        'change',
        function () {

            gererTypeAffectation{{ $affectation->id }}();

        }
    );


    /*
    | Initialisation
    */

    gererTypeAffectation{{ $affectation->id }}();

});

</script>
