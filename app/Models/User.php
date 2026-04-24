<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Tambahkan import ini
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    // 2. Tambahkan HasFactory di dalam sini
    use HasApiTokens, HasFactory, Notifiable; 

    // 3. Ubah USERS menjadi users (huruf kecil). 
    // Di log migrasi sebelumnya, Laravel membuat tabel dengan nama huruf kecil.
    protected $table = 'users'; 
    protected $primaryKey = 'id'; 
    public $timestamps = true;    

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}