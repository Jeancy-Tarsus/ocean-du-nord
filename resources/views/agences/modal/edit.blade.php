@foreach($agences as $agence)

<div class="modal fade"
     id="modalEditAgence{{ $agence->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('agences.update', $agence) }}"
                  method="POST"
                  autocomplete="off">

                @csrf

                @method('PUT')


                {{-- HEADER --}}
                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-edit mr-2"></i>

                            Modifier l'agence

                        </h5>

                        <small class="text-white">

                            Modifier les informations de l'agence
                            {{ $agence->code }}

                        </small>

                    </div>

                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal"
                            aria-label="Fermer">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>


                {{-- BODY --}}
                <div class="modal-body p-4">


                    {{-- NOM --}}
                    <div class="form-group">

                        <label>

                            Nom de l'agence

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-building ocn-green"></i>

                                </span>

                            </div>

                            <input type="text"
                                   name="nom"
                                   value="{{ old('nom', $agence->nom) }}"
                                   class="form-control"
                                   placeholder="Ex : Agence Centre"
                                   autocomplete="off"
                                   required>

                        </div>

                    </div>


                    {{-- VILLE --}}
                    <div class="form-group">

                        <label>

                            Ville

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-map-marker-alt ocn-green"></i>

                                </span>

                            </div>

                            <input type="text"
                                   name="ville"
                                   value="{{ old('ville', $agence->ville) }}"
                                   class="form-control"
                                   placeholder="Ex : Brazzaville"
                                   autocomplete="off"
                                   required>

                        </div>

                    </div>


                    {{-- ADRESSE --}}
                    <div class="form-group">

                        <label>

                            Adresse

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-map-marker-alt ocn-green"></i>

                                </span>

                            </div>

                            <input type="text"
                                   name="adresse"
                                   value="{{ old('adresse', $agence->adresse) }}"
                                   class="form-control"
                                   placeholder="Adresse de l'agence"
                                   autocomplete="off">

                        </div>

                    </div>


                    {{-- TELEPHONE --}}
                    <div class="form-group">

                        <label>

                            Téléphone

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-phone ocn-green"></i>

                                </span>

                            </div>

                            <input type="text"
                                   name="telephone"
                                   value="{{ old('telephone', $agence->telephone) }}"
                                   class="form-control"
                                   placeholder="Ex : 06 XXX XX XX"
                                   autocomplete="off">

                        </div>

                    </div>


                    {{-- STATUT --}}
                    <div class="form-group">

                        <label>

                            Statut

                        </label>

                        <div class="custom-control custom-switch">

                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="activeEditAgence{{ $agence->id }}"
                                   name="active"
                                   value="1"
                                   {{ old('active', $agence->active) ? 'checked' : '' }}>

                            <label class="custom-control-label"
                                   for="activeEditAgence{{ $agence->id }}">

                                Agence active

                            </label>

                        </div>

                    </div>


                    <small class="text-muted">

                        Le code de l'agence est généré automatiquement et ne peut pas être modifié.

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
