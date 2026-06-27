# Deploy ke Shared Hosting cPanel

Panduan deploy backend Laravel ini ke shared hosting cPanel (mis. Hostinger,
Niagahoster, Rumahweb). Berbeda dengan serverless, di sini filesystem persisten
— upload file, MySQL, dan storage berfungsi normal tanpa layanan eksternal.

## Prasyarat hosting

- **PHP 8.2+** (set di cPanel → "Select PHP Version", aktifkan ekstensi:
  `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `ctype`, `fileinfo`, `tokenizer`).
- **MySQL** (buat database + user di cPanel → "MySQL Databases").
- **Composer** & **SSH** sangat dianjurkan (paket Hostinger Premium/Business,
  Niagahoster). Tanpa SSH masih bisa, tapi lebih repot (lihat bagian alternatif).

---

## Langkah deploy pertama

### 1. Siapkan di lokal
```bash
npm run build          # build aset Vite → public/build
```
Build aset dilakukan **di lokal** karena shared hosting umumnya tidak punya Node.
Folder `public/build` hasil build harus ikut terupload.

### 2. Atur document root (PALING PENTING)
Laravel harus menyajikan folder `public/`, bukan root project. Pilih salah satu:

**Opsi A — arahkan domain ke `public/` (disarankan).**
Upload seluruh project ke folder di luar `public_html`, mis.
`/home/USER/markup-app`. Lalu cPanel → "Domains" → ubah Document Root domain ke
`/home/USER/markup-app/public`.

**Opsi B — pakai `public_html` (kalau document root tak bisa diubah).**
1. Upload isi project ke `/home/USER/markup-app` (di luar `public_html`).
2. Pindahkan **isi** folder `public/` ke `public_html/`.
3. Edit `public_html/index.php`, ubah dua path agar menunjuk ke folder app:
   ```php
   require __DIR__.'/../markup-app/vendor/autoload.php';
   $app = require_once __DIR__.'/../markup-app/bootstrap/app.php';
   ```

### 3. Upload file & install dependency
**Dengan SSH (mudah):**
```bash
cd ~/markup-app
git clone <repo-url> .            # atau upload via cPanel Git Version Control
composer install --no-dev --optimize-autoloader
```
**Tanpa SSH:** zip project di lokal **termasuk folder `vendor/`** (jalankan
`composer install --no-dev` dulu), upload via File Manager, lalu extract.

### 4. Konfigurasi `.env` di server
Buat file `.env` (via File Manager atau `cp .env.example .env`) lalu isi:
```ini
APP_NAME=MARK-UP
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-kamu.com

APP_KEY=                      # diisi di langkah 5

DB_CONNECTION=mysql
DB_HOST=127.0.0.1             # atau 'localhost' sesuai info hosting
DB_PORT=3306
DB_DATABASE=namauser_markup   # nama DB dari cPanel
DB_USERNAME=namauser_dbuser
DB_PASSWORD=passwordrahasia

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
LOG_LEVEL=error

# Midtrans (tetap sandbox — tugas kuliah)
MIDTRANS_SERVER_KEY=Mid-server-xxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false

GOOGLE_CLIENT_ID=xxxx.apps.googleusercontent.com
```
> `QUEUE_CONNECTION=sync` karena shared hosting tak punya worker queue;
> job dijalankan langsung. `.env` tidak ikut git — aman dari kebocoran.

### 5. Generate key, migrasi, & optimize
**Dengan SSH:**
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force        # opsional: data demo
php artisan config:cache
php artisan view:cache
```
**Tanpa SSH:** generate `APP_KEY` di lokal (`php artisan key:generate --show`),
tempel ke `.env`. Migrasi dijalankan dengan mengimpor SQL ke phpMyAdmin
(export skema dari DB lokal), atau pakai cPanel "Terminal" bila tersedia.

### 6. Set permission folder writable
```bash
chmod -R 775 storage bootstrap/cache public/uploads
```
> Gambar yang di-upload admin (produk, lomba, katalog) disimpan **langsung** ke
> `public/uploads/` — **tidak** memerlukan `php artisan storage:link`, jadi aman
> di shared host yang menonaktifkan symlink. Pastikan folder `public/uploads`
> writable (dibuat otomatis saat upload pertama bila parent-nya writable).

### 7. Verifikasi
```
https://domain-kamu.com/up                → 200 (health check)
https://domain-kamu.com/api/competitions  → JSON
```

---

## Update setelah deploy (lanjut kerjakan web)

Mobile sudah pakai API live; kamu bisa terus update web tanpa mengganggu mobile
**selama tidak mengubah/menghapus route & response API yang dipakai mobile.**

Alur update dengan SSH — jalankan `bash deploy.sh` di server (lihat file
[deploy.sh](deploy.sh)), yang melakukan:
```bash
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force      # kalau ada migrasi baru
php artisan config:cache
php artisan view:cache
```
Aset web: build di lokal (`npm run build`) lalu upload ulang folder `public/build`.

> **Catatan:** jangan jalankan `php artisan route:cache`. Route `api/media/{path}`
> memakai Closure sehingga route caching akan gagal. `config:cache` & `view:cache`
> aman dan sudah cukup.

---

## Checklist keamanan produksi
- [x] `APP_DEBUG=false` (wajib — mencegah kebocoran stack trace)
- [x] `APP_ENV=production`
- [x] `APP_KEY` terisi
- [x] Document root = `public/` (file `.env`, kode app tidak bisa diakses publik)
- [x] `.env` tidak ter-commit ke git
