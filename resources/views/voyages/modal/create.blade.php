<div class="modal fade"
     id="modalCreateVoyage"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('voyages.store') }}"
                  method="POST">

                @csrf


                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-road mr-2"></i>

                            Nouveau voyage

                        </h5>

                        <small class="text-white">

                            Planifier un nouveau voyage

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


                    {{-- =================================================
                         INFORMATIONS DU VOYAGE
                    ================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-info-circle mr-2"></i>

                            Informations du voyage

                        </h6>


                        <div class="row mt-3">


                            {{-- LIGNE --}}

                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>

                                        Ligne

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="ligne_id"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner une ligne

                                        </option>

                                        @foreach($lignes as $ligne)

                                            <option value="{{ $ligne->id }}"
                                                {{ old('ligne_id') == $ligne->id ? 'selected' : '' }}>

                                                {{ $ligne->code }}
                                                —
                                                {{ $ligne->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- BUS --}}

                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>

                                        Bus

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="bus_id"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner un bus

                                        </option>

                                        @foreach($buses as $bus)

                                            <option value="{{ $bus->id }}"
                                                {{ old('bus_id') == $bus->id ? 'selected' : '' }}>

                                                {{-- {{ {{ $bus->numero }} — {{ $bus->immatriculation }} ?? 'Bus #' . $bus->id }} --}}
                                                {{ $bus->numero }} — {{ $bus->immatriculation }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            {{-- EQUIPE --}}

                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>

                                        Équipe

                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="equipe_id"
                                            class="form-control"
                                            required>

                                        <option value="">

                                            Sélectionner une équipe

                                        </option>

                                        @foreach($equipes as $equipe)

                                            <option value="{{ $equipe->id }}"
                                                {{ old('equipe_id') == $equipe->id ? 'selected' : '' }}>

                                                {{ $equipe->code }}
                                                —
                                                {{ $equipe->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         DEPART / ARRIVEE
                    ================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-clock mr-2"></i>

                            Horaires

                        </h6>


                        <div class="row mt-3">


                            {{-- DATE DEPART --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Date de départ

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="date"
                                           name="date_depart"
                                           value="{{ old('date_depart') }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- HEURE DEPART --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Heure de départ

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="time"
                                           name="heure_depart"
                                           value="{{ old('heure_depart') }}"
                                           class="form-control"
                                           required>

                                </div>

                            </div>


                            {{-- DATE ARRIVEE --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Date d'arrivée prévue

                                    </label>

                                    <input type="date"
                                           name="date_arrivee_prevue"
                                           value="{{ old('date_arrivee_prevue') }}"
                                           class="form-control">

                                </div>

                            </div>


                            {{-- HEURE ARRIVEE --}}

                            <div class="col-md-3">

                                <div class="form-group">

                                    <label>

                                        Heure d'arrivée prévue

                                    </label>

                                    <input type="time"
                                           name="heure_arrivee_prevue"
                                           value="{{ old('heure_arrivee_prevue') }}"
                                           class="form-control">

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         AGENCES
                    ================================================== --}}

                    <div class="mb-4">

                        <h6 class="ocn-title font-weight-bold border-bottom pb-2">

                            <i class="fas fa-building mr-2"></i>

                            Agences du parcours

                        </h6>


                        <p class="text-muted small mt-2">

                            Les agences seront enregistrées dans l'ordre
                            de passage du voyage.

                        </p>


                        <div class="row mt-3">


                            {{-- AGENCES DE DEPART --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Agences de départ

                                    </label>

                                    <select name="agences_depart[]"
                                            class="form-control"
                                            multiple
                                            size="5">

                                        @foreach($agences as $agence)

                                            <option value="{{ $agence->id }}">

                                                {{ $agence->code }}
                                                —
                                                {{ $agence->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                    <small class="text-muted">

                                        Maintenez Ctrl pour sélectionner
                                        plusieurs agences.

                                    </small>

                                </div>

                            </div>


                            {{-- AGENCES D'ARRIVEE --}}

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Agences d'arrivée

                                    </label>

                                    <select name="agences_arrivee[]"
                                            class="form-control"
                                            multiple
                                            size="5">

                                        @foreach($agences as $agence)

                                            <option value="{{ $agence->id }}">

                                                {{ $agence->code }}
                                                —
                                                {{ $agence->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                    <small class="text-muted">

                                        Maintenez Ctrl pour sélectionner
                                        plusieurs agences.

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         STATUT
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Statut

                            <span class="text-danger">*</span>

                        </label>

                        <select name="statut"
                                class="form-control"
                                required>

                            <option value="planifie"
                                {{ old('statut', 'planifie') === 'planifie' ? 'selected' : '' }}>

                                Planifié

                            </option>

                            <option value="en_cours"
                                {{ old('statut') === 'en_cours' ? 'selected' : '' }}>

                                En cours

                            </option>

                            <option value="termine"
                                {{ old('statut') === 'termine' ? 'selected' : '' }}>

                                Terminé

                            </option>

                            <option value="annule"
                                {{ old('statut') === 'annule' ? 'selected' : '' }}>

                                Annulé

                            </option>

                        </select>

                    </div>


                    {{-- OBSERVATION --}}

                    <div class="form-group">

                        <label>

                            Observation

                        </label>

                        <textarea name="observation"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Informations supplémentaires...">{{ old('observation') }}</textarea>

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
                            class="btn ocn-btn">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
