@foreach($voyages as $voyage)

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

                            Départ

                        </strong>

                        <div class="mt-1">

                            {{ $voyage->date_depart?->format('d/m/Y') }}

                            à

                            {{ $voyage->heure_depart }}

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <strong>

                            Arrivée prévue

                        </strong>

                        <div class="mt-1">

                            @if($voyage->date_arrivee_prevue)

                                {{ $voyage->date_arrivee_prevue->format('d/m/Y') }}

                                @if($voyage->heure_arrivee_prevue)

                                    à {{ $voyage->heure_arrivee_prevue }}

                                @endif

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                <hr>


                {{-- AGENCES --}}

                <h6 class="ocn-title font-weight-bold mb-3">

                    <i class="fas fa-building mr-2"></i>

                    Agences du parcours

                </h6>


                @if($voyage->voyageAgences->count())

                    <div class="table-responsive">

                        <table class="table table-bordered table-sm">

                            <thead>

                                <tr>

                                    <th>
                                        Ordre
                                    </th>

                                    <th>
                                        Agence
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Heure prévue
                                    </th>

                                    <th>
                                        Statut
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($voyage->voyageAgences as $voyageAgence)

                                    <tr>

                                        <td>

                                            {{ $voyageAgence->ordre }}

                                        </td>

                                        <td>

                                            {{ $voyageAgence->agence->nom ?? '-' }}

                                        </td>

                                        <td>

                                            @if($voyageAgence->type === 'depart')

                                                <span class="badge badge-info">

                                                    Départ

                                                </span>

                                            @else

                                                <span class="badge badge-success">

                                                    Arrivée

                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            {{ $voyageAgence->heure_prevue ?? '-' }}

                                        </td>

                                        <td>

                                            @switch($voyageAgence->statut)

                                                @case('prevu')

                                                    <span class="badge badge-secondary">

                                                        Prévu

                                                    </span>

                                                    @break

                                                @case('arrive')

                                                    <span class="badge badge-success">

                                                        Arrivé

                                                    </span>

                                                    @break

                                                @case('reparti')

                                                    <span class="badge badge-primary">

                                                        Reparti

                                                    </span>

                                                    @break

                                                @default

                                                    {{ $voyageAgence->statut }}

                                            @endswitch

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <p class="text-muted">

                        Aucune agence associée à ce voyage.

                    </p>

                @endif


                {{-- STATUT --}}

                <div class="mt-4">

                    <strong>

                        <i class="fas fa-toggle-on mr-2 ocn-green"></i>

                        Statut du voyage

                    </strong>


                    <div class="mt-2">

                        @switch($voyage->statut)

                            @case('planifie')

                                <span class="badge badge-info">

                                    Planifié

                                </span>

                                @break

                            @case('en_cours')

                                <span class="badge badge-primary">

                                    En cours

                                </span>

                                @break

                            @case('termine')

                                <span class="badge badge-success">

                                    Terminé

                                </span>

                                @break

                            @case('annule')

                                <span class="badge badge-danger">

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

                        <p class="mt-2 mb-0">

                            {{ $voyage->observation }}

                        </p>

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

@endforeach
