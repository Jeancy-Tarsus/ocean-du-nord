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
        Schema::create('equipes', function (Blueprint $table) {

            $table->id();

            // Matricule de l'équipe : EQ-001, EQ-002...
            $table->string('code')->unique();

            // Chauffeur titulaire
            $table->foreignId('chauffeur_titulaire_id')
                ->constrained('chauffeurs')
                ->restrictOnDelete();

            // Chauffeur secondaire / contrôleur
            $table->foreignId('chauffeur_secondaire_id')
                ->constrained('chauffeurs')
                ->restrictOnDelete();

            // État de l'équipe
            $table->string('statut')
                ->default('disponible');

            // Informations complémentaires
            $table->text('observation')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipes');
    }
};
