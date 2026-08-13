<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
