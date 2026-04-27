<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | MARK-UP</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://kit.fontawesome.com/bba1f9c6ae.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 antialiased">

    @php
        $sidebarLinks = [
            ['route' => 'dashboard.index', 'label' => 'Produk Saya', 'icon' => 'fa-cube'],
            ['route' => 'dashboard.transactions', 'label' => 'Riwayat Pembelian', 'icon' => 'fa-receipt'],
            [
                'route' => 'dashboard.profile.index',
                'label' => 'Profil Saya',
                'icon' => 'fa-user',
                'aliases' => ['dashboard.profile.password'],
            ],
        ];
    @endphp

    <div class="flex min-h-screen">

        {{-- ============== SIDEBAR ============== --}}
        <aside data-dashboard-sidebar
            class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform lg:static lg:translate-x-0">

            <div class="flex h-[85px] items-center border-b border-slate-100 px-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-extrabold text-navy-600">
                    <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="h-9">
                    <span>MARK-UP</span>
                </a>
            </div>

            {{-- User profile summary --}}
            <div class="border-b border-slate-100 px-6 py-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-navy-600 text-base font-bold text-white"
                        data-user-initials>FA</div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-navy-600" data-user-name>Faisal Fahmi</p>
                        <p class="truncate text-xs text-slate-500" data-user-email>faisal@markup.id</p>
                    </div>
                </div>
                <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-yellow-800"
                    data-user-role>
                    <i class="fas fa-graduation-cap"></i>
                    <span>Student</span>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 space-y-1 py-4 pr-4">
                @foreach ($sidebarLinks as $link)
                    <x-dashboard.sidebar-link
                        :route="$link['route']"
                        :label="$link['label']"
                        :icon="$link['icon']"
                        :aliases="$link['aliases'] ?? []" />
                @endforeach
            </nav>

            {{-- Logout --}}
            <div class="border-t border-slate-100 px-5 py-4">
                <button type="button" data-dashboard-logout
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold text-red-600 transition-colors hover:bg-red-50">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span>Keluar</span>
                </button>
            </div>
        </aside>

        {{-- Sidebar overlay (mobile) --}}
        <div data-dashboard-overlay
            class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

        {{-- ============== MAIN ============== --}}
        <div class="flex w-full flex-1 flex-col lg:pl-0">

            {{-- Mobile top bar --}}
            <div class="flex items-center justify-between border-b border-slate-200 bg-white px-5 py-4 lg:hidden">
                <button type="button" data-dashboard-toggle
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-navy-600 hover:bg-slate-100"
                    aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-base font-extrabold text-navy-600">
                    <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="h-7">
                </a>
                <span class="w-10"></span>
            </div>

            {{-- Page header --}}
            <header class="border-b border-slate-200 bg-white px-6 py-7 lg:px-10">
                <h1 class="text-2xl font-extrabold text-navy-600 md:text-3xl">@yield('page-title')</h1>
                @hasSection('page-subtitle')
                    <p class="mt-1 text-sm text-slate-500">@yield('page-subtitle')</p>
                @endif
            </header>

            <main class="flex-1 px-6 py-8 lg:px-10">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            const sidebar = document.querySelector('[data-dashboard-sidebar]');
            const overlay = document.querySelector('[data-dashboard-overlay]');
            const toggle = document.querySelector('[data-dashboard-toggle]');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            toggle?.addEventListener('click', openSidebar);
            overlay?.addEventListener('click', closeSidebar);

            // Hydrate user info from localStorage
            const userJson = localStorage.getItem('markup.auth.user');
            if (userJson) {
                try {
                    const user = JSON.parse(userJson);
                    const nameEl = document.querySelector('[data-user-name]');
                    const emailEl = document.querySelector('[data-user-email]');
                    const initialsEl = document.querySelector('[data-user-initials]');
                    const roleEl = document.querySelector('[data-user-role] span');

                    if (nameEl) nameEl.textContent = user.name;
                    if (emailEl) emailEl.textContent = user.email;
                    if (initialsEl) {
                        initialsEl.textContent = user.name
                            .split(' ').map((p) => p[0]).join('').slice(0, 2).toUpperCase();
                    }
                    if (roleEl && user.role) {
                        roleEl.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
                    }
                } catch {}
            }

            document.querySelector('[data-dashboard-logout]')?.addEventListener('click', () => {
                localStorage.removeItem('markup.auth.user');
                sessionStorage.setItem('markup.flash', 'Berhasil keluar dari akun.');
                window.location.href = '/';
            });
        })();
    </script>
</body>

</html>
