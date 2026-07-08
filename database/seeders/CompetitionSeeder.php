<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        // 3 lomba per kategori (15 total) memakai data statis deterministik —
        // sengaja TIDAK memakai factory/Faker agar seeder tetap jalan di
        // produksi (composer install --no-dev tidak menyertakan Faker).
        $categories = [
            'Business Case',
            'Business Plan',
            'Business Model Canvas',
            'UI/UX',
            'LKTI',
        ];

        $audiences  = ['Mahasiswa', 'Umum'];
        $fees       = [0, 50000, 75000, 100000, 150000];
        $prizes     = [5_000_000, 10_000_000, 25_000_000, 50_000_000, 100_000_000];
        $organizers = [
            'Universitas Airlangga',
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'BEM FEB',
            'Startup Hub Indonesia',
        ];

        // Poster lomba yang tersedia di public/img — dipakai bergiliran agar
        // tiap kompetisi tampil berbeda (sebelumnya semua memakai iyref.jpeg).
        $images = [
            'img/iyref.jpeg',
            'img/ignite.jpeg',
            'img/nbcc.jpeg',
            'img/npbc.jpeg',
            'img/dreamcareer.jpeg',
            'img/ascend.jpg',
            'img/loreal.jpg',
            'img/hsbc.png',
        ];

        $seq = 0;
        foreach ($categories as $ci => $category) {
            for ($i = 1; $i <= 3; $i++) {
                $start = Carbon::now()->addDays(($ci * 10) + ($i * 5));
                $end   = (clone $start)->addDays(30 + ($i * 10));
                $pick  = $ci + $i;

                Competition::create([
                    'title'            => "{$category} Competition #{$i}",
                    'category'         => $category,
                    'start_date'       => $start->format('Y-m-d'),
                    'end_date'         => $end->format('Y-m-d'),
                    'target_audience'  => $audiences[$pick % count($audiences)],
                    'registration_fee' => $fees[$pick % count($fees)],
                    'total_prize'      => $prizes[$pick % count($prizes)],
                    'organizer'        => $organizers[$pick % count($organizers)],
                    'image_url'        => $images[$seq % count($images)],
                    'link_pendaftaran' => 'https://example.com/daftar',
                ]);

                $seq++;
            }
        }
    }
}
