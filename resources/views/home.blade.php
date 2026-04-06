<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MARK-UP | Jadi Juara Bukan Sekedar Mimpi</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Sora:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Custom CSS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

  <!-- ===== NAVBAR ===== -->
  <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand" href="#">
        <span class="brand-icon">M</span> MARK-UP
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Informasi</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Produk</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Business Case Mastery</a></li>
              <li><a class="dropdown-item" href="#">Strategic Consulting</a></li>
              <li><a class="dropdown-item" href="#">Career Mentoring</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Tentang Kami</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Tentang</a></li>
              <li><a class="dropdown-item" href="#">Tim</a></li>
            </ul>
          </li>
        </ul>
        <div class="nav-actions d-flex gap-2">
          <a href="#" class="btn btn-outline-nav">Masuk</a>
          <a href="#" class="btn btn-primary-nav">Daftar</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ===== HERO SECTION ===== -->
  <section class="hero-section" id="hero">
    <div class="hero-overlay"></div>
    <div class="hero-particles" id="particles"></div>
    <div class="container hero-content">
      <div class="row justify-content-center text-center">
        <div class="col-lg-9 col-md-11">
          <div class="hero-badge animate-fade-up" style="--delay:0.1s">
            <span>🏆 Platform #1 Business Case Indonesia</span>
          </div>
          <h1 class="hero-title animate-fade-up" style="--delay:0.2s">
            Jadi <span class="text-highlight">Juara</span><br/>Bukan Sekedar <em>Mimpi</em>
          </h1>
          <p class="hero-subtitle animate-fade-up" style="--delay:0.35s">
            Bergabunglah bersama ribuan mahasiswa yang telah meraih prestasi dalam business case competition, konsultansi, dan profesional dari berbagai industri.
          </p>
          <div class="hero-cta animate-fade-up" style="--delay:0.5s">
            <a href="#" class="btn btn-hero-primary">
              <i class="fas fa-rocket me-2"></i>Mulai Jadi Juara!
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="hero-scroll-indicator">
      <div class="scroll-dot"></div>
    </div>
  </section>

  <!-- ===== WHY MARK-UP ===== -->
  <section class="why-section section-pad" id="why">
    <div class="container">
      <div class="section-header text-center mb-5">
        <h2 class="section-title reveal">Mengapa Memilih <span class="text-accent">MARK-UP?</span></h2>
        <p class="section-desc reveal">Platform terlengkap yang mempersiapkan kamu dari nol hingga juara dan pengembangan karir profesional</p>
      </div>
      <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="feature-card">
            <div class="feature-icon yellow">
              <i class="fas fa-briefcase"></i>
            </div>
            <h4>Fokus Business Case</h4>
            <p>Kuasai framework konsultasi top McKinsey, BCG dengan latihan nyata dari berbagai industri.</p>
            <div class="feature-tags">
              <span class="tag">Case Studies</span>
              <span class="tag">Real Projects</span>
              <span class="tag">Industry Focus</span>
            </div>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="feature-card">
            <div class="feature-icon yellow">
              <i class="fas fa-users"></i>
            </div>
            <h4>Fokus Business Case</h4>
            <p>Bimbingan langsung dari konsultan Fortune 500 dan profesional dari perusahaan besar.</p>
            <div class="feature-tags">
              <span class="tag">Case Studies</span>
              <span class="tag">People Development</span>
              <span class="tag">Real Projects</span>
            </div>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="feature-card">
            <div class="feature-icon yellow">
              <i class="fas fa-trophy"></i>
            </div>
            <h4>Track Record Juara</h4>
            <p>Bimbingan langsung yang menjamin kelulusan dari perusahaan Fortune 500.</p>
            <div class="feature-tags">
              <span class="tag">Project Development</span>
              <span class="tag">Case Studies</span>
            </div>
          </div>
        </div>
        <!-- Card 4 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="feature-card">
            <div class="feature-icon yellow">
              <i class="fas fa-chart-line"></i>
            </div>
            <h4>Track Record Juara</h4>
            <p>Bimbingan langsung dari konsultan lini Fortune 500 dan perusahaan top Indonesia.</p>
            <div class="feature-tags">
              <span class="tag">Purple Development</span>
              <span class="tag">Case Studies</span>
            </div>
          </div>
        </div>
        <!-- Card 5 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="feature-card featured">
            <div class="feature-icon yellow">
              <i class="fas fa-star"></i>
            </div>
            <h4>Fokus Business Case</h4>
            <p>Tingkatkan kemampuan top McKinsey, BCG dengan latihan nyata dari berbagai industri dan perusahaan Fortune 500.</p>
            <div class="feature-tags">
              <span class="tag">Case Studies</span>
              <span class="tag">Real Projects</span>
              <span class="tag">Industry Focus</span>
            </div>
          </div>
        </div>
        <!-- Card 6 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="feature-card">
            <div class="feature-icon yellow">
              <i class="fas fa-lightbulb"></i>
            </div>
            <h4>Fokus Business Case</h4>
            <p>Bimbingan langsung dari konsultan senior dan perusahaan Fortune 500 dan berbagai industri.</p>
            <div class="feature-tags">
              <span class="tag">Case Studies</span>
              <span class="tag">Real Projects</span>
              <span class="tag">Industry Focus</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== TRUSTED BY ===== -->
  <section class="trusted-section section-pad-sm">
    <div class="container">
      <p class="trusted-label text-center reveal">Dipercaya oleh Alumni dari Top Companies</p>
      <div class="logo-track-wrapper reveal">
        <div class="logo-track">
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg" alt="Samsung" height="28"/></div>
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google" height="28"/></div>
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/sco/2/21/Nvidia_logo.svg" alt="NVIDIA" height="22"/></div>
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple" height="26"/></div>
          <div class="logo-item"><span class="logo-text">BADAN NASIONAL</span></div>
          <!-- duplicate for infinite scroll -->
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg" alt="Samsung" height="28"/></div>
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google" height="28"/></div>
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/sco/2/21/Nvidia_logo.svg" alt="NVIDIA" height="22"/></div>
          <div class="logo-item"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple" height="26"/></div>
          <div class="logo-item"><span class="logo-text">BADAN NASIONAL</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== IMPACT STATS ===== -->
  <section class="impact-section section-pad">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-5 reveal">
          <div class="impact-image-stack">
            <div class="impact-img-main">
              <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80" alt="Team" />
            </div>
            <div class="impact-badge-float">
              <span class="count" data-target="1000">0</span><span>+</span>
              <small>Mahasiswa<br/>Sukses</small>
            </div>
          </div>
        </div>
        <div class="col-lg-7 reveal">
          <div class="impact-text mb-4">
            <h2 class="section-title">Impact yang Terukur &<br/><span class="text-accent">Terbukti</span></h2>
            <p>Data pencapaian nyata dari ribuan mahasiswa yang telah kami bantu di berbagai kompetisi bisnis dan karir profesional.</p>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <div class="stat-card">
                <div class="stat-num"><span class="count" data-target="100">0</span>+</div>
                <div class="stat-label">Universities</div>
              </div>
            </div>
            <div class="col-6">
              <div class="stat-card">
                <div class="stat-num"><span class="count" data-target="89">0</span>%</div>
                <div class="stat-label">Win Rate Success Rate</div>
              </div>
            </div>
            <div class="col-6">
              <div class="stat-card">
                <div class="stat-num"><span class="count" data-target="50">0</span>+</div>
                <div class="stat-label">Perusahaan Corporate Partners</div>
              </div>
            </div>
            <div class="col-6">
              <div class="stat-card">
                <div class="stat-num"><span class="count" data-target="95">0</span>%</div>
                <div class="stat-label">Satisfaction Student Rating</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== PROGRAMS ===== -->
  <section class="programs-section section-pad">
    <div class="programs-bg"></div>
    <div class="container position-relative">
      <div class="section-header text-center mb-5">
        <p class="section-eyebrow reveal">— Program Unggulan —</p>
        <h2 class="section-title text-white reveal">Jelajahi Program <span class="text-yellow">Kami</span></h2>
        <p class="section-desc text-white-50 reveal">Pilih program yang sesuai dengan tujuan karirmu dari mulai perjalanan menuju kesuksesan</p>
      </div>

      <div class="row g-4 justify-content-center">
        <!-- Program 1 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="program-card">
            <div class="program-badge championship">Championship</div>
            <div class="program-img">
              <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=400&q=80" alt="Business Case" />
            </div>
            <div class="program-body">
              <h4>Business Case Mastery</h4>
              <div class="program-rating">
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star-half-alt text-yellow"></i>
                <span>4.8 (234 reviews)</span>
              </div>
              <div class="program-meta">
                <span><i class="fas fa-clock"></i> 8 Minggu</span>
                <span><i class="fas fa-user-graduate"></i> Sp.level</span>
              </div>
              <div class="program-price">
                <span class="price-old">Rp 299rb</span>
                <span class="price-new">Rp 1499rb</span>
              </div>
              <a href="#" class="btn btn-program">Lihat Detail</a>
            </div>
          </div>
        </div>

        <!-- Program 2 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="program-card featured-program">
            <div class="program-badge new">New</div>
            <div class="program-img">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80" alt="Strategic" />
            </div>
            <div class="program-body">
              <h4>Strategic Consulting Framework</h4>
              <div class="program-rating">
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star-half-alt text-yellow"></i>
                <span>4.7 (276 reviews)</span>
              </div>
              <div class="program-meta">
                <span><i class="fas fa-clock"></i> 6 Minggu</span>
                <span><i class="fas fa-user-graduate"></i> All level</span>
              </div>
              <div class="program-price">
                <span class="price-old">Rp 199rb</span>
                <span class="price-new">Rp 1199rb</span>
              </div>
              <a href="#" class="btn btn-program-white">Lihat Detail</a>
            </div>
          </div>
        </div>

        <!-- Program 3 -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="program-card">
            <div class="program-badge career">Career Acceleration</div>
            <div class="program-img">
              <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80" alt="Mentoring" />
            </div>
            <div class="program-body">
              <h4>Career Mentoring Program</h4>
              <div class="program-rating">
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star text-yellow"></i>
                <i class="fas fa-star-half-alt text-yellow"></i>
                <span>4.7 (17 reviews)</span>
              </div>
              <div class="program-meta">
                <span><i class="fas fa-clock"></i> 12 Sesi</span>
                <span><i class="fas fa-user-graduate"></i> Sp.level</span>
              </div>
              <div class="program-price">
                <span class="price-old">Rp 399rb</span>
                <span class="price-new">Rp 2299rb</span>
              </div>
              <a href="#" class="btn btn-program">Lihat Detail</a>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-5 reveal">
        <a href="#" class="btn btn-outline-yellow">Lihat Seluruh Produk <i class="fas fa-arrow-right ms-2"></i></a>
      </div>
    </div>
  </section>

  <!-- ===== 4 STEPS ===== -->
  <section class="steps-section section-pad">
    <div class="container">
      <div class="section-header text-center mb-5">
        <h2 class="section-title reveal">Mulai dalam <span class="text-accent">4 Langkah</span> Mudah</h2>
        <p class="section-desc reveal">Proses yang simpel dan terbukti untuk membantu kamu mencapai goals</p>
      </div>
      <div class="steps-wrapper">
        <div class="steps-line"></div>
        <div class="row g-4 justify-content-center">
          <div class="col-lg-3 col-sm-6 reveal">
            <div class="step-card">
              <div class="step-number">1</div>
              <div class="step-icon"><i class="fas fa-user-plus"></i></div>
              <h5>Daftar & Pilih Program</h5>
              <p>Buat akun gratis dan pilih program yang paling sesuai dengan tujuan karirmu.</p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 reveal">
            <div class="step-card">
              <div class="step-number">2</div>
              <div class="step-icon"><i class="fas fa-book-open"></i></div>
              <h5>Akses Materi Program</h5>
              <p>Pelajari framework berkualitas dan tools dari industri terbaik dunia. Kapan saja.</p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 reveal">
            <div class="step-card">
              <div class="step-number">3</div>
              <div class="step-icon"><i class="fas fa-comments"></i></div>
              <h5>Mentoring 1-on-1</h5>
              <p>Diskusikan langsung dengan mentor profesional berpengalaman di industri.</p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6 reveal">
            <div class="step-card">
              <div class="step-number">4</div>
              <div class="step-icon"><i class="fas fa-trophy"></i></div>
              <h5>Raih Prestasi</h5>
              <p>Ikuti kompetisi dan raih sertifikasi bergengsi untuk portofolio karirmu.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-5 reveal">
        <a href="#" class="btn btn-hero-primary">
          <i class="fas fa-bolt me-2"></i>Mulai Sekarang — Gratis!
        </a>
      </div>
    </div>
  </section>

  <!-- ===== TESTIMONIALS ===== -->
  <section class="testimonials-section section-pad" id="testimonials">
    <div class="container">
      <div class="section-header text-center mb-5">
        <h2 class="section-title reveal">Mereka Telah <span class="text-accent">Membuktikannya</span></h2>
        <p class="section-desc reveal">Cerita sukses dan ulasan mahasiswa yang telah meraih prestasi bersama MARK-UP</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-4 col-md-6 reveal">
          <div class="testi-card">
            <div class="testi-stars">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p>"MARK-UP benar-benar mengubah cara saya memandang Business Mentoring dan career BCG. Langkah-langkah mentoring sangat membantu saya menjawab tantangan wawancara dengan lebih efektif!"</p>
            <div class="testi-author">
              <div class="testi-avatar">A</div>
              <div>
                <strong>Anastasya Putri</strong>
                <span>Mahasiswi Universitas Indonesia</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 reveal">
          <div class="testi-card featured-testi">
            <div class="testi-stars">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p>"Program Career Mentoring di MARK-UP luar biasa. Mereka berhasil melatih peserta secara intensif dengan CV review, persiapan interview dan teknik konsultasi yang sangat relevan!"</p>
            <div class="testi-author">
              <div class="testi-avatar">R</div>
              <div>
                <strong>Ryan Mahendra</strong>
                <span>Direktur Startup Bandung</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 reveal">
          <div class="testi-card">
            <div class="testi-stars">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p>"Framework dan case studies yang diberikan di MARK-UP sangat berdampak. Saya berhasil lulus seleksi ketat BCG dan sekarang bekerja sebagai konsultan profesional!"</p>
            <div class="testi-author">
              <div class="testi-avatar">M</div>
              <div>
                <strong>Mia Della Tan</strong>
                <span>Direktur Senior Muda</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Rating Summary -->
      <div class="rating-summary reveal mt-5">
        <div class="row g-4 text-center">
          <div class="col-md-4">
            <div class="rating-box">
              <div class="rating-score">4.9<span>/5.0</span></div>
              <div class="rating-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
              </div>
              <p>Average Rating</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="rating-box">
              <div class="rating-score">850<span>+</span></div>
              <p>Total Reviews</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="rating-box">
              <div class="rating-score">98<span>%</span></div>
              <p>Recommended</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer class="footer-section">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand">
            <span class="brand-icon">M</span> MARK-UP
          </div>
          <p class="footer-desc mt-3">Platform terlengkap yang membantu mahasiswa dan profesional muda meraih prestasi di kompetisi bisnis dan karir impian mereka.</p>
          <div class="footer-socials mt-4">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-tiktok"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 col-6">
          <h6 class="footer-heading">Quick Links</h6>
          <ul class="footer-links">
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Informasi</a></li>
            <li><a href="#">Produk</a></li>
            <li><a href="#">Tentang Kami</a></li>
            <li><a href="#">Testimoni</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6 col-6">
          <h6 class="footer-heading">Program</h6>
          <ul class="footer-links">
            <li><a href="#">Business Case Mastery</a></li>
            <li><a href="#">Strategic Consulting</a></li>
            <li><a href="#">Career Mentoring</a></li>
            <li><a href="#">Corporate Business</a></li>
            <li><a href="#">Corporate Training</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6">
          <h6 class="footer-heading">Hubungi Kami</h6>
          <ul class="footer-contact">
            <li><i class="fas fa-phone"></i> +62 851 – 5656 – 7890</li>
            <li><i class="fas fa-envelope"></i> hello@markup.id</li>
            <li><i class="fas fa-map-marker-alt"></i> Surabaya, Indonesia</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom mt-5">
        <p>© 2024 MARK-UP. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
      </div>
    </div>
  </footer>

  <!-- Back to top button -->
  <button class="back-to-top" id="backToTop"><i class="fas fa-arrow-up"></i></button>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="script.js"></script>
</body>
</html>
