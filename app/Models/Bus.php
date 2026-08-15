<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    protected $table = 'bus';

    protected $fillable = [
        'numero',
        'immatriculation',
        'marque',
        'modele',
        'capacite',
        'etat',
        'statut',
        'observation',
    ];

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }
}
