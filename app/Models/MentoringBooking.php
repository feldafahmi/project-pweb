<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentoringBooking extends Model
{
    protected $table = 'MENTORING_BOOKING';
    protected $primaryKey = 'ID_MENTORING_BOOKING';
    public $timestamps = false;

    protected $fillable = [
        'ID_USERS', 'USE_ID_USERS', 'SCEHEDULE_DATE', 'MEETING_LINK', 'STATUS'
    ];
}
