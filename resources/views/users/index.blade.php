@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('page_title', 'Gestion des utilisateurs')

@section('content')


{{-- =========================================================
     MESSAGES SERVEUR
========================================================= --}}

@if(session('success'))

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            });

        });
    </script>

@endif


@if(session('error'))

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            });

        });
    </script>

@endif


{{-- =========================================================
     ERREURS DE VALIDATION
========================================================= --}}

@if($errors->any())

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            Swal.fire({
                icon: 'error',
                title: 'Erreur de validation',
                html: `
                    <ul style="text-align:left;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'OK'
            });

        });
    </script>

@endif


{{-- =========================================================
     CARD PRINCIPALE
========================================================= --}}

<div class="card">

    {{-- HEADER --}}
    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-users-cog mr-2"></i>

            Liste des utilisateurs

        </h3>


        <div class="card-tools">

            {{-- NOUVEAU --}}
            <button type="button"
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#modalCreateUser">

                <i class="fas fa-plus mr-1"></i>

                Nouvel utilisateur

            </button>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Nom</th>

                        <th>Email</th>

                        <th>Rôle</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>
                                {{ $user->id }}
                            </td>

                            <td>
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>


                            {{-- ROLE --}}
                            <td>

                                @switch($user->role)

                                    @case('admin')

                                        <span class="badge badge-danger">
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


                                    @default

                                        <span class="badge badge-light">
                                            {{ $user->role }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- ACTIONS --}}
                            <td class="text-center">


                                {{-- VOIR --}}
                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowUser{{ $user->id }}"
                                        title="Voir">

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
                                @if($user->id !== auth()->id())

                                    <form action="{{ route('users.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline delete-user-form">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Supprimer">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>


                        {{-- =================================================
                             MODAL VOIR
                        ================================================== --}}

                        <div class="modal fade"
                             id="modalShowUser{{ $user->id }}"
                             data-backdrop="static"
                             data-keyboard="false"
                             tabindex="-1"
                             role="dialog"
                             aria-hidden="true">

                            <div class="modal-dialog modal-md"
                                 role="document">

                                <div class="modal-content">

                                    <div class="modal-header bg-info">

                                        <h5 class="modal-title text-white">

                                            <i class="fas fa-user mr-2"></i>

                                            Détails de l'utilisateur

                                        </h5>


                                        <button type="button"
                                                class="close text-white"
                                                data-dismiss="modal">

                                            <span>&times;</span>

                                        </button>

                                    </div>


                                    <div class="modal-body">

                                        <div class="row mb-3">

                                            <div class="col-md-4 font-weight-bold">
                                                Nom :
                                            </div>

                                            <div class="col-md-8">
                                                {{ $user->name }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-4 font-weight-bold">
                                                Email :
                                            </div>

                                            <div class="col-md-8">
                                                {{ $user->email }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-4 font-weight-bold">
                                                Rôle :
                                            </div>

                                            <div class="col-md-8">

                                                @switch($user->role)

                                                    @case('admin')
                                                        Administrateur
                                                        @break

                                                    @case('directeur_exploitation')
                                                        Directeur d'exploitation
                                                        @break

                                                    @case('chef_parc')
                                                        Chef de parc
                                                        @break

                                                    @case('chef_agence')
                                                        Agent d'agence
                                                        @break

                                                    @case('chauffeur')
                                                        Chauffeur
                                                        @break

                                                @endswitch

                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-md-4 font-weight-bold">
                                                Créé le :
                                            </div>

                                            <div class="col-md-8">
                                                {{ $user->created_at?->format('d/m/Y H:i') }}
                                            </div>

                                        </div>

                                    </div>


                                    <div class="modal-footer">

                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-dismiss="modal">

                                            Fermer

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             MODAL MODIFIER
                        ================================================== --}}

                        <div class="modal fade"
                             id="modalEditUser{{ $user->id }}"
                             data-backdrop="static"
                             data-keyboard="false"
                             tabindex="-1"
                             role="dialog"
                             aria-hidden="true">

                            <div class="modal-dialog modal-lg"
                                 role="document">

                                <div class="modal-content">

                                    <form action="{{ route('users.update', $user) }}"
                                          method="POST">

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-header bg-warning">

                                            <h5 class="modal-title">

                                                <i class="fas fa-user-edit mr-2"></i>

                                                Modifier l'utilisateur

                                            </h5>


                                            <button type="button"
                                                    class="close"
                                                    data-dismiss="modal">

                                                <span>&times;</span>

                                            </button>

                                        </div>


                                        <div class="modal-body">

                                            {{-- NOM --}}
                                            <div class="form-group">

                                                <label>
                                                    Nom complet
                                                </label>

                                                <input type="text"
                                                       name="name"
                                                       value="{{ $user->name }}"
                                                       class="form-control"
                                                       required>

                                            </div>


                                            {{-- EMAIL --}}
                                            <div class="form-group">

                                                <label>
                                                    Adresse e-mail
                                                </label>

                                                <input type="email"
                                                       name="email"
                                                       value="{{ $user->email }}"
                                                       class="form-control"
                                                       required>

                                            </div>


                                            {{-- ROLE --}}
                                            <div class="form-group">

                                                <label>
                                                    Rôle
                                                </label>

                                                <select name="role"
                                                        class="form-control"
                                                        required>

                                                    <option value="admin"
                                                        {{ $user->role === 'admin' ? 'selected' : '' }}>

                                                        Administrateur

                                                    </option>


                                                    <option value="directeur_exploitation"
                                                        {{ $user->role === 'directeur_exploitation' ? 'selected' : '' }}>

                                                        Directeur d'exploitation

                                                    </option>


                                                    <option value="chef_parc"
                                                        {{ $user->role === 'chef_parc' ? 'selected' : '' }}>

                                                        Chef de parc

                                                    </option>


                                                    <option value="chef_agence"
                                                        {{ $user->role === 'chef_agence' ? 'selected' : '' }}>

                                                        Chef d'agence

                                                    </option>


                                                    <option value="chauffeur"
                                                        {{ $user->role === 'chauffeur' ? 'selected' : '' }}>

                                                        Chauffeur

                                                    </option>

                                                </select>

                                            </div>


                                            <hr>


                                            <h6>

                                                <i class="fas fa-key mr-2"></i>

                                                Modifier le mot de passe

                                            </h6>


                                            <small class="text-muted">

                                                Laissez les champs vides
                                                si vous ne souhaitez pas
                                                modifier le mot de passe.

                                            </small>


                                            <div class="row mt-3">


                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label>
                                                            Nouveau mot de passe
                                                        </label>

                                                        <input type="password"
                                                               name="password"
                                                               class="form-control">

                                                    </div>

                                                </div>


                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label>
                                                            Confirmation
                                                        </label>

                                                        <input type="password"
                                                               name="password_confirmation"
                                                               class="form-control">

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-dismiss="modal">

                                                Annuler

                                            </button>


                                            <button type="submit"
                                                    class="btn btn-warning">

                                                <i class="fas fa-save mr-1"></i>

                                                Enregistrer

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>


                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center py-4">

                                <i class="fas fa-users fa-2x text-muted"></i>

                                <p class="mt-2 mb-0">
                                    Aucun utilisateur enregistré.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}
    @if($users->hasPages())

        <div class="card-footer">

            {{ $users->links() }}

        </div>

    @endif

</div>



{{-- =========================================================
     MODAL NOUVEL UTILISATEUR
========================================================= --}}

<div class="modal fade"
     id="modalCreateUser"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg"
         role="document">

        <div class="modal-content">

            <form action="{{ route('users.store') }}"
                method="POST"
                autocomplete="off">

                @csrf


                <div class="modal-header bg-primary">

                    <h5 class="modal-title text-white">

                        <i class="fas fa-user-plus mr-2"></i>

                        Nouvel utilisateur

                    </h5>


                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body">

                    {{-- NOM --}}
                    <div class="form-group">

                        <label>
                            Nom complet
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control"
                               placeholder="Ex : Jean Dupont"
                               required>

                    </div>


                    {{-- EMAIL --}}
                    <div class="form-group">

                        <label>
                            Adresse e-mail
                        </label>

                        <input type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control"
                            placeholder="Ex : jean@example.com"
                            autocomplete="off"
                            required>

                    </div>


                    {{-- ROLE --}}
                    <div class="form-group">

                        <label>
                            Rôle
                        </label>

                        <select name="role"
                                class="form-control"
                                required>

                            <option value="">
                                -- Sélectionner un rôle --
                            </option>


                            <option value="admin">
                                Administrateur
                            </option>


                            <option value="directeur_exploitation">
                                Directeur d'exploitation
                            </option>


                            <option value="chef_parc">
                                Chef de parc
                            </option>


                            <option value="chef_agence">
                                Agent d'agence
                            </option>


                            <option value="chauffeur">
                                Chauffeur
                            </option>

                        </select>

                    </div>


                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Mot de passe
                                </label>

                                <input type="password"
                                    name="password"
                                    class="form-control"
                                    autocomplete="new-password"
                                    required>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Confirmation
                                </label>

                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       required>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        Annuler

                    </button>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     SWEETALERT : SUPPRESSION
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ========================================================
       CONFIRMATION SUPPRESSION
    ======================================================== */

    const deleteForms = document.querySelectorAll('.delete-user-form');

    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();


            Swal.fire({

                title: 'Êtes-vous sûr ?',

                text: 'Cet utilisateur sera définitivement supprimé.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Oui, supprimer',

                cancelButtonText: 'Annuler',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    });


});

</script>

@endpush

@endsection
