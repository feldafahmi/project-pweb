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

    <!-- FEATURES SECTION logo alumni -->
    <section class="alumni-section mu-container">
        <div class="section-header text-center" style="text-align: center; margin-bottom: 40px;">
            <h2 style="color: var(--mu-navy); font-size: 28px; font-weight: 800; margin-bottom: 10px;">
                Dipercaya oleh Alumni dari Top Companies
            </h2>
            <p style="color: var(--mu-gray); font-size: 16px;">
                Mentor dan alumni kami bekerja di perusahaan-perusahaan terkemuka dunia
            </p>
        </div>

        <div class="marquee-container">
            <div class="marquee-content">

                <img src="{{ asset('img/google.png') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/Nvidia_logo.png') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/Spongebob-sangar.jpg') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/unair-horisontal.png') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/BGN_LOGO.png') }}" alt="Logo Company" class="company-logo">

                <img src="{{ asset('img/google.png') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/Nvidia_logo.png') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/Spongebob-sangar.jpg') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/unair-horisontal.png') }}" alt="Logo Company" class="company-logo">
                <img src="{{ asset('img/BGN_LOGO.png') }}" alt="Logo Company" class="company-logo">

            </div>
        </div>
    </section>
    <!-- AKHIR FEATURES SECTION logo alumni -->

    <!-- TESTIMONI SECTION -->
    <section class="testimonials-section mu-container">
        <div class="section-header text-center" style="margin-bottom: 50px;">
            <h2 style="color: var(--mu-navy); font-size: 32px; font-weight: 800; margin-bottom: 10px;">
                Mereka Telah Membuktikannya
            </h2>
            <p style="color: var(--mu-gray); font-size: 16px;">
                Cerita sukses dari ribuan mahasiswa yang telah meraih prestasi bersama MARK-UP
            </p>
        </div>

        <div class="testimonials-grid">

            <div class="testimonial-card">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                <div class="stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                        class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="review-text">
                    "MARK-UP benar-benar mengubah cara saya approach business cases. Mentoring dari praktisi BCG sangat
                    membantu saya meraih juara 1 di kompetisi nasional. Materinya applicable dan mentornya sangat
                    supportive!"
                </p>
                <hr class="card-divider">
                <div class="user-profile">
                    <img src="{{ asset('img/4622.jpg') }}" alt="Anastasia" class="user-avatar">
                    <div class="user-info">
                        <h4>Anastasia Putri</h4>
                        <p>Universitas Indonesia</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                <div class="stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                        class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="review-text">
                    "Program Career Mentoring di MARK-UP luar biasa! Saya berhasil diterima di Big 4 consulting firm berkat
                    persiapan interview dan CV review yang detail. Investment terbaik untuk karir saya."
                </p>
                <hr class="card-divider">
                <div class="user-profile">
                    <img src="{{ asset('img/4620.jpg') }}" alt="Ryan" class="user-avatar">
                    <div class="user-info">
                        <h4>Ryan Mahendra</h4>
                        <p>Institut Teknologi Bandung</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fa-solid fa-quote-left quote-icon"></i>
                <div class="stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                        class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="review-text">
                    "Framework dan case studies yang diajarkan sangat komprehensif. Dalam 2 bulan, tim saya berhasil juara
                    di 3 kompetisi berbeda. Highly recommended untuk semua business students!"
                </p>
                <hr class="card-divider">
                <div class="user-profile">
                    <img src="{{ asset('img/4623.jpg') }}" alt="Michelle" class="user-avatar">
                    <div class="user-info">
                        <h4>Michelle Tan</h4>
                        <p>Universitas Gadjah Mada</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="stats-banner">
            <div class="stat-item">
                <h3>4.9/5.0</h3>
                <p>Average Rating</p>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <h3>850+</h3>
                <p>Total Reviews</p>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <h3>98%</h3>
                <p>Would Recommend</p>
            </div>
        </div>
    </section>
    <!-- AKHIR TESTIMONI SECTION -->
@endsection
