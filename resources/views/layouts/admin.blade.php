<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Océan du Nord')</title>

    {{-- AdminLTE --}}
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    {{-- SweetAlert2 --}}
    <link rel="stylesheet"
          href="{{ asset('sweetalert/dist/sweetalert2.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    {{-- =========================================================
         NAVBAR
    ========================================================== --}}

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

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


        <ul class="navbar-nav ml-auto">

            {{-- Utilisateur connecté --}}
            <li class="nav-item dropdown">

                <a class="nav-link"
                   data-toggle="dropdown"
                   href="#">

                    <i class="fas fa-user mr-1"></i>

                    {{ Auth::user()->name }}

                    <i class="fas fa-caret-down ml-1"></i>

                </a>


                <div class="dropdown-menu dropdown-menu-right">

                    <div class="dropdown-header">
                        {{ Auth::user()->email }}
                    </div>

                    <div class="dropdown-divider"></div>


                    {{-- Déconnexion --}}
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

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}"
           class="brand-link">

            <span class="brand-text font-weight-light">
                <strong>Océan du Nord</strong>
            </span>

        </a>


        <div class="sidebar">

            {{-- Utilisateur --}}
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                <div class="image">

                    <i class="fas fa-user-circle fa-2x text-white"></i>

                </div>

                <div class="info">

                    <a href="#"
                       class="d-block">

                        {{ Auth::user()->name }}

                    </a>

                </div>

            </div>


            {{-- MENU --}}
            <nav class="mt-2">

                <ul class="nav nav-pills nav-sidebar flex-column"
                    role="menu">


                    {{-- Dashboard --}}
                    <li class="nav-item">

                        <a href="{{ route('dashboard') }}"
                           class="nav-link">

                            <i class="nav-icon fas fa-tachometer-alt"></i>

                            <p>
                                Tableau de bord
                            </p>

                        </a>

                    </li>


                    {{-- Utilisateurs --}}
                    @if(auth()->user()->isAdmin())

                        <li class="nav-item">

                            <a href="{{ route('users.index') }}"
                               class="nav-link">

                                <i class="nav-icon fas fa-users-cog"></i>

                                <p>
                                    Utilisateurs
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- Agences --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->role === 'chef_agence')

                        <li class="nav-item">

                            <a href="{{ route('agences.index') }}"
                            class="nav-link">

                                <i class="nav-icon fas fa-building"></i>

                                <p>
                                    Agences
                                </p>

                            </a>

                        </li>

                    @endif

                    {{-- Parc automobile --}}
                   @if(
                        auth()->user()->role === 'admin' ||
                        auth()->user()->role === 'chef_parc'
                    )

                        <li class="nav-item">

                            <a href="{{ route('bus.index') }}"
                            class="nav-link">

                                <i class="nav-icon fas fa-bus"></i>

                                <p>
                                    Parc automobile
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- Chauffeurs --}}
                    @if(
                        auth()->user()->role === 'admin' ||
                        auth()->user()->role === 'chef_parc'
                    )

                        <li class="nav-item">

                            <a href="{{ route('chauffeurs.index') }}"
                            class="nav-link">

                                <i class="nav-icon fas fa-id-card"></i>

                                <p>
                                    Chauffeurs
                                </p>

                            </a>

                        </li>

                    @endif

                    {{-- Équipes --}}
                    <li class="nav-item">

                        <a href="#"
                           class="nav-link">

                            <i class="nav-icon fas fa-users"></i>

                            <p>
                                Équipes
                            </p>

                        </a>

                    </li>


                    {{-- Lignes --}}
                   @if(
                        auth()->user()->role === 'admin' ||
                        auth()->user()->role === 'directeur_exploitation'
                    )

                        <li class="nav-item">

                            <a href="{{ route('lignes.index') }}"
                            class="nav-link">

                                <i class="nav-icon fas fa-route"></i>

                                <p>
                                    Lignes
                                </p>

                            </a>

                        </li>

                    @endif


                    {{-- Voyages --}}
                    <li class="nav-item">

                        <a href="#"
                           class="nav-link">

                            <i class="nav-icon fas fa-bus-alt"></i>

                            <p>
                                Voyages
                            </p>

                        </a>

                    </li>


                    {{-- Planning --}}
                    <li class="nav-item">

                        <a href="#"
                           class="nav-link">

                            <i class="nav-icon fas fa-calendar-alt"></i>

                            <p>
                                Planning
                            </p>

                        </a>

                    </li>


                    {{-- Incidents --}}
                    <li class="nav-item">

                        <a href="#"
                           class="nav-link">

                            <i class="nav-icon fas fa-exclamation-triangle"></i>

                            <p>
                                Incidents
                            </p>

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


        {{-- Header --}}
        <section class="content-header">

            <div class="container-fluid">

                <div class="row mb-2">

                    <div class="col-sm-6">

                        <h1>
                            @yield('page_title', 'Tableau de bord')
                        </h1>

                    </div>

                </div>

            </div>

        </section>


        {{-- Content --}}
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

        <strong>
            Océan du Nord
        </strong>

        <span class="float-right">
            Gestion du transport
        </span>

    </footer>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================== --}}

{{-- jQuery --}}
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

{{-- Bootstrap --}}
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

{{-- AdminLTE --}}
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

{{-- SweetAlert2 --}}
<script src="{{ asset('sweetalert/dist/sweetalert2.all.min.js') }}"></script>


@stack('scripts')

</body>

</html>
