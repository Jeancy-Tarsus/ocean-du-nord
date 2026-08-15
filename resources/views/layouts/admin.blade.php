<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Océan du Nord')
    </title>


    {{-- =========================================================
         ADMINLTE
    ========================================================== --}}

    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">


    {{-- =========================================================
         FONT AWESOME
    ========================================================== --}}

    <link rel="stylesheet"
          href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">


    {{-- =========================================================
         SWEETALERT
    ========================================================== --}}

    <link rel="stylesheet"
          href="{{ asset('sweetalert/dist/sweetalert2.min.css') }}">


    {{-- =========================================================
         VITE
    ========================================================== --}}

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    @stack('styles')


    {{-- =========================================================
         STYLE OCÉAN DU NORD
    ========================================================== --}}

    <style>

        /*
        |--------------------------------------------------------------------------
        | GLOBAL
        |--------------------------------------------------------------------------
        */

        body {
            font-family: "Source Sans Pro", sans-serif;
            background: #f4f6f9;
        }


        /*
        |--------------------------------------------------------------------------
        | NAVBAR
        |--------------------------------------------------------------------------
        */

        .main-header {
            height: 70px;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
            background: #ffffff !important;
        }


        /*
        |--------------------------------------------------------------------------
        | LOGO NAVBAR
        |--------------------------------------------------------------------------
        */

        /* .ocn-navbar-logo {
            height: 50px;
            width: auto;
            object-fit: contain;
        } */


        /* .ocn-brand-navbar {
            display: flex;
            align-items: center;
            height: 70px;
            padding: 5px 15px;
        } */
        .ocn-navbar-brand {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ocn-navbar-logo {
            height: 68px;
            width: auto;
            object-fit: contain;
        }



        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        */

        .main-sidebar {
            background: #142c3d !important;
        }


        .main-sidebar .brand-link {
            height: 70px;
            /* background: #ffffff; */
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
        }


        .ocn-sidebar-logo {
            width: 205px;
            max-height: 58px;
            object-fit: contain;
        }


        /*
        |--------------------------------------------------------------------------
        | USER PANEL
        |--------------------------------------------------------------------------
        */

        .ocn-user-panel {
            padding: 15px 10px;
            margin: 0 10px 10px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }


        .ocn-user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #506070;
            font-size: 22px;
        }


        .ocn-user-name {
            color: #ffffff !important;
            font-weight: 600;
        }


        .ocn-online {
            color: #35d16f;
            font-size: 12px;
        }


        .ocn-online i {
            font-size: 9px;
            margin-right: 4px;
        }


        /*
        |--------------------------------------------------------------------------
        | MENU
        |--------------------------------------------------------------------------
        */

        .sidebar .nav-link {
            color: #d7e0e7 !important;
            margin: 3px 10px;
            border-radius: 5px;
            padding: 11px 12px;
            transition: all .2s ease;
        }


        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: #ffffff !important;
        }


        .sidebar .nav-link.active {
            background: #1677d2 !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(0,0,0,.15);
        }


        .sidebar .nav-icon {
            width: 25px;
            font-size: 16px;
        }


        .sidebar .nav-link p {
            font-size: 14px;
            margin-left: 4px;
        }


        /*
        |--------------------------------------------------------------------------
        | TITRE DU MENU
        |--------------------------------------------------------------------------
        */

        .ocn-menu-title {
            color: rgba(255,255,255,.35);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 15px 20px 7px;
            font-weight: 600;
        }


        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        .content-wrapper {
            background: #f4f6f9;
        }


        .content-header {
            padding: 20px 25px 10px;
        }


        .ocn-page-title {
            color: #20384d;
            font-size: 28px;
            font-weight: 600;
            margin: 0;
        }


        .ocn-page-subtitle {
            color: #8995a1;
            font-size: 14px;
            margin-left: 10px;
        }


        /*
        |--------------------------------------------------------------------------
        | BREADCRUMB
        |--------------------------------------------------------------------------
        */

        .ocn-breadcrumb {
            background: transparent;
            margin: 0;
            padding: 8px 0;
            font-size: 13px;
        }


        .ocn-breadcrumb a {
            color: #337ab7;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .main-footer {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            color: #64748b;
            padding: 15px 20px;
        }


        .ocn-footer-title {
            color: #20384d;
            font-weight: 600;
        }


        /*
        |--------------------------------------------------------------------------
        | NAVBAR SEARCH
        |--------------------------------------------------------------------------
        */

        .ocn-search {
            width: 230px;
        }


        .ocn-search .form-control {
            border-right: 0;
            border-color: #dfe4e8;
            height: 38px;
        }


        .ocn-search .btn {
            background: #ffffff;
            border: 1px solid #dfe4e8;
            border-left: 0;
            color: #506070;
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        .ocn-notification {
            position: relative;
            margin-left: 8px;
        }


        .ocn-notification .badge {
            position: absolute;
            top: 1px;
            right: 1px;
            font-size: 8px;
            min-width: 16px;
            height: 16px;
            line-height: 11px;
            border-radius: 50%;
        }


        /*
        |--------------------------------------------------------------------------
        | USER NAVBAR
        |--------------------------------------------------------------------------
        */

        .ocn-navbar-user {
            display: flex;
            align-items: center;
            margin-left: 12px;
        }


        .ocn-navbar-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #edf1f4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #596b7a;
            margin-right: 7px;
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 768px) {

            .ocn-search {
                display: none;
            }

            .ocn-page-title {
                font-size: 23px;
            }

            .ocn-page-subtitle {
                display: none;
            }

            .ocn-navbar-logo {
                height: 42px;
            }

        }

    </style>

