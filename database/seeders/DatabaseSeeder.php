<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Akun admin (idempoten) untuk akses fitur admin di app mobile.
        User::updateOrCreate(
            ['email' => 'admin@markup.test'],
            [
                'name' => 'Admin Mark-Up',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );

        $this->call([
            ProductSeeder::class,
            MentorSeeder::class,
            ProductDetailSeeder::class,
            BareProductDetailSeeder::class,
            LearningContentSeeder::class,
            ReviewSeeder::class,
            CompetitionSeeder::class,
        ]);
    }
}
