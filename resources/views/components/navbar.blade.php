@php
    // Nav utama: item aktif tampil sbg pill (ikon + label), item lain sbg tombol ikon.
    $navLinks = [
        ['label' => 'Beranda', 'href' => url('/'), 'icon' => 'fa-house', 'active' => request()->is('/')],
        ['label' => 'Info Lomba', 'href' => route('lomba'), 'icon' => 'fa-trophy', 'active' => request()->routeIs('lomba')],
        ['label' => 'Mentoring', 'href' => route('mentoring'), 'icon' => 'fa-chalkboard-user', 'active' => request()->routeIs('mentoring')],
        ['label' => 'Produk', 'href' => route('produk'), 'icon' => 'fa-bag-shopping', 'active' => request()->routeIs('produk')],
    ];
    $aboutActive = request()->routeIs('about') || request()->routeIs('about.mentor');

    // Kelas dipakai berulang → didefinisikan sekali agar konsisten.
    $pill = 'inline-flex items-center gap-2 rounded-full bg-[#A855F7] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-purple-200 transition';
    $iconBtn = 'inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-navy-600 transition hover:bg-slate-200 hover:text-[#A855F7]';
@endphp

<nav class="fixed inset-x-0 top-0 z-50 flex h-[85px] items-center bg-white shadow-[0_2px_10px_rgba(0,0,0,0.03)]">
    <div class="mu-container flex w-full items-center gap-6">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex shrink-0">
            <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="h-[60px] w-auto">
        </a>

        {{-- ===== Desktop nav (pill aktif + tombol ikon) ===== --}}
        <ul class="hidden items-center gap-2 lg:flex">
            @foreach ($navLinks as $link)
                <li>
                    @if ($link['active'])
                        <a href="{{ $link['href'] }}" class="{{ $pill }}">
                            <i class="fa-solid {{ $link['icon'] }}"></i>{{ $link['label'] }}
                        </a>
                    @else
                        <a href="{{ $link['href'] }}" title="{{ $link['label'] }}" aria-label="{{ $link['label'] }}"
                            class="{{ $iconBtn }}">
                            <i class="fa-solid {{ $link['icon'] }}"></i>
                        </a>
                    @endif
                </li>
            @endforeach

            {{-- Tentang Kami (dropdown) --}}
            <li class="group relative">
                @if ($aboutActive)
                    <button type="button" class="{{ $pill }}">
                        <i class="fa-solid fa-circle-info"></i>Tentang Kami
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                    </button>
                @else
                    <button type="button" title="Tentang Kami" aria-label="Tentang Kami"
                        class="inline-flex h-11 items-center gap-1.5 rounded-full bg-slate-100 px-3.5 text-navy-600 transition hover:bg-slate-200 hover:text-[#A855F7]">
                        <i class="fa-solid fa-circle-info"></i>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                    </button>
                @endif

                <ul class="invisible absolute left-0 top-full z-50 mt-2 min-w-[230px] translate-y-2 rounded-2xl bg-white py-2 opacity-0 shadow-[0_12px_34px_rgba(0,0,0,0.1)] transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                    <li>
                        <a href="{{ route('about') }}"
                            class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-[#A855F7]">
                            <i class="fa-solid fa-building w-4 text-center text-slate-400"></i>Profil Perusahaan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about.mentor') }}"
                            class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-[#A855F7]">
                            <i class="fa-solid fa-user-tie w-4 text-center text-slate-400"></i>Profil Mentor
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        {{-- ===== Kanan: keranjang + auth + toggle ===== --}}
        <div class="ml-auto flex shrink-0 items-center gap-3">
            {{-- Keranjang — hanya tampil saat sudah login (dikontrol via JS) --}}
            <a href="{{ route('dashboard.cart') }}" data-nav-cart aria-label="Keranjang"
                class="relative hidden h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-navy-600 transition hover:bg-slate-200 hover:text-[#A855F7]">
                <i class="fas fa-cart-shopping"></i>
                <span data-cart-count
                    class="absolute -right-0.5 -top-0.5 hidden h-5 min-w-5 items-center justify-center rounded-full bg-[#A855F7] px-1 text-[10px] font-bold leading-none text-white">0</span>
            </a>

            <div class="hidden items-center gap-2.5 sm:flex" data-nav-auth>
                <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
            </div>

            <button type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-navy-600 transition hover:bg-slate-200 lg:hidden"
                aria-label="Toggle menu" data-mobile-menu-toggle>
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>

    {{-- ===== Mobile menu ===== --}}
    <div class="absolute left-0 right-0 top-full hidden border-t border-slate-100 bg-white shadow-md lg:hidden"
        data-mobile-menu>
        <div class="mu-container flex flex-col gap-1 py-4">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $link['active'] ? 'bg-[#A855F7] text-white' : 'text-navy-600 hover:bg-slate-50' }}">
                    <i class="fa-solid {{ $link['icon'] }} w-5 text-center"></i>{{ $link['label'] }}
                </a>
            @endforeach

            <div class="mt-1 border-t border-slate-100 pt-1">
                <a href="{{ route('about') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ $aboutActive ? 'text-[#A855F7]' : 'text-navy-600' }} hover:bg-slate-50">
                    <i class="fa-solid fa-building w-5 text-center text-slate-400"></i>Profil Perusahaan
                </a>
                <a href="{{ route('about.mentor') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-navy-600 hover:bg-slate-50">
                    <i class="fa-solid fa-user-tie w-5 text-center text-slate-400"></i>Profil Mentor
                </a>
            </div>

            <div class="mt-3 flex gap-3 sm:hidden">
                <a href="{{ route('login') }}" class="btn-outline flex-1">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary flex-1">Daftar</a>
            </div>
        </div>
    </div>
</nav>
