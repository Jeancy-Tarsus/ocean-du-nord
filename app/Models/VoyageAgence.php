<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoyageAgence extends Model
{
    use HasFactory;

    protected $table = 'voyage_agences';

    protected $fillable = [
        'voyage_id',
        'agence_id',
        'type',
        'ordre',
        'heure_prevue',
        'heure_arrivee_reelle',
        'heure_depart_reelle',
        'statut',
        'observation',
    ];


    /**
     * Voyage concerné.
     */
    public function voyage()
    {
        return $this->belongsTo(Voyage::class);
    }


    /**
     * Agence concernée.
     */
    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }
}
