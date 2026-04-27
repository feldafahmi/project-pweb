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
            'desc' => 'Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.',
            'tags' => ['People Development', 'Case Studies', 'Real Projects', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-award',
            'title' => 'Sertifikasi Kompetensi',
            'desc' => 'Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.',
            'tags' => ['People Development', 'Case Studies', 'Real Projects', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-crosshairs',
            'title' => 'Kurikulum Terstruktur',
            'desc' => 'Kuasai framework konsultan top (McKinsey, BCG) dengan latihan kasus nyata dari berbagai industri.',
            'tags' => ['Case Studies', 'Real Projects', 'People Development', 'Industry Focus'],
        ],
        [
            'icon' => 'fa-users',
            'title' => 'Komunitas Aktif',
            'desc' => 'Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.',
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
        <div class="absolute inset-0 z-[1]">
            <img src="{{ asset('img/hero-section-bg.svg') }}" alt="" class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-b from-navy-600/80 to-navy-600/40"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-4xl px-5 text-center text-white">
            <h1 class="mb-3 font-extrabold uppercase leading-[0.9] tracking-tight text-white"
                style="font-size: clamp(60px, 12vw, 140px); letter-spacing: -3px;">MARK-UP</h1>
            <p class="mb-5 font-bold" style="font-size: clamp(24px, 4vw, 42px);">
                Jadi <span
                    class="relative text-yellow-brand after:absolute after:bottom-1 after:left-0 after:h-1 after:w-full after:bg-yellow-brand">Juara</span>
                Bukan Sekedar
                <span
                    class="relative text-yellow-brand after:absolute after:bottom-1 after:left-0 after:h-1 after:w-full after:bg-yellow-brand">Mimpi!</span>
            </p>
            <p class="mx-auto mb-9 max-w-[700px] text-lg leading-relaxed text-white/90">
                Bergabunglah dengan ribuan mahasiswa yang telah meraih prestasi dalam business case competitions.
                Dapatkan mentoring eksklusif dari praktisi terbaik di industri.
            </p>
            <a href="{{ route('register') }}" class="btn-yellow">Mulai Jadi Juara!</a>
        </div>
    </section>

    {{-- WHY MARK-UP --}}
    <section class="bg-slate-50 py-24">
        <div class="mu-container">
            <div class="mb-12 text-center">
                <h2 class="mb-3 text-3xl font-extrabold text-navy-600 md:text-4xl">Mengapa Memilih MARK-UP?</h2>
                <p class="text-base text-slate-500">
                    Platform terlengkap untuk persiapan kompetisi bisnis dan pengembangan karir profesional
                </p>
            </div>

            <div class="grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($features as $feature)
                    <div
                        class="rounded-2xl border border-slate-100 bg-white p-8 shadow-[0_10px_30px_rgba(0,0,0,0.04)] transition-all hover:-translate-y-1 hover:shadow-[0_15px_35px_rgba(0,0,0,0.08)]">
                        <div
                            class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-600 text-2xl text-white">
                            <i class="fa-solid {{ $feature['icon'] }}"></i>
                        </div>
                        <h3 class="mb-3 text-xl font-extrabold text-navy-600">{{ $feature['title'] }}</h3>
                        <p class="mb-6 text-sm leading-relaxed text-slate-500">{{ $feature['desc'] }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($feature['tags'] as $tag)
                                <span class="rounded-full bg-yellow-100 px-3 py-1.5 text-xs font-bold text-yellow-800">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ALUMNI COMPANIES --}}
    <section class="overflow-hidden bg-white py-16">
        <div class="mu-container">
            <div class="mb-10 text-center">
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
            <div class="mb-12 text-center">
                <h2 class="mb-3 text-3xl font-extrabold text-navy-600 md:text-4xl">Mereka Telah Membuktikannya</h2>
                <p class="text-base text-slate-500">
                    Cerita sukses dari ribuan mahasiswa yang telah meraih prestasi bersama MARK-UP
                </p>
            </div>

            <div class="mb-12 grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testi)
                    <article
                        class="flex flex-col rounded-2xl border border-slate-100 bg-white p-9 shadow-[0_10px_30px_rgba(0,0,0,0.04)]">
                        <i class="fa-solid fa-quote-left mb-4 text-4xl text-transparent"
                            style="-webkit-text-stroke: 2px #f0abfc;"></i>
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
                                class="h-12 w-12 rounded-full border-2 border-fuchsia-500 object-cover">
                            <div>
                                <h4 class="text-[15px] font-bold text-navy-600">{{ $testi['name'] }}</h4>
                                <p class="text-xs text-slate-500">{{ $testi['school'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div
                class="mx-auto flex max-w-3xl flex-col items-center justify-around gap-6 rounded-2xl border border-yellow-200 bg-gradient-to-r from-pink-50 to-yellow-50 p-8 sm:flex-row">
                <div class="text-center">
                    <h3 class="mb-1 text-3xl font-extrabold text-fuchsia-600">4.9/5.0</h3>
                    <p class="text-sm font-medium text-slate-500">Average Rating</p>
                </div>
                <div class="h-px w-12 bg-slate-200 sm:h-12 sm:w-px"></div>
                <div class="text-center">
                    <h3 class="mb-1 text-3xl font-extrabold text-fuchsia-600">850+</h3>
                    <p class="text-sm font-medium text-slate-500">Total Reviews</p>
                </div>
                <div class="h-px w-12 bg-slate-200 sm:h-12 sm:w-px"></div>
                <div class="text-center">
                    <h3 class="mb-1 text-3xl font-extrabold text-fuchsia-600">98%</h3>
                    <p class="text-sm font-medium text-slate-500">Would Recommend</p>
                </div>
            </div>
        </div>
    </section>
@endsection
