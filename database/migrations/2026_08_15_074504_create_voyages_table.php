<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voyages', function (Blueprint $table) {

            $table->id();

            // Code automatique du voyage
            $table->string('code')->unique();

            // Ligne concernée
            $table->foreignId('ligne_id')
                ->constrained('lignes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Bus affecté au voyage
            $table->foreignId('bus_id')
                ->constrained('bus')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Équipe de chauffeurs
            $table->foreignId('equipe_id')
                ->constrained('equipes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Date et heure du départ
            $table->date('date_depart');

            $table->time('heure_depart');

            // Arrivée prévue
            $table->date('date_arrivee_prevue')
                ->nullable();

            $table->time('heure_arrivee_prevue')
                ->nullable();

            // Statut du voyage
            $table->string('statut')
                ->default('planifie');

            // Observation
            $table->text('observation')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voyages');
    }
};
