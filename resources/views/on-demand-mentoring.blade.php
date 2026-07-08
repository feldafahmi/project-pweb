@extends('layouts.app')

@section('title', 'On Demand Mentoring')

@php
    $idr = fn ($v) => $v > 0 ? 'Rp ' . number_format($v, 0, ',', '.') : 'GRATIS';

    // Ringkasan untuk trust-strip di hero.
    $totalMentor = $mentors->count();
    $availableCount = $mentors->where('available', true)->count();
    $totalSessions = $mentors->sum('sessions');
    $avgRating = $totalMentor ? round($mentors->avg('rating'), 1) : 0;

    // Mentor unggulan yang ditonjolkan sbg kartu di hero (mirip app mobile).
    // $mentors sudah diurutkan controller (available desc, rating desc).
    $featured = $mentors->first();
@endphp

@section('content')
    {{-- 1. BREADCRUMB STRIP --}}
    <div class="border-b border-slate-100 bg-white">
        <div class="mx-auto flex max-w-6xl items-center gap-2 px-4 py-4 text-sm text-slate-500">
            <a href="/" class="transition hover:text-[#A855F7]"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="font-semibold text-[#1A2B56]">On Demand Mentoring</span>
        </div>
    </div>

    {{-- 2. HERO SECTION — dua kolom: teks + kartu mentor unggulan (mirip app mobile) --}}
    <section class="relative flex min-h-[420px] items-center overflow-hidden bg-navy-600 md:min-h-[520px]">
        {{-- HERO: foto + aurora animasi menyala di atasnya --}}
        <div class="absolute inset-0 z-0 overflow-hidden bg-navy-800">
            {{-- Foto latar (gedung perkantoran saat senja) --}}
            <img src="{{ asset('img/hero-mentoring.jpg') }}" alt=""
                class="absolute inset-0 h-full w-full object-cover object-center">
            {{-- Peredup foto agar aurora & teks menonjol --}}
            <div class="absolute inset-0 bg-navy-900/70"></div>
            {{-- Blob aurora (mix-blend-screen → menyala di atas foto) --}}
            <span class="animate-aurora absolute -left-24 -top-28 h-96 w-96 rounded-full bg-[#A855F7] opacity-70 mix-blend-screen blur-3xl"
                style="animation-duration: 12s;"></span>
            <span class="animate-aurora absolute -right-24 -top-16 h-[30rem] w-[30rem] rounded-full bg-[#6366F1] opacity-65 mix-blend-screen blur-3xl"
                style="animation-duration: 16s; animation-delay: -5s;"></span>
            <span class="animate-aurora absolute -bottom-32 left-1/3 h-[28rem] w-[28rem] rounded-full bg-[#EC4899] opacity-60 mix-blend-screen blur-3xl"
                style="animation-duration: 14s; animation-delay: -9s;"></span>
            <span class="animate-aurora absolute -bottom-24 right-1/4 h-80 w-80 rounded-full bg-[#22D3EE] opacity-50 mix-blend-screen blur-3xl"
                style="animation-duration: 18s; animation-delay: -3s;"></span>
            {{-- Gradient bawah untuk kontras teks --}}
            <div class="absolute inset-0 bg-gradient-to-t from-navy-900/70 via-navy-900/10 to-transparent"></div>
        </div>

        <div class="relative z-10 mx-auto grid w-full max-w-6xl items-center gap-10 px-4 py-14 lg:grid-cols-2 lg:gap-12">
            {{-- Kiri: teks --}}
            <div class="text-center lg:text-left">
                <p class="mb-4 text-xs font-bold uppercase tracking-[0.25em] text-white/70 md:text-sm">On Demand Mentoring</p>
                <h1 class="mb-4 text-4xl font-extrabold leading-tight text-white drop-shadow-sm md:text-5xl">Booking Mentor Kapan Saja</h1>
                <p class="mx-auto mb-8 max-w-xl text-base leading-relaxed text-white/90 lg:mx-0 md:text-lg">
                    Pilih mentor sesuai kebutuhanmu dan pesan sesi 1-on-1 secara instan
                </p>

                {{-- Trust strip: horizontal, 3 kolom sama lebar (count-up saat masuk viewport) --}}
                <div class="mx-auto flex max-w-md items-start divide-x divide-white/20 text-white lg:mx-0">
                    <div class="flex-1 px-3 text-center">
                        <p class="text-2xl font-black md:text-3xl" data-countup="{{ $totalMentor }}">0</p>
                        <p class="mt-1 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wide text-white/60 md:text-[11px]">Mentor Ahli</p>
                    </div>
                    <div class="flex-1 px-3 text-center">
                        <p class="text-2xl font-black md:text-3xl">
                            <span data-countup="{{ $avgRating }}" data-decimals="1">0.0</span><span class="text-amber-300">★</span>
                        </p>
                        <p class="mt-1 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wide text-white/60 md:text-[11px]">Rating Rata-rata</p>
                    </div>
                    <div class="flex-1 px-3 text-center">
                        <p class="text-2xl font-black md:text-3xl" data-countup="{{ $totalSessions }}" data-suffix="+">0</p>
                        <p class="mt-1 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wide text-white/60 md:text-[11px]">Sesi Selesai</p>
                    </div>
                </div>
            </div>

            {{-- Kanan: kartu mentor unggulan --}}
            @if ($featured)
                <div class="flex justify-center lg:justify-end">
                    <div data-reveal data-reveal-delay="120"
                        class="group relative w-full max-w-sm overflow-hidden rounded-3xl border border-white/50 bg-white/95 shadow-[0_30px_60px_-15px_rgba(10,20,50,0.55)] backdrop-blur">
                        {{-- Aksen atas --}}
                        <div class="h-1.5 w-full bg-gradient-to-r from-[#A855F7] via-[#6366F1] to-[#22D3EE]"></div>

                        <div class="p-6">
                            {{-- Baris atas: badge unggulan + status --}}
                            <div class="mb-5 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-3 py-1 text-[11px] font-bold text-[#A855F7]">
                                    <i class="fa-solid fa-star"></i> Mentor Pilihan
                                </span>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold {{ $featured->available ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="fa-solid fa-circle text-[6px]"></i> {{ $featured->available ? 'Tersedia' : 'Penuh' }}
                                </span>
                            </div>

                            {{-- Identitas --}}
                            <div class="flex items-center gap-4">
                                <img src="{{ asset($featured->avatar_url ?? 'img/mentor1.png') }}" alt="{{ $featured->name }}"
                                    class="h-20 w-20 shrink-0 rounded-2xl object-cover ring-2 ring-slate-100">
                                <div class="min-w-0">
                                    <h3 class="truncate text-lg font-bold leading-tight text-[#1A2B56]">{{ $featured->name }}</h3>
                                    <p class="mt-0.5 line-clamp-2 text-sm text-slate-500">{{ $featured->title }}</p>
                                </div>
                            </div>

                            {{-- Meta --}}
                            <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1.5 font-bold text-amber-500">
                                    <i class="fa-solid fa-star"></i> {{ number_format($featured->rating, 1) }}
                                </span>
                                <span class="text-slate-200">•</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-users text-slate-300"></i> {{ $featured->sessions }} sesi
                                </span>
                                @if ($featured->response_time)
                                    <span class="text-slate-200">•</span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-bolt text-slate-300"></i> {{ $featured->response_time }}
                                    </span>
                                @endif
                            </div>

                            {{-- Tags --}}
                            @if (!empty($featured->tags))
                                <div class="mt-4 flex flex-wrap gap-1.5">
                                    @foreach (array_slice($featured->tags, 0, 3) as $tag)
                                        <span class="rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-semibold text-[#A855F7]">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Harga + CTA --}}
                            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mulai dari</p>
                                    <p class="text-lg font-black text-[#1A2B56]">{{ $idr($featured->price_per_session) }}<span class="text-xs font-medium text-slate-400">/sesi</span></p>
                                </div>
                                <button
                                    onclick='openMentorModal(@json($featured->name), @json($featured->title), @json($featured->about), @json($featured->highlights ?? []), @json($featured->tags ?? []), @json($featured->response_time), {{ $featured->rating }}, {{ $featured->sessions }}, {{ $featured->price_per_session }}, {{ $featured->available ? 'true' : 'false' }}, @json(asset($featured->avatar_url ?? "img/mentor1.png")))'
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-[#1A2B56] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#A855F7] active:scale-95">
                                    Booking <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <div class="mx-auto max-w-6xl px-4 pb-24">

        {{-- 3. FILTER + JUMLAH HASIL --}}
        <div class="sticky top-[85px] z-30 -mx-4 mb-10 border-b border-slate-100 bg-[#FAFAF7]/90 px-4 py-4 backdrop-blur md:static md:mx-0 md:mt-12 md:rounded-2xl md:border md:border-slate-100 md:bg-white md:px-6 md:shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap gap-2.5">
                    <button onclick="filterMentor('Semua', this)"
                        class="mentor-filter-btn rounded-full bg-[#A855F7] px-5 py-2 text-sm font-bold text-white shadow-lg shadow-purple-200 transition-all duration-300">
                        <i class="fa-solid fa-layer-group mr-1.5"></i>Semua Mentor
                    </button>
                    <button onclick="filterMentor('available', this)"
                        class="mentor-filter-btn rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-bold text-[#1A2B56] transition-all duration-300 hover:bg-slate-50">
                        <span class="mr-1.5 inline-block h-2 w-2 rounded-full bg-green-500 align-middle"></span>Tersedia Sekarang
                    </button>
                </div>
                <p class="text-sm text-slate-500">
                    <span id="mentor-count" class="font-bold text-[#1A2B56]">{{ $totalMentor }}</span> mentor ditemukan
                </p>
            </div>
        </div>

        {{-- 4. GRID KARTU MENTOR --}}
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">

            @forelse ($mentors as $mentor)
            {{-- Wrapper: reveal saat scroll (transform terpisah dari hover-lift kartu) --}}
            <div data-available="{{ $mentor->available ? 'available' : 'unavailable' }}"
                data-reveal data-reveal-delay="{{ ($loop->index % 3) * 90 }}"
                class="mentor-card">
            <div class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white transition-all duration-300 hover:-translate-y-2 hover:border-purple-100 hover:shadow-[0_20px_50px_-12px_rgba(168,85,247,0.25)]">

                {{-- Accent bar --}}
                <div class="h-1.5 w-full bg-gradient-to-r from-[#A855F7] via-[#6366F1] to-[#22D3EE] opacity-80"></div>

                <div class="flex flex-1 flex-col p-6">
                    {{-- Header: avatar + status --}}
                    <div class="mb-4 flex items-start justify-between">
                        <div class="h-20 w-20 shrink-0">
                            <img src="{{ asset($mentor->avatar_url ?? 'img/mentor1.png') }}" alt="{{ $mentor->name }}"
                                class="h-full w-full rounded-2xl object-cover ring-2 ring-slate-100 transition group-hover:ring-purple-200">
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold {{ $mentor->available ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-400' }}">
                            <i class="fa-solid fa-circle text-[6px]"></i> {{ $mentor->available ? 'Tersedia' : 'Penuh' }}
                        </span>
                    </div>

                    {{-- Identitas --}}
                    <h3 class="truncate text-lg font-bold leading-tight text-[#1A2B56] transition group-hover:text-[#A855F7]">{{ $mentor->name }}</h3>
                    <p class="mb-4 mt-1 line-clamp-2 min-h-[2.5rem] text-sm text-slate-500">{{ $mentor->title }}</p>

                    {{-- Rating + sesi --}}
                    <div class="mb-4 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-slate-500">
                        <span class="inline-flex items-center gap-1.5 font-bold text-amber-500">
                            <i class="fa-solid fa-star"></i> {{ number_format($mentor->rating, 1) }}
                        </span>
                        <span class="text-slate-200">•</span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-slate-300"></i> {{ $mentor->sessions }} sesi
                        </span>
                        @if ($mentor->response_time)
                            <span class="text-slate-200">•</span>
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-bolt text-slate-300"></i> {{ $mentor->response_time }}
                            </span>
                        @endif
                    </div>

                    {{-- Tags (baris selalu dirender agar tinggi kartu konsisten) --}}
                    <div class="mb-5 flex min-h-[1.75rem] flex-wrap gap-1.5">
                        @forelse (array_slice($mentor->tags ?? [], 0, 3) as $tag)
                            <span class="rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-semibold text-[#A855F7]">{{ $tag }}</span>
                        @empty
                            <span class="rounded-full bg-slate-50 px-2.5 py-1 text-[10px] font-semibold text-slate-400">Mentoring umum</span>
                        @endforelse
                    </div>

                    {{-- Footer: harga + CTA --}}
                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mulai dari</p>
                            <p class="text-base font-black text-[#1A2B56]">{{ $idr($mentor->price_per_session) }}<span class="text-xs font-medium text-slate-400">/sesi</span></p>
                        </div>
                        <button
                            onclick='openMentorModal(@json($mentor->name), @json($mentor->title), @json($mentor->about), @json($mentor->highlights ?? []), @json($mentor->tags ?? []), @json($mentor->response_time), {{ $mentor->rating }}, {{ $mentor->sessions }}, {{ $mentor->price_per_session }}, {{ $mentor->available ? 'true' : 'false' }}, @json(asset($mentor->avatar_url ?? "img/mentor1.png")))'
                            class="inline-flex items-center gap-1.5 rounded-xl bg-[#1A2B56] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#A855F7] active:scale-95">
                            Lihat Detail <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </button>
                    </div>
                </div>
            </div>{{-- /inner card --}}
            </div>{{-- /reveal wrapper --}}
            @empty
            <div class="col-span-full py-16 text-center text-slate-400">
                <i class="fa-solid fa-user-group mb-4 block text-5xl text-slate-300"></i>
                <p class="text-lg font-semibold text-slate-500">Belum Ada Mentor</p>
                <p class="text-sm text-slate-400">Mentor on demand akan segera tersedia di sini.</p>
            </div>
            @endforelse

            {{-- Client-side filter empty state --}}
            <div id="no-mentor-results" class="col-span-full hidden py-16 text-center text-slate-400">
                <i class="fa-solid fa-magnifying-glass mb-4 block text-5xl text-slate-300"></i>
                <p class="text-lg font-semibold text-slate-500">Tidak Ada Mentor</p>
                <p class="text-sm text-slate-400">Tidak ada mentor yang tersedia saat ini. Coba lagi nanti.</p>
            </div>
        </div>
    </div>

    {{-- 5. MODAL DETAIL & BOOKING MENTOR --}}
    <div id="mentorModal"
        class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-md transition-all">
        <div class="relative flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl md:flex-row">

            <button onclick="closeMentorModal()"
                class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 text-slate-800 shadow-md transition hover:bg-red-500 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>

            {{-- Sisi kiri: identitas --}}
            <div class="flex w-full flex-col items-center bg-gradient-to-b from-[#1A2B56] to-[#2d1a56] p-8 text-center text-white md:w-5/12">
                <img id="modalMentorImage" src="" alt="" class="mb-4 h-28 w-28 rounded-2xl object-cover ring-4 ring-white/20">
                <h2 id="modalMentorName" class="text-xl font-black leading-tight">Nama Mentor</h2>
                <p id="modalMentorTitle" class="mt-1 text-sm text-white/70">Titel</p>

                {{-- Status ketersediaan --}}
                <span id="modalMentorStatus" class="mt-4 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold"></span>

                <div class="mt-6 grid w-full grid-cols-2 gap-3">
                    <div class="rounded-xl bg-white/10 p-3">
                        <p id="modalMentorRating" class="text-lg font-black text-amber-300">5.0</p>
                        <p class="text-[10px] uppercase tracking-wider text-white/60">Rating</p>
                    </div>
                    <div class="rounded-xl bg-white/10 p-3">
                        <p id="modalMentorSessions" class="text-lg font-black">0</p>
                        <p class="text-[10px] uppercase tracking-wider text-white/60">Sesi</p>
                    </div>
                </div>

                {{-- Waktu respon (tampil bila tersedia) --}}
                <div id="modalMentorResponseWrap" class="mt-3 hidden w-full items-center justify-center gap-2 rounded-xl bg-white/10 p-3 text-xs text-white/80">
                    <i class="fa-solid fa-bolt text-[#22D3EE]"></i>
                    <span>Respon rata-rata <span id="modalMentorResponse" class="font-bold text-white"></span></span>
                </div>
            </div>

            {{-- Sisi kanan: detail (scroll bila konten panjang) --}}
            <div class="flex w-full min-h-0 flex-col overflow-y-auto p-8 md:w-7/12">
                <div id="modalMentorAboutWrap" class="mb-6">
                    <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Tentang Mentor</h4>
                    <p id="modalMentorAbout" class="text-sm leading-relaxed text-slate-600"></p>
                </div>

                <div id="modalMentorHighlightsWrap" class="mb-6">
                    <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Keunggulan</h4>
                    <ul id="modalMentorHighlights" class="space-y-1.5 text-sm text-slate-600"></ul>
                </div>

                <div id="modalMentorTagsWrap" class="mb-6">
                    <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Bidang Keahlian</h4>
                    <div id="modalMentorTags" class="flex flex-wrap gap-1.5"></div>
                </div>

                <div class="mt-auto border-t border-slate-100 pt-6">
                    <div class="mb-4 flex items-end justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Biaya per sesi</span>
                        <span id="modalMentorPrice" class="text-2xl font-black text-[#1A2B56]">Rp 0</span>
                    </div>
                    <a id="modalMentorBook" href="#" target="_blank"
                        class="flex h-14 w-full items-center justify-center rounded-2xl bg-[#1A2B56] text-lg font-bold text-white shadow-lg transition hover:bg-[#A855F7] hover:scale-[1.02] active:scale-95">
                        Booking Sesi Sekarang <i class="fa-solid fa-arrow-right ml-3 text-sm"></i>
                    </a>
                    <p id="modalMentorUnavailable" class="mt-2 hidden text-center text-xs font-semibold text-slate-400">
                        Mentor sedang penuh — coba lagi nanti.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Nomor WhatsApp admin untuk booking (ganti sesuai kebutuhan).
        const MENTOR_BOOKING_WA = '6281234567890';
        const modal = document.getElementById('mentorModal');

        const rupiah = (v) => v > 0 ? 'Rp ' + Number(v).toLocaleString('id-ID') : 'GRATIS';

        function openMentorModal(name, title, about, highlights, tags, responseTime, rating, sessions, price, available, image) {
            document.getElementById('modalMentorImage').src = image;
            document.getElementById('modalMentorName').innerText = name;
            document.getElementById('modalMentorTitle').innerText = title;
            document.getElementById('modalMentorRating').innerText = Number(rating).toFixed(1);
            document.getElementById('modalMentorSessions').innerText = sessions;
            document.getElementById('modalMentorPrice').innerText = rupiah(price);

            // Status ketersediaan
            const statusEl = document.getElementById('modalMentorStatus');
            if (available) {
                statusEl.className = 'mt-4 inline-flex items-center gap-1.5 rounded-full bg-green-400/20 px-3 py-1 text-xs font-bold text-green-300';
                statusEl.innerHTML = '<i class="fa-solid fa-circle text-[6px]"></i> Tersedia untuk booking';
            } else {
                statusEl.className = 'mt-4 inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-white/60';
                statusEl.innerHTML = '<i class="fa-solid fa-circle text-[6px]"></i> Jadwal penuh';
            }

            // Waktu respon
            const respWrap = document.getElementById('modalMentorResponseWrap');
            if (responseTime) {
                document.getElementById('modalMentorResponse').innerText = responseTime;
                respWrap.classList.remove('hidden');
                respWrap.classList.add('flex');
            } else {
                respWrap.classList.add('hidden');
                respWrap.classList.remove('flex');
            }

            // Tentang — tampilkan fallback agar panel tidak kosong.
            document.getElementById('modalMentorAbout').innerText = about
                ? about
                : `${name} siap membantumu lewat sesi mentoring 1-on-1 yang personal dan terarah.`;

            // Highlights — dukung dua format: string biasa ATAU objek {icon, text}
            // (format yang dipakai app Flutter).
            const hlWrap = document.getElementById('modalMentorHighlightsWrap');
            const hlList = document.getElementById('modalMentorHighlights');
            hlList.innerHTML = '';
            const items = (Array.isArray(highlights) ? highlights : [])
                .map(h => (h && typeof h === 'object')
                    ? { icon: h.icon || 'circle-check', text: h.text || '' }
                    : { icon: 'circle-check', text: String(h) })
                .filter(h => h.text.trim() !== '');
            if (items.length) {
                items.forEach(h => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start gap-2.5';
                    const icon = document.createElement('i');
                    icon.className = `fa-solid fa-${h.icon} mt-0.5 w-4 shrink-0 text-center text-[#A855F7]`;
                    const span = document.createElement('span');
                    span.innerText = h.text;
                    li.append(icon, span);
                    hlList.appendChild(li);
                });
                hlWrap.classList.remove('hidden');
            } else {
                hlWrap.classList.add('hidden');
            }

            // Tags
            const tagWrap = document.getElementById('modalMentorTagsWrap');
            const tagBox = document.getElementById('modalMentorTags');
            tagBox.innerHTML = '';
            if (Array.isArray(tags) && tags.length) {
                tags.forEach(t => {
                    const span = document.createElement('span');
                    span.className = 'rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-[#A855F7]';
                    span.innerText = t;
                    tagBox.appendChild(span);
                });
                tagWrap.classList.remove('hidden');
            } else {
                tagWrap.classList.add('hidden');
            }

            // CTA Booking
            const bookBtn = document.getElementById('modalMentorBook');
            const unavailableNote = document.getElementById('modalMentorUnavailable');
            if (available) {
                const msg = encodeURIComponent(`Halo, saya ingin booking sesi On Demand Mentoring bersama ${name} (${title}).`);
                bookBtn.href = `https://wa.me/${MENTOR_BOOKING_WA}?text=${msg}`;
                bookBtn.classList.remove('pointer-events-none', 'opacity-50');
                unavailableNote.classList.add('hidden');
            } else {
                bookBtn.href = '#';
                bookBtn.classList.add('pointer-events-none', 'opacity-50');
                unavailableNote.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeMentorModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeMentorModal();
        });

        // Filter ketersediaan mentor (client-side)
        function filterMentor(filter, btn) {
            document.querySelectorAll('.mentor-filter-btn').forEach(el => {
                el.className = 'mentor-filter-btn rounded-full border border-slate-200 bg-white px-6 py-2 text-sm font-bold text-[#1A2B56] transition-all duration-300 hover:bg-slate-50';
            });
            btn.className = 'mentor-filter-btn rounded-full bg-[#A855F7] px-6 py-2 text-sm font-bold text-white shadow-lg shadow-purple-200 transition-all duration-300';

            let visible = 0;
            document.querySelectorAll('.mentor-card').forEach(card => {
                const match = filter === 'Semua' || card.dataset.available === filter;
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            const count = document.getElementById('mentor-count');
            if (count) count.innerText = visible;

            const noResults = document.getElementById('no-mentor-results');
            if (noResults) noResults.classList.toggle('hidden', visible !== 0);
        }
    </script>
@endpush
