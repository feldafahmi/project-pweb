<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Console\Command;

/**
 * Rekonsiliasi transaksi 'pending' langsung dari Midtrans (Status API).
 *
 * Jaring pengaman untuk gejala "sudah bayar tapi belum sinkron":
 *   - Webhook Midtrans (server-to-server) tidak bisa menjangkau localhost.
 *   - Sync sisi-klien (afterPaid / tombol "Cek Status") bisa terlewat: pembayaran
 *     async (VA/QRIS) yang lunas setelah popup ditutup, tab keburu ditutup, dsb.
 *
 * Perintah ini menanyakan status otoritatif ke Midtrans untuk tiap transaksi
 * pending yang PEMBAYARANNYA SUDAH DIMULAI (punya snap_token) dan masih dalam
 * jendela waktu wajar, lalu menerapkannya (paid/failed/cancelled).
 */
class ReconcilePendingTransactions extends Command
{
    protected $signature = 'transactions:reconcile
                            {--days=7 : Hanya proses transaksi pending yang dibuat dalam N hari terakhir}';

    protected $description = 'Tarik status Midtrans untuk transaksi pending dan sinkronkan status + kepemilikan produk';

    public function handle(MidtransService $midtrans): int
    {
        $days = (int) $this->option('days');

        $pending = Transaction::where('status', 'pending')
            ->whereNotNull('snap_token')          // pembayaran benar-benar sudah dimulai
            ->where('created_at', '>=', now()->subDays($days))
            ->get();

        if ($pending->isEmpty()) {
            $this->info('Tidak ada transaksi pending yang perlu direkonsiliasi.');
            return self::SUCCESS;
        }

        $updated = 0;
        $paid    = 0;

        foreach ($pending as $trx) {
            $payload = $midtrans->fetchStatus($trx->code);
            if (! $payload) {
                continue; // belum ada percobaan bayar di Midtrans / error (sudah di-log)
            }

            if ($midtrans->applyStatus($trx, $payload)) {
                $updated++;
                if ($trx->refresh()->status === 'paid') {
                    $paid++;
                }
                $this->line("  {$trx->code}: {$trx->status}");
            }
        }

        $this->info("Selesai. Diperiksa: {$pending->count()}, diperbarui: {$updated}, jadi lunas: {$paid}.");

        return self::SUCCESS;
    }
}
