<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Data per produk: [product_id => [[name, email, rating, comment], ...]]
        $reviewData = [
            // Kelas: Winner Class: Business Case Mastery (id=1)
            1 => [
                ['Rafi Pratama',   'rafi.p@example.com',   5, 'Langsung bisa dipraktekkan! Framework-nya jelas banget dan langsung relevan untuk lomba.'],
                ['Sinta Larasati', 'sinta.l@example.com',  5, 'Materi pitch deck-nya gold. Tim kami revamp deck dan masuk final setelah ikut kelas ini.'],
                ['Dimas Ariyanto', 'dimas.a@example.com',  4, 'Overall sangat bagus. Saran: video bagian QnA juri bisa diperpanjang lebih dalam.'],
                ['Nadia Permata',  'nadia.p2@example.com', 5, 'Kak Rizka menjelaskan dengan sangat sistematis. Worth every rupiah!'],
                ['Budi Santoso',   'budi.s@example.com',   5, 'Sudah coba 3 kelas sejenis, ini yang paling praktis dan actionable.'],
            ],
            // Modul: Strategi Debat Nasional (id=7)
            7 => [
                ['Ayu Dewanti',  'ayu.d@example.com',  5, 'Bab rebuttal-nya sangat komprehensif. Langsung saya aplikasikan di lomba debat kampus.'],
                ['Yoga Saputra', 'yoga.s@example.com', 5, 'Preview bab 1 bikin yakin beli. Konten bab-bab berikutnya tidak mengecewakan sama sekali.'],
                ['Putra Hendra', 'putra.h@example.com',4, 'Materinya solid dan padat. Kalau ada video pendamping pasti lebih sempurna.'],
            ],
            // Bootcamp: Business Plan 30 Hari (id=8)
            8 => [
                ['Hasna Fauziyah', 'hasna.f@example.com', 5, 'Demo day di depan juri industri adalah pengalaman yang tidak ternilai. Bootcamp terbaik!'],
                ['Arif Wicaksono', 'arif.w@example.com',  5, 'Review dokumen 1-on-1 mingguan benar-benar mengasah business plan kami secara drastis.'],
                ['Citra Maharani', 'citra.m@example.com', 5, 'Mentor-mentornya responsif dan feedback-nya konstruktif. Sangat rekomendasikan!'],
            ],
        ];

        foreach ($reviewData as $productId => $reviews) {
            foreach ($reviews as [$name, $email, $rating, $comment]) {
                $user = User::firstOrCreate(
                    ['email' => $email],
                    ['name' => $name, 'password' => Hash::make('password')]
                );

                Review::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $productId],
                    ['rating' => $rating, 'comment' => $comment]
                );
            }

            // Recompute products.rating setelah semua review satu produk diinsert
            $avg = Review::where('product_id', $productId)->avg('rating') ?? 0;
            DB::table('products')->where('id', $productId)->update(['rating' => round($avg, 2)]);
        }
    }
}
