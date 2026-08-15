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



    /* =====================================================
       INCIDENTS
       VOYAGE → INFORMATIONS AUTOMATIQUES
    ===================================================== */

    const incidentVoyageSelect =
        document.getElementById('incident_voyage_id');

    const incidentAgenceSelect =
        document.getElementById('incident_agence_id');

    const incidentVoyageInfo =
        document.getElementById('incidentVoyageInfo');


    /*
    |--------------------------------------------------------------------------
    | Si nous ne sommes pas sur la page/modal Incident,
    | on ne fait rien.
    |--------------------------------------------------------------------------
    */

    if (
        !incidentVoyageSelect ||
        !incidentAgenceSelect
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Quand le voyage change
    |--------------------------------------------------------------------------
    */

    incidentVoyageSelect.addEventListener(
        'change',
        function () {

            const voyageId =
                this.value;


            /*
            |--------------------------------------------------------------------------
            | Aucun voyage sélectionné
            |--------------------------------------------------------------------------
            */

            if (!voyageId) {

                if (incidentVoyageInfo) {

                    incidentVoyageInfo.classList.add(
                        'd-none'
                    );

                }


                incidentAgenceSelect.innerHTML = `
                    <option value="">
                        Sélectionnez d'abord un voyage
                    </option>
                `;


                incidentAgenceSelect.disabled =
                    true;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Affichage du chargement
            |--------------------------------------------------------------------------
            */

            if (incidentVoyageInfo) {

                incidentVoyageInfo.classList.remove(
                    'd-none'
                );

            }


            const code =
                document.getElementById(
                    'incidentInfoCode'
                );

            const ligne =
                document.getElementById(
                    'incidentInfoLigne'
                );

            const bus =
                document.getElementById(
                    'incidentInfoBus'
                );

            const equipe =
                document.getElementById(
                    'incidentInfoEquipe'
                );

            const chauffeur1 =
                document.getElementById(
                    'incidentInfoChauffeur1'
                );

            const chauffeur2 =
                document.getElementById(
                    'incidentInfoChauffeur2'
                );


            if (code) {

                code.textContent =
                    'Chargement...';

            }

            if (ligne) {

                ligne.textContent =
                    'Chargement...';

            }

            if (bus) {

                bus.textContent =
                    'Chargement...';

            }

            if (equipe) {

                equipe.textContent =
                    'Chargement...';

            }

            if (chauffeur1) {

                chauffeur1.textContent =
                    'Chargement...';

            }

            if (chauffeur2) {

                chauffeur2.textContent =
                    'Chargement...';

            }


            incidentAgenceSelect.innerHTML = `
                <option value="">
                    Chargement des agences...
                </option>
            `;


            incidentAgenceSelect.disabled =
                true;


            /*
            |--------------------------------------------------------------------------
            | Appel du contrôleur
            |--------------------------------------------------------------------------
            */

            const url =
                `/incidents/voyages/${voyageId}/informations`;


            fetch(url, {

                method: 'GET',

                headers: {

                    'Accept':
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'

                }

            })


            /*
            |--------------------------------------------------------------------------
            | Réponse
            |--------------------------------------------------------------------------
            */

            .then(function (response) {

                if (!response.ok) {

                    return response.json()
                        .then(function (data) {

                            throw new Error(
                                data.message ||
                                'Impossible de récupérer les informations du voyage.'
                            );

                        });

                }


                return response.json();

            })


            /*
            |--------------------------------------------------------------------------
            | Traitement
            |--------------------------------------------------------------------------
            */

            .then(function (data) {

                if (!data.success) {

                    throw new Error(
                        data.message ||
                        'Une erreur est survenue.'
                    );

                }


                const voyage =
                    data.voyage;


                /*
                |--------------------------------------------------------------------------
                | Voyage
                |--------------------------------------------------------------------------
                */

                if (code) {

                    code.textContent =
                        voyage.code || '-';

                }


                /*
                |--------------------------------------------------------------------------
                | Ligne
                |--------------------------------------------------------------------------
                */

                if (ligne) {

                    ligne.textContent =
                        voyage.ligne || '-';

                }


                /*
                |--------------------------------------------------------------------------
                | Bus
                |--------------------------------------------------------------------------
                */

                if (bus) {

                    if (voyage.bus) {

                        bus.textContent =
                            voyage.bus.numero +
                            ' - ' +
                            voyage.bus.immatriculation;

                    } else {

                        bus.textContent =
                            'Aucun bus';

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Équipe
                |--------------------------------------------------------------------------
                */

                if (equipe) {

                    equipe.textContent =
                        voyage.equipe || '-';

                }


                /*
                |--------------------------------------------------------------------------
                | Chauffeur titulaire
                |--------------------------------------------------------------------------
                */

                if (chauffeur1) {

                    chauffeur1.textContent =
                        voyage.chauffeur_titulaire ||
                        '-';

                }


                /*
                |--------------------------------------------------------------------------
                | Chauffeur secondaire
                |--------------------------------------------------------------------------
                */

                if (chauffeur2) {

                    chauffeur2.textContent =
                        voyage.chauffeur_secondaire ||
                        '-';

                }


                /*
                |--------------------------------------------------------------------------
                | Agences du parcours
                |--------------------------------------------------------------------------
                */

                incidentAgenceSelect.innerHTML = `
                    <option value="">
                        Sélectionner l'agence concernée
                    </option>
                `;


                if (
                    !voyage.agences ||
                    voyage.agences.length === 0
                ) {

                    incidentAgenceSelect.innerHTML = `
                        <option value="">
                            Aucune agence disponible
                        </option>
                    `;

                    incidentAgenceSelect.disabled =
                        true;

                    return;

                }


                voyage.agences.forEach(
                    function (agence) {

                        const option =
                            document.createElement(
                                'option'
                            );


                        option.value =
                            agence.id;


                        let texte =
                            agence.nom ||
                            'Agence';


                        if (
                            agence.type ===
                            'depart'
                        ) {

                            texte +=
                                ' — Départ';

                        }

                        else if (
                            agence.type ===
                            'arrivee'
                        ) {

                            texte +=
                                ' — Arrivée';

                        }

                        else if (
                            agence.type ===
                            'passage'
                        ) {

                            texte +=
                                ' — Passage';

                        }


                        option.textContent =
                            texte;


                        incidentAgenceSelect
                            .appendChild(
                                option
                            );

                    }
                );


                incidentAgenceSelect.disabled =
                    false;

            })


            /*
            |--------------------------------------------------------------------------
            | Erreur
            |--------------------------------------------------------------------------
            */

            .catch(function (error) {

                console.error(
                    'Erreur Incident :',
                    error
                );


                incidentAgenceSelect.innerHTML = `
                    <option value="">
                        Impossible de charger les agences
                    </option>
                `;


                incidentAgenceSelect.disabled =
                    true;


                if (incidentVoyageInfo) {

                    incidentVoyageInfo.classList.add(
                        'd-none'
                    );

                }


                Swal.fire({

                    icon: 'error',

                    title: 'Erreur',

                    text: error.message,

                    confirmButtonText: 'OK'

                });

            });

        }
    );

});
