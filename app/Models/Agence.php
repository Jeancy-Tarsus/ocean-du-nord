<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agence extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'ville',
        'adresse',
        'telephone',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }
}
