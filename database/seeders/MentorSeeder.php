<?php

namespace Database\Seeders;

use App\Models\Mentor;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    public function run(): void
    {
        $mentors = [
            [
                'name'              => 'Rizka Nabilah',
                'title'             => 'Case Competition Champion - 3x Juara Nasional',
                'rating'            => 5.0,
                'sessions'          => 142,
                'available'         => true,
                'price_per_session' => 150000,
                'tags'              => ['Business Case', 'LKTI', 'Pitch Deck'],
                'avatar_url'        => null,
            ],
            [
                'name'              => 'Ahmad Fauzi',
                'title'             => 'Founder & CEO',
                'rating'            => 4.9,
                'sessions'          => 98,
                'available'         => true,
                'price_per_session' => 200000,
                'tags'              => ['Business Plan', 'Startup', 'MVP'],
                'avatar_url'        => null,
            ],
            [
                'name'              => 'Dewi Santoso',
                'title'             => 'Peneliti BRIN',
                'rating'            => 4.8,
                'sessions'          => 67,
                'available'         => false,
                'price_per_session' => 175000,
                'tags'              => ['LKTI', 'Karya Tulis', 'Riset'],
                'avatar_url'        => null,
            ],
        ];

        foreach ($mentors as $mentor) {
            Mentor::create($mentor);
        }
    }
}
