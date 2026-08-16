@extends('layouts.admin')

@section('title', 'Mon profil')

@section('page_title', 'Mon profil')

@section('page_subtitle', 'Gestion de votre compte')

@section('content')

<div class="ocn-profile-page">

    <div class="ocn-profile-content">


        {{-- =====================================================
             INFORMATIONS PERSONNELLES
        ====================================================== --}}

        <div class="card ocn-profile-card">

            <div class="card-header ocn-profile-header">

                <div class="d-flex align-items-center">

                    <div class="ocn-profile-icon">

                        <i class="fas fa-user"></i>

                    </div>

                    <div>

                        <div class="card-title">
                            Informations personnelles
                        </div>

                        <small>
                            Modifiez votre nom et votre adresse e-mail.
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body">

                @include(
                    'profile.partials.update-profile-information-form'
                )

            </div>

        </div>


        {{-- =====================================================
             MOT DE PASSE
        ====================================================== --}}

        <div class="card ocn-profile-card">

            <div class="card-header ocn-profile-header">

                <div class="d-flex align-items-center">

                    <div class="ocn-profile-icon">

                        <i class="fas fa-lock"></i>

                    </div>

                    <div>

                        <div class="card-title">
                            Modifier le mot de passe
                        </div>

                        <small>
                            Changez votre mot de passe pour sécuriser
                            votre compte.
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body">

                @include(
                    'profile.partials.update-password-form'
                )

            </div>

        </div>


        {{-- =====================================================
             SUPPRESSION DU COMPTE
        ====================================================== --}}

        <div class="card ocn-profile-card ocn-profile-danger">

            <div class="card-header ocn-profile-header">

                <div class="d-flex align-items-center">

                    <div class="ocn-profile-icon">

                        <i class="fas fa-trash"></i>

                    </div>

                    <div>

                        <div class="card-title">
                            Supprimer le compte
                        </div>

                        <small>
                            La suppression du compte est définitive.
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body">

                @include(
                    'profile.partials.delete-user-form'
                )

            </div>

        </div>


    </div>

</div>

@endsection
