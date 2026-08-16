<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Connexion - Océan du Nord</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/bootstrap.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/bootstrap.css') }}"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="ocn-login">


    {{-- =====================================================
         BUS EN ARRIÈRE-PLAN
    ====================================================== --}}

    <img
        src="{{ asset('images/bus.png') }}"
        alt=""
        class="ocn-bus-background"
    >


    {{-- =====================================================
         VOILE LÉGER
    ====================================================== --}}

    <div class="ocn-login-overlay"></div>



    {{-- =====================================================
         CONTENU
    ====================================================== --}}

    <div class="ocn-login-container">


        {{-- =================================================
             CARTE
        ================================================== --}}

        <div class="ocn-login-card">


            {{-- LOGO --}}

            <div class="ocn-logo">

                <img
                    src="{{ asset('images/logo1.png') }}"
                    alt="Océan du Nord"
                >

            </div>



            {{-- NOM --}}

            <div class="ocn-brand-name">

                <span class="ocn-green">
                    OCÉAN
                </span>

                <span class="ocn-yellow">
                    DU
                </span>

                <span class="ocn-red">
                    NORD
                </span>

            </div>


            <div class="ocn-slogan">

                VOYAGEONS ENSEMBLE

            </div>



            {{-- BIENVENUE --}}

            <div class="ocn-welcome">

                <h1>

                    Bienvenue

                </h1>

                <p>

                    Connectez-vous à votre espace

                </p>

            </div>



            {{-- ERREURS --}}

            @if($errors->any())

                <div class="ocn-errors">

                    @foreach($errors->all() as $error)

                        <div>

                            {{ $error }}

                        </div>

                    @endforeach

                </div>

            @endif



            {{-- SUCCÈS --}}

            @if(session('status'))

                <div class="ocn-success">

                    {{ session('status') }}

                </div>

            @endif



            {{-- =================================================
                 FORMULAIRE
            ================================================== --}}

            <form
                method="POST"
                action="{{ route('login') }}"
            >

                @csrf


                {{-- EMAIL --}}

                <div class="ocn-form-group">

                    <label for="email">

                        Adresse e-mail

                    </label>


                    <div class="ocn-input-wrapper">


                       <span class="ocn-icon">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <path d="M3 7l9 6 9-6"></path>
                            </svg>
                        </span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Votre adresse e-mail"
                            autocomplete="username"
                            required
                            autofocus
                        >


                    </div>

                </div>



                {{-- MOT DE PASSE --}}

                <div class="ocn-form-group">

                    <label for="password">

                        Mot de passe

                    </label>


                    <div class="ocn-input-wrapper">


                       <span class="ocn-icon">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <rect x="3" y="10" width="18" height="11" rx="2"></rect>
                                <path d="M7 10V7a5 5 0 0 1 10 0v3"></path>
                                <circle cx="12" cy="15" r="1"></circle>
                            </svg>
                        </span>


                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Votre mot de passe"
                            autocomplete="current-password"
                            required
                        >


                        <button
                            type="button"
                            id="togglePassword"
                            class="ocn-password-toggle"
                            aria-label="Afficher le mot de passe"
                        >
                            <svg
                                id="eyeIcon"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>


                    </div>

                </div>



                {{-- OPTIONS --}}

                <div class="ocn-login-options">


                    <label>

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span>

                            Se souvenir de moi

                        </span>

                    </label>


                    @if(Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                        >

                            Mot de passe oublié ?

                        </a>

                    @endif


                </div>



                {{-- BOUTON --}}

                <button
                    type="submit"
                    class="ocn-login-button"
                >
                    <span>
                        Se connecter
                    </span>

                    <b class="ocn-button-icon">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path d="M5 12h14"></path>
                            <path d="M13 6l6 6-6 6"></path>
                        </svg>
                    </b>
                </button>


            </form>



            {{-- FOOTER --}}

            <div class="ocn-login-footer">

                Océan du Nord
                <span>•</span>
                Voyageons ensemble

            </div>


        </div>


    </div>



    {{-- =====================================================
         AFFICHER / MASQUER MOT DE PASSE
    ====================================================== --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const password =
                    document.getElementById('password');

                const button =
                    document.getElementById('togglePassword');


                if (!password || !button) {

                    return;

                }


                button.addEventListener(
                    'click',
                    function () {

                        if (
                            password.type === 'password'
                        ) {

                            password.type = 'text';

                            button.textContent = '🙈';

                        } else {

                            password.type = 'password';

                            button.textContent = '👁';

                        }

                    }
                );

            }
        );

    </script>


</body>

</html>
