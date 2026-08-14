@foreach($equipes as $equipe)

<div class="modal fade"
     id="modalEditEquipe{{ $equipe->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('equipes.update', $equipe) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-edit mr-2"></i>

                            Modifier l'équipe

                        </h5>

                        <small class="text-white">

                            {{ $equipe->nom }}

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
                            Code de l'équipe
                        </label>

                        <input type="text"
                            value="{{ $equipe->code }}"
                            class="form-control bg-light"
                            readonly>

                        <small class="text-muted">
                            Le code de l'équipe est généré automatiquement et ne peut pas être modifié.
                        </small>

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

                                    @foreach($equipe->chauffeursEdit as $chauffeur)

                                        <option value="{{ $chauffeur->id }}"
                                            {{ $equipe->chauffeur_titulaire_id == $chauffeur->id ? 'selected' : '' }}>

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

                                    @foreach($equipe->chauffeursEdit as $chauffeur)

                                        <option value="{{ $chauffeur->id }}"
                                            {{ $equipe->chauffeur_secondaire_id == $chauffeur->id ? 'selected' : '' }}>

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
                                {{ $equipe->statut === 'disponible' ? 'selected' : '' }}>

                                Disponible

                            </option>

                            <option value="en_voyage"
                                {{ $equipe->statut === 'en_voyage' ? 'selected' : '' }}>

                                En voyage

                            </option>

                            <option value="indisponible"
                                {{ $equipe->statut === 'indisponible' ? 'selected' : '' }}>

                                Indisponible

                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Observation</label>

                        <textarea name="observation"
                                  class="form-control"
                                  rows="3">{{ $equipe->observation }}</textarea>

                    </div>

                </div>


                <div class="modal-footer ocn-modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

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
