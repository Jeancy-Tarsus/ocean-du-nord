<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chauffeur extends Model
{
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'telephone',
        'numero_permis',
        'date_expiration_permis',
        'statut',
        'disponible',
        'observation',
    ];

    protected $casts = [
        'date_expiration_permis' => 'date',
        'disponible' => 'boolean',
    ];

    /**
     * Équipes dans lesquelles le chauffeur est titulaire.
     */
    public function equipesTitulaires(): HasMany
    {
        return $this->hasMany(
            Equipe::class,
            'chauffeur_titulaire_id'
        );
    }

    /**
     * Équipes dans lesquelles le chauffeur est secondaire / contrôleur.
     */
    public function equipesSecondaires(): HasMany
    {
        return $this->hasMany(
            Equipe::class,
            'chauffeur_secondaire_id'
        );
    }
}
