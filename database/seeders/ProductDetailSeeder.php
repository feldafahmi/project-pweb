<?php

namespace Database\Seeders;

use App\Models\BootcampBatch;
use App\Models\CurriculumItem;
use App\Models\CurriculumSection;
use App\Models\Mentor;
use App\Models\MentorReview;
use App\Models\MentorSlot;
use App\Models\Product;
use App\Models\ProductChapter;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        // ── Pastikan author tambahan untuk modul ada (idempotent) ────────
        $kartika = Mentor::firstOrCreate(
            ['name' => 'Kartika Ayu'],
            [
                'title'             => 'Penulis & Mentor LKTI Nasional',
                'rating'            => 4.8,
                'sessions'          => 54,
                'available'         => true,
                'price_per_session' => 175000,
                'tags'              => ['LKTI', 'Karya Tulis', 'Debat'],
                'avatar_url'        => null,
            ]
        );

        $rizka = Mentor::where('name', 'Rizka Nabilah')->first();

        // ── Update detail mentor Rizka (idempotent via update) ───────────
        if ($rizka) {
            $rizka->update([
                'about' => 'Halo, saya Rizka! Saya sudah membantu 142+ peserta lolos final '
                         . 'kompetisi business case nasional. Spesialisasi saya: framework '
                         . 'analisis, struktur pitch deck, dan strategi tanya-jawab juri.',
                'highlights' => [
                    ['icon' => 'bolt',   'text' => 'Respon cepat — rata-rata 2 jam'],
                    ['icon' => 'video',  'text' => 'Sesi via Zoom + rekaman tersedia'],
                    ['icon' => 'trophy', 'text' => '3× juara nasional case competition'],
                    ['icon' => 'users',  'text' => '142+ mentee dengan rating 5.0'],
                ],
                'response_time' => '< 2j',
            ]);

            // Mentor slots — idempotent via updateOrCreate (mentor_id + day + time unik)
            $slots = [
                ['day' => 'Sen, 27 Apr', 'time' => '09:00', 'duration' => '60 mnt', 'date' => '2026-04-27'],
                ['day' => 'Sen, 27 Apr', 'time' => '14:00', 'duration' => '60 mnt', 'date' => '2026-04-27'],
                ['day' => 'Sel, 28 Apr', 'time' => '10:00', 'duration' => '90 mnt', 'date' => '2026-04-28'],
                ['day' => 'Rab, 29 Apr', 'time' => '13:00', 'duration' => '60 mnt', 'date' => '2026-04-29'],
                ['day' => 'Kam, 30 Apr', 'time' => '15:30', 'duration' => '60 mnt', 'date' => '2026-04-30'],
            ];
            foreach ($slots as $slot) {
                MentorSlot::updateOrCreate(
                    [
                        'mentor_id' => $rizka->id,
                        'day'       => $slot['day'],
                        'time'      => $slot['time'],
                    ],
                    [
                        'duration'  => $slot['duration'],
                        'is_booked' => false,
                        'date'      => $slot['date'],
                    ]
                );
            }

            // Mentor reviews — pakai user_id null biar tidak collision dgn auth user lain
            $reviewerNadia = User::firstOrCreate(
                ['email' => 'nadia.reviewer@example.com'],
                ['name' => 'Nadia P.', 'password' => Hash::make('password')]
            );
            $reviewerBima = User::firstOrCreate(
                ['email' => 'bima.reviewer@example.com'],
                ['name' => 'Bima R.', 'password' => Hash::make('password')]
            );

            MentorReview::updateOrCreate(
                ['mentor_id' => $rizka->id, 'user_id' => $reviewerNadia->id],
                ['stars' => 5, 'text' => 'Mentor terbaik! Sesinya jelas, langsung praktek, hasil tim kami juara.']
            );
            MentorReview::updateOrCreate(
                ['mentor_id' => $rizka->id, 'user_id' => $reviewerBima->id],
                ['stars' => 5, 'text' => 'Feedback Kak Rizka tajam banget. Pitch deck kami dirombak total dan menang.']
            );
        }

        // ── 1. KELAS — Winner Class: Business Case Mastery ───────────────
        $kelas = Product::where('title', 'Winner Class: Business Case Mastery')->first();
        if ($kelas) {
            $kelas->update([
                'description' => 'Kelas ini dirancang khusus untuk mahasiswa yang serius '
                               . 'memenangkan kompetisi business case tingkat nasional. '
                               . 'Kamu akan belajar framework analisis, structuring pitch '
                               . 'deck, hingga strategi tanya-jawab juri — dari juara '
                               . 'nasional yang sudah membantu 1200+ peserta.',
                'learnings' => [
                    'Analisis kasus dengan framework profesional (MECE, SWOT, Porter)',
                    'Membangun solusi yang memenangkan juri',
                    'Membuat pitch deck yang clear, memorable, dan persuasif',
                    'Strategi presentasi & body language saat di depan juri',
                    'Tanya-jawab juri: cara handle pertanyaan tricky',
                    'Studi kasus dari 8 lomba besar tahun 2025',
                ],
                'includes' => [
                    ['icon' => 'video', 'text' => '16 video materi (8+ jam total)'],
                    ['icon' => 'file',  'text' => '12 modul PDF + template siap pakai'],
                    ['icon' => 'check', 'text' => 'Akses seumur hidup'],
                    ['icon' => 'trophy','text' => 'Sertifikat penyelesaian'],
                    ['icon' => 'users', 'text' => 'Komunitas alumni eksklusif'],
                    ['icon' => 'clock', 'text' => 'Update materi 2× setahun'],
                ],
                'win_rate'  => 87,
                'students'  => 1247,
                'duration'  => '8+ jam',
                'author_id' => $rizka?->id,
            ]);

            // Curriculum sections + items — idempotent via updateOrCreate
            $sections = [
                [
                    'title'    => 'Fondasi Business Case',
                    'subtitle' => null,
                    'items'    => [
                        ['title' => 'Apa itu Business Case Competition?', 'duration' => '8 mnt',  'type' => 'video'],
                        ['title' => 'Anatomi soal & ekspektasi juri',     'duration' => '12 mnt', 'type' => 'video'],
                        ['title' => 'Modul Pendahuluan (PDF)',            'duration' => '12 hal', 'type' => 'pdf'],
                        ['title' => 'Studi kasus: kemenangan finalis 2024','duration' => '15 mnt','type' => 'video'],
                    ],
                ],
                [
                    'title'    => 'Framework Analisis',
                    'subtitle' => null,
                    'items'    => [
                        ['title' => 'MECE Principle untuk problem solving', 'duration' => '14 mnt', 'type' => 'video'],
                        ['title' => 'SWOT vs Porter — kapan pakai apa',     'duration' => '11 mnt', 'type' => 'video'],
                        ['title' => 'Template framework analysis',          'duration' => '8 hal',  'type' => 'pdf'],
                        ['title' => 'Latihan: bedah kasus startup',         'duration' => '20 mnt', 'type' => 'video'],
                    ],
                ],
                [
                    'title'    => 'Struktur Solusi & Pitch Deck',
                    'subtitle' => null,
                    'items'    => [
                        ['title' => 'Story arc dalam pitch deck',         'duration' => '13 mnt', 'type' => 'video'],
                        ['title' => 'Visual hierarchy & data viz',        'duration' => '17 mnt', 'type' => 'video'],
                        ['title' => 'Template Pitch Deck (Keynote/PPT)',  'duration' => '24 hal', 'type' => 'pdf'],
                        ['title' => 'Review pitch deck juara nasional',   'duration' => '22 mnt', 'type' => 'video'],
                        ['title' => 'Common mistakes',                    'duration' => '9 mnt',  'type' => 'video'],
                    ],
                ],
                [
                    'title'    => 'Presentasi & Tanya-Jawab Juri',
                    'subtitle' => null,
                    'items'    => [
                        ['title' => 'Body language & vokal',                  'duration' => '10 mnt', 'type' => 'video'],
                        ['title' => 'Handling pertanyaan tricky',             'duration' => '16 mnt', 'type' => 'video'],
                        ['title' => 'Worksheet QnA',                          'duration' => '6 hal',  'type' => 'pdf'],
                        ['title' => 'Simulasi penjurian (rekaman live demo)', 'duration' => '28 mnt', 'type' => 'video'],
                    ],
                ],
            ];
            foreach ($sections as $i => $sec) {
                $section = CurriculumSection::updateOrCreate(
                    ['product_id' => $kelas->id, 'sort_order' => $i],
                    ['title' => $sec['title'], 'subtitle' => $sec['subtitle']]
                );
                foreach ($sec['items'] as $j => $item) {
                    CurriculumItem::updateOrCreate(
                        ['curriculum_section_id' => $section->id, 'sort_order' => $j],
                        $item
                    );
                }
            }

            // Reviews
            $reviewerRafi = User::firstOrCreate(
                ['email' => 'rafi.reviewer@example.com'],
                ['name' => 'Rafi P.', 'password' => Hash::make('password')]
            );
            $reviewerSinta = User::firstOrCreate(
                ['email' => 'sinta.reviewer@example.com'],
                ['name' => 'Sinta L.', 'password' => Hash::make('password')]
            );
            $reviewerDimas = User::firstOrCreate(
                ['email' => 'dimas.reviewer@example.com'],
                ['name' => 'Dimas A.', 'password' => Hash::make('password')]
            );

            ProductReview::updateOrCreate(
                ['product_id' => $kelas->id, 'user_id' => $reviewerRafi->id],
                ['stars' => 5, 'text' => 'Langsung bisa dipraktekkan! Tim kami menang regional setelah ikut kelas ini.']
            );
            ProductReview::updateOrCreate(
                ['product_id' => $kelas->id, 'user_id' => $reviewerSinta->id],
                ['stars' => 5, 'text' => 'Penjelasan Kak Rizka jelas banget, framework-nya benar-benar dipakai juri.']
            );
            ProductReview::updateOrCreate(
                ['product_id' => $kelas->id, 'user_id' => $reviewerDimas->id],
                ['stars' => 4, 'text' => 'Materinya padat. Beberapa bagian bisa lebih singkat, tapi overall worth it.']
            );
        }

        // ── 2. MODUL — Strategi Debat Nasional ──────────────────────────
        $modul = Product::firstOrCreate(
            ['title' => 'Strategi Debat Nasional'],
            [
                'type'           => 'modul',
                'rating'         => 4.7,
                'students'       => 612,
                'price'          => 79000,
                'original_price' => null,
                'duration'       => '145 hal',
                'is_featured'    => false,
                'is_bestseller'  => false,
                'image_url'      => 'https://picsum.photos/seed/modul-debat/600/400',
            ]
        );
        $modul->update([
            'description' => 'Modul lengkap untuk persiapan kompetisi debat tingkat nasional. '
                           . 'Mencakup teknik argumentasi, struktur pidato, riset isu kontemporer, '
                           . 'hingga strategi rebuttal. Disusun oleh juara LKTI nasional.',
            'includes' => [
                ['icon' => 'file',   'text' => '145 halaman PDF + lampiran'],
                ['icon' => 'book',   'text' => '7 bab terstruktur'],
                ['icon' => 'play',   'text' => 'Bab 1 preview gratis'],
                ['icon' => 'check',  'text' => 'Akses seumur hidup'],
                ['icon' => 'down',   'text' => 'Bisa di-download offline'],
            ],
            'total_pages' => 145,
            'author_id'   => $kartika->id,
        ]);

        $chapters = [
            ['chapter_number' => '01', 'title' => 'Pengantar Dunia Debat Kompetitif',  'page_range' => '1–18',    'is_free' => true],
            ['chapter_number' => '02', 'title' => 'Struktur Argumen yang Solid',       'page_range' => '19–38',   'is_free' => false],
            ['chapter_number' => '03', 'title' => 'Riset Isu Kontemporer',             'page_range' => '39–62',   'is_free' => false],
            ['chapter_number' => '04', 'title' => 'Teknik Rebuttal & Counter-Argument','page_range' => '63–86',   'is_free' => false],
            ['chapter_number' => '05', 'title' => 'Public Speaking & Vokal',           'page_range' => '87–104',  'is_free' => false],
            ['chapter_number' => '06', 'title' => 'Simulasi Lomba Lengkap',            'page_range' => '105–128', 'is_free' => false],
            ['chapter_number' => '07', 'title' => 'Studi Kasus: Final LKTI 2025',      'page_range' => '129–145', 'is_free' => false],
        ];
        foreach ($chapters as $i => $ch) {
            ProductChapter::updateOrCreate(
                ['product_id' => $modul->id, 'chapter_number' => $ch['chapter_number']],
                array_merge($ch, ['sort_order' => $i])
            );
        }

        $reviewerAyu = User::firstOrCreate(
            ['email' => 'ayu.reviewer@example.com'],
            ['name' => 'Ayu D.', 'password' => Hash::make('password')]
        );
        $reviewerYoga = User::firstOrCreate(
            ['email' => 'yoga.reviewer@example.com'],
            ['name' => 'Yoga S.', 'password' => Hash::make('password')]
        );
        $reviewerPutra = User::firstOrCreate(
            ['email' => 'putra.reviewer@example.com'],
            ['name' => 'Putra H.', 'password' => Hash::make('password')]
        );

        ProductReview::updateOrCreate(
            ['product_id' => $modul->id, 'user_id' => $reviewerAyu->id],
            ['stars' => 5, 'text' => 'Modulnya komprehensif. Bab tentang rebuttal sangat membantu.']
        );
        ProductReview::updateOrCreate(
            ['product_id' => $modul->id, 'user_id' => $reviewerYoga->id],
            ['stars' => 5, 'text' => 'Bab 1 preview-nya bikin saya yakin beli. Konten berikutnya tidak mengecewakan.']
        );
        ProductReview::updateOrCreate(
            ['product_id' => $modul->id, 'user_id' => $reviewerPutra->id],
            ['stars' => 4, 'text' => 'Ilmunya padat. Saran: tambahkan video pelengkap untuk simulasi.']
        );

        // ── 3. BOOTCAMP — Business Plan 30 Hari ─────────────────────────
        $bootcamp = Product::firstOrCreate(
            ['title' => 'Business Plan 30 Hari'],
            [
                'type'           => 'bootcamp',
                'rating'         => 4.9,
                'students'       => 230,
                'price'          => 1499000,
                'original_price' => 1999000,
                'duration'       => '30 Hari',
                'is_featured'    => false,
                'is_bestseller'  => false,
                'image_url'      => 'https://picsum.photos/seed/bootcamp-bp/600/400',
            ]
        );
        $bootcamp->update([
            'description' => 'Program intensif 30 hari untuk membangun business plan '
                           . 'siap-juara dari nol. Live mentoring 4× seminggu, review '
                           . 'dokumen 1-on-1, dan simulasi presentasi di depan juri tamu.',
            'includes' => [
                ['icon' => 'cal',    'text' => '30 hari program intensif'],
                ['icon' => 'users',  'text' => 'Cohort terbatas: 20 peserta'],
                ['icon' => 'video',  'text' => '16× live session via Zoom'],
                ['icon' => 'check',  'text' => 'Review dokumen 1-on-1 tiap minggu'],
                ['icon' => 'trophy', 'text' => 'Demo day + juri tamu industri'],
                ['icon' => 'file',   'text' => 'Template + workbook lengkap'],
            ],
            'win_rate' => 92,
        ]);

        $batches = [
            ['label' => 'Batch 5 — Mei 2026',  'date_range' => '5 Mei – 2 Jun 2026',  'spots' => 8,  'status' => 'open'],
            ['label' => 'Batch 6 — Juni 2026', 'date_range' => '2 Jun – 30 Jun 2026', 'spots' => 20, 'status' => 'soon'],
        ];
        foreach ($batches as $b) {
            BootcampBatch::updateOrCreate(
                ['product_id' => $bootcamp->id, 'label' => $b['label']],
                $b
            );
        }

        $bootcampSections = [
            [
                'title' => 'Fondasi & Validasi Ide', 'subtitle' => 'Minggu 1',
                'items' => [
                    ['title' => 'Kick-off & assessment awal',         'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Problem-solution fit framework',     'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Validasi pasar via customer interview','duration' => 'Live','type' => 'live'],
                    ['title' => 'Review individual minggu 1',         'duration' => 'Live', 'type' => 'live'],
                ],
            ],
            [
                'title' => 'Business Model & Unit Economics', 'subtitle' => 'Minggu 2',
                'items' => [
                    ['title' => 'Business Model Canvas mendalam',     'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Pricing strategy & unit economics',  'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Competitive analysis',                'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Review individual minggu 2',         'duration' => 'Live', 'type' => 'live'],
                ],
            ],
            [
                'title' => 'Finansial & Proyeksi', 'subtitle' => 'Minggu 3',
                'items' => [
                    ['title' => 'Financial modeling fundamentals', 'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Proyeksi 3 tahun + skenario',      'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Funding & cap table',              'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Review individual minggu 3',       'duration' => 'Live', 'type' => 'live'],
                ],
            ],
            [
                'title' => 'Pitch & Demo Day', 'subtitle' => 'Minggu 4',
                'items' => [
                    ['title' => 'Pitch deck mastery',           'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Mock pitch + feedback round',  'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Latihan QnA dengan juri tamu', 'duration' => 'Live', 'type' => 'live'],
                    ['title' => 'Demo Day — pitch ke industri', 'duration' => 'Live', 'type' => 'live'],
                ],
            ],
        ];
        foreach ($bootcampSections as $i => $sec) {
            $section = CurriculumSection::updateOrCreate(
                ['product_id' => $bootcamp->id, 'sort_order' => $i],
                ['title' => $sec['title'], 'subtitle' => $sec['subtitle']]
            );
            foreach ($sec['items'] as $j => $item) {
                CurriculumItem::updateOrCreate(
                    ['curriculum_section_id' => $section->id, 'sort_order' => $j],
                    $item
                );
            }
        }

        $reviewerHasna = User::firstOrCreate(
            ['email' => 'hasna.reviewer@example.com'],
            ['name' => 'Hasna F.', 'password' => Hash::make('password')]
        );
        $reviewerArif = User::firstOrCreate(
            ['email' => 'arif.reviewer@example.com'],
            ['name' => 'Arif W.', 'password' => Hash::make('password')]
        );

        ProductReview::updateOrCreate(
            ['product_id' => $bootcamp->id, 'user_id' => $reviewerHasna->id],
            ['stars' => 5, 'text' => 'Bootcamp paling worth it. Demo day di depan juri industri sangat berharga.']
        );
        ProductReview::updateOrCreate(
            ['product_id' => $bootcamp->id, 'user_id' => $reviewerArif->id],
            ['stars' => 5, 'text' => 'Mentoring 1-on-1 mingguan benar-benar mengasah business plan kami.']
        );
    }
}
