<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorSlot extends Model
{
    protected $fillable = ['mentor_id', 'day', 'time', 'duration', 'is_booked', 'date'];

    protected $casts = [
        'is_booked' => 'boolean',
        'date'      => 'date:Y-m-d',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
