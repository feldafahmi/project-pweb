<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'user_id',
        'text',
        'completed',
        'feedback',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * Get the user that owns the milestone.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
