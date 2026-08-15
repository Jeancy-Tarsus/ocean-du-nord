<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'ligne_id',
        'bus_id',
        'equipe_id',
        'date_depart',
        'heure_depart',
        'date_arrivee_prevue',
        'heure_arrivee_prevue',
        'statut',
        'observation',
    ];

    protected $casts = [
        'date_depart' => 'date',
        'date_arrivee_prevue' => 'date',
    ];


    /**
     * Ligne du voyage.
     */
    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }


    /**
     * Bus affecté au voyage.
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }


    /**
     * Équipe affectée au voyage.
     */
    public function equipe()
    {
        return $this->belongsTo(Equipe::class);
    }


    /**
     * Agences traversées par le voyage.
     */
    public function voyageAgences()
    {
        return $this->hasMany(VoyageAgence::class)
                    ->orderBy('ordre');
    }


    /**
     * Agences de départ.
     */
    public function agencesDepart()
    {
        return $this->hasMany(VoyageAgence::class)
                    ->where('type', 'depart')
                    ->orderBy('ordre');
    }


    /**
     * Agences d'arrivée.
     */
    public function agencesArrivee()
    {
        return $this->hasMany(VoyageAgence::class)
                    ->where('type', 'arrivee')
                    ->orderBy('ordre');
    }
}
