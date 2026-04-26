<nav class="mu-nav shadow-sm">
    <div class="mu-container nav-wrapper">

        <div class="nav-left">
            <a href="/">
                <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="mu-logo-img">
            </a>
        </div>

        <div class="nav-center">
            <ul class="nav-links">
                <li>
                    <a href="/" class="nav-link-custom {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                </li>
                <li>
                    <a href="#" class="nav-link-custom">Info Lomba</a>
                </li>
                <li>
                    <a href="{{ route('produk') }}"
                        class="nav-link-custom {{ request()->routeIs('produk') ? 'active' : '' }}">Produk</a>
                </li>
                <li class="nav-item-dropdown">
                    <a href="#" class="nav-link-custom {{ request()->routeIs('about') ? 'active' : '' }}">
                        Tentang Kami <i class="fas fa-chevron-down ms-1" style="font-size: 10px; transition: 0.3s;"></i>
                    </a>
                    <ul class="dropdown-menu-custom">
                        <li><a href="{{ route('about') }}">Profil Perusahaan</a></li>
                        <li><a href="#">Profil Mentor</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="nav-auth">
                <a href="/login" class="btn-nav-outline">Masuk</a>
                <a href="/register" class="btn-nav-primary">Daftar</a>
            </div>
        </div>

    </div>
</nav>
