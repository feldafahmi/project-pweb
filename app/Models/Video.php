<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'VIDEO';
    protected $primaryKey = 'ID_VIDEO';
    public $timestamps = false;

    protected $fillable = [
        'TITLE', 
        'VIDEO_URL', 
        'DURASI'
    ];
}
