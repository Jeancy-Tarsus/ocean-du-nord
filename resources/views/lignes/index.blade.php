@extends('layouts.admin')

@section('title', 'Lignes')

@section('page_title', 'Gestion des lignes')

@section('content')

{{-- =========================================================
     MESSAGES
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

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-route mr-2"></i>

            Liste des lignes

        </h3>


        <div class="card-tools">

            <form action="{{ route('lignes.index') }}"
                  method="GET"
                  class="d-flex align-items-center">


                {{-- RECHERCHE --}}
                <div class="input-group input-group-sm mr-2"
                     style="width: 300px;">

                    <input type="text"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control"
                           placeholder="Rechercher une ligne..."
                           autocomplete="off">

                    <div class="input-group-append">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-search"></i>

                        </button>

                    </div>

                </div>


                {{-- RESET --}}
                @if(!empty($search))

                    <a href="{{ route('lignes.index') }}"
                       class="btn btn-secondary btn-sm mr-2"
                       title="Réinitialiser">

                        <i class="fas fa-times"></i>

                    </a>

                @endif


                {{-- NOUVELLE LIGNE --}}
                <button type="button"
                        class="btn btn-primary btn-sm"
                        data-toggle="modal"
                        data-target="#modalCreateLigne">

                    <i class="fas fa-plus mr-1"></i>

                    Nouvelle ligne

                </button>

            </form>

        </div>

    </div>



    {{-- =====================================================
         RESULTATS RECHERCHE
    ====================================================== --}}

    @if(!empty($search))

        <div class="alert alert-info mx-3 mt-3 mb-0">

            <i class="fas fa-search mr-2"></i>

            Résultats pour :

            <strong>{{ $search }}</strong>

            —

            {{ $lignes->total() }} résultat(s)

        </div>

    @endif



    {{-- =====================================================
         TABLEAU
    ====================================================== --}}

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Code</th>

                        <th>Nom de la ligne</th>

                        <th>Description</th>

                        <th>Statut</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($lignes as $ligne)

                        <tr>

                            <td>
                                {{ $ligne->id }}
                            </td>


                            <td>

                                <strong>

                                    {{ $ligne->code }}

                                </strong>

                            </td>


                            <td>

                                {{ $ligne->nom }}

                            </td>


                            <td>

                                @if($ligne->description)

                                    {{ Str::limit($ligne->description, 60) }}

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- STATUT --}}
                            <td>

                                @if($ligne->active)

                                    <span class="badge badge-success">

                                        <i class="fas fa-check mr-1"></i>

                                        Active

                                    </span>

                                @else

                                    <span class="badge badge-secondary">

                                        <i class="fas fa-times mr-1"></i>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td class="text-center">


                                {{-- VOIR --}}
                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowLigne{{ $ligne->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                {{-- MODIFIER --}}
                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditLigne{{ $ligne->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>


                                {{-- SUPPRIMER --}}
                                <form action="{{ route('lignes.destroy', $ligne) }}"
                                      method="POST"
                                      class="d-inline delete-ligne-form">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Supprimer">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>



                        {{-- =================================================
                             MODAL VOIR
                        ================================================== --}}

                        <div class="modal fade"
                             id="modalShowLigne{{ $ligne->id }}"
                             data-backdrop="static"
                             data-keyboard="false"
                             tabindex="-1"
                             role="dialog"
                             aria-hidden="true">

                            <div class="modal-dialog modal-md modal-dialog-centered"
                                 role="document">

                                <div class="modal-content shadow-lg border-0">


                                    <div class="modal-header bg-info">

                                        <div>

                                            <h5 class="modal-title text-white">

                                                <i class="fas fa-route mr-2"></i>

                                                Détails de la ligne

                                            </h5>

                                            <small class="text-white-50">

                                                Informations du parcours

                                            </small>

                                        </div>


                                        <button type="button"
                                                class="close text-white"
                                                data-dismiss="modal">

                                            <span>&times;</span>

                                        </button>

                                    </div>



                                    <div class="modal-body p-4">


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">

                                                Code :

                                            </div>

                                            <div class="col-md-7">

                                                <span class="badge badge-primary">

                                                    {{ $ligne->code }}

                                                </span>

                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">

                                                Nom :

                                            </div>

                                            <div class="col-md-7">

                                                {{ $ligne->nom }}

                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">

                                                Statut :

                                            </div>

                                            <div class="col-md-7">

                                                @if($ligne->active)

                                                    <span class="badge badge-success">

                                                        Active

                                                    </span>

                                                @else

                                                    <span class="badge badge-secondary">

                                                        Inactive

                                                    </span>

                                                @endif

                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-md-5 font-weight-bold">

                                                Description :

                                            </div>

                                            <div class="col-md-7">

                                                {{ $ligne->description ?? 'Aucune description' }}

                                            </div>

                                        </div>

                                    </div>



                                    <div class="modal-footer bg-light">

                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-dismiss="modal">

                                            <i class="fas fa-times mr-1"></i>

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
                             id="modalEditLigne{{ $ligne->id }}"
                             data-backdrop="static"
                             data-keyboard="false"
                             tabindex="-1"
                             role="dialog"
                             aria-hidden="true">

                            <div class="modal-dialog modal-lg modal-dialog-centered"
                                 role="document">

                                <div class="modal-content shadow-lg border-0">


                                    <form action="{{ route('lignes.update', $ligne) }}"
                                          method="POST"
                                          autocomplete="off">

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-header bg-warning">

                                            <div>

                                                <h5 class="modal-title">

                                                    <i class="fas fa-edit mr-2"></i>

                                                    Modifier la ligne

                                                </h5>

                                                <small class="text-dark">

                                                    Modifier les informations de la ligne

                                                </small>

                                            </div>


                                            <button type="button"
                                                    class="close"
                                                    data-dismiss="modal">

                                                <span>&times;</span>

                                            </button>

                                        </div>



                                        <div class="modal-body p-4">


                                            <div class="mb-4">

                                                <h6 class="text-warning font-weight-bold border-bottom pb-2">

                                                    <i class="fas fa-route mr-2"></i>

                                                    Informations de la ligne

                                                </h6>


                                                <div class="row mt-3">


                                                    {{-- CODE --}}
                                                    <div class="col-md-6">

                                                        <div class="form-group">

                                                            <label>

                                                                Code
                                                                <span class="text-danger">*</span>

                                                            </label>

                                                            <div class="input-group">

                                                                <div class="input-group-prepend">

                                                                    <span class="input-group-text">

                                                                        <i class="fas fa-hashtag"></i>

                                                                    </span>

                                                                </div>

                                                                <input type="text"
                                                                       name="code"
                                                                       value="{{ $ligne->code }}"
                                                                       class="form-control"
                                                                       required>

                                                            </div>

                                                        </div>

                                                    </div>


                                                    {{-- NOM --}}
                                                    <div class="col-md-6">

                                                        <div class="form-group">

                                                            <label>

                                                                Nom de la ligne
                                                                <span class="text-danger">*</span>

                                                            </label>

                                                            <div class="input-group">

                                                                <div class="input-group-prepend">

                                                                    <span class="input-group-text">

                                                                        <i class="fas fa-route"></i>

                                                                    </span>

                                                                </div>

                                                                <input type="text"
                                                                       name="nom"
                                                                       value="{{ $ligne->nom }}"
                                                                       class="form-control"
                                                                       required>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                {{-- DESCRIPTION --}}
                                                <div class="form-group">

                                                    <label>

                                                        Description

                                                    </label>

                                                    <textarea name="description"
                                                              class="form-control"
                                                              rows="3"
                                                              placeholder="Décrire le parcours...">{{ $ligne->description }}</textarea>

                                                </div>


                                                {{-- ACTIVE --}}
                                                <div class="form-group">

                                                    <label>

                                                        Statut

                                                    </label>

                                                    <select name="active"
                                                            class="form-control">

                                                        <option value="1"
                                                            {{ $ligne->active ? 'selected' : '' }}>

                                                            Active

                                                        </option>

                                                        <option value="0"
                                                            {{ !$ligne->active ? 'selected' : '' }}>

                                                            Inactive

                                                        </option>

                                                    </select>

                                                </div>

                                            </div>

                                        </div>



                                        <div class="modal-footer bg-light">

                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-dismiss="modal">

                                                <i class="fas fa-times mr-1"></i>

                                                Annuler

                                            </button>


                                            <button type="submit"
                                                    class="btn btn-warning px-4">

                                                <i class="fas fa-save mr-1"></i>

                                                Enregistrer les modifications

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-4">

                                <i class="fas fa-route fa-2x text-muted"></i>

                                <p class="mt-2 mb-0">

                                    @if(!empty($search))

                                        Aucune ligne trouvée pour

                                        <strong>
                                            {{ $search }}
                                        </strong>.

                                    @else

                                        Aucune ligne enregistrée.

                                    @endif

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- PAGINATION --}}
    @if($lignes->hasPages())

        <div class="card-footer">

            {{ $lignes->links() }}

        </div>

    @endif

</div>



{{-- =========================================================
     MODAL NOUVELLE LIGNE
========================================================= --}}

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


                <div class="modal-header bg-primary">

                    <div>

                        <h5 class="modal-title text-white">

                            <i class="fas fa-route mr-2"></i>

                            Nouvelle ligne

                        </h5>

                        <small class="text-white-50">

                            Ajouter une nouvelle ligne de transport

                        </small>

                    </div>


                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>



                <div class="modal-body p-4">


                    <div class="mb-4">

                        <h6 class="text-primary font-weight-bold border-bottom pb-2">

                            <i class="fas fa-route mr-2"></i>

                            Informations de la ligne

                        </h6>


                        <div class="row mt-3">


                            {{-- CODE --}}
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Code
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">

                                                <i class="fas fa-hashtag"></i>

                                            </span>

                                        </div>

                                        <input type="text"
                                               name="code"
                                               value="{{ old('code') }}"
                                               class="form-control"
                                               placeholder="Ex : LIG-001"
                                               autocomplete="off"
                                               required>

                                    </div>

                                </div>

                            </div>


                            {{-- NOM --}}
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>

                                        Nom de la ligne
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">

                                                <i class="fas fa-route"></i>

                                            </span>

                                        </div>

                                        <input type="text"
                                               name="nom"
                                               value="{{ old('nom') }}"
                                               class="form-control"
                                               placeholder="Ex : Brazzaville → Pointe-Noire"
                                               autocomplete="off"
                                               required>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="form-group">

                            <label>

                                Description

                            </label>

                            <textarea name="description"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Décrire le parcours de la ligne...">{{ old('description') }}</textarea>

                        </div>


                        {{-- ACTIVE --}}
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


                        <div class="mt-3">

                            <small class="text-muted">

                                <span class="text-danger">*</span>

                                Champs obligatoires

                            </small>

                        </div>

                    </div>

                </div>



                <div class="modal-footer bg-light">

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                        <i class="fas fa-times mr-1"></i>

                        Annuler

                    </button>


                    <button type="submit"
                            class="btn btn-primary px-4">

                        <i class="fas fa-save mr-1"></i>

                        Enregistrer la ligne

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =========================================================
     SWEETALERT SUPPRESSION
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteForms =
        document.querySelectorAll('.delete-ligne-form');


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();


            Swal.fire({

                title: 'Êtes-vous sûr ?',

                text: 'Cette ligne sera définitivement supprimée.',

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
