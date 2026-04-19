<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MARK-UP | Jadi Juara Bukan Sekedar Mimpi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css'])

</head>
<body class="bg-light">
<!-- NAVBAR -->
<nav class="mu-nav shadow-sm">
    <div class="mu-container nav-wrapper">
        
        <div class="nav-left">
            <a href="/">
                <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="mu-logo-img">
            </a>
        </div>

        <div class="nav-center">
            <ul class="nav-links">
                <li><a href="#" class="nav-link-custom active">Beranda</a></li>
                <li><a href="#" class="nav-link-custom">Info Lomba</a></li>
                <li><a href="#" class="nav-link-custom">Produk</a></li>
                <li>
                    <a href="#" class="nav-link-custom">
                        Tentang Kami <i class="fas fa-chevron-down ms-1" style="font-size: 10px;"></i>
                    </a>
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
<!-- AKHIR NAVBAR -->


<!-- HERO SECTION -->
<section class="hero-section">
   <div class="hero-bg-container">
        <img src="{{ asset('img/hero-section-bg.svg') }}" alt="Background">
        <div class="hero-overlay"></div>
    </div>
    <div class="hero-content">
        <h1>MARK-UP</h1>
        <p class="hero-tagline">Jadi <span class="highlight-yellow">Juara</span> Bukan Sekedar <span class="highlight-yellow">Mimpi!</span></p>
        <p class="mx-auto" style="max-width: 700px; font-size: 18px; opacity: 0.9;">
            Bergabunglah dengan ribuan mahasiswa yang telah meraih prestasi dalam business case competitions. Dapatkan mentoring eksklusif dari praktisi terbaik di industri.
        </p>
        
        <a href="#" class="btn btn-yellow-cta mt-4">
            Mulai Jadi Juara!
        </a>
    </div>
</section>
<!-- AKHIR HERO SECTION -->

</body>
</html>
