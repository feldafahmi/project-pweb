<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /** Biaya layanan tetap (sinkron dengan tampilan di checkout.blade). */
    private const SERVICE_FEE = 5000;

    /** Voucher diskon (server-side — tidak boleh dipercayakan ke klien). */
    private const VOUCHERS = [
        'MARKUPJUARA'  => 0.20,
        'MENTORINGMHS' => 0.50,
    ];

    /**
     * POST /dashboard/checkout
     * Buat transaksi nyata dari keranjang web lalu hasilkan Snap token Midtrans.
     * Harga & diskon DIHITUNG ULANG DI SERVER (harga dari DB), tidak memakai
     * angka kiriman klien. Mengembalikan snap_token untuk dibayar via snap.js.
     */
    public function store(Request $request, MidtransService $midtrans)
    {
        $validated = $request->validate([
            'product_ids'   => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'voucher_code'  => ['nullable', 'string', 'max:50'],
        ]);

        $userId = auth()->id();
        $user   = auth()->user();

        // Produk unik yang BELUM dimiliki user (cegah beli ulang).
        $ownedIds  = $user->products()->pluck('id')->all();
        $wantedIds = collect($validated['product_ids'])->unique()->diff($ownedIds)->values();

        if ($wantedIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua produk di keranjang sudah kamu miliki.',
            ], 422);
        }

        $products = Product::whereIn('id', $wantedIds)->get();

        // Hitung subtotal dari harga DB (otoritatif), bukan dari klien.
        $subtotal = (int) $products->sum('price');

        // Validasi voucher di server.
        $code     = strtoupper(trim($validated['voucher_code'] ?? ''));
        $percent  = self::VOUCHERS[$code] ?? 0.0;
        $discount = (int) round($subtotal * $percent);

        $total = $subtotal - $discount + self::SERVICE_FEE;

        $transaction = DB::transaction(function () use ($products, $userId, $total) {
            $trx = Transaction::create([
                'user_id'        => $userId,
                'code'           => $this->generateCode(),
                'total_amount'   => $total,
                'payment_method' => 'midtrans',
                'status'         => 'pending',
                'paid_at'        => null,
            ]);

            foreach ($products as $product) {
                $trx->items()->create([
                    'product_id'        => $product->id,
                    'product_title'     => $product->title,
                    'product_type'      => $product->type,
                    'product_image_url' => $product->image_url,
                    'price'             => $product->price,
                    'quantity'          => 1,
                    'subtotal'          => $product->price,
                ]);
            }

            return $trx->load('items');
        });

        try {
            $snap = $midtrans->createSnap($transaction);
        } catch (\Throwable $e) {
            Log::error('Midtrans createSnap (web) gagal: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai pembayaran. Coba lagi sebentar.',
            ], 500);
        }

        return response()->json([
            'success'        => true,
            'snap_token'     => $snap['token'],
            'transaction_id' => $transaction->id,
            'total'          => $total,
        ]);
    }

    /**
     * POST /dashboard/transactions/{transaction}/sync
     * Tarik status pembayaran langsung dari Midtrans (Status API) lalu update DB.
     *
     * Ini kunci untuk lingkungan lokal: webhook Midtrans (server-to-server) tidak
     * bisa menjangkau localhost, sehingga status transaksi tak pernah berubah dari
     * 'pending'. Endpoint ini melakukan panggilan KELUAR ke Midtrans (berjalan
     * normal dari localhost) sebagai sumber kebenaran — dipanggil otomatis setelah
     * bayar dan lewat tombol "Cek Status" di riwayat pembelian.
     */
    public function syncStatus(Request $request, Transaction $transaction, MidtransService $midtrans)
    {
        abort_if($transaction->user_id !== auth()->id(), 403, 'Tidak diizinkan.');

        // Sudah final 'paid' → tidak perlu tanya Midtrans lagi.
        if ($transaction->status !== 'paid') {
            $payload = $midtrans->fetchStatus($transaction->code);
            if ($payload) {
                $midtrans->applyStatus($transaction, $payload);
            }
        }

        return response()->json([
            'success' => true,
            'status'  => $transaction->refresh()->status,
        ]);
    }

    private function generateCode(): string
    {
        do {
            $code = 'TRX-'.strtoupper(Str::random(8));
        } while (Transaction::where('code', $code)->exists());

        return $code;
    }
}
