<div class="modal fade"
     id="modalCreateUser"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('users.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf


                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-user-plus mr-2"></i>

                            Nouvel utilisateur

                        </h5>

                        <small class="text-white">

                            Créer un nouveau compte utilisateur

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


                {{-- BODY --}}

                <div class="modal-body p-4">


                    {{-- NOM --}}

                    <div class="form-group">

                        <label>

                            Nom complet

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-user ocn-green"></i>

                                </span>

                            </div>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control"
                                   placeholder="Ex : Jean Tarsus"
                                   autocomplete="off"
                                   required>

                        </div>

                    </div>



                    {{-- EMAIL --}}

                    <div class="form-group">

                        <label>

                            Adresse e-mail

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-envelope ocn-green"></i>

                                </span>

                            </div>

                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control"
                                   placeholder="exemple@ocean-du-nord.com"
                                   autocomplete="off"
                                   required>

                        </div>

                    </div>



                    {{-- RÔLE --}}

                    <div class="form-group">

                        <label>

                            Rôle

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-user-tag ocn-green"></i>

                                </span>

                            </div>

                            <select name="role"
                                    id="createUserRole"
                                    class="form-control"
                                    required>

                                <option value="">

                                    Sélectionner un rôle

                                </option>

                                <option value="admin">

                                    Administrateur

                                </option>

                                <option value="directeur_exploitation">

                                    Directeur d'exploitation

                                </option>

                                <option value="chef_parc">

                                    Chef de parc

                                </option>

                                <option value="chef_agence">

                                    Chef d'agence

                                </option>

                                <option value="chauffeur">

                                    Chauffeur

                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- AGENCE --}}

                    <div class="form-group"
                         id="createUserAgenceContainer"
                         style="display:none;">

                        <label>

                            Agence

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-building ocn-green"></i>

                                </span>

                            </div>

                            <select name="agence_id"
                                    id="createUserAgence"
                                    class="form-control">

                                <option value="">

                                    Sélectionner une agence

                                </option>

                                @foreach($agences as $agence)

                                    <option value="{{ $agence->id }}">

                                        {{ $agence->nom }}
                                        —
                                        {{ $agence->ville }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>



                    {{-- MOT DE PASSE --}}

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Mot de passe

                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text ocn-light">

                                            <i class="fas fa-lock ocn-green"></i>

                                        </span>

                                    </div>

                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           autocomplete="new-password"
                                           required>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Confirmation

                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text ocn-light">

                                            <i class="fas fa-lock ocn-green"></i>

                                        </span>

                                    </div>

                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control"
                                           autocomplete="new-password"
                                           required>

                                </div>

                            </div>

                        </div>

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
                            class="btn ocn-btn">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


