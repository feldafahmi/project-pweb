@extends('layouts.app')

@php
    $idr = fn ($v) => $v > 0 ? 'Rp ' . number_format($v, 0, ',', '.') : 'GRATIS';
    $date = fn ($v) => $v ? \Carbon\Carbon::parse($v)->isoFormat('D MMM') : '-';
    $fullDate = fn ($v) => $v ? \Carbon\Carbon::parse($v)->isoFormat('D MMMM Y') : '-';

    if (!function_exists('uppercase')) {
        function uppercase($str) {
            return strtoupper($str);
        }
    }
@endphp

@section('content')
    {{-- 1. BREADCRUMBS --}}
    <div class="mx-auto max-w-6xl px-4 mt-6">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="/" class="transition hover:text-[#1A2B56]"><i class="fa-solid fa-house"></i></a>
            <span>/</span>
            <span class="text-slate-400 font-medium">Info Lomba</span>
        </nav>
    </div>

    {{-- 2. HERO SECTION --}}
    <section
        class="relative mb-12 mt-4 flex h-[250px] items-center justify-center overflow-hidden rounded-3xl mx-auto max-w-6xl bg-[#1A2B56]">
        {{-- Background Image (Bisa diisi nanti) --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/hero-info-lomba.png') }}" alt="" class="h-full w-full object-cover opacity-30">
        </div>

        <div class="relative z-10 px-4 text-center">
            <h1 class="mb-3 text-3xl font-black text-white md:text-5xl">Eksplor Kompetisi</h1>
            <p class="text-slate-200 md:text-lg max-w-xl mx-auto text-sm">
                Temukan peluang untuk mengasah skill dan membangun portofolio juara di sini.
            </p>
        </div>
    </section>

    <div class="mx-auto max-w-6xl px-4 pb-24">

        {{-- 3. FILTER KATEGORI LOMBA --}}
        <div class="mb-12 flex flex-wrap justify-center gap-3">
            <button
                onclick="filterCategory('Semua', this)"
                class="filter-btn rounded-full bg-[#A855F7] px-6 py-2 text-sm font-bold text-white shadow-lg shadow-purple-200 transition-all duration-300">Semua</button>
            <button
                onclick="filterCategory('Business Case', this)"
                class="filter-btn rounded-full bg-white border border-slate-200 px-6 py-2 text-sm font-bold text-[#1A2B56] hover:bg-slate-50 transition-all duration-300">Business Case</button>
            <button
                onclick="filterCategory('Business Plan', this)"
                class="filter-btn rounded-full bg-white border border-slate-200 px-6 py-2 text-sm font-bold text-[#1A2B56] hover:bg-slate-50 transition-all duration-300">Business Plan</button>
            <button
                onclick="filterCategory('Business Model Canvas', this)"
                class="filter-btn rounded-full bg-white border border-slate-200 px-6 py-2 text-sm font-bold text-[#1A2B56] hover:bg-slate-50 transition-all duration-300">Business Canvas</button>
            <button
                onclick="filterCategory('UI/UX', this)"
                class="filter-btn rounded-full bg-white border border-slate-200 px-6 py-2 text-sm font-bold text-[#1A2B56] hover:bg-slate-50 transition-all duration-300">UI/UX</button>
            <button
                onclick="filterCategory('LKTI', this)"
                class="filter-btn rounded-full bg-white border border-slate-200 px-6 py-2 text-sm font-bold text-[#1A2B56] hover:bg-slate-50 transition-all duration-300">LKTI</button>
        </div>

        {{-- 4. GRID KARTU LOMBA --}}
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">

            @forelse ($competitions as $competition)
            <div onclick="openLombaModal('{{ addslashes($competition->title) }}', '{{ addslashes($competition->organizer) }}', '{{ $fullDate($competition->end_date) }}', 'Kategori: {{ $competition->category }} | Target: {{ $competition->target_audience }}', '{{ $competition->link_pendaftaran }}', '{{ asset($competition->image_url ?? 'img/iyref.jpeg') }}')"
                data-category="{{ $competition->category }}"
                class="competition-card group cursor-pointer flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">

                <div class="relative h-48 w-full overflow-hidden bg-slate-200">
                    <img src="{{ asset($competition->image_url ?? 'img/iyref.jpeg') }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                    {{-- Badge Deadline --}}
                    <div
                        class="absolute top-4 left-4 rounded-lg bg-red-500 px-3 py-1 text-[10px] font-bold text-white shadow-lg">
                        <i class="fa-solid fa-clock mr-1"></i> DEADLINE: {{ uppercase($date($competition->end_date)) }}
                    </div>
                </div>

                <div class="flex flex-grow flex-col p-6">
                    <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-[#A855F7]">{{ $competition->category }}</p>
                    <h3 class="mb-2 text-lg font-bold leading-tight text-[#1A2B56] group-hover:text-[#A855F7] transition">
                        {{ $competition->title }}</h3>
                    <p class="text-xs text-slate-400 mb-4"><i class="fa-solid fa-building mr-1"></i>
                        {{ $competition->organizer }}</p>
                    <div class="mt-auto pt-4 border-t border-slate-50 flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-700">{{ $idr($competition->registration_fee) }}</span>
                        <span class="text-purple-600 font-bold">Lihat Detail <i
                                class="fa-solid fa-arrow-right ml-1 text-[10px]"></i></span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center text-slate-400">
                <i class="fa-solid fa-folder-open text-5xl mb-4 block text-slate-300"></i>
                <p class="text-lg font-semibold text-slate-500">Belum Ada Kompetisi</p>
                <p class="text-sm text-slate-400">Info kompetisi yang aktif akan segera muncul di sini.</p>
            </div>
            @endforelse

            {{-- Client-side filter empty state --}}
            <div id="no-filtered-results" class="col-span-full py-16 text-center text-slate-400 hidden">
                <i class="fa-solid fa-magnifying-glass text-5xl mb-4 block text-slate-300"></i>
                <p class="text-lg font-semibold text-slate-500">Tidak Ada Kompetisi</p>
                <p class="text-sm text-slate-400">Tidak ada kompetisi aktif untuk kategori ini.</p>
            </div>
        </div>
    </div>

    {{-- 5. MODAL POP-UP INFO LOMBA --}}
    <div id="lombaModal"
        class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-md transition-all">
        <div
            class="relative flex w-full max-w-4xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl md:flex-row animate-in fade-in zoom-in duration-300">

            {{-- Close Button --}}
            <button onclick="closeLombaModal()"
                class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 text-slate-800 shadow-md hover:bg-red-500 hover:text-white transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            {{-- Modal Image Side --}}
            <div class="h-56 w-full bg-slate-200 md:h-auto md:w-5/12">
                <img id="modalLombaImage" src="" alt="Detail Lomba" class="h-full w-full object-cover">
            </div>

            {{-- Modal Content Side --}}
            <div class="flex w-full flex-col p-8 md:w-7/12">
                <p id="modalLombaCategory" class="text-xs font-bold uppercase tracking-widest text-[#A855F7] mb-2">Kategori
                    Lomba</p>
                <h2 id="modalLombaTitle" class="mb-2 text-2xl font-black text-[#1A2B56] leading-tight">Nama Kompetisi</h2>
                <p id="modalLombaOrg" class="text-sm text-slate-400 mb-6 font-medium">Penyelenggara</p>

                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-3 text-sm text-slate-700">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Deadline Pendaftaran</p>
                            <p id="modalLombaDeadline" class="font-bold">25 Mei 2024</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Singkat</h4>
                        <p id="modalLombaDesc" class="text-sm leading-relaxed text-slate-600">
                            Keterangan mengenai lomba akan muncul di sini secara dinamis.
                        </p>
                    </div>
                </div>

                <div class="mt-auto border-t border-slate-100 pt-6">
                    <a id="modalLombaLink" href="#" target="_blank"
                        class="flex h-14 w-full items-center justify-center rounded-2xl bg-[#1A2B56] text-lg font-bold text-white shadow-lg transition hover:bg-[#A855F7] hover:scale-[1.02] active:scale-95">
                        Kunjungi Website Lomba <i class="fa-solid fa-arrow-up-right-from-square ml-3 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * LOGIKA MODAL INFO LOMBA
         * Fungsi ini menerima parameter agar satu modal bisa dipakai semua kartu
         */
        const modal = document.getElementById('lombaModal');

        function openLombaModal(title, organizer, deadline, desc, link, imageSrc) {
            // Isi data ke dalam modal
            document.getElementById('modalLombaTitle').innerText = title;
            document.getElementById('modalLombaOrg').innerText = organizer;
            document.getElementById('modalLombaDeadline').innerText = deadline;
            document.getElementById('modalLombaDesc').innerText = desc;
            document.getElementById('modalLombaLink').href = link;
            document.getElementById('modalLombaImage').src = imageSrc;

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLombaModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal saat klik area luar
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeLombaModal();
            }
        });

        /**
         * LOGIKA FILTER KATEGORI LOMBA
         * Fungsi ini memfilter kartu kompetisi berdasarkan kategori secara client-side
         */
        function filterCategory(category, btn) {
            // Reset style semua tombol filter
            document.querySelectorAll('.filter-btn').forEach(el => {
                el.className = "filter-btn rounded-full bg-white border border-slate-200 px-6 py-2 text-sm font-bold text-[#1A2B56] hover:bg-slate-50 transition-all duration-300";
            });

            // Set style tombol filter yang diklik menjadi aktif
            btn.className = "filter-btn rounded-full bg-[#A855F7] px-6 py-2 text-sm font-bold text-white shadow-lg shadow-purple-200 transition-all duration-300";

            // Filter kartu kompetisi
            let visibleCount = 0;
            document.querySelectorAll('.competition-card').forEach(card => {
                if (category === 'Semua' || card.dataset.category === category) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Tampilkan pesan kosong jika tidak ada kartu yang cocok
            const noResults = document.getElementById('no-filtered-results');
            if (noResults) {
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }
    </script>
@endpush
