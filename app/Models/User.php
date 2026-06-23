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
        'username',
        'email',
        'first_name',
        'last_name',
        'institution',
        'password',
        'role',
    ];

    /**
     * Get user's full name.
     */
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the transactions of the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Get the products purchased by the user.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'transactions', 'user_id', 'product_id')
                    ->wherePivot('status', 'completed')
                    ->withTimestamps();
    }

    /**
     * Get the mentees of the user (if mentor).
     */
    public function mentees()
    {
        return $this->belongsToMany(User::class, 'mentorships', 'mentor_id', 'user_id');
    }

    /**
     * Get the mentors of the user (if mentee).
     */
    public function mentors()
    {
        return $this->belongsToMany(User::class, 'mentorships', 'user_id', 'mentor_id');
    }

    /**
     * Get the milestones of the user.
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'user_id');
    }
}