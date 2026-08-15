<div class="modal fade"
     id="modalCreateIncident"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalCreateIncidentLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">

            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header">

                <h5 class="modal-title"
                    id="modalCreateIncidentLabel">

                    <i class="fas fa-exclamation-triangle mr-2"></i>

                    Déclarer un incident

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

            <form action="{{ route('incidents.store') }}"
                  method="POST">

                @csrf

                <div class="modal-body">

                    {{-- =================================================
                         VOYAGE
                    ================================================== --}}

                    <div class="form-group">

                        <label for="incident_voyage_id">

                            Voyage

                            <span class="text-danger">*</span>

                        </label>

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


                    {{-- =================================================
                         INFORMATIONS AUTOMATIQUES DU VOYAGE
                    ================================================== --}}

                    <div id="incidentVoyageInfo"
                         class="d-none mb-3">

                        <div class="card border">

                            <div class="card-header bg-light">

                                <strong>

                                    <i class="fas fa-route mr-1"></i>

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

                                        <strong id="incidentInfoCode">

                                            -

                                        </strong>

                                    </div>


                                    {{-- LIGNE --}}

                                    <div class="col-md-8 mb-3">

                                        <small class="text-muted d-block">

                                            Ligne

                                        </small>

                                        <strong id="incidentInfoLigne">

                                            -

                                        </strong>

                                    </div>


                                    {{-- BUS --}}

                                    <div class="col-md-6 mb-3">

                                        <small class="text-muted d-block">

                                            Bus

                                        </small>

                                        <strong id="incidentInfoBus">

                                            -

                                        </strong>

                                    </div>


                                    {{-- EQUIPE --}}

                                    <div class="col-md-6 mb-3">

                                        <small class="text-muted d-block">

                                            Équipe

                                        </small>

                                        <strong id="incidentInfoEquipe">

                                            -

                                        </strong>

                                    </div>


                                    {{-- CHAUFFEUR 1 --}}

                                    <div class="col-md-6 mb-3">

                                        <small class="text-muted d-block">

                                            Chauffeur titulaire

                                        </small>

                                        <span id="incidentInfoChauffeur1">

                                            -

                                        </span>

                                    </div>


                                    {{-- CHAUFFEUR 2 --}}

                                    <div class="col-md-6 mb-3">

                                        <small class="text-muted d-block">

                                            Chauffeur secondaire

                                        </small>

                                        <span id="incidentInfoChauffeur2">

                                            -

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

                        <select name="agence_id"
                                id="incident_agence_id"
                                class="form-control"
                                required
                                disabled>

                            <option value="">

                                Sélectionnez d'abord un voyage

                            </option>

                        </select>

                        <small class="form-text text-muted">

                            Les agences proposées correspondent
                            uniquement au parcours du voyage.

                        </small>

                    </div>


                    {{-- =================================================
                         TYPE + GRAVITE
                    ================================================== --}}

                    <div class="row">

                        {{-- TYPE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="incident_type">

                                    Type d'incident

                                    <span class="text-danger">*</span>

                                </label>

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


                        {{-- GRAVITE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="incident_gravite">

                                    Gravité

                                    <span class="text-danger">*</span>

                                </label>

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


                    {{-- =================================================
                         TITRE
                    ================================================== --}}

                    <div class="form-group">

                        <label for="incident_titre">

                            Titre

                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="titre"
                               id="incident_titre"
                               class="form-control"
                               maxlength="255"
                               placeholder="Exemple : Panne moteur"
                               required>

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

                                <input type="date"
                                       name="date_incident"
                                       id="incident_date"
                                       class="form-control"
                                       value="{{ now()->format('Y-m-d') }}"
                                       required>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="incident_heure">

                                    Heure de l'incident

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="time"
                                       name="heure_incident"
                                       id="incident_heure"
                                       class="form-control"
                                       value="{{ now()->format('H:i') }}"
                                       required>

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
                                  required></textarea>

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
                                  placeholder="Informations complémentaires..."></textarea>

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
                               value="{{ auth()->user()->name }}"
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
                            class="btn ocn-btn">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer l'incident

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
