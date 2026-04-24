<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'start_date',
        'end_date',
        'target_audience',
        'registration_fee',
        'total_prize',
        'organizer',
        'image_url',
        'link_pendaftaran',
    ];

    protected $casts = [
        'start_date'       => 'date:Y-m-d',
        'end_date'         => 'date:Y-m-d',
        'registration_fee' => 'integer',
        'total_prize'      => 'integer',
    ];
}
