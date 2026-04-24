@extends('layouts.app')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="hero-bg-container">
            <img src="{{ asset('img/hero-section-bg.svg') }}" alt="Background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <h1>MARK-UP</h1>
            <p class="hero-tagline">Jadi <span class="highlight-yellow">Juara</span> Bukan Sekedar <span
                    class="highlight-yellow">Mimpi!</span></p>
            <p class="mx-auto" style="max-width: 700px; font-size: 18px; opacity: 0.9;">
                Bergabunglah dengan ribuan mahasiswa yang telah meraih prestasi dalam business case competitions. Dapatkan
                mentoring eksklusif dari praktisi terbaik di industri.
            </p>
            <br>
            <a href="#" class="btn btn-yellow-cta mt-4">
                Mulai Jadi Juara!
            </a>
        </div>
    </section>
    <!-- AKHIR HERO SECTION -->

    <!-- FEATURES SECTION Mengapa Memilih MARK-UP -->
    <section class="features-section mu-container">
        <div class="section-header text-center" style="text-align: center; margin-bottom: 50px;">
            <h2 style="color: var(--mu-navy); font-size: 32px; font-weight: 800; margin-bottom: 10px;">Mengapa Memilih
                MARK-UP?</h2>
            <p style="color: var(--mu-gray); font-size: 16px;">Platform terlengkap untuk persiapan kompetisi bisnis dan
                pengembangan karir profesional</p>
        </div>

        <div class="features-grid">

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3>Fokus Business Case</h3>
                <p>Kuasai framework konsultan top (McKinsey, BCG) dengan latihan kasus nyata dari berbagai industri.</p>
                <div class="feature-tags">
                    <span class="tag">Case Studies</span>
                    <span class="tag">Real Projects</span>
                    <span class="tag">People Development</span>
                    <span class="tag">Industry Focus</span>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h3>Fokus Business Case</h3>
                <p>Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.
                </p>
                <div class="feature-tags">
                    <span class="tag">Case Studies</span>
                    <span class="tag">People Development</span>
                    <span class="tag">Real Projects</span>
                    <span class="tag">Industry Focus</span>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-medal"></i>
                </div>
                <h3>Track Record Juara</h3>
                <p>Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.
                </p>
                <div class="feature-tags">
                    <span class="tag">People Development</span>
                    <span class="tag">Case Studies</span>
                    <span class="tag">Real Projects</span>
                    <span class="tag">Industry Focus</span>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3>Track Record Juara</h3>
                <p>Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.
                </p>
                <div class="feature-tags">
                    <span class="tag">People Development</span>
                    <span class="tag">Case Studies</span>
                    <span class="tag">Real Projects</span>
                    <span class="tag">Industry Focus</span>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-crosshairs"></i>
                </div>
                <h3>Fokus Business Case</h3>
                <p>Kuasai framework konsultan top (McKinsey, BCG) dengan latihan kasus nyata dari berbagai industri.</p>
                <div class="feature-tags">
                    <span class="tag">Case Studies</span>
                    <span class="tag">Real Projects</span>
                    <span class="tag">People Development</span>
                    <span class="tag">Industry Focus</span>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3>Fokus Business Case</h3>
                <p>Bimbingan langsung dari konsultan senior, juara kompetisi, dan profesional dari perusahaan Fortune 500.
                </p>
                <div class="feature-tags">
                    <span class="tag">Case Studies</span>
                    <span class="tag">People Development</span>
                    <span class="tag">Real Projects</span>
                    <span class="tag">Industry Focus</span>
                </div>
            </div>

        </div>
    </section>
    <!-- AKHIR FEATURES SECTION Mengapa Memilih MARK-UP -->
@endsection
