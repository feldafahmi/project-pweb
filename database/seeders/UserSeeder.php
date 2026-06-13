<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Root Admin
        User::firstOrCreate(
            ['email' => 'admin@markup.com'],
            [
                'name' => 'Admin MARK-UP',
                'password' => Hash::make('passwordadmin'),
                'role' => 'admin',
            ]
        );

        // Mentor
        User::firstOrCreate(
            ['email' => 'mentor@markup.com'],
            [
                'name' => 'Mentor MARK-UP',
                'password' => Hash::make('passwordmentor'),
                'role' => 'mentor',
            ]
        );

        // Standard User
        User::firstOrCreate(
            ['email' => 'user@markup.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('passworduser'),
                'role' => 'user',
            ]
        );
    }
}
