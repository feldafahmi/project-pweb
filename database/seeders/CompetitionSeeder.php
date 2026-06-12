<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        // 3 lomba per kategori (15 total) memakai CompetitionFactory,
        // supaya filter kategori di halaman Info Lomba juga ada isinya.
        $categories = [
            'Business Case',
            'Business Plan',
            'Business Model Canvas',
            'UI/UX',
            'LKTI',
        ];

        foreach ($categories as $category) {
            Competition::factory()->count(3)->create(['category' => $category]);
        }
    }
}
