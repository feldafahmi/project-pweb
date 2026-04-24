<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompetitionFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Business Case',
            'Business Plan',
            'Business Model Canvas',
            'UI/UX',
            'LKTI',
        ];

        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate   = $this->faker->dateTimeBetween($startDate, '+4 months');

        return [
            'title'             => $this->faker->sentence(4),
            'category'          => $this->faker->randomElement($categories),
            'start_date'        => $startDate->format('Y-m-d'),
            'end_date'          => $endDate->format('Y-m-d'),
            'target_audience'   => $this->faker->randomElement(['Mahasiswa', 'Umum']),
            'registration_fee'  => $this->faker->randomElement([0, 50000, 75000, 100000, 150000]),
            'total_prize'       => $this->faker->randomElement([
                5_000_000, 10_000_000, 25_000_000, 50_000_000, 100_000_000,
            ]),
            'organizer'         => $this->faker->company(),
            'image_url'         => 'https://picsum.photos/seed/' . $this->faker->word() . '/600/400',
            'link_pendaftaran'  => $this->faker->url(),
        ];
    }
}
