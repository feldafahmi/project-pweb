<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jaring pengaman status pembayaran: rekonsiliasi transaksi pending dengan
// Midtrans secara berkala, kalau-kalau webhook/sync sisi-klien terlewat.
// Menjaga kepemilikan produk tetap sinkron tanpa intervensi manual.
// Membutuhkan `php artisan schedule:work` (dev) atau cron schedule:run (prod).
Schedule::command('transactions:reconcile')
    ->everyFiveMinutes()
    ->withoutOverlapping();
