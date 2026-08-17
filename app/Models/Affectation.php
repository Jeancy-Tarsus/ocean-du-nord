<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    use HasFactory;

    protected $fillable = [

        'voyage_id',

        'ancien_bus_id',
        'nouveau_bus_id',

        'ancienne_equipe_id',
        'nouvelle_equipe_id',

        'type',

        'motif',

        'date_affectation',
        'heure_affectation',

        'observation',

        'user_id',
    ];


    protected $casts = [

        'date_affectation' => 'date',

    ];

    public function voyage(): BelongsTo
    {
        return $this->belongsTo(
            Voyage::class
        );
    }

    public function ancienBus(): BelongsTo
    {
        return $this->belongsTo(
            Bus::class,
            'ancien_bus_id'
        );
    }

    public function nouveauBus(): BelongsTo
    {
        return $this->belongsTo(
            Bus::class,
            'nouveau_bus_id'
        );
    }

    public function ancienneEquipe(): BelongsTo
    {
        return $this->belongsTo(
            Equipe::class,
            'ancienne_equipe_id'
        );
    }

    public function nouvelleEquipe(): BelongsTo
    {
        return $this->belongsTo(
            Equipe::class,
            'nouvelle_equipe_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}
