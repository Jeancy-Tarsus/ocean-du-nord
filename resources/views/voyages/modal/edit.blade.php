@foreach($voyages as $voyage)

@php

    /*
    |--------------------------------------------------------------------------
    | Pour la modification :
    | on affiche les bus disponibles ET le bus actuel du voyage.
    |--------------------------------------------------------------------------
    */

    $busesEdit = \App\Models\Bus::where(function ($query) use ($voyage) {

        $query->where('statut', 'disponible')
              ->orWhere('id', $voyage->bus_id);

    })
    ->orderBy('numero')
    ->get();

@endphp


<div class="modal fade"
     id="modalEditVoyage{{ $voyage->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('voyages.update', $voyage) }}"
                  method="POST">

                @csrf

                @method('PUT')


                {{-- =====================================================
                     HEADER
                ====================================================== --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-edit mr-2"></i>

                            Modifier le voyage

                        </h5>

                        <small class="text-white">

                            {{ $voyage->code }}

                        </small>

                    </div>


                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                {{-- =====================================================
                     BODY
                ====================================================== --}}

                <div class="modal-body p-4">


                    {{-- CODE AUTOMATIQUE --}}

                    <div class="form-group">

                        <label>

                            Code du voyage

                        </label>

                        <input type="text"
                               value="{{ $voyage->code }}"
                               class="form-control bg-light"
                               readonly>

                        <small class="text-muted">

                            Le code est généré automatiquement
                            et ne peut pas être modifié.

                        </small>

                    </div>


                    {{-- =================================================
                         LIGNE / BUS / EQUIPE
                    ================================================== --}}

                    <div class="row">


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
                                            {{ $voyage->ligne_id == $ligne->id ? 'selected' : '' }}>

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

                                    @foreach($busesEdit as $bus)

                                        <option value="{{ $bus->id }}"
                                            {{ $voyage->bus_id == $bus->id ? 'selected' : '' }}>

                                            {{ $bus->numero }}
                                            —
                                            {{ $bus->immatriculation }}

                                            @if($bus->id == $voyage->bus_id)

                                                — Bus actuel

                                            @endif

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
                                            {{ $voyage->equipe_id == $equipe->id ? 'selected' : '' }}>

                                            {{ $equipe->code }}
                                            —
                                            {{ $equipe->nom }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         HORAIRES
                    ================================================== --}}

                    <div class="row">


                        {{-- DATE DEPART --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label>

                                    Date de départ

                                    <span class="text-danger">*</span>

                                </label>

                                <input type="date"
                                       name="date_depart"
                                       value="{{ $voyage->date_depart?->format('Y-m-d') }}"
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
                                       value="{{ $voyage->heure_depart ? \Carbon\Carbon::parse($voyage->heure_depart)->format('H:i') : '' }}""
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
                                       value="{{ $voyage->date_arrivee_prevue?->format('Y-m-d') }}"
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
                                       value="value="{{ $voyage->heure_arrivee_prevue ? \Carbon\Carbon::parse($voyage->heure_arrivee_prevue)->format('H:i') : '' }}""
                                       class="form-control">

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
                                {{ $voyage->statut === 'planifie' ? 'selected' : '' }}>

                                Planifié

                            </option>

                            <option value="en_cours"
                                {{ $voyage->statut === 'en_cours' ? 'selected' : '' }}>

                                En cours

                            </option>

                            <option value="termine"
                                {{ $voyage->statut === 'termine' ? 'selected' : '' }}>

                                Terminé

                            </option>

                            <option value="annule"
                                {{ $voyage->statut === 'annule' ? 'selected' : '' }}>

                                Annulé

                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                         OBSERVATION
                    ================================================== --}}

                    <div class="form-group">

                        <label>

                            Observation

                        </label>

                        <textarea name="observation"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Informations supplémentaires...">{{ $voyage->observation }}</textarea>

                    </div>

                </div>


                {{-- =====================================================
                     FOOTER
                ====================================================== --}}

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
