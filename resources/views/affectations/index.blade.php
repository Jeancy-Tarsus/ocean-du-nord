@extends('layouts.admin')

@section('title', 'Affectations')

@section('page_title', 'Affectations')

@section('content')

<div class="container-fluid">

    <div class="card ocn-card shadow-sm">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="card-header bg-white">

            <div class="row align-items-center">

                <div class="col-md-7">

                    <h3 class="card-title ocn-title mb-0">

                        <i class="fas fa-exchange-alt mr-2"></i>

                        Affectations

                    </h3>

                    <small class="text-muted">

                        Historique des remplacements de bus et d'équipes
                        pendant les voyages

                    </small>

                </div>


                <div class="col-md-5 text-right">

                    <button type="button"
                            class="btn ocn-btn"
                            data-toggle="modal"
                            data-target="#modalCreateAffectation">

                        <i class="fas fa-plus mr-1"></i>

                        Nouvelle affectation

                    </button>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FILTRES
        ====================================================== --}}

        <div class="card-body border-bottom">

            <form action="{{ route('affectations.index') }}"
                  method="GET">

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group mb-md-0">

                            <label>

                                <i class="fas fa-search ocn-green mr-1"></i>

                                Recherche

                            </label>

                            <input type="text"
                                   name="search"
                                   value="{{ $search ?? '' }}"
                                   class="form-control"
                                   placeholder="Voyage, bus ou motif..."
                                   autocomplete="off">

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="form-group mb-md-0">

                            <label>

                                Type

                            </label>

                            <select name="type"
                                    class="form-control">

                                <option value="">

                                    Tous

                                </option>


                                <option value="remplacement_bus"
                                    {{ ($type ?? '') === 'remplacement_bus' ? 'selected' : '' }}>

                                    Remplacement bus

                                </option>


                                <option value="remplacement_equipe"
                                    {{ ($type ?? '') === 'remplacement_equipe' ? 'selected' : '' }}>

                                    Remplacement équipe

                                </option>


                                <option value="remplacement_bus_equipe"
                                    {{ ($type ?? '') === 'remplacement_bus_equipe' ? 'selected' : '' }}>

                                    Remplacement bus + équipe

                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="form-group mb-0">

                            <label class="d-block">

                                &nbsp;

                            </label>

                            <div class="d-flex">

                                <button type="submit"
                                        class="btn ocn-btn mr-2">

                                    <i class="fas fa-search mr-1"></i>

                                    Filtrer

                                </button>


                                <a href="{{ route('affectations.index') }}"
                                   class="btn btn-secondary"
                                   title="Réinitialiser">

                                    <i class="fas fa-redo"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>


        {{-- =====================================================
             TABLEAU
        ====================================================== --}}

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table ocn-table mb-0">

                    <thead class="ocn-table-header">

                        <tr>

                            <th>
                                Voyage
                            </th>

                            <th>
                                Bus
                            </th>

                            <th>
                                Équipe
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Motif
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Effectué par
                            </th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($affectations as $affectation)

                            <tr>

                                {{-- =================================================
                                     VOYAGE
                                ================================================== --}}

                                <td>

                                    @if($affectation->voyage)

                                        <strong class="ocn-green">

                                            {{ $affectation->voyage->code }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ $affectation->voyage->ligne->nom ?? 'Ligne inconnue' }}

                                        </small>

                                    @else

                                        <span class="text-muted">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                     BUS
                                ================================================== --}}

                                <td>

                                    @if($affectation->ancienBus)

                                        <span class="text-muted">

                                            {{ $affectation->ancienBus->numero }}

                                        </span>

                                    @else

                                        —

                                    @endif


                                    <i class="fas fa-arrow-right mx-1 ocn-green"></i>


                                    @if($affectation->nouveauBus)

                                        <strong>

                                            {{ $affectation->nouveauBus->numero }}

                                        </strong>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =================================================
                                     ÉQUIPE
                                ================================================== --}}

                                <td>

                                    @if($affectation->ancienneEquipe)

                                        {{ $affectation->ancienneEquipe->nom }}

                                    @else

                                        —

                                    @endif


                                    <i class="fas fa-arrow-right mx-1 ocn-green"></i>


                                    @if($affectation->nouvelleEquipe)

                                        <strong>

                                            {{ $affectation->nouvelleEquipe->nom }}

                                        </strong>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =================================================
                                     TYPE
                                ================================================== --}}

                                <td>

                                    @switch($affectation->type)

                                        @case('remplacement_bus')

                                            <span class="badge badge-warning">

                                                Bus

                                            </span>

                                            @break


                                        @case('remplacement_equipe')

                                            <span class="badge badge-info">

                                                Équipe

                                            </span>

                                            @break


                                        @case('remplacement_bus_equipe')

                                            <span class="badge badge-primary">

                                                Bus + équipe

                                            </span>

                                            @break


                                        @default

                                            <span class="badge badge-secondary">

                                                Autre

                                            </span>

                                    @endswitch

                                </td>


                                {{-- =================================================
                                     MOTIF
                                ================================================== --}}

                                <td>

                                    {{ $affectation->motif }}

                                </td>


                                {{-- =================================================
                                     DATE
                                ================================================== --}}

                                <td>

                                    @if($affectation->date_affectation)

                                        {{ \Carbon\Carbon::parse(
                                            $affectation->date_affectation
                                        )->format('d/m/Y') }}

                                    @else

                                        —

                                    @endif


                                    @if($affectation->heure_affectation)

                                        <br>

                                        <small class="text-muted">

                                            {{ $affectation->heure_affectation }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                     UTILISATEUR
                                ================================================== --}}

                                <td>

                                    @if($affectation->user)

                                        {{ $affectation->user->name }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =================================================
                                     ACTIONS
                                ================================================== --}}

                                <td class="text-center">

                                    <div class="d-inline-flex align-items-center">


                                        {{-- VOIR --}}

                                        <button type="button"
                                                class="btn btn-info btn-sm mr-1"
                                                data-toggle="modal"
                                                data-target="#modalShowAffectation{{ $affectation->id }}"
                                                title="Voir">

                                            <i class="fas fa-eye"></i>

                                        </button>


                                        {{-- MODIFIER --}}

                                        @if(
                                            auth()->user()->role === 'admin'
                                            ||
                                            auth()->user()->role === 'directeur_exploitation'
                                        )

                                            <button type="button"
                                                    class="btn btn-warning btn-sm mr-1"
                                                    data-toggle="modal"
                                                    data-target="#modalEditAffectation{{ $affectation->id }}"
                                                    title="Modifier">

                                                <i class="fas fa-edit"></i>

                                            </button>


                                            {{-- SUPPRIMER --}}

                                            <form action="{{ route('affectations.destroy', $affectation) }}"
                                                  method="POST"
                                                  class="d-inline delete-affectation">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Supprimer">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5">

                                    <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>

                                    <h5 class="text-muted">

                                        Aucune affectation trouvée

                                    </h5>

                                    <p class="text-muted mb-0">

                                        Les affectations enregistrées apparaîtront ici.

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

        @if($affectations->hasPages())

            <div class="card-footer bg-white">

                {{ $affectations->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     MODAL CRÉATION
========================================================= --}}

