<p align="center">
  <img src="public/img
/Markup-Logo.png" width="96" alt="MARK-UP logo">
</p>

<h1 align="center">MARK-UP</h1>

<p align="center">
  Platform EdTech untuk <strong>mentoring</strong> & persiapan <strong>lomba business case</strong>.<br>
  Dibangun dengan Laravel 12, Blade, Tailwind CSS 4, dan API Sanctum untuk klien mobile.
</p>

---

## Tentang Proyek

**MARK-UP** menyajikan halaman marketing publik, autentikasi pengguna, katalog produk/kelas,
informasi lomba, on-demand mentoring, serta area dashboard untuk siswa, mentor, dan admin.
Selain web (Blade SSR), aplikasi juga mengekspos **REST API** (`/api/*`) yang diamankan dengan
Laravel Sanctum untuk dikonsumsi klien eksternal (mobile/Flutter).

### Fitur Utama

- **Landing publik** — beranda, tentang kami, profil mentor, info lomba, kontak.
- **Katalog produk** — daftar produk dengan filter & pencarian, halaman detail, dan kurikulum belajar.
- **On-Demand Mentoring** — halaman publik daftar mentor + CRUD mentor di panel admin.
- **Info Lomba** — daftar kompetisi business case (publik + CRUD admin).
- **Keranjang & Checkout** — integrasi pembayaran **Midtrans** (webhook notifikasi + sinkronisasi status).
- **Milestone Tracker** — checklist progres belajar per pengguna.
- **Manajemen peran** — `admin`, `mentor`, dan `user` dengan middleware dan area dashboard terpisah.
- **REST API bertoken** — produk, mentor, kompetisi, transaksi, cart, review, dan profil.

## Tech Stack

| Lapisan | Teknologi |
|---|---|
| Backend | Laravel 12 (PHP) |
| Auth API | Laravel Sanctum (Bearer token) |
| View | Blade SSR (`layouts/app`, `guest`, `dashboard`, `admin`) |
| Styling | Tailwind CSS 4 via `@tailwindcss/vite` |
| Build | Vite |
| Pembayaran | Midtrans |
| Testing | PHPUnit (SQLite in-memory) |

## Persyaratan

- PHP 8.2+ dan Composer
- Node.js 18+ dan npm
- Database (MySQL/MariaDB untuk pemakaian nyata; SQLite untuk testing)

## Setup Cepat

```bash
# Pasang dependency, generate key, migrasi, dan build aset sekaligus
composer run setup

# Isi data demo (produk, mentor, lomba, kurikulum, review, akun contoh)
php artisan migrate:fresh --seed

# Jalankan semua service dev (server PHP, queue, Pail logs, Vite) secara paralel
composer run dev
```

Atau jalankan aset secara terpisah:

```bash
npm run dev     # Vite dev server dengan HMR
npm run build   # Build produksi
```

## Akun Demo

Tersedia setelah `--seed` (lihat `database/seeders/DatabaseSeeder.php`):

| Peran | Email | Password |
|---|---|---|
| Admin | `admin@markup.com` | `passwordadmin` |
| Admin (mobile) | `admin@markup.test` | `admin12345` |
| Mentor | `mentor@markup.com` | `passwordmentor` |

## Struktur Routing

- **`routes/web.php`** — halaman Blade + submit form auth, dashboard (`/dashboard/*`),
  panel admin (`/admin/*`), dan area mentor (`/mentor/*`).
- **`routes/api.php`** — endpoint JSON `/api/*`; endpoint tulis dilindungi `auth:sanctum`
  (dan middleware `admin` untuk operasi admin). Webhook Midtrans diamankan lewat signature.

## Testing

```bash
composer run test                              # Semua tes (config cache dibersihkan dulu)
php artisan test tests/Feature/ExampleTest.php # Satu file tes
```

Tes memakai database SQLite in-memory (dikonfigurasi di `phpunit.xml`) — tidak menyentuh database aplikasi.

## Branding

Warna brand didefinisikan sebagai CSS variable di `resources/css/app.css`:
`--navy: #1a2b56`, `--yellow: #f5eb5e`, aksen ungu `#A855F7`. Logo ada di
`public/img/Markup-Logo.png`; favicon di `public/img/favicon.svg`.
