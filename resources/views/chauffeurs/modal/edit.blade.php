@foreach($chauffeurs as $chauffeur)

<div class="modal fade"
     id="modalEditChauffeur{{ $chauffeur->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('chauffeurs.update', $chauffeur) }}"
                  method="POST"
                  autocomplete="off">

                @csrf

                @method('PUT')


                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-user-edit mr-2"></i>

                            Modifier le chauffeur

                        </h5>

                        <small class="text-white">

                            {{ $chauffeur->matricule }}
                            —
                            {{ $chauffeur->nom }}
                            {{ $chauffeur->prenom }}

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


                    {{-- IDENTITÉ --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-user mr-2"></i>

                            Informations personnelles

                        </h6>


                        <div class="row mt-3">

                            {{-- MATRICULE --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Matricule

                                    </label>

                                    <input type="text"
                                           value="{{ $chauffeur->matricule }}"
                                           class="form-control bg-light"
                                           readonly>

                                    <small class="text-muted">

                                        Le matricule ne peut pas être modifié.

                                    </small>

                                </div>

                            </div>


                            {{-- TELEPHONE --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Téléphone

                                    </label>

                                    <input type="text"
                                           name="telephone"
                                           value="{{ $chauffeur->telephone }}"
                                           class="form-control"
                                           placeholder="Ex : 06 XXX XX XX"
                                           autocomplete="off">

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            {{-- NOM --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Nom
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text"
                                           name="nom"
                                           value="{{ $chauffeur->nom }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- PRENOM --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Prénom
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text"
                                           name="prenom"
                                           value="{{ $chauffeur->prenom }}"
                                           class="form-control"
                                           required>

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

                                    <label>

                                        Numéro de permis
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text"
                                           name="numero_permis"
                                           value="{{ $chauffeur->numero_permis }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Date d'expiration

                                    </label>

                                    <input type="date"
                                           name="date_expiration_permis"
                                           value="{{ $chauffeur->date_expiration_permis?->format('Y-m-d') }}"
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
                                            {{ $chauffeur->statut === 'actif' ? 'selected' : '' }}>

                                            Actif

                                        </option>

                                        <option value="en_voyage"
                                            {{ $chauffeur->statut === 'en_voyage' ? 'selected' : '' }}>

                                            En voyage

                                        </option>

                                        <option value="indisponible"
                                            {{ $chauffeur->statut === 'indisponible' ? 'selected' : '' }}>

                                            Indisponible

                                        </option>

                                        <option value="suspendu"
                                            {{ $chauffeur->statut === 'suspendu' ? 'selected' : '' }}>

                                            Suspendu

                                        </option>

                                        <option value="inactif"
                                            {{ $chauffeur->statut === 'inactif' ? 'selected' : '' }}>

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
                                            {{ $chauffeur->disponible ? 'selected' : '' }}>

                                            Disponible

                                        </option>

                                        <option value="0"
                                            {{ !$chauffeur->disponible ? 'selected' : '' }}>

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
                                  placeholder="Informations supplémentaires...">{{ $chauffeur->observation }}</textarea>

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
                            class="btn ocn-btn px-4">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach
