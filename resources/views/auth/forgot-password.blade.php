<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Mot de passe oublié - Océan du Nord
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/bootstrap.min.css') }}"
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


        <div class="ocn-login-card ocn-forgot-card">


            {{-- =================================================
                 LOGO
            ================================================== --}}

            <div class="ocn-logo">

                <img
                    src="{{ asset('images/logo1.png') }}"
                    alt="Océan du Nord"
                >

            </div>



            {{-- =================================================
                 NOM
            ================================================== --}}

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



            {{-- =================================================
                 TITRE
            ================================================== --}}

            <div class="ocn-welcome">

                <div class="ocn-forgot-icon">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >

                        <path
                            d="M3 8l9 6 9-6"
                        ></path>

                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="14"
                            rx="2"
                        ></rect>

                    </svg>

                </div>


                <h1>

                    Mot de passe oublié ?

                </h1>


                <p>

                    Entrez votre adresse e-mail
                    pour recevoir un lien de réinitialisation.

                </p>

            </div>



            {{-- =================================================
                 MESSAGE SESSION
            ================================================== --}}

            @if(session('status'))

                <div class="ocn-success">

                    {{ session('status') }}

                </div>

            @endif



            {{-- =================================================
                 ERREURS
            ================================================== --}}

            @if($errors->any())

                <div class="ocn-errors">

                    @foreach($errors->all() as $error)

                        <div>

                            {{ $error }}

                        </div>

                    @endforeach

                </div>

            @endif



            {{-- =================================================
                 FORMULAIRE
            ================================================== --}}

            <form
                method="POST"
                action="{{ route('password.email') }}"
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

                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="14"
                                    rx="2"
                                ></rect>

                                <path
                                    d="M3 7l9 6 9-6"
                                ></path>

                            </svg>

                        </span>


                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Votre adresse e-mail"
                            required
                            autofocus
                            autocomplete="email"
                        >

                    </div>

                </div>



                {{-- =================================================
                     BOUTON
                ================================================== --}}

                <button
                    type="submit"
                    class="ocn-login-button ocn-reset-button"
                >

                    <span>

                        Envoyer le lien

                    </span>


                    <b class="ocn-button-icon">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >

                            <path
                                d="M5 12h14"
                            ></path>

                            <path
                                d="M13 6l6 6-6 6"
                            ></path>

                        </svg>

                    </b>

                </button>


            </form>



            {{-- =================================================
                 RETOUR CONNEXION
            ================================================== --}}

            <a
                href="{{ route('login') }}"
                class="ocn-back-login"
            >

                <span class="ocn-back-icon">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >

                        <path
                            d="M19 12H5"
                        ></path>

                        <path
                            d="M12 19l-7-7 7-7"
                        ></path>

                    </svg>

                </span>


                Retour à la connexion

            </a>



            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div class="ocn-login-footer">

                Océan du Nord

                <span>•</span>

                Voyageons ensemble

            </div>


        </div>


    </div>


</body>

</html>
