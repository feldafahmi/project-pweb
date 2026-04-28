<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | MARK-UP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://kit.fontawesome.com/bba1f9c6ae.js" crossorigin="anonymous"></script>

    {{-- Alpine.js (CDN, deferred) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-slate-50 antialiased" x-data="{ sidebarOpen: false }">

    @php
        $adminLinks = [
            ['route' => 'admin.products.index', 'label' => 'Manajemen Produk', 'icon' => 'fa-cube'],
            ['route' => 'admin.competitions.index', 'label' => 'Manajemen Info Lomba', 'icon' => 'fa-trophy'],
            ['route' => 'admin.users.index', 'label' => 'Manajemen Pengguna', 'icon' => 'fa-users'],
        ];
    @endphp

    <div class="flex min-h-screen">

        {{-- ========== SIDEBAR ========== --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform lg:static lg:translate-x-0">

            <div class="flex h-16 items-center border-b border-slate-100 px-6">
                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center gap-2 text-base font-extrabold text-slate-800">
                    <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="h-8">
                    <span>MARK-UP <span class="text-orange-500">Admin</span></span>
                </a>
            </div>

            <nav class="flex-1 space-y-1 py-4 pr-4">
                @foreach ($adminLinks as $link)
                    <x-admin.sidebar-link :route="$link['route']" :label="$link['label']" :icon="$link['icon']" />
                @endforeach
            </nav>

            <div class="border-t border-slate-100 px-5 py-4">
                <a href="{{ url('/') }}"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-500 transition-colors hover:bg-slate-50 hover:text-blue-600">
                    <i class="fas fa-arrow-left w-5 text-center"></i>
                    <span>Kembali ke Situs</span>
                </a>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"></div>

        {{-- ========== MAIN ========== --}}
        <div class="flex w-full flex-1 flex-col">

            {{-- Top navbar --}}
            <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-slate-200 bg-white/90 px-4 backdrop-blur lg:px-8">
                <button type="button" @click="sidebarOpen = true"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden"
                    aria-label="Open sidebar">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="relative hidden flex-1 max-w-md md:block">
                    <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="search" placeholder="Cari di seluruh data admin..."
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm outline-none transition-colors focus:border-blue-600 focus:bg-white">
                </div>

                <div class="flex flex-1 items-center justify-end gap-3">
                    <button type="button" aria-label="Notifications"
                        class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100">
                        <i class="fas fa-bell"></i>
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-orange-500 ring-2 ring-white"></span>
                    </button>

                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button type="button" @click="open = !open"
                            class="flex items-center gap-2 rounded-full border border-slate-200 py-1.5 pl-1.5 pr-3 transition hover:bg-slate-50">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">AD</span>
                            <span class="hidden text-sm font-semibold text-slate-700 sm:inline">Admin</span>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute right-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-100 bg-white py-2 shadow-lg">
                            <div class="border-b border-slate-100 px-4 pb-2 pt-1">
                                <p class="truncate text-sm font-bold text-slate-700">Admin MARK-UP</p>
                                <p class="truncate text-xs text-slate-500">admin@markup.id</p>
                            </div>
                            <a href="{{ url('/') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600">
                                <i class="fas fa-globe mr-2 text-slate-400"></i>Lihat Situs
                            </a>
                            <div class="my-1 border-t border-slate-100"></div>
                            <a href="{{ url('/') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 lg:px-8 lg:py-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
