<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorReview extends Model
{
    protected $fillable = ['mentor_id', 'user_id', 'stars', 'text'];

    protected $casts = [
        'stars' => 'integer',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
