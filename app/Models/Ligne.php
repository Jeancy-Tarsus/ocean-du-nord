<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ligne extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
