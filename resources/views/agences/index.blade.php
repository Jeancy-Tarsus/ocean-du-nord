@extends('layouts.admin')

@section('title', 'Agences')

@section('page_title', 'Gestion des agences')

@section('content')

{{-- =========================================================
     MESSAGES DE SUCCÈS / ERREUR
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

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-building mr-2"></i>

            Liste des agences

        </h3>


        <div class="card-tools">

            {{-- BOUTON NOUVELLE AGENCE --}}
            <button type="button"
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#modalCreateAgence">

                <i class="fas fa-plus mr-1"></i>

                Nouvelle agence

            </button>

        </div>

    </div>


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

                        <th>Agence</th>

                        <th>Ville</th>

                        <th>Téléphone</th>

                        <th>Statut</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($agences as $agence)

                        <tr>

                            <td>
                                {{ $agence->id }}
                            </td>

                            <td>

                                <span class="font-weight-bold">
                                    {{ $agence->code }}
                                </span>

                            </td>

                            <td>
                                {{ $agence->nom }}
                            </td>

                            <td>
                                {{ $agence->ville }}
                            </td>

                            <td>
                                {{ $agence->telephone ?? '-' }}
                            </td>


                            {{-- STATUT --}}
                            <td>

                                @if($agence->active)

                                    <span class="badge badge-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge badge-secondary">
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
                                        data-target="#modalShowAgence{{ $agence->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                {{-- MODIFIER --}}
                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditAgence{{ $agence->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>


                                {{-- SUPPRIMER --}}
                                <form action="{{ route('agences.destroy', $agence) }}"
                                      method="POST"
                                      class="d-inline delete-agence-form">

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
                             id="modalShowAgence{{ $agence->id }}"
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

                                            <i class="fas fa-building mr-2"></i>

                                            Détails de l'agence

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
                                                Code :
                                            </div>

                                            <div class="col-md-8">
                                                {{ $agence->code }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-4 font-weight-bold">
                                                Nom :
                                            </div>

                                            <div class="col-md-8">
                                                {{ $agence->nom }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-4 font-weight-bold">
                                                Ville :
                                            </div>

                                            <div class="col-md-8">
                                                {{ $agence->ville }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-4 font-weight-bold">
                                                Adresse :
                                            </div>

                                            <div class="col-md-8">

                                                {{ $agence->adresse ?? 'Non renseignée' }}

                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-4 font-weight-bold">
                                                Téléphone :
                                            </div>

                                            <div class="col-md-8">

                                                {{ $agence->telephone ?? 'Non renseigné' }}

                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-md-4 font-weight-bold">
                                                Statut :
                                            </div>

                                            <div class="col-md-8">

                                                @if($agence->active)

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
                             id="modalEditAgence{{ $agence->id }}"
                             data-backdrop="static"
                             data-keyboard="false"
                             tabindex="-1"
                             role="dialog"
                             aria-hidden="true">

                            <div class="modal-dialog modal-lg"
                                 role="document">

                                <div class="modal-content">


                                    <form action="{{ route('agences.update', $agence) }}"
                                          method="POST">

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-header bg-warning">

                                            <h5 class="modal-title">

                                                <i class="fas fa-edit mr-2"></i>

                                                Modifier l'agence

                                            </h5>


                                            <button type="button"
                                                    class="close"
                                                    data-dismiss="modal">

                                                <span>&times;</span>

                                            </button>

                                        </div>


                                        <div class="modal-body">


                                            {{-- CODE --}}
                                            <div class="form-group">

                                                <label>
                                                    Code de l'agence
                                                </label>

                                                <input type="text"
                                                       name="code"
                                                       value="{{ $agence->code }}"
                                                       class="form-control"
                                                       required>

                                            </div>


                                            {{-- NOM --}}
                                            <div class="form-group">

                                                <label>
                                                    Nom de l'agence
                                                </label>

                                                <input type="text"
                                                       name="nom"
                                                       value="{{ $agence->nom }}"
                                                       class="form-control"
                                                       required>

                                            </div>


                                            {{-- VILLE --}}
                                            <div class="form-group">

                                                <label>
                                                    Ville
                                                </label>

                                                <input type="text"
                                                       name="ville"
                                                       value="{{ $agence->ville }}"
                                                       class="form-control"
                                                       required>

                                            </div>


                                            {{-- ADRESSE --}}
                                            <div class="form-group">

                                                <label>
                                                    Adresse
                                                </label>

                                                <input type="text"
                                                       name="adresse"
                                                       value="{{ $agence->adresse }}"
                                                       class="form-control">

                                            </div>


                                            {{-- TELEPHONE --}}
                                            <div class="form-group">

                                                <label>
                                                    Téléphone
                                                </label>

                                                <input type="text"
                                                       name="telephone"
                                                       value="{{ $agence->telephone }}"
                                                       class="form-control">

                                            </div>


                                            {{-- STATUT --}}
                                            <div class="form-group">

                                                <div class="custom-control custom-switch">

                                                    <input type="checkbox"
                                                           class="custom-control-input"
                                                           id="activeEdit{{ $agence->id }}"
                                                           name="active"
                                                           value="1"
                                                           {{ $agence->active ? 'checked' : '' }}>

                                                    <label class="custom-control-label"
                                                           for="activeEdit{{ $agence->id }}">

                                                        Agence active

                                                    </label>

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

                            <td colspan="7"
                                class="text-center py-4">

                                <i class="fas fa-building fa-2x text-muted"></i>

                                <p class="mt-2 mb-0">

                                    Aucune agence enregistrée.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}
    @if($agences->hasPages())

        <div class="card-footer">

            {{ $agences->links() }}

        </div>

    @endif

</div>



{{-- =========================================================
     MODAL NOUVELLE AGENCE
========================================================= --}}

<div class="modal fade"
     id="modalCreateAgence"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg"
         role="document">

        <div class="modal-content">


            <form action="{{ route('agences.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf


                <div class="modal-header bg-primary">

                    <h5 class="modal-title text-white">

                        <i class="fas fa-building mr-2"></i>

                        Nouvelle agence

                    </h5>


                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body">


                    {{-- CODE --}}
                    <div class="form-group">

                        <label>
                            Code de l'agence
                        </label>

                        <input type="text"
                               name="code"
                               value="{{ old('code') }}"
                               class="form-control"
                               placeholder="Ex : AG001"
                               autocomplete="off"
                               required>

                    </div>


                    {{-- NOM --}}
                    <div class="form-group">

                        <label>
                            Nom de l'agence
                        </label>

                        <input type="text"
                               name="nom"
                               value="{{ old('nom') }}"
                               class="form-control"
                               placeholder="Ex : Agence Mazala"
                               required>

                    </div>


                    {{-- VILLE --}}
                    <div class="form-group">

                        <label>
                            Ville
                        </label>

                        <input type="text"
                               name="ville"
                               value="{{ old('ville') }}"
                               class="form-control"
                               placeholder="Ex : Brazzaville"
                               required>

                    </div>


                    {{-- ADRESSE --}}
                    <div class="form-group">

                        <label>
                            Adresse
                        </label>

                        <input type="text"
                               name="adresse"
                               value="{{ old('adresse') }}"
                               class="form-control"
                               placeholder="Adresse de l'agence">

                    </div>


                    {{-- TELEPHONE --}}
                    <div class="form-group">

                        <label>
                            Téléphone
                        </label>

                        <input type="text"
                               name="telephone"
                               value="{{ old('telephone') }}"
                               class="form-control"
                               placeholder="Ex : 06 xxx xx xx"
                               autocomplete="off">

                    </div>


                    {{-- STATUT --}}
                    <div class="form-group">

                        <div class="custom-control custom-switch">

                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="activeCreateAgence"
                                   name="active"
                                   value="1"
                                   checked>

                            <label class="custom-control-label"
                                   for="activeCreateAgence">

                                Agence active

                            </label>

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
     SWEETALERT SUPPRESSION
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {


    const deleteForms =
        document.querySelectorAll('.delete-agence-form');


    deleteForms.forEach(function (form) {


        form.addEventListener('submit', function (event) {

            event.preventDefault();


            Swal.fire({

                title: 'Êtes-vous sûr ?',

                text: 'Cette agence sera définitivement supprimée.',

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
