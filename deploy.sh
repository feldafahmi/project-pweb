#!/usr/bin/env bash
#
# Skrip update untuk shared hosting cPanel (butuh SSH).
# Jalankan dari root project di server:  bash deploy.sh
#
# CATATAN: 'php artisan route:cache' sengaja TIDAK dipakai — route
# api/media/{path} memakai Closure sehingga route caching akan gagal.

set -e

echo "→ [1/5] Menarik perubahan terbaru dari git..."
git pull origin main

echo "→ [2/5] Menginstall dependency PHP (mode produksi)..."
composer install --no-dev --optimize-autoloader

echo "→ [3/5] Menjalankan migrasi database..."
php artisan migrate --force

echo "→ [4/5] Me-refresh cache konfigurasi..."
php artisan config:clear
php artisan config:cache

echo "→ [5/5] Me-cache view Blade..."
php artisan view:cache

echo ""
echo "✓ Update selesai."
echo "  Jika ada perubahan aset web, build di lokal (npm run build)"
echo "  lalu upload ulang folder public/build."
