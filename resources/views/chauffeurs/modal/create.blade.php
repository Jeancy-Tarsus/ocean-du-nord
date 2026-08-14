<div class="modal fade"
     id="modalCreateChauffeur"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('chauffeurs.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf

                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white mb-1">

                            <i class="fas fa-user-plus mr-2"></i>

                            Nouveau chauffeur

                        </h5>

                        <small class="text-white">

                            Enregistrer un nouveau chauffeur

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


                    {{-- INFORMATIONS PERSONNELLES --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-user mr-2"></i>

                            Informations personnelles

                        </h6>


                        <div class="row mt-3">

                            {{-- NOM --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label for="nom">

                                        Nom
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text ocn-light">

                                                <i class="fas fa-user ocn-green"></i>

                                            </span>

                                        </div>

                                        <input type="text"
                                               id="nom"
                                               name="nom"
                                               value="{{ old('nom') }}"
                                               class="form-control"
                                               placeholder="Ex : MBOUKOU"
                                               autocomplete="off"
                                               required>

                                    </div>

                                </div>

                            </div>


                            {{-- PRENOM --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label for="prenom">

                                        Prénom
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text ocn-light">

                                                <i class="fas fa-user ocn-green"></i>

                                            </span>

                                        </div>

                                        <input type="text"
                                               id="prenom"
                                               name="prenom"
                                               value="{{ old('prenom') }}"
                                               class="form-control"
                                               placeholder="Ex : Jean"
                                               autocomplete="off"
                                               required>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            {{-- TELEPHONE --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label for="telephone">

                                        Téléphone

                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text ocn-light">

                                                <i class="fas fa-phone ocn-green"></i>

                                            </span>

                                        </div>

                                        <input type="text"
                                               id="telephone"
                                               name="telephone"
                                               value="{{ old('telephone') }}"
                                               class="form-control"
                                               placeholder="Ex : 06 XXX XX XX"
                                               autocomplete="off">

                                    </div>

                                </div>

                            </div>


                            {{-- MATRICULE --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Matricule

                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text ocn-light">

                                                <i class="fas fa-id-badge ocn-green"></i>

                                            </span>

                                        </div>

                                        <input type="text"
                                               class="form-control bg-light"
                                               value="Généré automatiquement"
                                               readonly>

                                    </div>

                                    <small class="text-muted">

                                        Le matricule est généré automatiquement.

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- PERMIS --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-id-card mr-2"></i>

                            Informations du permis

                        </h6>


                        <div class="row mt-3">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label for="numero_permis">

                                        Numéro de permis
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text"
                                           id="numero_permis"
                                           name="numero_permis"
                                           value="{{ old('numero_permis') }}"
                                           class="form-control"
                                           placeholder="Numéro du permis"
                                           autocomplete="off"
                                           required>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group">

                                    <label for="date_expiration_permis">

                                        Date d'expiration

                                    </label>

                                    <input type="date"
                                           id="date_expiration_permis"
                                           name="date_expiration_permis"
                                           value="{{ old('date_expiration_permis') }}"
                                           class="form-control">

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SITUATION --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-cogs mr-2"></i>

                            Situation du chauffeur

                        </h6>


                        <div class="row mt-3">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Statut
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="statut"
                                            class="form-control"
                                            required>

                                        <option value="actif"
                                            {{ old('statut', 'actif') === 'actif' ? 'selected' : '' }}>

                                            Actif

                                        </option>

                                        <option value="en_voyage">
                                            En voyage
                                        </option>

                                        <option value="indisponible">
                                            Indisponible
                                        </option>

                                        <option value="suspendu">
                                            Suspendu
                                        </option>

                                        <option value="inactif">
                                            Inactif
                                        </option>

                                    </select>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Disponibilité
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="disponible"
                                            class="form-control"
                                            required>

                                        <option value="1"
                                            {{ old('disponible', '1') == '1' ? 'selected' : '' }}>

                                            Disponible

                                        </option>

                                        <option value="0"
                                            {{ old('disponible') === '0' ? 'selected' : '' }}>

                                            Indisponible

                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- OBSERVATION --}}

                    <div class="form-group">

                        <label>

                            <i class="fas fa-comment-alt mr-1 ocn-green"></i>

                            Observation

                        </label>

                        <textarea name="observation"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Informations supplémentaires...">{{ old('observation') }}</textarea>

                    </div>


                    <small class="text-muted">

                        <span class="text-danger">*</span>

                        Champs obligatoires.

                    </small>

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
                            class="btn ocn-btn px-4">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer le chauffeur

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