</head>


<body class="hold-transition sidebar-mini layout-fixed">


<div class="wrapper">


    {{-- =========================================================
         NAVBAR
    ========================================================== --}}

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">


       {{-- LOGO --}}
       <a href="{{ route('dashboard') }}"
            class="ocn-navbar-brand">

            <img src="{{ asset('images/logo.png') }}"
                alt="Océan du Nord"
                class="ocn-navbar-logo">

        </a>


        {{-- BOUTON SIDEBAR --}}
        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link"
                   data-widget="pushmenu"
                   href="#"
                   role="button">

                    <i class="fas fa-bars"></i>

                </a>

            </li>

        </ul>


        {{-- TITRE --}}
        <div class="d-none d-md-flex align-items-center ml-2">

            <span class="font-weight-semibold"
                  style="font-size:18px; color:#20384d;">

                @yield('title', 'Tableau de bord')

            </span>

        </div>


        {{-- PARTIE DROITE --}}
        <ul class="navbar-nav ml-auto">

            {{-- PLEIN ÉCRAN --}}
            <li class="nav-item">

                <a class="nav-link"
                   href="#"
                   data-widget="fullscreen"
                   role="button">

                    <i class="fas fa-expand-arrows-alt"></i>

                </a>

            </li>


            {{-- NOTIFICATION --}}
            <li class="nav-item dropdown ocn-notification">

                <a class="nav-link"
                   data-toggle="dropdown"
                   href="#">

                    <i class="far fa-bell"></i>

                    <span class="badge badge-danger">
                        0
                    </span>

                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                    <span class="dropdown-item dropdown-header">
                        Notifications
                    </span>

                    <div class="dropdown-divider"></div>

                    <span class="dropdown-item text-muted text-center">

                        Aucune notification

                    </span>

                </div>

            </li>


            {{-- MESSAGES --}}
            <li class="nav-item dropdown ocn-notification">

                <a class="nav-link"
                   data-toggle="dropdown"
                   href="#">

                    <i class="far fa-envelope"></i>

                    <span class="badge badge-success">
                        0
                    </span>

                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                    <span class="dropdown-item dropdown-header">
                        Messages
                    </span>

                    <div class="dropdown-divider"></div>

                    <span class="dropdown-item text-muted text-center">

                        Aucun message

                    </span>

                </div>

            </li>


            {{-- UTILISATEUR --}}
            <li class="nav-item dropdown">

                <a class="nav-link"
                   data-toggle="dropdown"
                   href="#">

                    <span class="ocn-navbar-user">

                        <span class="ocn-navbar-avatar">

                            <i class="fas fa-user"></i>

                        </span>

                        <span class="d-none d-md-inline">

                            {{ Auth::user()->name }}

                        </span>

                        <i class="fas fa-caret-down ml-2"></i>

                    </span>

                </a>


                <div class="dropdown-menu dropdown-menu-right">


                    <div class="dropdown-header">

                        <strong>
                            {{ Auth::user()->name }}
                        </strong>

                        <br>

                        <small class="text-muted">

                            {{ Auth::user()->email }}

                        </small>

                    </div>


                    <div class="dropdown-divider"></div>


                    {{-- DÉCONNEXION --}}
                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit"
                                class="dropdown-item">

                            <i class="fas fa-sign-out-alt mr-2"></i>

                            Déconnexion

                        </button>

                    </form>

                </div>

            </li>

        </ul>

    </nav>


    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside class="main-sidebar sidebar-dark-primary elevation-4">


        {{-- LOGO --}}
        <a href="{{ route('dashboard') }}"
           class="brand-link">

            <img src="{{ asset('images/logo1.png') }}"
                 class="ocn-sidebar-logo"
                 alt="Océan du Nord">

        </a>


        <div class="sidebar">

            {{-- =================================================
                 MENU PRINCIPAL
            ================================================== --}}

            <div class="ocn-menu-title">

                Menu principal

            </div>


            <nav class="mt-1">

                <ul class="nav nav-pills nav-sidebar flex-column"
                    role="menu">


                    {{-- =================================================
                         DASHBOARD
                    ================================================== --}}

                    <li class="nav-item">

                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-tachometer-alt"></i>

                            <p>
                                Tableau de bord
                            </p>

                        </a>

                    </li>


                    {{-- =================================================
                         UTILISATEURS
                    ================================================== --}}

                    @if(auth()->user()->isAdmin())

                        <li class="nav-item">

                            <a href="{{ route('users.index') }}"
                               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-users-cog"></i>

                                <p>
                                    Utilisateurs
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- =================================================
                         AGENCES
                    ================================================== --}}

                    @if(
                        auth()->user()->isAdmin() ||
                        auth()->user()->role === 'chef_agence'
                    )

                        <li class="nav-item">

                            <a href="{{ route('agences.index') }}"
                               class="nav-link {{ request()->routeIs('agences.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-building"></i>

                                <p>
                                    Agences
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- =================================================
                         BUS
                    ================================================== --}}

                    @if(
                        auth()->user()->role === 'admin' ||
                        auth()->user()->role === 'chef_parc'
                    )

                        <li class="nav-item">

                            <a href="{{ route('bus.index') }}"
                               class="nav-link {{ request()->routeIs('bus.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-bus"></i>

                                <p>
                                    Parc automobile
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- =================================================
                         CHAUFFEURS
                    ================================================== --}}

                    @if(
                        auth()->user()->role === 'admin' ||
                        auth()->user()->role === 'chef_parc'
                    )

                        <li class="nav-item">

                            <a href="{{ route('chauffeurs.index') }}"
                               class="nav-link {{ request()->routeIs('chauffeurs.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-id-card"></i>

                                <p>
                                    Chauffeurs
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- =================================================
                         ÉQUIPES
                    ================================================== --}}

                    @if(
                        auth()->user()->role === 'admin' ||
                        auth()->user()->role === 'chef_parc'
                    )

                        <li class="nav-item">

                            <a href="{{ route('equipes.index') }}"
                               class="nav-link {{ request()->routeIs('equipes.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-users-cog"></i>

                                <p>
                                    Équipes
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- =================================================
                         LIGNES
                    ================================================== --}}

                    @if(
                        auth()->user()->role === 'admin' ||
                        auth()->user()->role === 'directeur_exploitation'
                    )

                        <li class="nav-item">

                            <a href="{{ route('lignes.index') }}"
                               class="nav-link {{ request()->routeIs('lignes.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-route"></i>

                                <p>
                                    Lignes
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- =================================================
                         VOYAGES
                    ================================================== --}}

                    <li class="nav-item">

                        <a href="{{ route('voyages.index') }}"
                           class="nav-link {{ request()->routeIs('voyages.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-bus-alt"></i>

                            <p>
                                Voyages
                            </p>

                        </a>

                    </li>


                    {{-- =================================================
                         PLANNING
                    ================================================== --}}

                    <li class="nav-item">

                        <a href="#"
                           class="nav-link">

                            <i class="nav-icon fas fa-calendar-alt"></i>

                            <p>
                                Planning
                            </p>

                            <span class="right">

                                <i class="fas fa-chevron-right"
                                   style="font-size:10px;"></i>

                            </span>

                        </a>

                    </li>


                    {{-- =================================================
                         INCIDENTS
                    ================================================== --}}

                    <li class="nav-item">

                        <a href="#"
                           class="nav-link">

                            <i class="nav-icon fas fa-exclamation-triangle"></i>

                            <p>
                                Incidents
                            </p>

                            <span class="right">

                                <i class="fas fa-chevron-right"
                                   style="font-size:10px;"></i>

                            </span>

                        </a>

                    </li>


                </ul>

            </nav>

        </div>

    </aside>


    {{-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== --}}

    <div class="content-wrapper">


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <section class="content-header">

            <div class="container-fluid">

                <div class="row align-items-center mb-2">


                    {{-- TITRE --}}
                    <div class="col-md-7">

                        <div class="d-flex align-items-center">

                            <h1 class="ocn-page-title">

                                @yield(
                                    'page_title',
                                    'Tableau de bord'
                                )

                            </h1>


                            @hasSection('page_subtitle')

                                <span class="ocn-page-subtitle">

                                    @yield('page_subtitle')

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- BREADCRUMB --}}
                    <div class="col-md-5 d-none d-md-block">

                        <ol class="breadcrumb float-sm-right ocn-breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('dashboard') }}">

                                    <i class="fas fa-home mr-1"></i>

                                    Accueil

                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                @yield(
                                    'page_title',
                                    'Tableau de bord'
                                )

                            </li>

                        </ol>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             CONTENT
        ====================================================== --}}

        <section class="content">

            <div class="container-fluid">

                @yield('content')

            </div>

        </section>

    </div>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <footer class="main-footer">


        <strong class="ocn-footer-title">

            Océan du Nord

        </strong>


        <span>

            - Système de gestion de transport

        </span>


        <span class="float-right d-none d-md-inline">

            Version 1.0.0

        </span>

    </footer>


</div>


{{-- =========================================================
     JAVASCRIPT
========================================================== --}}

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

<script src="{{ asset('sweetalert/dist/sweetalert2.all.min.js') }}"></script>


@stack('scripts')


</body>

</html>
