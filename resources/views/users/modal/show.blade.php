@foreach($users as $user)

<div class="modal fade"
     id="modalShowUser{{ $user->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content shadow-lg border-0">


            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="modal-header ocn-modal-header">

                <div>

                    <h5 class="modal-title text-white">

                        <i class="fas fa-user mr-2"></i>

                        Détails de l'utilisateur

                    </h5>

                    <small class="text-white">

                        Informations du compte

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



            {{-- =====================================================
                 BODY
            ====================================================== --}}

            <div class="modal-body p-4">


                {{-- IDENTITÉ --}}

                <div class="text-center mb-4">

                    <div
                        class="mx-auto mb-2 d-flex align-items-center justify-content-center"
                        style="
                            width: 70px;
                            height: 70px;
                            border-radius: 50%;
                            background: var(--ocn-green-light);
                        "
                    >

                        <i class="fas fa-user ocn-green"
                           style="font-size: 28px;">
                        </i>

                    </div>


                    <h4 class="mb-1"
                        style="font-size: 18px;">

                        {{ $user->name }}

                    </h4>


                    <small class="text-muted">

                        {{ $user->email }}

                    </small>

                </div>



                <hr>



                {{-- INFORMATIONS --}}

                <div class="row">


                    {{-- NOM --}}

                    <div class="col-md-6 mb-3">

                        <label class="text-muted mb-1">

                            Nom complet

                        </label>

                        <div>

                            <i class="fas fa-user ocn-green mr-2"></i>

                            <strong>

                                {{ $user->name }}

                            </strong>

                        </div>

                    </div>



                    {{-- EMAIL --}}

                    <div class="col-md-6 mb-3">

                        <label class="text-muted mb-1">

                            Adresse e-mail

                        </label>

                        <div>

                            <i class="fas fa-envelope ocn-green mr-2"></i>

                            {{ $user->email }}

                        </div>

                    </div>



                    {{-- RÔLE --}}

                    <div class="col-md-6 mb-3">

                        <label class="text-muted mb-1">

                            Rôle

                        </label>

                        <div>

                            <i class="fas fa-user-tag ocn-green mr-2"></i>


                            @switch($user->role)

                                @case('admin')

                                    <span class="badge badge-success">

                                        Administrateur

                                    </span>

                                    @break


                                @case('directeur_exploitation')

                                    <span class="badge badge-primary">

                                        Directeur d'exploitation

                                    </span>

                                    @break


                                @case('chef_parc')

                                    <span class="badge badge-warning">

                                        Chef de parc

                                    </span>

                                    @break


                                @case('chef_agence')

                                    <span class="badge badge-info">

                                        Chef d'agence

                                    </span>

                                    @break


                                @case('chauffeur')

                                    <span class="badge badge-secondary">

                                        Chauffeur

                                    </span>

                                    @break

                            @endswitch

                        </div>

                    </div>



                    {{-- AGENCE --}}

                    <div class="col-md-6 mb-3">

                        <label class="text-muted mb-1">

                            Agence

                        </label>

                        <div>

                            <i class="fas fa-building ocn-green mr-2"></i>


                            @if($user->agence)

                                <strong>

                                    {{ $user->agence->nom }}

                                </strong>

                                <small class="text-muted">

                                    —
                                    {{ $user->agence->ville }}

                                </small>

                            @else

                                <span class="text-muted">

                                    Aucune agence

                                </span>

                            @endif

                        </div>

                    </div>



                    {{-- DATE DE CRÉATION --}}

                    <div class="col-md-6 mb-3">

                        <label class="text-muted mb-1">

                            Compte créé le

                        </label>

                        <div>

                            <i class="fas fa-calendar-alt ocn-green mr-2"></i>

                            {{ $user->created_at?->format('d/m/Y à H:i') }}

                        </div>

                    </div>



                    {{-- DERNIÈRE MODIFICATION --}}

                    <div class="col-md-6 mb-3">

                        <label class="text-muted mb-1">

                            Dernière modification

                        </label>

                        <div>

                            <i class="fas fa-clock ocn-green mr-2"></i>

                            {{ $user->updated_at?->format('d/m/Y à H:i') }}

                        </div>

                    </div>

                </div>



                <small class="text-muted">

                    <i class="fas fa-info-circle mr-1"></i>

                    Les informations affichées correspondent au compte utilisateur.

                </small>

            </div>



            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            <div class="modal-footer ocn-modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>

                    Fermer

                </button>


                <button type="button"
                        class="btn btn-warning"
                        data-dismiss="modal"
                        data-toggle="modal"
                        data-target="#modalEditUser{{ $user->id }}">

                    <i class="fas fa-edit mr-1"></i>

                    Modifier

                </button>

            </div>


        </div>

    </div>

</div>

@endforeach
