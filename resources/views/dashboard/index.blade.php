@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('page_title', 'Tableau de bord')

@section('content')


{{-- =========================================================
     STATISTIQUES PRINCIPALES
========================================================= --}}

<div class="row">


    {{-- =====================================================
         VOYAGES AUJOURD'HUI
    ====================================================== --}}

    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>
                    {{ $voyagesAujourdHui }}
                </h3>

                <p>
                    Voyages aujourd'hui
                </p>

            </div>


            <div class="icon">

                <i class="fas fa-bus"></i>

            </div>


            <a href="{{ route('voyages.index') }}"
               class="small-box-footer">

                Voir les voyages

                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>



    {{-- =====================================================
         BUS DISPONIBLES
    ====================================================== --}}

    <div class="col-lg-3 col-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>
                    {{ $busDisponibles }}
                </h3>

                <p>
                    Bus disponibles
                </p>

            </div>


            <div class="icon">

                <i class="fas fa-bus"></i>

            </div>


            <a href="{{ route('bus.index') }}"
               class="small-box-footer">

                Voir le parc

                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>



    {{-- =====================================================
         CHAUFFEURS DISPONIBLES
    ====================================================== --}}

    <div class="col-lg-3 col-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>
                    {{ $chauffeursDisponibles }}
                </h3>

                <p>
                    Chauffeurs disponibles
                </p>

            </div>


            <div class="icon">

                <i class="fas fa-users"></i>

            </div>


            <a href="{{ route('chauffeurs.index') }}"
               class="small-box-footer">

                Voir les chauffeurs

                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>



    {{-- =====================================================
         ÉQUIPES DISPONIBLES
    ====================================================== --}}

    <div class="col-lg-3 col-6">

        <div class="small-box bg-danger">

            <div class="inner">

                <h3>
                    {{ $equipesDisponibles }}
                </h3>

                <p>
                    Équipes disponibles
                </p>

            </div>


            <div class="icon">

                <i class="fas fa-user-friends"></i>

            </div>


            <a href="{{ route('equipes.index') }}"
               class="small-box-footer">

                Voir les équipes

                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>

</div>

{{-- =========================================================
     GRAPHIQUES
========================================================= --}}

<div class="row">


    {{-- VOYAGES DES 7 DERNIERS JOURS --}}
    <div class="col-lg-7">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h3 class="card-title font-weight-bold">

                    <i class="fas fa-chart-line mr-2 text-primary"></i>

                    Voyages des 7 derniers jours

                </h3>

            </div>


            <div class="card-body">

                <canvas id="voyagesChart"
                        height="120"></canvas>

            </div>

        </div>

    </div>


    {{-- RÉPARTITION PAR LIGNE --}}
    <div class="col-lg-5">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h3 class="card-title font-weight-bold">

                    <i class="fas fa-chart-pie mr-2 text-success"></i>

                    Répartition des voyages par ligne

                </h3>

            </div>


            <div class="card-body">

                <canvas id="lignesChart"
                        height="220"></canvas>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     PARTIE BASSE
========================================================= --}}

