@php

    $canEditIncident = false;

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    if (auth()->user()->role === 'admin') {

        $canEditIncident = true;

    }

    /*
    |--------------------------------------------------------------------------
    | DIRECTEUR EXPLOITATION
    |--------------------------------------------------------------------------
    */

    elseif (
        auth()->user()->role === 'directeur_exploitation'
    ) {

        $canEditIncident = true;

    }

    /*
    |--------------------------------------------------------------------------
    | INCIDENT NON RÉSOLU
    |--------------------------------------------------------------------------
    */

    elseif (
        $incident->statut !== 'resolu'
    ) {

        /*
        | Chef d'agence
        */

        if (
            auth()->user()->role === 'chef_agence'
            &&
            (int) $incident->agence_id ===
            (int) auth()->user()->agence_id
        ) {

            $canEditIncident = true;

        }

        /*
        | Déclarant
        */

        elseif (
            (int) $incident->user_id ===
            (int) auth()->id()
        ) {

            $canEditIncident = true;

        }

    }

@endphp


@if($canEditIncident)

<div class="modal fade"
     id="modalEditIncident{{ $incident->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">


            <form action="{{ route('incidents.update', $incident) }}"
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

                            Modifier l'incident

                        </h5>

                        <small class="text-white">

                            {{ $incident->reference }}

                            —

                            {{ $incident->titre }}

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
                         INFORMATIONS DU VOYAGE
                    ================================================== --}}

                    <div class="card border shadow-sm mb-4">

                        <div class="card-header ocn-light">

                            <strong>

                                <i class="fas fa-route ocn-green mr-1"></i>

                                Informations du voyage

                            </strong>

                        </div>


                        <div class="card-body">

                            <div class="row">


                                {{-- VOYAGE --}}

                                <div class="col-md-4 mb-3">

                                    <small class="text-muted d-block">

                                        Voyage

                                    </small>

                                    <strong>

                                        {{ $incident->voyage->code ?? '—' }}

                                    </strong>

                                </div>


                                {{-- LIGNE --}}

                                <div class="col-md-8 mb-3">

                                    <small class="text-muted d-block">

                                        Ligne

                                    </small>

                                    <strong>

                                        {{ $incident->voyage->ligne->nom ?? '—' }}

                                    </strong>

                                </div>


                                {{-- BUS --}}

                                <div class="col-md-6">

                                    <small class="text-muted d-block">

                                        <i class="fas fa-bus ocn-green mr-1"></i>

                                        Bus

                                    </small>

                                    <strong>

                                        @if($incident->bus)

                                            {{ $incident->bus->numero }}

                                            @if($incident->bus->immatriculation)

                                                —
                                                {{ $incident->bus->immatriculation }}

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </strong>

                                </div>


                                {{-- AGENCE --}}

                                <div class="col-md-6">

                                    <small class="text-muted d-block">

                                        <i class="fas fa-building ocn-green mr-1"></i>

                                        Agence concernée

                                    </small>

                                    <strong>

                                        {{ $incident->agence->nom ?? '—' }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         TYPE + GRAVITÉ
                    ================================================== --}}

                    <div class="row">


                        {{-- TYPE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

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
                                            class="form-control"
                                            required>

                                        <option value="panne"
                                            {{ old('type', $incident->type) === 'panne' ? 'selected' : '' }}>

                                            Panne

                                        </option>

                                        <option value="accident"
                                            {{ old('type', $incident->type) === 'accident' ? 'selected' : '' }}>

                                            Accident

                                        </option>

                                        <option value="retard"
                                            {{ old('type', $incident->type) === 'retard' ? 'selected' : '' }}>

                                            Retard

                                        </option>

                                        <option value="probleme_chauffeur"
                                            {{ old('type', $incident->type) === 'probleme_chauffeur' ? 'selected' : '' }}>

                                            Problème chauffeur

                                        </option>

                                        <option value="probleme_technique"
                                            {{ old('type', $incident->type) === 'probleme_technique' ? 'selected' : '' }}>

                                            Problème technique

                                        </option>

                                        <option value="autre"
                                            {{ old('type', $incident->type) === 'autre' ? 'selected' : '' }}>

                                            Autre

                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>



                        {{-- GRAVITÉ --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

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
                                            class="form-control"
                                            required>

                                        <option value="faible"
                                            {{ old('gravite', $incident->gravite) === 'faible' ? 'selected' : '' }}>

                                            Faible

                                        </option>

                                        <option value="moyenne"
                                            {{ old('gravite', $incident->gravite) === 'moyenne' ? 'selected' : '' }}>

                                            Moyenne

                                        </option>

                                        <option value="grave"
                                            {{ old('gravite', $incident->gravite) === 'grave' ? 'selected' : '' }}>

                                            Grave

                                        </option>

                                        <option value="critique"
                                            {{ old('gravite', $incident->gravite) === 'critique' ? 'selected' : '' }}>

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

                        <label>

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
                                   class="form-control"
                                   value="{{ old('titre', $incident->titre) }}"
                                   maxlength="255"
                                   required>

                        </div>

                    </div>



                    {{-- =================================================
                         DATE + HEURE
                    ================================================== --}}

                    <div class="row">


                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Date de l'incident

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="date"
                                       name="date_incident"
                                       class="form-control"
                                       value="{{ old('date_incident', optional($incident->date_incident)->format('Y-m-d')) }}"
                                       required>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Heure de l'incident

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="time"
                                       name="heure_incident"
                                       class="form-control"
                                       value="{{ old('heure_incident', $incident->heure_incident) }}"
                                       required>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         STATUT
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Statut

                            <span class="text-danger">*</span>

                        </label>


                        <select name="statut"
                                class="form-control"
                                required>

                            <option value="ouvert"
                                {{ old('statut', $incident->statut) === 'ouvert' ? 'selected' : '' }}>

                                Ouvert

                            </option>

                            <option value="en_cours"
                                {{ old('statut', $incident->statut) === 'en_cours' ? 'selected' : '' }}>

                                En cours

                            </option>

                            <option value="resolu"
                                {{ old('statut', $incident->statut) === 'resolu' ? 'selected' : '' }}>

                                Résolu

                            </option>

                        </select>

                    </div>



                    {{-- =================================================
                         DESCRIPTION
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Description

                            <span class="text-danger">*</span>

                        </label>


                        <textarea name="description"
                                  class="form-control"
                                  rows="4"
                                  required>{{ old('description', $incident->description) }}</textarea>

                    </div>



                    {{-- =================================================
                         RÉSOLUTION
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Résolution

                        </label>


                        <textarea name="resolution"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Décrire la solution apportée...">{{ old('resolution', $incident->resolution) }}</textarea>

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
                                  placeholder="Informations complémentaires...">{{ old('observation', $incident->observation) }}</textarea>

                    </div>



                    <small class="text-muted">

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

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif
