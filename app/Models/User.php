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

        // Setiap user baru mendapat milestone awal sebagai panduan onboarding.
        // Sebelumnya di-seed di DashboardController::index (tulis pada request GET);
        // dipindah ke sini agar halaman dashboard murni read-only.
        static::created(function (self $user) {
            $user->seedDefaultMilestones();
        });
    }

    /**
     * Buat milestone bawaan untuk user (dipakai saat registrasi).
     */
    public function seedDefaultMilestones(): void
    {
        $defaults = [
            ['text' => 'Membentuk Kelompok & Cari Nama Tim', 'completed' => true],
            ['text' => 'Analisis Kasus dengan Framework SWOT/BCG', 'completed' => false],
            ['text' => 'Asistensi Pitch Deck Bersama Mentor Mark-Up', 'completed' => false],
        ];

        foreach ($defaults as $milestone) {
            $this->milestones()->create($milestone);
        }
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
     * Produk yang sudah dibeli user (transaksi berstatus 'paid').
     *
     * Sumber kebenaran: transaction_items dari transaksi paid milik user —
     * sama persis dengan gate akses di mobile (endpoint /my-products). Bukan
     * relasi Eloquent murni (pivot transaction_items tidak menyimpan user_id),
     * jadi dikembalikan sebagai query Product; pakai dengan ->get()/->count().
     */
    public function products()
    {
        return Product::query()
            ->whereIn('id', TransactionItem::query()
                ->select('product_id')
                ->whereNotNull('product_id')
                ->whereHas('transaction', fn ($q) => $q
                    ->where('user_id', $this->id)
                    ->where('status', 'paid')))
            ->distinct();
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
