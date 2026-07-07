<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

/**
 * Wrapper integrasi Midtrans Snap. Dipakai bersama oleh mobile & web —
 * generate token Snap saat checkout, dan memetakan status webhook ke
 * enum status transaksi kita.
 */
class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$clientKey    = config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Buat Snap transaction untuk sebuah transaksi.
     * Menyimpan snap_token + payment_url ke transaksi dan mengembalikannya.
     *
     * @return array{token:string, redirect_url:string}
     */
    public function createSnap(Transaction $trx): array
    {
        $trx->loadMissing('items', 'user');

        $items = $trx->items->map(fn ($i) => [
            'id'       => (string) ($i->product_id ?? $i->id),
            'price'    => (int) $i->price,
            'quantity' => (int) $i->quantity,
            'name'     => mb_substr($i->product_title ?? 'Item', 0, 50), // Midtrans max 50 char
        ])->values()->all();

        // Midtrans mewajibkan gross_amount == sum(item_details). Bila total
        // transaksi berbeda dari jumlah harga produk (mis. web menambah biaya
        // layanan / potongan voucher yang tidak disimpan sebagai item), tambahkan
        // satu baris penyesuaian agar selalu konsisten. Mobile: diff = 0 → tanpa baris.
        $sum  = array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $items));
        $diff = (int) $trx->total_amount - $sum;
        if ($diff !== 0) {
            $items[] = [
                'id'       => 'ADJUSTMENT',
                'price'    => $diff,            // boleh negatif (diskon)
                'quantity' => 1,
                'name'     => $diff < 0 ? 'Diskon Voucher' : 'Biaya Layanan',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $trx->code,            // unik — sekaligus order_id Midtrans
                'gross_amount' => (int) $trx->total_amount,
            ],
            'item_details'     => $items,
            'customer_details' => [
                'first_name' => $trx->user->name ?? 'User',
                'email'      => $trx->user->email ?? 'noemail@markup.test',
            ],
        ];

        $snap = Snap::createTransaction($params);

        $trx->update([
            'snap_token'  => $snap->token,
            'payment_url' => $snap->redirect_url,
        ]);

        return ['token' => $snap->token, 'redirect_url' => $snap->redirect_url];
    }

    /**
     * Ambil status transaksi langsung dari Midtrans (Status API).
     * Dipakai sebagai fallback bila notifikasi webhook meleset.
     * Mengembalikan array payload, atau null bila transaksi belum ada di
     * Midtrans (user belum membayar sama sekali) / terjadi error.
     */
    public function fetchStatus(string $orderId): ?array
    {
        try {
            $res = MidtransTransaction::status($orderId);
            return json_decode(json_encode($res), true);
        } catch (\Throwable $e) {
            // 404 Not Found = belum ada percobaan bayar untuk order_id ini (normal).
            // Error lain (401 server key salah, 5xx, jaringan) BUKAN "belum bayar" —
            // kalau ditelan diam-diam, transaksi yang sebenarnya lunas akan macet di
            // 'pending' tanpa jejak. Log agar penyebab "sudah bayar belum sinkron"
            // bisa didiagnosis.
            if (! str_contains($e->getMessage(), '404')) {
                Log::warning('Midtrans fetchStatus gagal (bukan 404)', [
                    'order_id' => $orderId,
                    'error'    => $e->getMessage(),
                ]);
            }
            return null;
        }
    }

    /**
     * Verifikasi signature webhook secara lokal (tanpa call balik ke Midtrans).
     * signature_key = sha512(order_id + status_code + gross_amount + ServerKey)
     */
    public function isValidSignature(array $payload): bool
    {
        $expected = hash('sha512',
            ($payload['order_id'] ?? '').
            ($payload['status_code'] ?? '').
            ($payload['gross_amount'] ?? '').
            config('services.midtrans.server_key')
        );

        return hash_equals($expected, (string) ($payload['signature_key'] ?? ''));
    }

    /**
     * Terapkan status dari payload Midtrans (webhook ATAU Status API) ke
     * transaksi. Mengandung guard anti-regresi: transaksi yang sudah 'paid'
     * tidak akan diturunkan oleh notifikasi telat / out-of-order.
     *
     * @return bool true bila status di-update, false bila diabaikan.
     */
    public function applyStatus(Transaction $trx, array $payload): bool
    {
        $status = $this->mapStatus(
            (string) ($payload['transaction_status'] ?? ''),
            $payload['fraud_status'] ?? null
        );

        if ($trx->status === 'paid' && $status !== 'paid') {
            return false;
        }

        $trx->update([
            'midtrans_status' => $payload['transaction_status'] ?? $trx->midtrans_status,
            'status'          => $status,
            'paid_at'         => $status === 'paid' ? ($trx->paid_at ?? now()) : $trx->paid_at,
        ]);

        return true;
    }

    /**
     * Petakan transaction_status Midtrans ke enum status transaksi kita
     * ('pending' | 'paid' | 'failed' | 'cancelled').
     */
    public function mapStatus(string $trxStatus, ?string $fraudStatus = null): string
    {
        return match ($trxStatus) {
            'capture'    => $fraudStatus === 'challenge' ? 'pending' : 'paid',
            'settlement' => 'paid',
            'pending'    => 'pending',
            'deny'       => 'failed',
            'expire'     => 'cancelled',
            'cancel'     => 'cancelled',
            default      => 'pending',
        };
    }
}
