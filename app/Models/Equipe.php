<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipe extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'chauffeur_titulaire_id',
        'chauffeur_secondaire_id',
        'statut',
        'observation',
    ];

    /**
     * Chauffeur titulaire de l'équipe.
     */
    public function chauffeurTitulaire(): BelongsTo
    {
        return $this->belongsTo(
            Chauffeur::class,
            'chauffeur_titulaire_id'
        );
    }

    /**
     * Chauffeur secondaire / contrôleur.
     */
    public function chauffeurSecondaire(): BelongsTo
    {
        return $this->belongsTo(
            Chauffeur::class,
            'chauffeur_secondaire_id'
        );
    }
}
