<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Penting untuk fitur Mobile nantinya

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'USERS'; // Sesuaikan dengan nama tabel di SQL 
    protected $primaryKey = 'id'; // Ubah dari ID_USERS ke id
    public $timestamps = true;    // Di Tinker ada created_at, jadi ini set true

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}