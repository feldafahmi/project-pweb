<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'name',          // virtual: dipetakan ke first_name/last_name (lihat setNameAttribute)
        'email',
        'institution',
        'password',
        'role',
        'google_id',
        'avatar',
    ];

    /**
     * Auto-generate `username` unik dari email saat user baru dibuat tanpa
     * username (mis. registrasi mobile yang hanya mengirim `name`).
     */
    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if (empty($user->username)) {
                $user->username = static::generateUsername($user->email);
            }
        });
    }

    private static function generateUsername(?string $email): string
    {
        $base = Str::slug(explode('@', (string) $email)[0], '_') ?: 'user';
        $username = $base;
        $i = 1;
        while (static::where('username', $username)->exists()) {
            $username = $base . $i++;
        }
        return $username;
    }

    /**
     * Terima `name` tunggal dan petakan ke first_name/last_name. Membuat klien
     * yang memakai field `name` (mobile) kompatibel dengan skema first/last.
     */
    public function setNameAttribute($value): void
    {
        $parts = preg_split('/\s+/', trim((string) $value), 2);
        $this->attributes['first_name'] = $parts[0] ?? '';
        $this->attributes['last_name']  = $parts[1] ?? '';
    }

    // Jangan pernah kirim hash password / remember_token di response JSON.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Sertakan `name` (gabungan first+last) di JSON agar klien mobile yang
    // memakai field `name` tetap kompatibel dengan skema first_name/last_name.
    protected $appends = ['name'];

    /**
     * Nama lengkap user (first + last). Diekspos sebagai atribut `name`.
     */
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Transaksi milik user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Produk yang dibeli user (relasi web felda — lihat catatan integrasi).
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'transactions', 'user_id', 'product_id')
                    ->wherePivot('status', 'completed')
                    ->withTimestamps();
    }

    /**
     * Mentee user (jika mentor).
     */
    public function mentees()
    {
        return $this->belongsToMany(User::class, 'mentorships', 'mentor_id', 'user_id');
    }

    /**
     * Mentor user (jika mentee).
     */
    public function mentors()
    {
        return $this->belongsToMany(User::class, 'mentorships', 'user_id', 'mentor_id');
    }

    /**
     * Milestone milik user.
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'user_id');
    }
}
