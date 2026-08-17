@foreach($users as $user)

<div class="modal fade"
     id="modalEditUser{{ $user->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('users.update', $user) }}"
                  method="POST"
                  autocomplete="off">

                @csrf

                @method('PUT')


                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-user-edit mr-2"></i>

                            Modifier l'utilisateur

                        </h5>

                        <small class="text-white">

                            {{ $user->name }}
                            —
                            {{ $user->email }}

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
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control"
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
                                   value="{{ old('email', $user->email) }}"
                                   class="form-control"
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
                                    id="editUserRole{{ $user->id }}"
                                    class="form-control edit-user-role"
                                    data-user="{{ $user->id }}"
                                    required>

                                <option value="admin"
                                    {{ $user->role === 'admin' ? 'selected' : '' }}>

                                    Administrateur

                                </option>

                                <option value="directeur_exploitation"
                                    {{ $user->role === 'directeur_exploitation' ? 'selected' : '' }}>

                                    Directeur d'exploitation

                                </option>

                                <option value="chef_parc"
                                    {{ $user->role === 'chef_parc' ? 'selected' : '' }}>

                                    Chef de parc

                                </option>

                                <option value="chef_agence"
                                    {{ $user->role === 'chef_agence' ? 'selected' : '' }}>

                                    Chef d'agence

                                </option>

                                <option value="chauffeur"
                                    {{ $user->role === 'chauffeur' ? 'selected' : '' }}>

                                    Chauffeur

                                </option>

                            </select>

                        </div>

                    </div>



                    {{-- AGENCE --}}

                    <div class="form-group"
                         id="editUserAgenceContainer{{ $user->id }}"
                         style="{{ $user->role === 'chef_agence' ? '' : 'display:none;' }}">

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
                                    id="editUserAgence{{ $user->id }}"
                                    class="form-control">

                                <option value="">

                                    Sélectionner une agence

                                </option>

                                @foreach($agences as $agence)

                                    <option value="{{ $agence->id }}"
                                        {{ $user->agence_id == $agence->id ? 'selected' : '' }}>

                                        {{ $agence->nom }}
                                        —
                                        {{ $agence->ville }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>



                    {{-- MOT DE PASSE --}}

                    <div class="form-group">

                        <label>

                            Nouveau mot de passe

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
                                   placeholder="Laisser vide pour conserver l'actuel"
                                   autocomplete="new-password">

                        </div>

                    </div>



                    {{-- CONFIRMATION --}}

                    <div class="form-group">

                        <label>

                            Confirmation du nouveau mot de passe

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
                                   autocomplete="new-password">

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

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach

