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

            <a href="#" class="btn btn-yellow-cta mt-4">
                Mulai Jadi Juara!
            </a>
        </div>
    </section>
    <!-- AKHIR HERO SECTION -->
@endsection
