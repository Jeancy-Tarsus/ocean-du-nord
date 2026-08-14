<div class="modal fade"
     id="modalCreateBus"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('bus.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf


                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-bus mr-2"></i>

                            Nouveau bus

                        </h5>

                        <small class="text-white">

                            Ajouter un nouveau bus au parc automobile

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

                    <div class="row">

                        {{-- NUMERO --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    N° Bus

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       name="numero"
                                       value="{{ old('numero') }}"
                                       class="form-control"
                                       placeholder="Ex : BUS-001"
                                       autocomplete="off"
                                       required>

                            </div>

                        </div>


                        {{-- IMMATRICULATION --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Immatriculation

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       name="immatriculation"
                                       value="{{ old('immatriculation') }}"
                                       class="form-control"
                                       placeholder="Ex : CG-1234-AB"
                                       autocomplete="off"
                                       required>

                            </div>

                        </div>

                    </div>


                    <div class="row">

                        {{-- MARQUE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Marque
                                </label>

                                <input type="text"
                                       name="marque"
                                       value="{{ old('marque') }}"
                                       class="form-control"
                                       placeholder="Ex : Mercedes">

                            </div>

                        </div>


                        {{-- MODELE --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Modèle
                                </label>

                                <input type="text"
                                       name="modele"
                                       value="{{ old('modele') }}"
                                       class="form-control"
                                       placeholder="Ex : Tourismo">

                            </div>

                        </div>

                    </div>


                    <div class="row">

                        {{-- CAPACITE --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    Capacité

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="number"
                                       name="capacite"
                                       value="{{ old('capacite') }}"
                                       class="form-control"
                                       min="1"
                                       placeholder="Ex : 50"
                                       required>

                            </div>

                        </div>


                        {{-- ETAT --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    État

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="etat"
                                        class="form-control"
                                        required>

                                    <option value="bon"
                                        {{ old('etat', 'bon') === 'bon' ? 'selected' : '' }}>

                                        Bon

                                    </option>

                                    <option value="moyen"
                                        {{ old('etat') === 'moyen' ? 'selected' : '' }}>

                                        Moyen

                                    </option>

                                    <option value="mauvais"
                                        {{ old('etat') === 'mauvais' ? 'selected' : '' }}>

                                        Mauvais

                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- STATUT --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    Statut

                                    <span class="text-danger">*</span>

                                </label>

                                <select name="statut"
                                        class="form-control"
                                        required>

                                    <option value="disponible"
                                        {{ old('statut', 'disponible') === 'disponible' ? 'selected' : '' }}>

                                        Disponible

                                    </option>

                                    <option value="en_voyage">
                                        En voyage
                                    </option>

                                    <option value="en_maintenance">
                                        En maintenance
                                    </option>

                                    <option value="en_panne">
                                        En panne
                                    </option>

                                    <option value="hors_service">
                                        Hors service
                                    </option>

                                </select>

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

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
