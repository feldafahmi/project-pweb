@php
    $navLinks = [
        ['label' => 'Beranda', 'href' => url('/'), 'active' => request()->is('/')],
        ['label' => 'Info Lomba', 'href' => route('lomba'), 'active' => false],
        ['label' => 'Produk', 'href' => route('produk'), 'active' => request()->routeIs('produk')],
    ];
@endphp

<nav class="fixed inset-x-0 top-0 z-50 flex h-[85px] items-center bg-white shadow-[0_2px_10px_rgba(0,0,0,0.03)]">
    <div class="mu-container flex w-full items-center justify-between">

        <a href="{{ url('/') }}" class="flex flex-1 justify-start">
            <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="h-[60px] w-auto">
        </a>

        <ul class="hidden flex-2 items-center justify-center gap-9 lg:flex">
            @foreach ($navLinks as $link)
                <li>
                    <a href="{{ $link['href'] }}"
                        class="relative pb-1 text-sm font-semibold transition-colors hover:text-navy-600 {{ $link['active'] ? 'text-navy-600 after:absolute after:-bottom-0.5 after:left-0 after:h-[3px] after:w-full after:rounded-sm after:bg-navy-600' : 'text-slate-600' }}">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach

            <li class="group relative">
                <a href="#"
                    class="flex items-center gap-1.5 pb-1 text-sm font-semibold text-slate-600 transition-colors hover:text-navy-600 {{ request()->routeIs('about') ? 'text-navy-600' : '' }}">
                    Tentang Kami
                    <i class="fas fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                </a>
                <ul
                    class="invisible absolute left-0 top-full z-50 min-w-[220px] translate-y-4 rounded-xl bg-white py-3 opacity-0 shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                    <li>
                        <a href="{{ route('about') }}"
                            class="block px-6 py-2.5 text-sm font-semibold text-slate-500 transition-all hover:bg-slate-50 hover:pl-8 hover:text-navy-600">
                            Profil Perusahaan
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-6 py-2.5 text-sm font-semibold text-slate-500 transition-all hover:bg-slate-50 hover:pl-8 hover:text-navy-600">
                            Profil Mentor
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="flex flex-1 items-center justify-end gap-3">
            <div class="hidden items-center gap-3 sm:flex" data-nav-auth>
                <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
            </div>

            <button type="button"
                class="inline-flex items-center justify-center rounded-lg p-2 text-navy-600 lg:hidden"
                aria-label="Toggle menu" data-mobile-menu-toggle>
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

    </div>

    <div class="absolute left-0 right-0 top-full hidden border-t border-slate-100 bg-white shadow-md lg:hidden"
        data-mobile-menu>
        <div class="mu-container flex flex-col gap-1 py-4">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}"
                    class="rounded-lg px-3 py-2 text-sm font-semibold {{ $link['active'] ? 'bg-navy-50 text-navy-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('about') }}"
                class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('about') ? 'bg-navy-50 text-navy-600' : 'text-slate-600 hover:bg-slate-50' }}">
                Tentang Kami
            </a>
            <div class="mt-3 flex gap-3 sm:hidden">
                <a href="{{ route('login') }}" class="btn-outline flex-1">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary flex-1">Daftar</a>
            </div>
        </div>
    </div>
</nav>
