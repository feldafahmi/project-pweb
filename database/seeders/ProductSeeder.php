<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'type'           => 'kelas',
                'title'          => 'Winner Class: Business Case Mastery',
                'rating'         => 4.9,
                'students'       => 1200,
                'price'          => 299000,
                'original_price' => 399000,
                'duration'       => '12 Jam',
                'is_featured'    => true,
                'is_bestseller'  => true,
                'image_url'      => 'https://picsum.photos/seed/winner-class/600/400',
            ],
            [
                'type'           => 'modul',
                'title'          => 'Modul Pitch Deck untuk Startup Kompetisi',
                'rating'         => 4.7,
                'students'       => 850,
                'price'          => 49000,
                'original_price' => null,
                'duration'       => '5 Bab',
                'is_featured'    => false,
                'is_bestseller'  => false,
                'image_url'      => 'https://picsum.photos/seed/modul-pitch/600/400',
            ],
            [
                'type'           => 'modul',
                'title'          => 'Modul Business Model Canvas: Dari Nol ke Juara',
                'rating'         => 4.8,
                'students'       => 1100,
                'price'          => 59000,
                'original_price' => null,
                'duration'       => '6 Bab',
                'is_featured'    => false,
                'is_bestseller'  => true,
                'image_url'      => 'https://picsum.photos/seed/modul-bmc/600/400',
            ],
            [
                'type'           => 'kelas',
                'title'          => 'Kelas UI/UX untuk Kompetisi Desain',
                'rating'         => 4.6,
                'students'       => 540,
                'price'          => 179000,
                'original_price' => null,
                'duration'       => '10 Jam',
                'is_featured'    => false,
                'is_bestseller'  => false,
                'image_url'      => 'https://picsum.photos/seed/kelas-uiux/600/400',
            ],
            [
                'type'           => 'kelas',
                'title'          => 'Kelas LKTI: Riset & Karya Tulis Ilmiah',
                'rating'         => 4.8,
                'students'       => 720,
                'price'          => 149000,
                'original_price' => null,
                'duration'       => '8 Jam',
                'is_featured'    => false,
                'is_bestseller'  => true,
                'image_url'      => 'https://picsum.photos/seed/kelas-lkti/600/400',
            ],
            [
                'type'           => 'bootcamp',
                'title'          => 'Bootcamp Business Case 30 Hari - Juara Nasional',
                'rating'         => 4.9,
                'students'       => 320,
                'price'          => 899000,
                'original_price' => null,
                'duration'       => '30 Hari',
                'is_featured'    => false,
                'is_bestseller'  => false,
                'image_url'      => 'https://picsum.photos/seed/bootcamp-bc/600/400',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
