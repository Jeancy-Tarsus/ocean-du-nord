<div class="modal fade"
     id="modalCreateLigne"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">

            <form action="{{ route('lignes.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf


                {{-- HEADER --}}

                <div class="modal-header ocn-modal-header">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-route mr-2"></i>

                            Nouvelle ligne

                        </h5>

                        <small class="text-white">

                            Ajouter une nouvelle ligne de transport

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

    {{-- NOM DE LA LIGNE --}}

    <div class="form-group">

        <label>

            Nom de la ligne

            <span class="text-danger">*</span>

        </label>

        <div class="input-group">

            <div class="input-group-prepend">

                <span class="input-group-text ocn-light">

                    <i class="fas fa-route ocn-green"></i>

                </span>

            </div>

            <input type="text"
                   name="nom"
                   value="{{ old('nom') }}"
                   class="form-control"
                   placeholder="Ex : Brazzaville - Pointe-Noire"
                   autocomplete="off"
                   required>

        </div>

    </div>


    {{-- DESCRIPTION --}}

    <div class="form-group">

        <label>

            Description

        </label>

        <textarea name="description"
                  class="form-control"
                  rows="4"
                  placeholder="Décrire le parcours de la ligne...">{{ old('description') }}</textarea>

    </div>


    {{-- STATUT --}}

    <div class="form-group">

        <label>

            Statut

        </label>

        <select name="active"
                class="form-control">

            <option value="1"
                {{ old('active', '1') == '1' ? 'selected' : '' }}>

                Active

            </option>

            <option value="0"
                {{ old('active') === '0' ? 'selected' : '' }}>

                Inactive

            </option>

        </select>

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
