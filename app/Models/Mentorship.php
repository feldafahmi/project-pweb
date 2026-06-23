<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentorship extends Model
{
    protected $fillable = [
        'mentor_id',
        'user_id',
    ];

    /**
     * Get the mentor.
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Get the mentee.
     */
    public function mentee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