<div class="row">


    {{-- PROCHAINS VOYAGES --}}
    <div class="col-lg-5">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h3 class="card-title font-weight-bold">

                    <i class="fas fa-calendar-alt mr-2 text-primary"></i>

                    Prochains voyages

                </h3>

            </div>


            <div class="card-body p-0">

                @forelse($prochainsVoyages as $voyage)

                    <div class="p-3 border-bottom">

                        <div class="d-flex align-items-center">


                            <div class="mr-3">

                                <span class="btn btn-light">

                                    <i class="fas fa-bus text-primary"></i>

                                </span>

                            </div>


                            <div class="flex-grow-1">

                                <strong>

                                    {{ $voyage->code }}

                                </strong>


                                <div class="text-muted small">

                                    @if($voyage->ligne)

                                        {{ $voyage->ligne->nom }}

                                    @else

                                        Ligne non définie

                                    @endif

                                </div>


                                <div class="text-muted small">

                                    <i class="far fa-calendar mr-1"></i>

                                    {{ \Carbon\Carbon::parse($voyage->date_depart)->format('d/m/Y') }}

                                    à

                                    {{ substr($voyage->heure_depart, 0, 5) }}

                                </div>

                            </div>


                            <div>

                                @if($voyage->statut === 'confirme')

                                    <span class="badge badge-success">

                                        Confirmé

                                    </span>

                                @else

                                    <span class="badge badge-primary">

                                        Planifié

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted p-4">

                        <i class="fas fa-calendar-times fa-2x mb-2"></i>

                        <p class="mb-0">

                            Aucun prochain voyage.

                        </p>

                    </div>

                @endforelse

            </div>


            <div class="card-footer bg-white">

                <a href="{{ route('voyages.index') }}">

                    Voir tous les voyages

                    <i class="fas fa-arrow-right float-right"></i>

                </a>

            </div>

        </div>

    </div>


    {{-- STATUT DES BUS --}}
    <div class="col-lg-4">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h3 class="card-title font-weight-bold">

                    <i class="fas fa-bus mr-2 text-danger"></i>

                    Bus par statut

                </h3>

            </div>


            <div class="card-body p-0">


                <div class="d-flex justify-content-between p-3 border-bottom">

                    <span>
                        Disponibles
                    </span>

                    <span class="badge badge-success">

                        {{ $busParStatut['disponible'] }}

                    </span>

                </div>


                <div class="d-flex justify-content-between p-3 border-bottom">

                    <span>
                        En voyage
                    </span>

                    <span class="badge badge-primary">

                        {{ $busParStatut['en_voyage'] }}

                    </span>

                </div>


                <div class="d-flex justify-content-between p-3 border-bottom">

                    <span>
                        En maintenance
                    </span>

                    <span class="badge badge-warning">

                        {{ $busParStatut['en_maintenance'] }}

                    </span>

                </div>


                <div class="d-flex justify-content-between p-3 border-bottom">

                    <span>
                        En panne
                    </span>

                    <span class="badge badge-danger">

                        {{ $busParStatut['en_panne'] }}

                    </span>

                </div>


                <div class="d-flex justify-content-between p-3">

                    <span>
                        Hors service
                    </span>

                    <span class="badge badge-secondary">

                        {{ $busParStatut['hors_service'] }}

                    </span>

                </div>

            </div>


            <div class="card-footer bg-white">

                <a href="{{ route('bus.index') }}">

                    Voir tous les bus

                    <i class="fas fa-arrow-right float-right"></i>

                </a>

            </div>

        </div>

    </div>


    {{-- RÉSUMÉ DU PARC --}}
    <div class="col-lg-3">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h3 class="card-title font-weight-bold">

                    <i class="fas fa-chart-bar mr-2 text-warning"></i>

                    Résumé

                </h3>

            </div>


            <div class="card-body">


                <div class="mb-3">

                    <div class="d-flex justify-content-between">

                        <span>
                            Bus
                        </span>

                        <strong>
                            {{ $totalBus }}
                        </strong>

                    </div>

                    <div class="progress">

                        <div class="progress-bar bg-primary"
                             style="width: {{ $totalBus > 0 ? ($busDisponibles / $totalBus) * 100 : 0 }}%">
                        </div>

                    </div>

                </div>


                <div class="mb-3">

                    <div class="d-flex justify-content-between">

                        <span>
                            Chauffeurs
                        </span>

                        <strong>
                            {{ $totalChauffeurs }}
                        </strong>

                    </div>

                    <div class="progress">

                        <div class="progress-bar bg-success"
                             style="width: {{ $totalChauffeurs > 0 ? ($chauffeursDisponibles / $totalChauffeurs) * 100 : 0 }}%">
                        </div>

                    </div>

                </div>


                <div class="mb-3">

                    <div class="d-flex justify-content-between">

                        <span>
                            Équipes
                        </span>

                        <strong>
                            {{ $totalEquipes }}
                        </strong>

                    </div>

                    <div class="progress">

                        <div class="progress-bar bg-warning"
                             style="width: {{ $totalEquipes > 0 ? ($equipesDisponibles / $totalEquipes) * 100 : 0 }}%">
                        </div>

                    </div>

                </div>


                <div>

                    <div class="d-flex justify-content-between">

                        <span>
                            Lignes
                        </span>

                        <strong>
                            {{ $totalLignes }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


@endsection


{{-- =========================================================
     CHART.JS
========================================================= --}}

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | GRAPHIQUE VOYAGES
    |--------------------------------------------------------------------------
    */

    const voyagesCanvas =
        document.getElementById('voyagesChart');


    if (voyagesCanvas) {

        new Chart(
            voyagesCanvas,
            {
                type: 'line',

                data: {

                    labels: @json($dates),

                    datasets: [

                        {
                            label: 'Voyages effectués',

                            data: @json($voyagesEffectues),

                            borderColor: '#1677d2',

                            backgroundColor: 'rgba(22, 119, 210, 0.10)',

                            fill: true,

                            tension: 0.35,

                            borderWidth: 3,

                            pointRadius: 4
                        },


                        {
                            label: 'Voyages prévus',

                            data: @json($voyagesPrevus),

                            borderColor: '#8b98a5',

                            backgroundColor: 'transparent',

                            fill: false,

                            tension: 0.35,

                            borderWidth: 2,

                            pointRadius: 3
                        }

                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {

                            position: 'top'

                        }

                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            ticks: {

                                precision: 0

                            }

                        }

                    }

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GRAPHIQUE PAR LIGNE
    |--------------------------------------------------------------------------
    */

    const lignesCanvas =
        document.getElementById('lignesChart');


    if (lignesCanvas) {

        new Chart(
            lignesCanvas,
            {
                type: 'doughnut',

                data: {

                    labels: @json($lignesLabels),

                    datasets: [

                        {

                            data: @json($lignesData),

                            backgroundColor: [

                                '#1677d2',
                                '#16a34a',
                                '#f59e0b',
                                '#dc2626',
                                '#64748b',
                                '#7c3aed',
                                '#0891b2'

                            ],

                            borderWidth: 2,

                            borderColor: '#ffffff'

                        }

                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    cutout: '60%',

                    plugins: {

                        legend: {

                            position: 'right'

                        }

                    }

                }

            }
        );

    }

});

</script>

@endpush
