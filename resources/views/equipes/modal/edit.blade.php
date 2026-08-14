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

                            {{ $equipe->code }}

                        </small>

                    </div>

                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body p-4">

                    {{-- =====================================================
                        NOM DE L'ÉQUIPE
                    ====================================================== --}}

                    <div class="form-group">

                        <label>

                            Nom de l'équipe

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text ocn-light">

                                    <i class="fas fa-users ocn-green"></i>

                                </span>

                            </div>

                            <input type="text"
                                name="nom"
                                value="{{ old('nom', $equipe->nom) }}"
                                class="form-control"
                                placeholder="Ex : Alpha 1"
                                autocomplete="off"
                                required>

                        </div>

                    </div>


                    {{-- =====================================================
                        COMPOSITION DE L'ÉQUIPE
                    ====================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-user-friends mr-2"></i>

                            Composition de l'équipe

                        </h6>


                        <div class="row mt-3">


                            {{-- CHAUFFEUR TITULAIRE --}}

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


                            {{-- CHAUFFEUR SECONDAIRE --}}

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

                    </div>


                    {{-- =====================================================
                        STATUT
                    ====================================================== --}}

                    <div class="form-group">

                        <label>

                            Statut

                            <span class="text-danger">*</span>

                        </label>

                        <select name="statut"
                                class="form-control"
                                required>

                            <option value="disponible"
                                {{ old('statut', $equipe->statut) === 'disponible' ? 'selected' : '' }}>

                                Disponible

                            </option>

                            <option value="en_voyage"
                                {{ old('statut', $equipe->statut) === 'en_voyage' ? 'selected' : '' }}>

                                En voyage

                            </option>

                            <option value="indisponible"
                                {{ old('statut', $equipe->statut) === 'indisponible' ? 'selected' : '' }}>

                                Indisponible

                            </option>

                        </select>

                    </div>


                    {{-- =====================================================
                        OBSERVATION
                    ====================================================== --}}

                    <div class="form-group">

                        <label>

                            <i class="fas fa-comment-alt mr-1 ocn-green"></i>

                            Observation

                        </label>

                        <textarea name="observation"
                                class="form-control"
                                rows="3"
                                placeholder="Informations supplémentaires...">{{ old('observation', $equipe->observation) }}</textarea>

                    </div>


                    {{-- CHAMPS OBLIGATOIRES --}}

                    <small class="text-muted">

                        <span class="text-danger">*</span>

                        Champs obligatoires.

                    </small>

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
