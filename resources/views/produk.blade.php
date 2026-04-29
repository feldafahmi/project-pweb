@extends('layouts.app')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative mb-10 flex h-[300px] items-center justify-center overflow-hidden bg-slate-800">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/Hero-Info-Produk.png') }}" alt="Background Hero Produk"
                class="h-full w-full object-cover opacity-40">
        </div>

        <div class="relative z-10 px-4 text-center">
            <h1 class="mb-3 text-4xl font-extrabold text-white md:text-5xl">Produk Mark-Up</h1>
            <p class="text-base text-slate-200 md:text-lg">
                Pilih program yang sudah terbukti membantu memenangkan berbagai kompetisi
            </p>
        </div>
    </section>
    {{-- AKHIR HERO SECTION --}}


    <div class="mx-auto max-w-5xl px-4 pb-20">

        <div class="mb-8 mt-2 flex items-center gap-2 text-sm text-slate-500">
            <a href="/" class="transition hover:text-[#1A2B56]"><i class="fa-solid fa-house"></i></a>
            <span>></span>
            <span class="text-slate-400">Produk</span>
        </div>

        <div class="mb-10 flex flex-wrap justify-center gap-3">
            <button
                class="rounded-full bg-[#A855F7] px-6 py-2 text-sm font-bold text-white transition hover:bg-purple-600">Semua</button>
            <button
                class="rounded-full bg-slate-100 px-6 py-2 text-sm font-bold text-[#1A2B56] transition hover:bg-slate-200">Kelas</button>
            <button
                class="rounded-full bg-slate-100 px-6 py-2 text-sm font-bold text-[#1A2B56] transition hover:bg-slate-200">Live
                Bootcamp</button>
            <button
                class="rounded-full bg-slate-100 px-6 py-2 text-sm font-bold text-[#1A2B56] transition hover:bg-slate-200">Modul</button>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

            {{-- CARD --}}
            <div onclick="openModal()"
                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="h-56 w-full overflow-hidden bg-slate-200">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 1"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                </div>
                <div class="flex flex-grow flex-col p-6">
                    <h3 class="mb-2 text-lg font-bold leading-tight text-[#1A2B56] line-clamp-2">Bundle: Winner Class dan
                        Module (Debate)</h3>
                    <p class="mb-5 flex-grow text-sm leading-relaxed text-slate-500 line-clamp-3">
                        Gabungan materi Winner Class (Video on Demand) + modul eksklusif Tips & Trik Juara Lomba UI/UX yang
                        dirancang langsung untuk kamu yang ingin menang.
                    </p>
                    <div class="border-t border-slate-100 pt-4">
                        <span class="block text-xs text-slate-400 line-through">149.000</span>
                        <span class="block text-xl font-extrabold text-[#A855F7]">99.000</span>
                    </div>
                </div>
            </div>

            {{-- CARD --}}
            <div onclick="openModal()"
                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="h-56 w-full overflow-hidden bg-slate-200">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 2"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                </div>
                <div class="flex flex-grow flex-col p-6">
                    <h3 class="mb-2 text-lg font-bold leading-tight text-[#1A2B56] line-clamp-2">Business Case Mastery Class
                    </h3>
                    <p class="mb-5 flex-grow text-sm leading-relaxed text-slate-500 line-clamp-3">
                        Pelajari framework top tier consulting firm (McKinsey, BCG, Bain) untuk memecahkan kasus bisnis
                        kompleks dalam hitungan menit.
                    </p>
                    <div class="border-t border-slate-100 pt-4">
                        <span class="block text-xs text-slate-400 line-through">299.000</span>
                        <span class="block text-xl font-extrabold text-[#A855F7]">199.000</span>
                    </div>
                </div>
            </div>

            {{-- CARD --}}
            <div onclick="openModal()"
                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="h-56 w-full overflow-hidden bg-slate-200">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 3"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                </div>
                <div class="flex flex-grow flex-col p-6">
                    <h3 class="mb-2 text-lg font-bold leading-tight text-[#1A2B56] line-clamp-2">Pitch Deck Design Secrets
                    </h3>
                    <p class="mb-5 flex-grow text-sm leading-relaxed text-slate-500 line-clamp-3">
                        Modul komprehensif cara mendesain slide presentasi yang persuasif dan memukau dewan juri kompetisi
                        bisnis.
                    </p>
                    <div class="border-t border-slate-100 pt-4">
                        <span class="block text-xs text-slate-400 line-through">99.000</span>
                        <span class="block text-xl font-extrabold text-[#A855F7]">49.000</span>
                    </div>
                </div>
            </div>

            {{-- CARD --}}
            <div onclick="openModal()"
                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="h-56 w-full overflow-hidden bg-slate-200">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 4"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                </div>
                <div class="flex flex-grow flex-col p-6">
                    <h3 class="mb-2 text-lg font-bold leading-tight text-[#1A2B56] line-clamp-2">Live Mentoring: Mock
                        Interview</h3>
                    <p class="mb-5 flex-grow text-sm leading-relaxed text-slate-500 line-clamp-3">
                        Simulasi wawancara 1-on-1 dengan mentor expert dari Big 4 Company lengkap dengan feedback tertulis.
                    </p>
                    <div class="border-t border-slate-100 pt-4">
                        <span class="block text-xs text-slate-400 line-through">499.000</span>
                        <span class="block text-xl font-extrabold text-[#A855F7]">349.000</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="productModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="relative flex w-full max-w-4xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl md:flex-row">

            <button onclick="closeModal()"
                class="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="h-64 w-full bg-slate-200 md:h-auto md:w-1/2">
                <img src="{{ asset('img/63815.png') }}" alt="Detail Produk" class="h-full w-full object-cover">
            </div>

            <div class="flex w-full flex-col justify-between p-6 md:w-1/2 md:p-8">
                <div>
                    <h2 class="mb-4 text-2xl font-bold text-[#1A2B56]">Bundle: Winner Class dan Module (Debate)</h2>

                    <div class="mb-5">
                        <div class="mb-2 flex items-center gap-2">
                            <div class="h-5 w-1 rounded-full bg-yellow-400"></div>
                            <h3 class="font-semibold text-slate-800">Deskripsi Bundling</h3>
                        </div>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Gabungan materi Winner Class (Video on Demand) + modul eksklusif Tips & Trik Juara Lomba UI/UX
                            yang dirancang langsung untuk kamu yang ingin menang, bukan sekadar ikut lomba.
                        </p>
                    </div>

                    <div class="mb-6">
                        <div class="mb-3 flex items-center gap-2">
                            <div class="h-5 w-1 rounded-full bg-yellow-400"></div>
                            <h3 class="font-semibold text-slate-800">Produk Dalam Bundling</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="flex items-center gap-2 rounded-lg border border-slate-100 bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-700">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-yellow-300 text-xs font-bold text-yellow-900">1</span>
                                Business Case Class
                            </span>
                            <span
                                class="flex items-center gap-2 rounded-lg border border-slate-100 bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-700">
                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-yellow-300 text-xs font-bold text-yellow-900">2</span>
                                UI/UX Design Class
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-t border-purple-100 pt-5">
                    <div class="mb-4">
                        <p class="text-sm text-slate-400 line-through">149.000</p>
                        <p class="text-3xl font-extrabold text-[#A855F7]">99.000</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border-2 border-[#A855F7] text-[#A855F7] transition hover:bg-purple-50">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                        </button>
                        <button
                            class="flex h-12 w-full items-center justify-center rounded-xl bg-[#A855F7] text-lg font-bold italic text-white shadow-lg shadow-purple-200 transition hover:bg-purple-600">
                            Beli Bundling Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * LOGIKA MODAL DETAIL PRODUK
         * Menggunakan manipulasi class Tailwind 'hidden' dan 'flex'
         */
        const modal = document.getElementById('productModal');

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Disable scroll pada body agar tidak 'leaking' saat modal terbuka
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Kembalikan fungsi scroll
            document.body.style.overflow = 'auto';
        }

        // Event Listener: Menutup modal jika area di luar kotak putih (overlay) diklik
        modal.addEventListener('click', function(e) {
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
@endpush
