<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voyage_agences', function (Blueprint $table) {

            $table->id();

            // Voyage concerné
            $table->foreignId('voyage_id')
                ->constrained('voyages')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Agence concernée
            $table->foreignId('agence_id')
                ->constrained('agences')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Type de passage
            // depart / arrivee
            $table->string('type');

            // Ordre de passage dans le parcours
            $table->unsignedInteger('ordre');

            // Heure prévue
            $table->time('heure_prevue')
                ->nullable();

            // Heure réelle d'arrivée
            $table->time('heure_arrivee_reelle')
                ->nullable();

            // Heure réelle de départ
            $table->time('heure_depart_reelle')
                ->nullable();

            // Statut du passage
            // prevu / arrive / reparti
            $table->string('statut')
                ->default('prevu');

            // Observation
            $table->text('observation')
                ->nullable();

            $table->timestamps();

            // Évite de mettre deux fois la même agence
            // au même ordre dans un même voyage
            $table->unique(
                ['voyage_id', 'ordre'],
                'voyage_agences_voyage_ordre_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voyage_agences');
    }
};
