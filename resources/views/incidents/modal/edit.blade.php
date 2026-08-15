<div class="modal fade"
     id="modalEditIncident{{ $incident->id }}"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalEditIncidentLabel{{ $incident->id }}"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">

            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header">

                <h5 class="modal-title"
                    id="modalEditIncidentLabel{{ $incident->id }}">

                    <i class="fas fa-edit mr-2"></i>

                    Modifier l'incident

                    <span class="text-muted">

                        — {{ $incident->reference }}

                    </span>

                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Fermer">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>


            {{-- =====================================================
                 FORMULAIRE
            ====================================================== --}}

            <form action="{{ route('incidents.update', $incident) }}"
                  method="POST">

                @csrf

                @method('PUT')


                <div class="modal-body">

                    {{-- =================================================
                         INFORMATIONS DU VOYAGE
                    ================================================== --}}

                    <div class="card border mb-3">

                        <div class="card-header bg-light">

                            <strong>

                                <i class="fas fa-route mr-1"></i>

                                Voyage concerné

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

                                        {{ $incident->voyage->code ?? '-' }}

                                    </strong>

                                </div>


                                {{-- LIGNE --}}

                                <div class="col-md-8 mb-3">

                                    <small class="text-muted d-block">

                                        Ligne

                                    </small>

                                    <strong>

                                        {{ $incident->voyage->ligne->nom ?? '-' }}

                                    </strong>

                                </div>


                                {{-- BUS --}}

                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">

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

                                            -

                                        @endif

                                    </strong>

                                </div>


                                {{-- AGENCE --}}

                                <div class="col-md-6 mb-3">

                                    <small class="text-muted d-block">

                                        Agence concernée

                                    </small>

                                    <strong>

                                        {{ $incident->agence->nom ?? '-' }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         TYPE + GRAVITE
                    ================================================== --}}

                    <div class="row">

                        {{-- TYPE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Type d'incident

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="type"
                                        class="form-control"
                                        required>

                                    <option value="panne"
                                        {{ $incident->type === 'panne' ? 'selected' : '' }}>

                                        Panne

                                    </option>

                                    <option value="accident"
                                        {{ $incident->type === 'accident' ? 'selected' : '' }}>

                                        Accident

                                    </option>

                                    <option value="retard"
                                        {{ $incident->type === 'retard' ? 'selected' : '' }}>

                                        Retard

                                    </option>

                                    <option value="probleme_chauffeur"
                                        {{ $incident->type === 'probleme_chauffeur' ? 'selected' : '' }}>

                                        Problème chauffeur

                                    </option>

                                    <option value="probleme_technique"
                                        {{ $incident->type === 'probleme_technique' ? 'selected' : '' }}>

                                        Problème technique

                                    </option>

                                    <option value="autre"
                                        {{ $incident->type === 'autre' ? 'selected' : '' }}>

                                        Autre

                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- GRAVITE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Gravité

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="gravite"
                                        class="form-control"
                                        required>

                                    <option value="faible"
                                        {{ $incident->gravite === 'faible' ? 'selected' : '' }}>

                                        Faible

                                    </option>

                                    <option value="moyenne"
                                        {{ $incident->gravite === 'moyenne' ? 'selected' : '' }}>

                                        Moyenne

                                    </option>

                                    <option value="grave"
                                        {{ $incident->gravite === 'grave' ? 'selected' : '' }}>

                                        Grave

                                    </option>

                                    <option value="critique"
                                        {{ $incident->gravite === 'critique' ? 'selected' : '' }}>

                                        Critique

                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         TITRE
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Titre

                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="titre"
                               class="form-control"
                               value="{{ old('titre', $incident->titre) }}"
                               maxlength="255"
                               required>

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
                                       value="{{ old(
                                           'date_incident',
                                           $incident->date_incident
                                               ? $incident->date_incident->format('Y-m-d')
                                               : ''
                                       ) }}"
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
                                       value="{{ old(
                                           'heure_incident',
                                           $incident->heure_incident
                                               ? substr($incident->heure_incident, 0, 5)
                                               : ''
                                       ) }}"
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
                                {{ $incident->statut === 'ouvert' ? 'selected' : '' }}>

                                Ouvert

                            </option>

                            <option value="en_cours"
                                {{ $incident->statut === 'en_cours' ? 'selected' : '' }}>

                                En cours

                            </option>

                            <option value="resolu"
                                {{ $incident->statut === 'resolu' ? 'selected' : '' }}>

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
                                  required>{{ old(
                                      'description',
                                      $incident->description
                                  ) }}</textarea>

                    </div>


                    {{-- =================================================
                         RESOLUTION
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Résolution

                        </label>

                        <textarea name="resolution"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Indiquer la résolution si le problème est résolu...">{{ old(
                                      'resolution',
                                      $incident->resolution
                                  ) }}</textarea>

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
                                  rows="3">{{ old(
                                      'observation',
                                      $incident->observation
                                  ) }}</textarea>

                    </div>


                    {{-- =================================================
                         DECLARE PAR
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Déclaré par

                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $incident->user->name ?? '-' }}"
                               readonly>

                    </div>

                </div>


                {{-- =====================================================
                     FOOTER
                ====================================================== --}}

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        <i class="fas fa-times mr-1"></i>

                        Annuler

                    </button>


                    <button type="submit"
                            class="btn btn-warning">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
