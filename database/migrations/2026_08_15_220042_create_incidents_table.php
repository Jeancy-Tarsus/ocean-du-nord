<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->string('reference')->unique();

            $table->foreignId('voyage_id')
                ->nullable()
                ->constrained('voyages')
                ->nullOnDelete();

            $table->foreignId('bus_id')
                ->nullable()
                ->constrained('bus')
                ->nullOnDelete();

            $table->foreignId('agence_id')
                ->nullable()
                ->constrained('agences')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('type', [
                'panne',
                'accident',
                'retard',
                'probleme_chauffeur',
                'probleme_technique',
                'autre',
            ]);

            $table->string('titre');

            $table->text('description');

            $table->date('date_incident');

            $table->time('heure_incident');

            $table->enum('gravite', [
                'faible',
                'moyenne',
                'grave',
                'critique',
            ])->default('faible');

            $table->enum('statut', [
                'ouvert',
                'en_cours',
                'resolu',
            ])->default('ouvert');

            $table->text('resolution')->nullable();

            $table->dateTime('date_resolution')->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
