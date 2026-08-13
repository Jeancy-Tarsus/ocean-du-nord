<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bus', function (Blueprint $table) {
            $table->id();

            // Numéro interne du bus
            $table->string('numero')->unique();

            // Plaque d'immatriculation
            $table->string('immatriculation')->unique();

            // Informations du véhicule
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();

            // Nombre de voyageurs que le bus peut transporter
            $table->unsignedInteger('capacite');

            // État mécanique général
            $table->enum('etat', [
                'bon',
                'moyen',
                'mauvais',
            ])->default('bon');

            // Situation actuelle du bus
            $table->enum('statut', [
                'disponible',
                'en_voyage',
                'en_maintenance',
                'en_panne',
                'hors_service',
            ])->default('disponible');

            // Informations supplémentaires
            $table->text('observation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus');
    }
};
