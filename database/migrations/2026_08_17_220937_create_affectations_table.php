<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | VOYAGE CONCERNÉ
            |--------------------------------------------------------------------------
            */

            $table->foreignId('voyage_id')
                ->constrained('voyages')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | BUS
            |--------------------------------------------------------------------------
            |
            | On conserve l'ancien bus et le nouveau bus.
            |
            | Exemple :
            |
            | B001 → B007
            |
            */

            $table->foreignId('ancien_bus_id')
                ->nullable()
                ->constrained('bus')
                ->nullOnDelete();

            $table->foreignId('nouveau_bus_id')
                ->nullable()
                ->constrained('bus')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | ÉQUIPE
            |--------------------------------------------------------------------------
            |
            | Même principe si l'équipe doit être remplacée.
            |
            */

            $table->foreignId('ancienne_equipe_id')
                ->nullable()
                ->constrained('equipes')
                ->nullOnDelete();

            $table->foreignId('nouvelle_equipe_id')
                ->nullable()
                ->constrained('equipes')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | TYPE DE REMPLACEMENT
            |--------------------------------------------------------------------------
            */

            $table->enum('type', [
                'remplacement_bus',
                'remplacement_equipe',
                'remplacement_bus_equipe',
            ]);


            /*
            |--------------------------------------------------------------------------
            | MOTIF
            |--------------------------------------------------------------------------
            */

            $table->string('motif');


            /*
            |--------------------------------------------------------------------------
            | DATE / HEURE DU CHANGEMENT
            |--------------------------------------------------------------------------
            */

            $table->date('date_affectation');

            $table->time('heure_affectation');


            /*
            |--------------------------------------------------------------------------
            | OBSERVATION
            |--------------------------------------------------------------------------
            */

            $table->text('observation')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | UTILISATEUR AYANT EFFECTUÉ LE CHANGEMENT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
