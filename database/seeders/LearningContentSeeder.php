<?php

namespace Database\Seeders;

use App\Models\CurriculumItem;
use App\Models\ProductChapter;
use Illuminate\Database\Seeder;

/**
 * Mengisi URL konten demo (video/pdf/live) ke item kurikulum & chapter modul
 * yang sudah ada, agar fitur "akses konten setelah beli" bisa dites end-to-end.
 *
 * Sesuai keputusan: konten di-embed dari layanan eksternal (YouTube, Google
 * Drive / PDF publik, Google Meet). Idempotent — aman dijalankan berulang.
 *
 * Aturan preview gratis (is_free): item PERTAMA per produk dibuka sebagai
 * preview, sisanya terkunci sampai user membeli.
 */
class LearningContentSeeder extends Seeder
{
    // URL demo publik yang bisa langsung dibuka di browser/app.
    private const DEMO = [
        'video' => 'https://www.youtube.com/watch?v=aircAruvnKk',
        'pdf'   => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        'live'  => 'https://meet.google.com/landing',
    ];

    public function run(): void
    {
        // ── Curriculum items (kelas / bootcamp) ──────────────────────────
        $seenProducts = [];
        CurriculumItem::with('section')->orderBy('curriculum_section_id')->orderBy('sort_order')
            ->get()
            ->each(function (CurriculumItem $item) use (&$seenProducts) {
                $productId = $item->section?->product_id;
                // Item pertama yang ditemui untuk tiap produk → preview gratis.
                $isFirst = $productId !== null && ! in_array($productId, $seenProducts, true);
                if ($isFirst) {
                    $seenProducts[] = $productId;
                }

                $item->update([
                    'content_url' => self::DEMO[$item->type] ?? self::DEMO['video'],
                    'is_free'     => $isFirst ? true : $item->is_free,
                ]);
            });

        // ── Chapters (modul) — file PDF per bab; is_free sudah di-seed ────
        ProductChapter::all()->each(function (ProductChapter $ch) {
            $ch->update(['file_url' => self::DEMO['pdf']]);
        });
    }
}
