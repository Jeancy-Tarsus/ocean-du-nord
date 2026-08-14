@foreach($bus as $bu)

<div class="modal fade"
     id="modalEditBus{{ $bu->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('bus.update', $bu) }}"
                  method="POST"
                  autocomplete="off">

                @csrf

                @method('PUT')


                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-edit mr-2"></i>

                            Modifier le bus

                        </h5>

                        <small class="text-white">

                            {{ $bu->numero }}

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
                                       value="{{ $bu->numero }}"
                                       class="form-control"
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
                                       value="{{ $bu->immatriculation }}"
                                       class="form-control"
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
                                       value="{{ $bu->marque }}"
                                       class="form-control">

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
                                       value="{{ $bu->modele }}"
                                       class="form-control">

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
                                       value="{{ $bu->capacite }}"
                                       class="form-control"
                                       min="1"
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
                                        {{ $bu->etat === 'bon' ? 'selected' : '' }}>

                                        Bon

                                    </option>

                                    <option value="moyen"
                                        {{ $bu->etat === 'moyen' ? 'selected' : '' }}>

                                        Moyen

                                    </option>

                                    <option value="mauvais"
                                        {{ $bu->etat === 'mauvais' ? 'selected' : '' }}>

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
                                        {{ $bu->statut === 'disponible' ? 'selected' : '' }}>

                                        Disponible

                                    </option>

                                    <option value="en_voyage"
                                        {{ $bu->statut === 'en_voyage' ? 'selected' : '' }}>

                                        En voyage

                                    </option>

                                    <option value="en_maintenance"
                                        {{ $bu->statut === 'en_maintenance' ? 'selected' : '' }}>

                                        En maintenance

                                    </option>

                                    <option value="en_panne"
                                        {{ $bu->statut === 'en_panne' ? 'selected' : '' }}>

                                        En panne

                                    </option>

                                    <option value="hors_service"
                                        {{ $bu->statut === 'hors_service' ? 'selected' : '' }}>

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
                                  placeholder="Informations supplémentaires...">{{ $bu->observation }}</textarea>

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
