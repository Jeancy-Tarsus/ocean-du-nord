<div class="modal fade"
     id="modalShowVoyage{{ $voyage->id }}"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content shadow-lg border-0">

            {{-- HEADER --}}

            <div class="modal-header ocn-modal-header">

                <div>

                    <h5 class="modal-title text-white">

                        <i class="fas fa-road mr-2"></i>

                        Détails du voyage

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


            {{-- BODY --}}

            <div class="modal-body p-4">

                {{-- IDENTIFICATION --}}

                <div class="text-center mb-4">

                    <div class="ocn-light rounded p-4">

                        <i class="fas fa-road fa-3x ocn-green mb-2"></i>

                        <h5 class="font-weight-bold mb-1">

                            {{ $voyage->code }}

                        </h5>

                        @if($voyage->ligne)

                            <span>

                                {{ $voyage->ligne->nom }}

                            </span>

                        @endif

                    </div>

                </div>


                {{-- INFORMATIONS PRINCIPALES --}}

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <strong>

                            <i class="fas fa-route mr-2 ocn-green"></i>

                            Ligne

                        </strong>

                        <div class="mt-1">

                            {{ $voyage->ligne->nom ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <strong>

                            <i class="fas fa-bus mr-2 ocn-green"></i>

                            Bus

                        </strong>

                        <div class="mt-1">

                            @if($voyage->bus)

                                {{ $voyage->bus->numero }}

                                —

                                {{ $voyage->bus->immatriculation }}

                            @else

                                -

                            @endif

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <strong>

                            <i class="fas fa-users mr-2 ocn-green"></i>

                            Équipe

                        </strong>

                        <div class="mt-1">

                            {{ $voyage->equipe->nom ?? '-' }}

                        </div>

                    </div>

                </div>


                <hr>


                {{-- HORAIRES --}}

                <h6 class="ocn-title font-weight-bold mb-3">

                    <i class="fas fa-clock mr-2"></i>

                    Horaires

                </h6>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <strong>

                            <i class="fas fa-calendar-alt mr-2 ocn-green"></i>

                            Départ

                        </strong>

                        <div class="mt-1">

                            @if($voyage->date_depart)

                                {{ $voyage->date_depart->format('d/m/Y') }}

                            @else

                                -

                            @endif

                            @if($voyage->heure_depart)

                                à {{ substr($voyage->heure_depart, 0, 5) }}

                            @endif

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <strong>

                            <i class="fas fa-flag-checkered mr-2 ocn-green"></i>

                            Arrivée prévue

                        </strong>

                        <div class="mt-1">

                            @if($voyage->date_arrivee_prevue)

                                {{ $voyage->date_arrivee_prevue->format('d/m/Y') }}

                                @if($voyage->heure_arrivee_prevue)

                                    à {{ substr($voyage->heure_arrivee_prevue, 0, 5) }}

                                @endif

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <hr>


                {{-- PARCOURS --}}

                <h6 class="ocn-title font-weight-bold mb-3">

                    <i class="fas fa-route mr-2"></i>

                    Parcours du voyage

                </h6>


                @if($voyage->voyageAgences->count())

                    <div class="table-responsive">

                        <table class="table table-bordered table-sm">

                            <thead>

                                <tr>

                                    <th>Ordre</th>

                                    <th>Agence</th>

                                    <th>Type</th>

                                    <th>Heure prévue</th>

                                    <th>Arrivée réelle</th>

                                    <th>Départ réel</th>

                                    <th>Statut</th>

                                    <th class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $voyage->voyageAgences->sortBy('ordre')
                                    as $voyageAgence
                                )

                                    <tr>

                                        {{-- ORDRE --}}

                                        <td>

                                            {{ $voyageAgence->ordre }}

                                        </td>


                                        {{-- AGENCE --}}

                                        <td>

                                            <strong>

                                                {{ $voyageAgence->agence->nom ?? '-' }}

                                            </strong>

                                        </td>


                                        {{-- TYPE --}}

                                        <td>

                                            @if($voyageAgence->type === 'depart')

                                                <span class="badge badge-info">

                                                    Départ

                                                </span>

                                            @elseif($voyageAgence->type === 'passage')

                                                <span class="badge badge-warning">

                                                    Passage

                                                </span>

                                            @elseif($voyageAgence->type === 'arrivee')

                                                <span class="badge badge-success">

                                                    Arrivée

                                                </span>

                                            @else

                                                <span class="badge badge-secondary">

                                                    {{ $voyageAgence->type }}

                                                </span>

                                            @endif

                                        </td>


                                        {{-- HEURE PREVUE --}}

                                        <td>

                                            @if($voyageAgence->heure_prevue)

                                                {{ substr(
                                                    $voyageAgence->heure_prevue,
                                                    0,
                                                    5
                                                ) }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- ARRIVEE REELLE --}}

                                        <td class="arrivee-cell">

                                            @if($voyageAgence->heure_arrivee_reelle)

                                                {{ substr(
                                                    $voyageAgence->heure_arrivee_reelle,
                                                    0,
                                                    5
                                                ) }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- DEPART REEL --}}

                                        <td class="depart-cell">

                                            @if($voyageAgence->heure_depart_reelle)

                                                {{ substr(
                                                    $voyageAgence->heure_depart_reelle,
                                                    0,
                                                    5
                                                ) }}

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- STATUT --}}

                                        <td class="statut-cell">

                                            @if($voyageAgence->statut === 'prevu')

                                                <span class="badge badge-secondary">

                                                    Prévu

                                                </span>

                                            @elseif($voyageAgence->statut === 'arrive')

                                                <span class="badge badge-success">

                                                    Arrivé

                                                </span>

                                            @elseif($voyageAgence->statut === 'reparti')

                                                <span class="badge badge-primary">

                                                    Reparti

                                                </span>

                                            @else

                                                <span class="badge badge-secondary">

                                                    {{ $voyageAgence->statut }}

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ACTION --}}

                                        <td class="text-center action-cell">

                                            @if(
                                                $voyage->statut === 'en_cours' &&
                                                $voyageAgence->statut === 'prevu'
                                            )

                                                <form action="{{ route(
                                                    'voyage-agences.arrivee',
                                                    $voyageAgence
                                                ) }}"
                                                      method="POST"
                                                      class="d-inline arrivee-form"
                                                      data-depart-url="{{ route(
                                                          'voyage-agences.depart',
                                                          $voyageAgence
                                                      ) }}"
                                                      data-token="{{ csrf_token() }}">

                                                    @csrf

                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-success btn-sm"
                                                            title="Confirmer l'arrivée">

                                                        <i class="fas fa-map-marker-alt"></i>

                                                        Arrivée

                                                    </button>

                                                </form>

                                            @elseif(
                                                $voyage->statut === 'en_cours' &&
                                                $voyageAgence->statut === 'arrive' &&
                                                $voyageAgence->type !== 'arrivee'
                                            )

                                                <form action="{{ route(
                                                    'voyage-agences.depart',
                                                    $voyageAgence
                                                ) }}"
                                                      method="POST"
                                                      class="d-inline depart-form"
                                                      data-token="{{ csrf_token() }}">

                                                    @csrf

                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-primary btn-sm"
                                                            title="Confirmer le départ">

                                                        <i class="fas fa-play"></i>

                                                        Départ

                                                    </button>

                                                </form>

                                            @else

                                                <span class="text-muted">

                                                    -

                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="alert alert-info">

                        <i class="fas fa-info-circle mr-2"></i>

                        Aucune agence associée à ce voyage.

                    </div>

                @endif


                {{-- STATUT DU VOYAGE --}}

                <div class="mt-4">

                    <strong>

                        <i class="fas fa-toggle-on mr-2 ocn-green"></i>

                        Statut du voyage

                    </strong>

                    <div class="mt-2 voyage-status">

                        @switch($voyage->statut)

                            @case('planifie')

                                <span class="badge badge-info">

                                    <i class="fas fa-calendar-check mr-1"></i>

                                    Planifié

                                </span>

                                @break

                            @case('en_cours')

                                <span class="badge badge-primary">

                                    <i class="fas fa-play mr-1"></i>

                                    En cours

                                </span>

                                @break

                            @case('termine')

                                <span class="badge badge-success">

                                    <i class="fas fa-check mr-1"></i>

                                    Terminé

                                </span>

                                @break

                            @case('annule')

                                <span class="badge badge-danger">

                                    <i class="fas fa-times mr-1"></i>

                                    Annulé

                                </span>

                                @break

                            @default

                                <span class="badge badge-secondary">

                                    {{ $voyage->statut }}

                                </span>

                        @endswitch

                    </div>

                </div>


                {{-- OBSERVATION --}}

                @if($voyage->observation)

                    <div class="mt-4">

                        <strong>

                            <i class="fas fa-comment-alt mr-2 ocn-green"></i>

                            Observation

                        </strong>

                        <div class="mt-2 p-3 bg-light rounded">

                            {{ $voyage->observation }}

                        </div>

                    </div>

                @endif

            </div>


            {{-- FOOTER --}}

            <div class="modal-footer ocn-modal-footer">

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


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById(
        'modalShowVoyage{{ $voyage->id }}'
    );

    if (!modal) {
        return;
    }


    function afficherErreur(message) {

        Swal.fire({

            icon: 'error',

            title: 'Erreur',

            text: message

        });

    }


    function initialiserDepart() {

        modal.querySelectorAll('.depart-form').forEach(function (form) {

            if (form.dataset.initialized === '1') {
                return;
            }

            form.dataset.initialized = '1';


            form.addEventListener('submit', async function (event) {

                event.preventDefault();


                const button = form.querySelector('button');

                const token = form.dataset.token;

                button.disabled = true;

                button.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i>';


                try {

                    const response = await fetch(form.action, {

                        method: 'POST',

                        headers: {

                            'X-CSRF-TOKEN': token,

                            'Accept': 'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'

                        },

                        body: new FormData(form)

                    });


                    const data = await response.json();


                    if (!response.ok || !data.success) {

                        throw new Error(
                            data.message ||
                            'Une erreur est survenue.'
                        );

                    }


                    const row = form.closest('tr');


                    row.querySelector(
                        '.statut-cell'
                    ).innerHTML = `

                        <span class="badge badge-primary">

                            Reparti

                        </span>

                    `;


                    row.querySelector(
                        '.depart-cell'
                    ).innerHTML = data.heure_depart;


                    form.outerHTML = `

                        <span class="text-muted">

                            -

                        </span>

                    `;


                    Swal.fire({

                        icon: 'success',

                        title: 'Départ enregistré',

                        text: data.message,

                        timer: 900,

                        showConfirmButton: false

                    });


                    activerProchaineArrivee(row);

                } catch (error) {

                    button.disabled = false;

                    button.innerHTML = `

                        <i class="fas fa-play"></i>

                        Départ

                    `;


                    afficherErreur(error.message);

                }

            });

        });

    }


    function activerProchaineArrivee(row) {

        const ordreActuel = parseInt(
            row.querySelector('td:first-child')
                .textContent
                .trim()
        );


        const rows = modal.querySelectorAll('tbody tr');


        rows.forEach(function (nextRow) {

            const ordre = parseInt(
                nextRow.querySelector('td:first-child')
                    .textContent
                    .trim()
            );


            if (ordre === ordreActuel + 1) {

                const actionCell =
                    nextRow.querySelector('.action-cell');


                const arrivalForm =
                    actionCell.querySelector('.arrivee-form');


                if (arrivalForm) {

                    arrivalForm
                        .querySelector('button')
                        .disabled = false;

                }

            }

        });

    }


    modal.querySelectorAll('.arrivee-form')
        .forEach(function (form) {

            form.addEventListener('submit', async function (event) {

                event.preventDefault();


                const button = form.querySelector('button');

                const token = form.dataset.token;


                button.disabled = true;

                button.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i>';


                try {

                    const response = await fetch(form.action, {

                        method: 'POST',

                        headers: {

                            'X-CSRF-TOKEN': token,

                            'Accept': 'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'

                        },

                        body: new FormData(form)

                    });


                    const data = await response.json();


                    if (!response.ok || !data.success) {

                        throw new Error(
                            data.message ||
                            'Une erreur est survenue.'
                        );

                    }


                    const row = form.closest('tr');


                    row.querySelector(
                        '.statut-cell'
                    ).innerHTML = `

                        <span class="badge badge-success">

                            Arrivé

                        </span>

                    `;


                    row.querySelector(
                        '.arrivee-cell'
                    ).innerHTML =
                        data.heure_arrivee;


                    /*
                     * ARRIVÉE FINALE
                     */

                    if (data.final) {

                        modal.querySelectorAll(
                            '.action-cell'
                        ).forEach(function (cell) {

                            cell.innerHTML = `

                                <span class="text-muted">

                                    -

                                </span>

                            `;

                        });


                        const voyageStatus =
                            modal.querySelector(
                                '.voyage-status'
                            );


                        if (voyageStatus) {

                            voyageStatus.innerHTML = `

                                <span class="badge badge-success">

                                    <i class="fas fa-check mr-1"></i>

                                    Terminé

                                </span>

                            `;

                        }


                        Swal.fire({

                            icon: 'success',

                            title: 'Voyage terminé',

                            text: data.message,

                            confirmButtonText: 'OK'

                        });

                        return;
                    }


                    /*
                     * TRANSFORMATION ARRIVÉE → DÉPART
                     */

                    form.outerHTML = `

                        <form action="${form.dataset.departUrl}"
                              method="POST"
                              class="d-inline depart-form"
                              data-token="${form.dataset.token}">

                            <input type="hidden"
                                   name="_token"
                                   value="${form.dataset.token}">

                            <input type="hidden"
                                   name="_method"
                                   value="PATCH">

                            <button type="submit"
                                    class="btn btn-primary btn-sm"
                                    title="Confirmer le départ">

                                <i class="fas fa-play"></i>

                                Départ

                            </button>

                        </form>

                    `;


                    Swal.fire({

                        icon: 'success',

                        title: 'Arrivée enregistrée',

                        text: data.message,

                        timer: 900,

                        showConfirmButton: false

                    });


                    initialiserDepart();

                } catch (error) {

                    button.disabled = false;

                    button.innerHTML = `

                        <i class="fas fa-map-marker-alt"></i>

                        Arrivée

                    `;


                    afficherErreur(error.message);

                }

            });

        });


    initialiserDepart();

});

</script>

@endpush
