<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    // Nama table
    protected $table = 'MENTOR';

    // primary key
    protected $primaryKey = 'ID_BOOKING';

    // tidak punya kolom created_at dan updated_at
    public $timestamps = false;
}
