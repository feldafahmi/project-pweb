@extends('layouts.app')

@section('title', 'Beranda')

@php
    $features = [
        [
            'icon' => 'fa-bullseye',
            'title' => 'Fokus Business Case',
            'desc' => 'Kuasai framework konsultan top (McKinsey, BCG) dengan latihan kasus nyata dari berbagai industri.',
            'tags' => ['Case Studies', 'Real Projects', 'People Development', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-user-tie',
            'title' => 'Mentor Berpengalaman',
            'desc' => 'Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.',
            'tags' => ['Case Studies', 'People Development', 'Real Projects', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-medal',
            'title' => 'Track Record Juara',
            'desc' => 'Ratusan alumni kami menjuarai kompetisi bisnis tingkat nasional hingga internasional.',
            'tags' => ['People Development', 'Case Studies', 'Real Projects', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-award',
            'title' => 'Sertifikasi Kompetensi',
            'desc' => 'Sertifikat penyelesaian yang memperkuat CV dan profil profesionalmu.',
            'tags' => ['People Development', 'Case Studies', 'Real Projects', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-crosshairs',
            'title' => 'Kurikulum Terstruktur',
            'desc' => 'Materi bertahap dari dasar hingga mahir, mengikuti alur kompetisi yang sesungguhnya.',
            'tags' => ['Case Studies', 'Real Projects', 'People Development', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-users',
            'title' => 'Komunitas Aktif',
            'desc' => 'Terhubung dengan ribuan pejuang kompetisi untuk diskusi, cari tim, dan kolaborasi.',
            'tags' => ['Case Studies', 'People Development', 'Real Projects', 'Industry Focus'],
        ],
    ];

    $companies = [
        ['src' => 'img/google.png', 'alt' => 'Google'],
        ['src' => 'img/Nvidia_logo.png', 'alt' => 'Nvidia'],
        ['src' => 'img/Spongebob-sangar.jpg', 'alt' => 'Partner'],
        ['src' => 'img/unair-horisontal.png', 'alt' => 'UNAIR'],
        ['src' => 'img/BGN_LOGO.png', 'alt' => 'BGN'],
    ];

    $testimonials = [
        [
            'name' => 'Anastasia Putri',
            'school' => 'Universitas Indonesia',
            'avatar' => 'img/4622.jpg',
            'text' => 'MARK-UP benar-benar mengubah cara saya approach business cases. Mentoring dari praktisi BCG sangat membantu saya meraih juara 1 di kompetisi nasional. Materinya applicable dan mentornya sangat supportive!',
        ],
        [
            'name' => 'Ryan Mahendra',
            'school' => 'Institut Teknologi Bandung',
            'avatar' => 'img/4620.jpg',
            'text' => 'Program Career Mentoring di MARK-UP luar biasa! Saya berhasil diterima di Big 4 consulting firm berkat persiapan interview dan CV review yang detail. Investment terbaik untuk karir saya.',
        ],
        [
            'name' => 'Michelle Tan',
            'school' => 'Universitas Gadjah Mada',
            'avatar' => 'img/4623.jpg',
            'text' => 'Framework dan case studies yang diajarkan sangat komprehensif. Dalam 2 bulan, tim saya berhasil juara di 3 kompetisi berbeda. Highly recommended untuk semua business students!',
        ],
    ];
@endphp

@section('content')
    {{-- HERO SECTION --}}
    <section
        class="relative -mt-[85px] flex min-h-[700px] w-full items-center justify-center overflow-hidden bg-navy-600"
        style="height: 100vh;">
        <div class="absolute inset-0 z-[1] overflow-hidden">
            {{-- Foto gedung (worm's-eye) --}}
            <img src="{{ asset('img/hero-home.jpg') }}" alt=""
                class="absolute inset-0 h-full w-full object-cover object-center">
            {{-- Peredup agar teks putih terbaca --}}
            <div class="absolute inset-0 bg-navy-900/70"></div>
            {{-- Aurora animasi — selaras logo & page lain (ungu/indigo/cyan) + aksen kuning brand --}}
            <span class="animate-aurora absolute -left-32 -top-32 h-[34rem] w-[34rem] rounded-full bg-[#A855F7] opacity-55 mix-blend-screen blur-3xl"
                style="animation-duration: 15s;"></span>
            <span class="animate-aurora absolute -right-28 -top-20 h-[32rem] w-[32rem] rounded-full bg-[#22D3EE] opacity-45 mix-blend-screen blur-3xl"
                style="animation-duration: 19s; animation-delay: -6s;"></span>
            <span class="animate-aurora absolute -bottom-40 left-1/4 h-[36rem] w-[36rem] rounded-full bg-[#6366F1] opacity-50 mix-blend-screen blur-3xl"
                style="animation-duration: 17s; animation-delay: -10s;"></span>
            <span class="animate-aurora absolute -bottom-32 right-1/4 h-[26rem] w-[26rem] rounded-full bg-[#f5eb5e] opacity-30 mix-blend-screen blur-3xl"
                style="animation-duration: 21s; animation-delay: -3s;"></span>
            {{-- Gradient untuk kedalaman & kontras teks --}}
            <div class="absolute inset-0 bg-gradient-to-b from-navy-900/50 via-navy-900/20 to-navy-900/80"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-4xl px-5 text-center text-white">
            {{-- Badge kaca --}}
            <div data-reveal
                class="glass mx-auto mb-7 inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-bold text-white/90 md:text-sm">
                <span class="flex h-2 w-2 rounded-full bg-yellow-brand"></span>
                #1 Platform Persiapan Kompetisi Bisnis
            </div>

            <h1 data-reveal data-reveal-delay="60"
                class="mb-3 font-extrabold uppercase leading-[0.9] tracking-tight text-white"
                style="font-size: clamp(60px, 12vw, 140px); letter-spacing: -3px;">MARK-UP</h1>
            <p data-reveal data-reveal-delay="120" class="mb-5 font-bold" style="font-size: clamp(24px, 4vw, 42px);">
                Jadi <span
                    class="relative text-yellow-brand after:absolute after:bottom-1 after:left-0 after:h-1 after:w-full after:bg-yellow-brand">Juara</span>
                Bukan Sekedar
                <span
                    class="relative text-yellow-brand after:absolute after:bottom-1 after:left-0 after:h-1 after:w-full after:bg-yellow-brand">Mimpi!</span>
            </p>
            <p data-reveal data-reveal-delay="180"
                class="mx-auto mb-9 max-w-[700px] text-lg leading-relaxed text-white/90">
                Bergabunglah dengan ribuan mahasiswa yang telah meraih prestasi dalam business case competitions.
                Dapatkan mentoring eksklusif dari praktisi terbaik di industri.
            </p>
            <div data-reveal data-reveal-delay="240" class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-yellow">Mulai Jadi Juara!</a>
                <a href="{{ route('produk') }}"
                    class="inline-flex items-center gap-2 rounded-lg border-2 border-white/40 px-8 py-4 text-lg font-bold text-white transition hover:border-white hover:bg-white/10">
                    Lihat Program <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>

        {{-- Indikator scroll-down --}}
        <a href="#kenapa-markup" aria-label="Scroll ke bawah"
            class="animate-scroll-bob absolute bottom-6 left-1/2 z-10 -translate-x-1/2 text-white/80 transition hover:text-white">
            <i class="fa-solid fa-chevron-down text-2xl"></i>
        </a>
    </section>

    {{-- TRUST BAR (glassmorphism, menimpa bawah hero) --}}
    <section class="relative z-20 -mt-16 px-5">
        <div data-reveal
            class="mx-auto grid max-w-4xl grid-cols-2 gap-6 rounded-3xl border border-slate-100 bg-white/90 p-8 shadow-[0_20px_50px_rgba(10,20,50,0.12)] backdrop-blur md:grid-cols-4">
            <div class="text-center">
                <div class="text-3xl font-extrabold text-navy-600 md:text-4xl">
                    <span data-countup="5000" data-suffix="+">0</span>
                </div>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Alumni</p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-extrabold text-navy-600 md:text-4xl">
                    <span data-countup="4.9" data-decimals="1">0</span>
                </div>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Rating</p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-extrabold text-navy-600 md:text-4xl">
                    <span data-countup="87" data-suffix="%">0</span>
                </div>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Win Rate</p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-extrabold text-navy-600 md:text-4xl">
                    <span data-countup="120" data-suffix="+">0</span>
                </div>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Kompetisi</p>
            </div>
        </div>
    </section>

    {{-- WHY MARK-UP (bento grid) --}}
    <section id="kenapa-markup" class="bg-slate-50 py-24">
        <div class="mu-container">
            <div data-reveal class="mb-14 text-center">
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.25em] text-[#A855F7]">Kenapa Kami</p>
                <h2 class="mx-auto max-w-2xl bg-gradient-to-r from-navy-600 to-[#A855F7] bg-clip-text text-3xl font-extrabold text-transparent md:text-4xl">
                    Mengapa Memilih MARK-UP?
                </h2>
                <p class="mx-auto mt-3 max-w-xl text-base text-slate-500">
                    Platform terlengkap untuk persiapan kompetisi bisnis dan pengembangan karir profesional
                </p>
            </div>

            @php
                // Peran tiap kartu di bento — menentukan ukuran & tata letak isi.
                $bento = [
                    0 => 'featured', // besar 2×2
                    1 => 'wide',     // lebar 2×1 (isi horizontal)
                    2 => 'narrow',   // kecil 1×1
                    3 => 'narrow',   // kecil 1×1
                    4 => 'wide',     // lebar 2×1
                    5 => 'wide',     // lebar 2×1
                ];
                $spanClass = [
                    'featured' => 'lg:col-span-2 lg:row-span-2',
                    'wide'     => 'lg:col-span-2',
                    'narrow'   => 'lg:col-span-1',
                ];
                // Sorotan untuk kartu besar (checklist singkat).
                $featuredPoints = ['Framework MECE, SWOT, & Porter', 'Studi kasus dari 8+ lomba nasional', 'Review pitch deck 1-on-1'];
            @endphp
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:auto-rows-fr lg:grid-cols-4">
                @foreach ($features as $i => $feature)
                    @php $kind = $bento[$i] ?? 'narrow'; @endphp

                    @if ($kind === 'featured')
                        {{-- ── Kartu besar (highlight, 2×2) ── --}}
                        <div data-reveal
                            class="group relative flex flex-col overflow-hidden rounded-3xl bg-gradient-to-br from-navy-600 to-navy-800 p-8 text-white shadow-[0_20px_50px_rgba(10,20,50,0.25)] transition-all duration-300 hover:-translate-y-1 {{ $spanClass[$kind] }}">
                            <i class="fa-solid {{ $feature['icon'] }} pointer-events-none absolute -right-6 -top-6 text-[9rem] leading-none text-white/5"></i>
                            <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-brand text-2xl text-navy-600 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6">
                                <i class="fa-solid {{ $feature['icon'] }}"></i>
                            </div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-yellow-brand">Program Unggulan</p>
                            <h3 class="mb-3 text-2xl font-extrabold">{{ $feature['title'] }}</h3>
                            <p class="mb-6 text-base leading-relaxed text-white/80">{{ $feature['desc'] }}</p>
                            <ul class="space-y-3 text-sm text-white/85">
                                @foreach ($featuredPoints as $point)
                                    <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-yellow-brand"></i> {{ $point }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route('produk') }}"
                                class="mt-auto inline-flex w-fit items-center gap-2 rounded-lg bg-yellow-brand px-6 py-3 text-sm font-bold text-navy-600 transition hover:-translate-y-0.5 hover:shadow-lg">
                                Jelajahi Program <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>

                    @elseif ($kind === 'wide')
                        {{-- ── Kartu lebar (isi horizontal, 2×1) ── --}}
                        <div data-reveal data-reveal-delay="{{ $i * 60 }}"
                            class="group flex items-start gap-5 rounded-3xl border border-slate-100 bg-white p-8 shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_18px_40px_rgba(0,0,0,0.09)] {{ $spanClass[$kind] }}">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-navy-600 to-[#A855F7] text-2xl text-white transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6">
                                <i class="fa-solid {{ $feature['icon'] }}"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="mb-2 text-xl font-extrabold text-navy-600">{{ $feature['title'] }}</h3>
                                <p class="mb-4 text-sm leading-relaxed text-slate-500">{{ $feature['desc'] }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($feature['tags'] as $tag)
                                        <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-bold text-[#A855F7]">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- ── Kartu kecil (kompak vertikal, 1×1) ── --}}
                        <div data-reveal data-reveal-delay="{{ $i * 60 }}"
                            class="group flex flex-col rounded-3xl border border-slate-100 bg-white p-7 shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_18px_40px_rgba(0,0,0,0.09)] {{ $spanClass[$kind] }}">
                            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-navy-600 to-[#A855F7] text-xl text-white transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6">
                                <i class="fa-solid {{ $feature['icon'] }}"></i>
                            </div>
                            <h3 class="mb-2 text-lg font-extrabold text-navy-600">{{ $feature['title'] }}</h3>
                            <p class="text-sm leading-relaxed text-slate-500">{{ $feature['desc'] }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- ALUMNI COMPANIES --}}
    <section class="overflow-hidden bg-white py-16">
        <div class="mu-container">
            <div data-reveal class="mb-10 text-center">
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.25em] text-[#A855F7]">Dipercaya</p>
                <h2 class="mb-3 text-2xl font-extrabold text-navy-600 md:text-3xl">
                    Dipercaya oleh Alumni dari Top Companies
                </h2>
                <p class="text-base text-slate-500">
                    Mentor dan alumni kami bekerja di perusahaan-perusahaan terkemuka dunia
                </p>
            </div>
        </div>

        <div
            class="relative w-full overflow-hidden whitespace-nowrap py-5
                    before:absolute before:left-0 before:top-0 before:z-10 before:h-full before:w-24 before:bg-gradient-to-r before:from-white before:to-transparent
                    after:absolute after:right-0 after:top-0 after:z-10 after:h-full after:w-24 after:bg-gradient-to-l after:from-white after:to-transparent">
            <div class="marquee-track inline-flex items-center gap-14">
                @foreach (array_merge($companies, $companies) as $company)
                    <img src="{{ asset($company['src']) }}" alt="{{ $company['alt'] }}"
                        class="h-16 w-auto object-contain opacity-60 transition-all hover:opacity-100 md:h-20">
                @endforeach
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="bg-slate-50 py-24">
        <div class="mu-container">
            <div data-reveal class="mb-12 text-center">
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.25em] text-[#A855F7]">Testimoni</p>
                <h2 class="mx-auto max-w-2xl bg-gradient-to-r from-navy-600 to-[#A855F7] bg-clip-text text-3xl font-extrabold text-transparent md:text-4xl">
                    Mereka Telah Membuktikannya
                </h2>
                <p class="mx-auto mt-3 max-w-xl text-base text-slate-500">
                    Cerita sukses dari ribuan mahasiswa yang telah meraih prestasi bersama MARK-UP
                </p>
            </div>

            <div class="mb-12 grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testi)
                    <article data-reveal data-reveal-delay="{{ $loop->index * 90 }}"
                        class="flex flex-col rounded-2xl border border-slate-100 bg-white p-9 shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-[0_18px_40px_rgba(0,0,0,0.09)]">
                        <i class="fa-solid fa-quote-left mb-4 text-4xl text-transparent"
                            style="-webkit-text-stroke: 2px var(--color-navy-200);"></i>
                        <div class="mb-5 flex gap-1 text-sm text-yellow-400">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                        </div>
                        <p class="mb-6 flex-1 text-[15px] italic leading-relaxed text-slate-500">
                            "{{ $testi['text'] }}"
                        </p>
                        <hr class="mb-5 border-slate-100">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset($testi['avatar']) }}" alt="{{ $testi['name'] }}"
                                class="h-12 w-12 rounded-full border-2 border-yellow-brand object-cover">
                            <div>
                                <h4 class="text-[15px] font-bold text-navy-600">{{ $testi['name'] }}</h4>
                                <p class="text-xs text-slate-500">{{ $testi['school'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div data-reveal
                class="mx-auto flex max-w-3xl flex-col items-center justify-around gap-6 rounded-2xl border border-navy-100 bg-gradient-to-r from-navy-50 to-yellow-50 p-8 sm:flex-row">
                <div class="text-center">
                    <h3 class="mb-1 text-3xl font-extrabold text-navy-600">
                        <span data-countup="4.9" data-decimals="1">0</span>/5.0
                    </h3>
                    <p class="text-sm font-medium text-slate-500">Average Rating</p>
                </div>
                <div class="h-px w-12 bg-slate-200 sm:h-12 sm:w-px"></div>
                <div class="text-center">
                    <h3 class="mb-1 text-3xl font-extrabold text-navy-600">
                        <span data-countup="850" data-suffix="+">0</span>
                    </h3>
                    <p class="text-sm font-medium text-slate-500">Total Reviews</p>
                </div>
                <div class="h-px w-12 bg-slate-200 sm:h-12 sm:w-px"></div>
                <div class="text-center">
                    <h3 class="mb-1 text-3xl font-extrabold text-navy-600">
                        <span data-countup="98" data-suffix="%">0</span>
                    </h3>
                    <p class="text-sm font-medium text-slate-500">Would Recommend</p>
                </div>
            </div>
        </div>
    </section>
@endsection
