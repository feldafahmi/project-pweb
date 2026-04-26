@extends('layouts.app')

@section('content')
    <div class="mu-container pt-4 pb-2">
        <div class="breadcrumb-custom">
            <a href="/"><i class="fa-solid fa-house"></i></a>
            <span class="mx-2">></span>
            <span class="current-page">Produk</span>
        </div>
    </div>

    <section class="produk-hero">
        <div class="produk-hero-bg">
            <img src="{{ asset('img/Hero-Info-Produk.png') }}" alt="Background Hero Produk" class="hero-img-cover">
            <div class="hero-overlay-dark"></div>
        </div>
        <div class="produk-hero-content text-center">
            <h1>Produk Mark-Up</h1>
            <p>Pilih program yang sudah terbukti membantu memenangkan berbagai kompetisi</p>
        </div>
    </section>

    <section class="produk-main mu-container pb-5">

        <div class="produk-filters">
            <button class="filter-pill active">Semua</button>
            <button class="filter-pill">Kelas</button>
            <button class="filter-pill">Live Bootcamp</button>
            <button class="filter-pill">Modul</button>
        </div>

        <div class="produk-grid">

            <div class="produk-card">
                <div class="produk-img-wrapper">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 1">
                </div>
                <div class="produk-info">
                    <h3 class="produk-title">Bundle: Winner Class dan Module (Debate)</h3>
                    <p class="produk-desc">
                        Gabungan materi Winner Class (Video on Demand) + modul eksklusif Tips & Trik Juara Lomba UI/UX yang
                        dirancang langsung untuk kamu yang ingin menang, bukan sekadar ikut lomba.
                    </p>
                    <div class="produk-price-box">
                        <span class="old-price">149.000</span>
                        <span class="new-price">99.000</span>
                    </div>
                </div>
            </div>

            <div class="produk-card">
                <div class="produk-img-wrapper">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 2">
                </div>
                <div class="produk-info">
                    <h3 class="produk-title">Bundle: Winner Class dan Module (Debate)</h3>
                    <p class="produk-desc">
                        Gabungan materi Winner Class (Video on Demand) + modul eksklusif Tips & Trik Juara Lomba UI/UX yang
                        dirancang langsung untuk kamu yang ingin menang, bukan sekadar ikut lomba.
                    </p>
                    <div class="produk-price-box">
                        <span class="old-price">149.000</span>
                        <span class="new-price">99.000</span>
                    </div>
                </div>
            </div>

            <div class="produk-card">
                <div class="produk-img-wrapper">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 3">
                </div>
                <div class="produk-info">
                    <h3 class="produk-title">Bundle: Winner Class dan Module (Debate)</h3>
                    <p class="produk-desc">
                        Gabungan materi Winner Class (Video on Demand) + modul eksklusif Tips & Trik Juara Lomba UI/UX yang
                        dirancang langsung untuk kamu yang ingin menang, bukan sekadar ikut lomba.
                    </p>
                    <div class="produk-price-box">
                        <span class="old-price">149.000</span>
                        <span class="new-price">99.000</span>
                    </div>
                </div>
            </div>

            <div class="produk-card">
                <div class="produk-img-wrapper">
                    <img src="{{ asset('img/63815.png') }}" alt="Produk 4">
                </div>
                <div class="produk-info">
                    <h3 class="produk-title">Bundle: Winner Class dan Module (Debate)</h3>
                    <p class="produk-desc">
                        Gabungan materi Winner Class (Video on Demand) + modul eksklusif Tips & Trik Juara Lomba UI/UX yang
                        dirancang langsung untuk kamu yang ingin menang, bukan sekadar ikut lomba.
                    </p>
                    <div class="produk-price-box">
                        <span class="old-price">149.000</span>
                        <span class="new-price">99.000</span>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
