@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('page_title', 'Gestion des utilisateurs')

@section('content')


{{-- =========================================================
     MESSAGES POUR SWEETALERT
========================================================= --}}

@if(session('success'))

    <div id="ocn-success-message"
         data-message="{{ session('success') }}">
    </div>

@endif


@if(session('error'))

    <div id="ocn-error-message"
         data-message="{{ session('error') }}">
    </div>

@endif


@if($errors->any())

    <div id="ocn-validation-errors"
         data-errors='@json($errors->all())'>
    </div>

@endif



{{-- =========================================================
     CARD PRINCIPALE
========================================================= --}}

<div class="card ocn-card shadow-sm">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="card-header bg-white">

        <div class="row align-items-center">


            {{-- TITRE --}}

            <div class="col-md-4">

                <h3 class="card-title ocn-title mb-0">

                    <i class="fas fa-users mr-2"></i>

                    Liste des utilisateurs

                </h3>

            </div>



            {{-- RECHERCHE --}}

            <div class="col-md-5">

                <form action="{{ route('users.index') }}"
                      method="GET">

                    <div class="input-group">

                        <input type="text"
                               name="search"
                               value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Rechercher par nom ou e-mail..."
                               autocomplete="off">


                        <div class="input-group-append">

                            @if(!empty($search))

                                <a href="{{ route('users.index') }}"
                                   class="btn btn-secondary"
                                   title="Réinitialiser">

                                    <i class="fas fa-times"></i>

                                </a>

                            @endif


                            <button type="submit"
                                    class="btn ocn-btn">

                                <i class="fas fa-search mr-1"></i>

                                Rechercher

                            </button>

                        </div>

                    </div>

                </form>

            </div>



            {{-- NOUVEL UTILISATEUR --}}

            <div class="col-md-3 text-right">

                <button type="button"
                        class="btn ocn-btn"
                        data-toggle="modal"
                        data-target="#modalCreateUser">

                    <i class="fas fa-user-plus mr-1"></i>

                    Nouvel utilisateur

                </button>

            </div>

        </div>

    </div>



    {{-- =====================================================
         TABLEAU
    ====================================================== --}}

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">


                {{-- EN-TÊTE --}}

                <thead class="ocn-table-header">

                    <tr>

                        <th>#</th>

                        <th>Utilisateur</th>

                        <th>E-mail</th>

                        <th>Rôle</th>

                        <th>Agence</th>

                        <th class="text-center">

                            Actions

                        </th>

                    </tr>

                </thead>



                {{-- CORPS --}}

                <tbody>

                    @forelse($users as $user)

                        <tr>


                            {{-- ID --}}

                            <td>

                                {{ $user->id }}

                            </td>



                            {{-- NOM --}}

                            <td>

                                <strong>

                                    {{ $user->name }}

                                </strong>

                            </td>



                            {{-- EMAIL --}}

                            <td>

                                {{ $user->email }}

                            </td>



                            {{-- RÔLE --}}

                            <td>

                                @switch($user->role)

                                    @case('admin')

                                        <span class="badge badge-success">

                                            <i class="fas fa-user-shield mr-1"></i>

                                            Administrateur

                                        </span>

                                        @break


                                    @case('directeur_exploitation')

                                        <span class="badge badge-primary">

                                            <i class="fas fa-user-tie mr-1"></i>

                                            Directeur d'exploitation

                                        </span>

                                        @break


                                    @case('chef_parc')

                                        <span class="badge badge-warning">

                                            <i class="fas fa-warehouse mr-1"></i>

                                            Chef de parc

                                        </span>

                                        @break


                                    @case('chef_agence')

                                        <span class="badge badge-info">

                                            <i class="fas fa-building mr-1"></i>

                                            Chef d'agence

                                        </span>

                                        @break


                                    @case('chauffeur')

                                        <span class="badge badge-secondary">

                                            <i class="fas fa-id-card mr-1"></i>

                                            Chauffeur

                                        </span>

                                        @break

                                @endswitch

                            </td>



                            {{-- AGENCE --}}

                            <td>

                                @if($user->agence)

                                    <strong>

                                        {{ $user->agence->nom }}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ $user->agence->ville }}

                                    </small>

                                @else

                                    <span class="text-muted">

                                        -

                                    </span>

                                @endif

                            </td>



                            {{-- ACTIONS --}}

                            <td class="text-center">

                                {{-- VOIR --}}
                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowUser{{ $user->id }}"
                                        title="Voir les détails">

                                    <i class="fas fa-eye"></i>

                                </button>

                                {{-- MODIFIER --}}

                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditUser{{ $user->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>



                                {{-- SUPPRIMER --}}

                                @if($user->id !== Auth::id())

                                    <form action="{{ route('users.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline delete-form"
                                          data-delete-message="Cet utilisateur sera définitivement supprimé.">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Supprimer">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                @else

                                    <button type="button"
                                            class="btn btn-secondary btn-sm"
                                            disabled
                                            title="Votre compte">

                                        <i class="fas fa-user-check"></i>

                                    </button>

                                @endif

                            </td>


                        </tr>


                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5">

                                <i class="fas fa-users fa-3x text-muted mb-3"></i>

                                <p class="text-muted mb-0">

                                    @if(!empty($search))

                                        Aucun utilisateur trouvé pour :

                                        <strong>
                                            {{ $search }}
                                        </strong>

                                    @else

                                        Aucun utilisateur enregistré.

                                    @endif

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if($users->hasPages())

        <div class="card-footer bg-white">

            {{ $users->links() }}

        </div>

    @endif


</div>



{{-- =========================================================
     MODALS
========================================================= --}}

@include('users.modal.create')

@include('users.modal.edit')

@include('users.modal.show')

@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | CRÉATION
    |--------------------------------------------------------------------------
    */

    $('#createUserRole').on('change', function () {

        const role = $(this).val();

        const container =
            $('#createUserAgenceContainer');

        const agence =
            $('#createUserAgence');


        if (role === 'chef_agence') {

            container.stop(true, true).slideDown(180);

            agence.prop('required', true);

        } else {

            container.stop(true, true).slideUp(180);

            agence.prop('required', false);

            agence.val('');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | MODIFICATION
    |--------------------------------------------------------------------------
    */

    $('.edit-user-role').on('change', function () {

        const role = $(this).val();

        const userId =
            $(this).data('user');


        const container =
            $('#editUserAgenceContainer' + userId);

        const agence =
            $('#editUserAgence' + userId);


        if (role === 'chef_agence') {

            container.stop(true, true).slideDown(180);

            agence.prop('required', true);

        } else {

            container.stop(true, true).slideUp(180);

            agence.prop('required', false);

            agence.val('');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION DES MODALS DE MODIFICATION
    |--------------------------------------------------------------------------
    */

    $('.edit-user-role').each(function () {

        const role = $(this).val();

        const userId =
            $(this).data('user');


        const container =
            $('#editUserAgenceContainer' + userId);

        const agence =
            $('#editUserAgence' + userId);


        if (role === 'chef_agence') {

            container.show();

            agence.prop('required', true);

        } else {

            container.hide();

            agence.prop('required', false);

        }

    });

});

</script>

@endpush

@endsection

