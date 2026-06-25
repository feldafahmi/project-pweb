<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin (dashboard web + admin API mobile)
        User::updateOrCreate(['email' => 'admin@markup.com'], [
            'username'    => 'adminmarkup',
            'first_name'  => 'Admin',
            'last_name'   => 'MARK-UP',
            'password'    => Hash::make('passwordadmin'),
            'role'        => 'admin',
            'institution' => 'Universitas Airlangga',
        ]);

        // Admin khusus pengujian app mobile.
        User::updateOrCreate(['email' => 'admin@markup.test'], [
            'username'   => 'adminmobile',
            'first_name' => 'Admin',
            'last_name'  => 'Mobile',
            'password'   => Hash::make('admin12345'),
            'role'       => 'admin',
        ]);

        // Mentor.
        User::updateOrCreate(['email' => 'mentor@markup.com'], [
            'username'    => 'mentormarkup',
            'first_name'  => 'Mentor',
            'last_name'   => 'Markup',
            'password'    => Hash::make('passwordmentor'),
            'role'        => 'mentor',
            'institution' => 'Universitas Airlangga',
        ]);

        // Katalog: mentor/author dulu (produk mereferensikan author_id), lalu
        // produk, detail kurikulum, review, dan lomba.
        $this->call([
            MentorSeeder::class,
            ProductSeeder::class,
            ProductDetailSeeder::class,
            BareProductDetailSeeder::class,
            LearningContentSeeder::class,
            ReviewSeeder::class,
            CompetitionSeeder::class,
        ]);
    }
}
