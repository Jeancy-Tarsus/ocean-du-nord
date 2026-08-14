<div class="modal fade"
     id="modalCreateEquipe"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('equipes.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-users mr-2"></i>

                            Nouvelle équipe

                        </h5>

                        <small class="text-white">

                            Créer une nouvelle équipe de chauffeurs

                        </small>

                    </div>

                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body p-4">

                    <div class="form-group">

                        <label>

                            Nom de l'équipe
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="nom"
                               value="{{ old('nom') }}"
                               class="form-control"
                               placeholder="Ex : Équipe A"
                               required>

                    </div>


                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Chauffeur titulaire
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="chauffeur_titulaire_id"
                                        class="form-control"
                                        required>

                                    <option value="">
                                        Sélectionner
                                    </option>

                                    @foreach($chauffeursDisponibles as $chauffeur)

                                        <option value="{{ $chauffeur->id }}"
                                            {{ old('chauffeur_titulaire_id') == $chauffeur->id ? 'selected' : '' }}>

                                            {{ $chauffeur->matricule }}
                                            —
                                            {{ $chauffeur->nom }}
                                            {{ $chauffeur->prenom }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Chauffeur secondaire
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="chauffeur_secondaire_id"
                                        class="form-control"
                                        required>

                                    <option value="">
                                        Sélectionner
                                    </option>

                                    @foreach($chauffeursDisponibles as $chauffeur)

                                        <option value="{{ $chauffeur->id }}"
                                            {{ old('chauffeur_secondaire_id') == $chauffeur->id ? 'selected' : '' }}>

                                            {{ $chauffeur->matricule }}
                                            —
                                            {{ $chauffeur->nom }}
                                            {{ $chauffeur->prenom }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>


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

                            <option value="en_voyage"
                                {{ old('statut') === 'en_voyage' ? 'selected' : '' }}>

                                En voyage

                            </option>

                            <option value="indisponible"
                                {{ old('statut') === 'indisponible' ? 'selected' : '' }}>

                                Indisponible

                            </option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Observation</label>

                        <textarea name="observation"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Informations supplémentaires...">{{ old('observation') }}</textarea>

                    </div>

                </div>


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