@include('affectations.modal.create')


{{-- =========================================================
     MODALS SHOW
========================================================= --}}

@foreach($affectations as $affectation)

    @include(
        'affectations.modal.show',
        ['affectation' => $affectation]
    )

@endforeach


{{-- =========================================================
     MODALS EDIT
========================================================= --}}

@foreach($affectations as $affectation)

    @if(
        auth()->user()->role === 'admin'
        ||
        auth()->user()->role === 'directeur_exploitation'
    )

        @include(
            'affectations.modal.edit',
            ['affectation' => $affectation]
        )

    @endif

@endforeach


{{-- =========================================================
     SCRIPTS
========================================================= --}}

@push('scripts')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | SUCCÈS
    |--------------------------------------------------------------------------
    */

    @if(session('success'))

        Swal.fire({

            icon: 'success',

            title: 'Succès',

            text: @json(session('success')),

            confirmButtonText: 'OK',

            confirmButtonColor: '#28a745',

            timer: 3000,

            timerProgressBar: true

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | ERREUR
    |--------------------------------------------------------------------------
    */

    @if(session('error'))

        Swal.fire({

            icon: 'error',

            title: 'Erreur',

            text: @json(session('error')),

            confirmButtonText: 'OK',

            confirmButtonColor: '#dc3545'

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | ERREURS DE VALIDATION
    |--------------------------------------------------------------------------
    */

    @if($errors->any())

        Swal.fire({

            icon: 'error',

            title: 'Erreur de validation',

            html: @json(
                implode(
                    '<br>',
                    $errors->all()
                )
            ),

            confirmButtonText: 'OK',

            confirmButtonColor: '#dc3545'

        });

    @endif


    /*
    |--------------------------------------------------------------------------
    | SUPPRESSION
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'submit',
        '.delete-affectation',
        function (e) {

            e.preventDefault();

            const form = this;


            Swal.fire({

                title: 'Supprimer cette affectation ?',

                text: 'Cette action est irréversible.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Oui, supprimer',

                cancelButtonText: 'Annuler',

                confirmButtonColor: '#dc3545',

                cancelButtonColor: '#6c757d',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PRÉREMPLISSAGE DEPUIS UN INCIDENT
    |--------------------------------------------------------------------------
    |
    | Exemple :
    |
    | /affectations?voyage_id=10&motif=Incident...
    |
    | Ici, on ne cherche PAS le voyage dans la table affectations.
    |
    | On prend voyage_id directement dans l'URL,
    | puis on sélectionne ce voyage dans le formulaire.
    |
    */

    const urlParams =
        new URLSearchParams(
            window.location.search
        );


    const voyageId =
        urlParams.get('voyage_id');


    const motif =
        urlParams.get('motif');


    /*
    |--------------------------------------------------------------------------
    | SI ON ARRIVE DEPUIS UN INCIDENT
    |--------------------------------------------------------------------------
    */

    if (voyageId) {

        /*
        | Le modal existe déjà car create.blade.php
        | a été inclus juste au-dessus.
        */

        const modal =
            $('#modalCreateAffectation');


        const select =
            $('#affectationVoyage');


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER QUE LE VOYAGE EXISTE DANS LE FORMULAIRE
        |--------------------------------------------------------------------------
        */

        if (
            modal.length
            &&
            select.length
        ) {

            const option =
                select.find(
                    'option[value="' + voyageId + '"]'
                );


            /*
            |--------------------------------------------------------------------------
            | LE VOYAGE EST PRÉSENT
            |--------------------------------------------------------------------------
            */

            if (option.length) {

                /*
                | Sélectionner le voyage.
                */

                select.val(
                    voyageId
                );


                /*
                | Déclencher le changement.
                |
                | Cela permet au formulaire de récupérer :
                | - le bus actuel
                | - l'équipe actuelle
                | - les informations du voyage
                */

                select.trigger(
                    'change'
                );


                /*
                |--------------------------------------------------------------------------
                | TYPE
                |--------------------------------------------------------------------------
                */

                const type =
                    $('#affectationType');


                if (type.length) {

                    type.val(
                        'remplacement_bus'
                    );


                    type.trigger(
                        'change'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | MOTIF
                |--------------------------------------------------------------------------
                */

                if (
                    motif
                    &&
                    $('#affectationMotif').length
                ) {

                    $('#affectationMotif')
                        .val(
                            motif
                        );

                }


                /*
                |--------------------------------------------------------------------------
                | OUVRIR LE FORMULAIRE
                |--------------------------------------------------------------------------
                */

                setTimeout(
                    function () {

                        modal.modal(
                            'show'
                        );

                    },
                    200
                );

            }

            /*
            |--------------------------------------------------------------------------
            | VOYAGE ABSENT DE LA LISTE
            |--------------------------------------------------------------------------
            */

            else {

                Swal.fire({

                    icon: 'error',

                    title: 'Voyage introuvable',

                    text:
                        'Le voyage sélectionné par l’incident n’est pas disponible dans le formulaire d’affectation.',

                    confirmButtonText: 'OK',

                    confirmButtonColor: '#dc3545'

                });

            }

        }

    }

});

</script>

@endpush

@endsection
