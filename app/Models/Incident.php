<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    protected $fillable = [
        'reference',
        'voyage_id',
        'bus_id',
        'agence_id',
        'user_id',
        'type',
        'titre',
        'description',
        'date_incident',
        'heure_incident',
        'gravite',
        'statut',
        'resolution',
        'date_resolution',
        'observation',
    ];

    protected $casts = [
        'date_incident' => 'date',
        'date_resolution' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Voyage
    |--------------------------------------------------------------------------
    */

    public function voyage(): BelongsTo
    {
        return $this->belongsTo(Voyage::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Bus
    |--------------------------------------------------------------------------
    */

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Agence
    |--------------------------------------------------------------------------
    */

    public function agence(): BelongsTo
    {
        return $this->belongsTo(Agence::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Utilisateur ayant déclaré l'incident
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
