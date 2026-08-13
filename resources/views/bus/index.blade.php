@extends('layouts.admin')

@section('title', 'Bus')

@section('page_title', 'Gestion du parc automobile')

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

            <i class="fas fa-bus mr-2"></i>

            Liste des bus

        </h3>


        <div class="card-tools">

            <button type="button"
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#modalCreateBus">

                <i class="fas fa-plus mr-1"></i>

                Nouveau bus

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

                        <th>N° Bus</th>

                        <th>Immatriculation</th>

                        <th>Marque / Modèle</th>

                        <th>Capacité</th>

                        <th>État</th>

                        <th>Statut</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($bus as $bu)

                        <tr>

                            <td>
                                {{ $bu->id }}
                            </td>


                            <td>

                                <strong>
                                    {{ $bu->numero }}
                                </strong>

                            </td>


                            <td>
                                {{ $bu->immatriculation }}
                            </td>


                            <td>

                                {{ $bu->marque ?? '-' }}

                                @if($bu->modele)
                                    <br>
                                    <small class="text-muted">
                                        {{ $bu->modele }}
                                    </small>
                                @endif

                            </td>


                            <td>

                                <span class="badge badge-primary">

                                    {{ $bu->capacite }} places

                                </span>

                            </td>


                            {{-- ÉTAT --}}
                            <td>

                                @switch($bu->etat)

                                    @case('bon')

                                        <span class="badge badge-success">
                                            Bon
                                        </span>

                                        @break


                                    @case('moyen')

                                        <span class="badge badge-warning">
                                            Moyen
                                        </span>

                                        @break


                                    @case('mauvais')

                                        <span class="badge badge-danger">
                                            Mauvais
                                        </span>

                                        @break

                                @endswitch

                            </td>


                            {{-- STATUT --}}
                            <td>

                                @switch($bu->statut)

                                    @case('disponible')

                                        <span class="badge badge-success">
                                            Disponible
                                        </span>

                                        @break


                                    @case('en_voyage')

                                        <span class="badge badge-primary">
                                            En voyage
                                        </span>

                                        @break


                                    @case('en_maintenance')

                                        <span class="badge badge-warning">
                                            Maintenance
                                        </span>

                                        @break


                                    @case('en_panne')

                                        <span class="badge badge-danger">
                                            En panne
                                        </span>

                                        @break


                                    @case('hors_service')

                                        <span class="badge badge-dark">
                                            Hors service
                                        </span>

                                        @break

                                @endswitch

                            </td>


                            {{-- ACTIONS --}}
                            <td class="text-center">


                                {{-- VOIR --}}
                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalShowBus{{ $bu->id }}"
                                        title="Voir">

                                    <i class="fas fa-eye"></i>

                                </button>


                                {{-- MODIFIER --}}
                                <button type="button"
                                        class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalEditBus{{ $bu->id }}"
                                        title="Modifier">

                                    <i class="fas fa-edit"></i>

                                </button>


                                {{-- SUPPRIMER --}}
                                <form action="{{ route('bus.destroy', $bu) }}"
                                      method="POST"
                                      class="d-inline delete-bus-form">

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
                             id="modalShowBus{{ $bu->id }}"
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

                                            <i class="fas fa-bus mr-2"></i>

                                            Détails du bus

                                        </h5>


                                        <button type="button"
                                                class="close text-white"
                                                data-dismiss="modal">

                                            <span>&times;</span>

                                        </button>

                                    </div>


                                    <div class="modal-body">


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">
                                                N° Bus :
                                            </div>

                                            <div class="col-md-7">
                                                {{ $bu->numero }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">
                                                Immatriculation :
                                            </div>

                                            <div class="col-md-7">
                                                {{ $bu->immatriculation }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">
                                                Marque :
                                            </div>

                                            <div class="col-md-7">
                                                {{ $bu->marque ?? 'Non renseignée' }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">
                                                Modèle :
                                            </div>

                                            <div class="col-md-7">
                                                {{ $bu->modele ?? 'Non renseigné' }}
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">
                                                Capacité :
                                            </div>

                                            <div class="col-md-7">
                                                {{ $bu->capacite }} places
                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">
                                                État :
                                            </div>

                                            <div class="col-md-7">

                                                @switch($bu->etat)

                                                    @case('bon')
                                                        <span class="badge badge-success">
                                                            Bon
                                                        </span>
                                                        @break

                                                    @case('moyen')
                                                        <span class="badge badge-warning">
                                                            Moyen
                                                        </span>
                                                        @break

                                                    @case('mauvais')
                                                        <span class="badge badge-danger">
                                                            Mauvais
                                                        </span>
                                                        @break

                                                @endswitch

                                            </div>

                                        </div>


                                        <div class="row mb-3">

                                            <div class="col-md-5 font-weight-bold">
                                                Statut :
                                            </div>

                                            <div class="col-md-7">

                                                @switch($bu->statut)

                                                    @case('disponible')
                                                        <span class="badge badge-success">
                                                            Disponible
                                                        </span>
                                                        @break

                                                    @case('en_voyage')
                                                        <span class="badge badge-primary">
                                                            En voyage
                                                        </span>
                                                        @break

                                                    @case('en_maintenance')
                                                        <span class="badge badge-warning">
                                                            Maintenance
                                                        </span>
                                                        @break

                                                    @case('en_panne')
                                                        <span class="badge badge-danger">
                                                            En panne
                                                        </span>
                                                        @break

                                                    @case('hors_service')
                                                        <span class="badge badge-dark">
                                                            Hors service
                                                        </span>
                                                        @break

                                                @endswitch

                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-md-5 font-weight-bold">
                                                Observation :
                                            </div>

                                            <div class="col-md-7">

                                                {{ $bu->observation ?? 'Aucune observation' }}

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
                             id="modalEditBus{{ $bu->id }}"
                             data-backdrop="static"
                             data-keyboard="false"
                             tabindex="-1"
                             role="dialog"
                             aria-hidden="true">

                            <div class="modal-dialog modal-lg"
                                 role="document">

                                <div class="modal-content">


                                    <form action="{{ route('bus.update', $bu) }}"
                                          method="POST">

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-header bg-warning">

                                            <h5 class="modal-title">

                                                <i class="fas fa-edit mr-2"></i>

                                                Modifier le bus

                                            </h5>


                                            <button type="button"
                                                    class="close"
                                                    data-dismiss="modal">

                                                <span>&times;</span>

                                            </button>

                                        </div>


                                        <div class="modal-body">


                                            <div class="row">


                                                {{-- NUMERO --}}
                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label>
                                                            N° Bus
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
                                                    Observation
                                                </label>

                                                <textarea name="observation"
                                                          class="form-control"
                                                          rows="3">{{ $bu->observation }}</textarea>

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

                            <td colspan="8"
                                class="text-center py-4">

                                <i class="fas fa-bus fa-2x text-muted"></i>

                                <p class="mt-2 mb-0">
                                    Aucun bus enregistré.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}
    @if($bus->hasPages())

        <div class="card-footer">

            {{ $bus->links() }}

        </div>

    @endif

</div>



{{-- =========================================================
     MODAL NOUVEAU BUS
========================================================= --}}

<div class="modal fade"
     id="modalCreateBus"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-lg"
         role="document">

        <div class="modal-content">


            <form action="{{ route('bus.store') }}"
                  method="POST"
                  autocomplete="off">

                @csrf


                <div class="modal-header bg-primary">

                    <h5 class="modal-title text-white">

                        <i class="fas fa-bus mr-2"></i>

                        Nouveau bus

                    </h5>


                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body">


                    <div class="row">


                        {{-- NUMERO --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    N° Bus
                                </label>

                                <input type="text"
                                       name="numero"
                                       value="{{ old('numero') }}"
                                       class="form-control"
                                       placeholder="Ex : BUS-001"
                                       autocomplete="off"
                                       required>

                            </div>

                        </div>


                        {{-- IMMATRICULATION --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Immatriculation
                                </label>

                                <input type="text"
                                       name="immatriculation"
                                       value="{{ old('immatriculation') }}"
                                       class="form-control"
                                       placeholder="Ex : CG-1234-AB"
                                       autocomplete="off"
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
                                       value="{{ old('marque') }}"
                                       class="form-control"
                                       placeholder="Ex : Mercedes">

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
                                       value="{{ old('modele') }}"
                                       class="form-control"
                                       placeholder="Ex : Tourismo">

                            </div>

                        </div>

                    </div>


                    <div class="row">


                        {{-- CAPACITE --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Capacité
                                </label>

                                <input type="number"
                                       name="capacite"
                                       value="{{ old('capacite') }}"
                                       class="form-control"
                                       min="1"
                                       placeholder="Ex : 50"
                                       required>

                            </div>

                        </div>


                        {{-- ETAT --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    État
                                </label>

                                <select name="etat"
                                        class="form-control"
                                        required>

                                    <option value="bon"
                                        {{ old('etat', 'bon') === 'bon' ? 'selected' : '' }}>

                                        Bon

                                    </option>

                                    <option value="moyen"
                                        {{ old('etat') === 'moyen' ? 'selected' : '' }}>

                                        Moyen

                                    </option>

                                    <option value="mauvais"
                                        {{ old('etat') === 'mauvais' ? 'selected' : '' }}>

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
                                </label>

                                <select name="statut"
                                        class="form-control"
                                        required>

                                    <option value="disponible"
                                        {{ old('statut', 'disponible') === 'disponible' ? 'selected' : '' }}>

                                        Disponible

                                    </option>

                                    <option value="en_voyage">
                                        En voyage
                                    </option>

                                    <option value="en_maintenance">
                                        En maintenance
                                    </option>

                                    <option value="en_panne">
                                        En panne
                                    </option>

                                    <option value="hors_service">
                                        Hors service
                                    </option>

                                </select>

                            </div>

                        </div>

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
        document.querySelectorAll('.delete-bus-form');


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();


            Swal.fire({

                title: 'Êtes-vous sûr ?',

                text: 'Ce bus sera définitivement supprimé.',

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
