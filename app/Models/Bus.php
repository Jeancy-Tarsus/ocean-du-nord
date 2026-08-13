<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
