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
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id();

            $table->string('matricule')->unique();

            $table->string('nom');

            $table->string('prenom');

            $table->string('telephone')->nullable();

            $table->string('numero_permis')->unique();

            $table->date('date_expiration_permis')->nullable();

            $table->enum('statut', [
                'actif',
                'en_voyage',
                'indisponible',
                'suspendu',
                'inactif',
            ])->default('actif');

            $table->boolean('disponible')->default(true);

            $table->text('observation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chauffeurs');
    }
};
