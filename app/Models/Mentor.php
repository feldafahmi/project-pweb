<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = [
        'name',
        'title',
        'about',
        'highlights',
        'response_time',
        'rating',
        'sessions',
        'available',
        'price_per_session',
        'tags',
        'avatar_url',
    ];

    protected $casts = [
        'rating'     => 'float',
        'available'  => 'boolean',
        'tags'       => 'array',
        'highlights' => 'array',
    ];
}
