<?php

namespace Database\Seeders;

use App\Models\BootcampBatch;
use App\Models\CurriculumItem;
use App\Models\CurriculumSection;
use App\Models\Mentor;
use App\Models\Product;
use App\Models\ProductChapter;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Mengisi detail (deskripsi, learnings, includes, kurikulum/chapters/batch,
 * review) untuk produk yang dibuat ProductSeeder tapi belum di-enrich oleh
 * ProductDetailSeeder. Semua idempotent.
 */
class BareProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        $rizka   = Mentor::where('name', 'Rizka Nabilah')->first();
        $kartika = Mentor::where('name', 'Kartika Ayu')->first();
        $dewi    = Mentor::where('name', 'Dewi Santoso')->first();

        // ── MODUL — Pitch Deck untuk Startup Kompetisi ───────────────────
        $this->enrichModul(
            'Modul Pitch Deck untuk Startup Kompetisi',
            author: $rizka,
            totalPages: 96,
            description: 'Panduan praktis menyusun pitch deck pemenang untuk kompetisi '
                . 'startup. Dari struktur 10 slide wajib, storytelling, hingga desain '
                . 'visual yang meyakinkan investor dan juri.',
            includes: [
                ['icon' => 'file',  'text' => '96 halaman PDF siap pakai'],
                ['icon' => 'book',  'text' => '6 bab + 10 template slide'],
                ['icon' => 'play',  'text' => 'Bab 1 preview gratis'],
                ['icon' => 'check', 'text' => 'Akses seumur hidup'],
                ['icon' => 'down',  'text' => 'Bisa di-download offline'],
            ],
            chapters: [
                ['01', 'Anatomi Pitch Deck Juara', '1–14', true],
                ['02', 'Problem & Solution Slide', '15–30', false],
                ['03', 'Market Size & Business Model', '31–48', false],
                ['04', 'Traction, Tim & Finansial', '49–68', false],
                ['05', 'Desain Visual & Data Viz', '69–84', false],
                ['06', 'Latihan & Studi Kasus', '85–96', false],
            ],
            reviews: [
                ['ferdi.reviewer@example.com', 'Ferdi N.', 5, 'Template slide-nya langsung saya pakai, deck jadi jauh lebih rapi.'],
                ['gita.reviewer@example.com', 'Gita R.', 4, 'Singkat tapi padat. Bab finansial paling membantu.'],
            ],
        );

        // ── MODUL — Business Model Canvas: Dari Nol ke Juara ─────────────
        $this->enrichModul(
            'Modul Business Model Canvas: Dari Nol ke Juara',
            author: $dewi,
            totalPages: 110,
            description: 'Bedah 9 blok Business Model Canvas dengan contoh nyata dari '
                . 'tim juara kompetisi bisnis. Dilengkapi worksheet untuk memetakan '
                . 'model bisnismu sendiri langkah demi langkah.',
            includes: [
                ['icon' => 'file',  'text' => '110 halaman PDF + worksheet'],
                ['icon' => 'book',  'text' => '7 bab terstruktur'],
                ['icon' => 'play',  'text' => 'Bab 1 preview gratis'],
                ['icon' => 'check', 'text' => 'Akses seumur hidup'],
                ['icon' => 'down',  'text' => 'Bisa di-download offline'],
            ],
            chapters: [
                ['01', 'Mengenal Business Model Canvas', '1–16', true],
                ['02', 'Customer Segments & Value Proposition', '17–36', false],
                ['03', 'Channels & Customer Relationships', '37–54', false],
                ['04', 'Revenue Streams & Cost Structure', '55–74', false],
                ['05', 'Key Resources, Activities & Partners', '75–92', false],
                ['06', 'Validasi & Iterasi Model', '93–104', false],
                ['07', 'Studi Kasus Tim Juara', '105–110', false],
            ],
            reviews: [
                ['hana.reviewer@example.com', 'Hana P.', 5, 'Worksheet-nya bikin paham cara mengisi BMC dengan benar.'],
                ['ivan.reviewer@example.com', 'Ivan S.', 5, 'Contoh kasusnya relevan banget untuk lomba.'],
            ],
        );

        // ── KELAS — UI/UX untuk Kompetisi Desain ─────────────────────────
        $this->enrichKelas(
            'Kelas UI/UX untuk Kompetisi Desain',
            author: $dewi,
            winRate: 81,
            description: 'Kelas intensif merancang solusi UI/UX yang memenangkan lomba '
                . 'desain: dari riset pengguna, wireframe, hingga prototype interaktif '
                . 'dan presentasi case study di depan juri.',
            learnings: [
                'Riset pengguna & definisi problem yang tajam',
                'Wireframing cepat dan terstruktur',
                'Membangun design system konsisten',
                'Prototype interaktif di Figma',
                'Menyusun case study presentasi',
                'Menjawab pertanyaan juri desain',
            ],
            includes: [
                ['icon' => 'video', 'text' => '14 video materi (6+ jam)'],
                ['icon' => 'file',  'text' => 'Template Figma + worksheet'],
                ['icon' => 'check', 'text' => 'Akses seumur hidup'],
                ['icon' => 'trophy','text' => 'Studi kasus lomba desain nyata'],
                ['icon' => 'users', 'text' => 'Komunitas desainer'],
                ['icon' => 'verified','text' => 'Sertifikat penyelesaian'],
            ],
            sections: [
                ['Fondasi UX Research', null, [
                    ['Memahami pengguna & masalah', '12 mnt', 'video'],
                    ['Teknik wawancara & survey', '15 mnt', 'video'],
                    ['Template riset', '6 hal', 'pdf'],
                ]],
                ['Desain & Wireframe', null, [
                    ['Information architecture', '14 mnt', 'video'],
                    ['Low-fi ke hi-fi wireframe', '18 mnt', 'video'],
                    ['Design system dasar', '16 mnt', 'video'],
                ]],
                ['Prototype & Presentasi', null, [
                    ['Prototype interaktif Figma', '20 mnt', 'video'],
                    ['Menyusun case study', '13 mnt', 'video'],
                    ['Simulasi presentasi juri', '22 mnt', 'video'],
                ]],
            ],
            reviews: [
                ['joko.reviewer@example.com', 'Joko W.', 5, 'Materi prototype Figma-nya gamblang, tim kami lolos final.'],
                ['kiki.reviewer@example.com', 'Kiki M.', 4, 'Bagus untuk pemula. Mau lebih banyak studi kasus lagi.'],
            ],
        );

        // ── KELAS — LKTI: Riset & Karya Tulis Ilmiah ─────────────────────
        $this->enrichKelas(
            'Kelas LKTI: Riset & Karya Tulis Ilmiah',
            author: $kartika,
            winRate: 84,
            description: 'Kelas pendampingan menulis Karya Tulis Ilmiah pemenang lomba: '
                . 'menentukan topik, metodologi riset, struktur penulisan, hingga '
                . 'teknik presentasi hasil di depan dewan juri.',
            learnings: [
                'Memilih topik & rumusan masalah',
                'Metodologi riset yang tepat',
                'Struktur penulisan KTI baku',
                'Teknik sitasi & referensi',
                'Visualisasi data hasil riset',
                'Presentasi & sidang juri',
            ],
            includes: [
                ['icon' => 'video', 'text' => '12 video materi (5+ jam)'],
                ['icon' => 'file',  'text' => 'Template KTI + checklist'],
                ['icon' => 'check', 'text' => 'Akses seumur hidup'],
                ['icon' => 'trophy','text' => 'Contoh KTI juara nasional'],
                ['icon' => 'users', 'text' => 'Grup diskusi peserta'],
                ['icon' => 'verified','text' => 'Sertifikat penyelesaian'],
            ],
            sections: [
                ['Menentukan Ide & Topik', null, [
                    ['Menggali ide riset', '11 mnt', 'video'],
                    ['Rumusan masalah & tujuan', '13 mnt', 'video'],
                    ['Template proposal', '5 hal', 'pdf'],
                ]],
                ['Metodologi & Penulisan', null, [
                    ['Memilih metode penelitian', '16 mnt', 'video'],
                    ['Struktur bab KTI', '18 mnt', 'video'],
                    ['Sitasi & daftar pustaka', '10 mnt', 'video'],
                ]],
                ['Finalisasi & Presentasi', null, [
                    ['Visualisasi data', '12 mnt', 'video'],
                    ['Menyusun slide sidang', '9 hal', 'pdf'],
                    ['Simulasi sidang juri', '24 mnt', 'video'],
                ]],
            ],
            reviews: [
                ['lina.reviewer@example.com', 'Lina K.', 5, 'Pendampingannya runut, KTI kami akhirnya juara 2 nasional.'],
                ['memet.reviewer@example.com', 'Memet A.', 5, 'Bab metodologi sangat membantu menyusun riset.'],
            ],
        );

        // ── BOOTCAMP — Business Case 30 Hari ─────────────────────────────
        $this->enrichBootcamp(
            'Bootcamp Business Case 30 Hari - Juara Nasional',
            author: $rizka,
            winRate: 90,
            description: 'Bootcamp intensif 30 hari menempa kemampuan business case '
                . 'dari nol hingga siap juara nasional. Live mentoring, review 1-on-1, '
                . 'dan simulasi penjurian dengan praktisi industri.',
            includes: [
                ['icon' => 'cal',    'text' => '30 hari program intensif'],
                ['icon' => 'users',  'text' => 'Cohort terbatas 25 peserta'],
                ['icon' => 'video',  'text' => '16× live session via Zoom'],
                ['icon' => 'check',  'text' => 'Review 1-on-1 tiap minggu'],
                ['icon' => 'trophy', 'text' => 'Simulasi penjurian praktisi'],
                ['icon' => 'verified','text' => 'Sertifikat bootcamp resmi'],
            ],
            sections: [
                ['Framework & Analisis', 'Minggu 1', [
                    ['Kick-off & studi kasus pembuka', 'Live', 'live'],
                    ['Framework analisis bisnis', 'Live', 'live'],
                    ['Riset cepat & benchmarking', 'Live', 'live'],
                    ['Review individual minggu 1', 'Live', 'live'],
                ]],
                ['Solusi & Rekomendasi', 'Minggu 2', [
                    ['Menyusun rekomendasi strategis', 'Live', 'live'],
                    ['Prioritisasi & trade-off', 'Live', 'live'],
                    ['Storytelling dengan data', 'Live', 'live'],
                    ['Review individual minggu 2', 'Live', 'live'],
                ]],
                ['Finansial & Pitch', 'Minggu 3', [
                    ['Dasar pemodelan finansial', 'Live', 'live'],
                    ['Menyusun pitch deck', 'Live', 'live'],
                    ['Mock pitch internal', 'Live', 'live'],
                    ['Review individual minggu 3', 'Live', 'live'],
                ]],
                ['Simulasi & Demo Day', 'Minggu 4', [
                    ['Simulasi lomba penuh', 'Live', 'live'],
                    ['Teknik QnA dengan juri', 'Live', 'live'],
                    ['Final pitch ke praktisi', 'Live', 'live'],
                    ['Penutupan & evaluasi', 'Live', 'live'],
                ]],
            ],
            batches: [
                ['Batch 3 — Mei 2026', '5 Mei – 3 Jun 2026', 12, 'open'],
                ['Batch 4 — Juli 2026', '7 Jul – 5 Agu 2026', 25, 'soon'],
            ],
            reviews: [
                ['nadia2.reviewer@example.com', 'Nadia W.', 5, 'Review mingguannya intens, kemampuan analisis kami melonjak.'],
                ['oki.reviewer@example.com', 'Oki R.', 5, 'Demo day di depan praktisi pengalaman terbaik. Recommended!'],
            ],
        );
    }

    /** Modul: deskripsi + includes + total_pages + chapters + reviews. */
    private function enrichModul(
        string $title,
        ?Mentor $author,
        int $totalPages,
        string $description,
        array $includes,
        array $chapters,
        array $reviews,
    ): void {
        $p = Product::where('title', $title)->first();
        if (! $p) {
            return;
        }
        $p->update([
            'description' => $description,
            'includes'    => $includes,
            'total_pages' => $totalPages,
            'author_id'   => $author?->id,
        ]);
        foreach ($chapters as $i => [$num, $t, $range, $free]) {
            ProductChapter::updateOrCreate(
                ['product_id' => $p->id, 'chapter_number' => $num],
                ['title' => $t, 'page_range' => $range, 'is_free' => $free, 'sort_order' => $i],
            );
        }
        $this->seedReviews($p->id, $reviews);
    }

    /** Kelas: deskripsi + learnings + includes + win_rate + curriculum + reviews. */
    private function enrichKelas(
        string $title,
        ?Mentor $author,
        int $winRate,
        string $description,
        array $learnings,
        array $includes,
        array $sections,
        array $reviews,
    ): void {
        $p = Product::where('title', $title)->first();
        if (! $p) {
            return;
        }
        $p->update([
            'description' => $description,
            'learnings'   => $learnings,
            'includes'    => $includes,
            'win_rate'    => $winRate,
            'author_id'   => $author?->id,
        ]);
        $this->seedSections($p->id, $sections);
        $this->seedReviews($p->id, $reviews);
    }

    /** Bootcamp: deskripsi + includes + win_rate + curriculum + batches + reviews. */
    private function enrichBootcamp(
        string $title,
        ?Mentor $author,
        int $winRate,
        string $description,
        array $includes,
        array $sections,
        array $batches,
        array $reviews,
    ): void {
        $p = Product::where('title', $title)->first();
        if (! $p) {
            return;
        }
        $p->update([
            'description' => $description,
            'includes'    => $includes,
            'win_rate'    => $winRate,
            'author_id'   => $author?->id,
        ]);
        $this->seedSections($p->id, $sections);
        foreach ($batches as [$label, $range, $spots, $status]) {
            BootcampBatch::updateOrCreate(
                ['product_id' => $p->id, 'label' => $label],
                ['date_range' => $range, 'spots' => $spots, 'status' => $status],
            );
        }
        $this->seedReviews($p->id, $reviews);
    }

    private function seedSections(int $productId, array $sections): void
    {
        foreach ($sections as $i => [$title, $subtitle, $items]) {
            $section = CurriculumSection::updateOrCreate(
                ['product_id' => $productId, 'sort_order' => $i],
                ['title' => $title, 'subtitle' => $subtitle],
            );
            foreach ($items as $j => [$itemTitle, $duration, $type]) {
                CurriculumItem::updateOrCreate(
                    ['curriculum_section_id' => $section->id, 'sort_order' => $j],
                    ['title' => $itemTitle, 'duration' => $duration, 'type' => $type],
                );
            }
        }
    }

    private function seedReviews(int $productId, array $reviews): void
    {
        foreach ($reviews as [$email, $name, $stars, $text]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make('password')],
            );
            ProductReview::updateOrCreate(
                ['product_id' => $productId, 'user_id' => $user->id],
                ['stars' => $stars, 'text' => $text],
            );
        }
    }
}
