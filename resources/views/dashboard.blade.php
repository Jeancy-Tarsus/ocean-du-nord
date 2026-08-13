@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('page_title', 'Tableau de bord')

@section('content')

<div class="row">

    {{-- Voyages --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>0</h3>

                <p>Voyages programmés</p>

            </div>

            <div class="icon">
                <i class="fas fa-bus"></i>
            </div>

            <a href="#" class="small-box-footer">

                Voir les voyages
                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>


    {{-- Bus --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>0</h3>

                <p>Bus disponibles</p>

            </div>

            <div class="icon">
                <i class="fas fa-bus"></i>
            </div>

            <a href="#" class="small-box-footer">

                Voir le parc
                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>


    {{-- Chauffeurs --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>0</h3>

                <p>Chauffeurs disponibles</p>

            </div>

            <div class="icon">
                <i class="fas fa-users"></i>
            </div>

            <a href="#" class="small-box-footer">

                Voir les chauffeurs
                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>


    {{-- Incidents --}}
    <div class="col-lg-3 col-6">

        <div class="small-box bg-danger">

            <div class="inner">

                <h3>0</h3>

                <p>Incidents</p>

            </div>

            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <a href="#" class="small-box-footer">

                Voir les incidents
                <i class="fas fa-arrow-circle-right"></i>

            </a>

        </div>

    </div>

</div>


{{-- Planning du jour --}}
<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-calendar-alt mr-2"></i>

            Planning du jour

        </h3>

    </div>


    <div class="card-body">

        <div class="alert alert-info">

            Aucun voyage n'est encore programmé.

        </div>

    </div>

</div>

@endsection
