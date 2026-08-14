/* =========================================================
   OCÉAN DU NORD
   JavaScript global
========================================================= */

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       SWEETALERT - MESSAGE SUCCÈS
    ===================================================== */

    const successMessage =
        document.getElementById('ocn-success-message');

    if (successMessage) {

        Swal.fire({

            icon: 'success',

            title: 'Succès',

            text: successMessage.dataset.message,

            confirmButtonText: 'OK'

        });

    }


    /* =====================================================
       SWEETALERT - MESSAGE ERREUR
    ===================================================== */

    const errorMessage =
        document.getElementById('ocn-error-message');

    if (errorMessage) {

        Swal.fire({

            icon: 'error',

            title: 'Erreur',

            text: errorMessage.dataset.message,

            confirmButtonText: 'OK'

        });

    }


    /* =====================================================
       SWEETALERT - ERREURS DE VALIDATION
    ===================================================== */

    const validationErrors =
        document.getElementById('ocn-validation-errors');

    if (validationErrors) {

        let errors = [];

        try {

            errors = JSON.parse(
                validationErrors.dataset.errors
            );

        } catch (error) {

            console.error(
                'Impossible de lire les erreurs de validation.',
                error
            );

        }


        if (errors.length > 0) {

            let html = '<ul style="text-align:left;">';


            errors.forEach(function (error) {

                html += `<li>${error}</li>`;

            });


            html += '</ul>';


            Swal.fire({

                icon: 'error',

                title: 'Erreur de validation',

                html: html,

                confirmButtonText: 'OK'

            });

        }

    }


    /* =====================================================
     SWEETALERT - CONFIRMATION SUPPRESSION
  ===================================================== */

    const deleteForms =
        document.querySelectorAll('.delete-form');


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            event.preventDefault();


            const message =
                form.dataset.deleteMessage ||
                'Cet élément sera définitivement supprimé.';


            Swal.fire({

                title: 'Êtes-vous sûr ?',

                text: message,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Oui, supprimer',

                cancelButtonText: 'Annuler',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    });
});
