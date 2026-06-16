<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin (Root Kasta Tertinggi)
        User::create([
            'name' => 'Admin MARK-UP',
            'username' => 'adminmarkup',
            'first_name' => 'Admin',
            'last_name' => 'MARK-UP',
            'email' => 'admin@markup.com',
            'password' => Hash::make('passwordadmin'), // Otomatis enkripsi password bcrypt
            'role' => 'admin',
            'institution' => 'Universitas Airlangga',
            'department' => 'Sistem Informasi',
        ]);

        // 2. Membuat Akun Mentor (Kasta Menengah)
        User::create([
            'name' => 'Mentor MARK-UP',
            'username' => 'mentormarkup',
            'first_name' => 'Mentor',
            'last_name' => 'Markup',
            'email' => 'mentor@markup.com',
            'password' => Hash::make('passwordmentor'),
            'role' => 'mentor',
            'institution' => 'Universitas Airlangga',
            'department' => 'Sistem Informasi',
        ]);

        // 3. Membuat Akun User/Student biasa (Kasta Dasar)
        // User::create([
        //     'name' => 'User MARK-UP',
        //     'username' => 'usermarkup',
        //     'first_name' => 'User',
        //     'last_name' => 'Markup',
        //     'email' => 'user@markup.com',
        //     'password' => Hash::make('passworduser'),
        //     'role' => 'user',
        //     'institution' => 'Universitas Airlangga',
        //     'department' => 'Sistem Informasi',
        // ]);
    }
}