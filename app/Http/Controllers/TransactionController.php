<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\CartItem;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * GET /api/transactions
     * Riwayat transaksi milik user yang sedang login (terbaru di atas).
     */
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->withCount('items')
            ->latest()
            ->paginate(20);

        return TransactionResource::collection($transactions);
    }

    /**
     * POST /api/transactions
     * Checkout: snapshot semua cart user → transaksi + transaction_items,
     * lalu kosongkan cart. Semua dalam 1 DB transaction agar atomic.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(['transfer', 'e_wallet', 'qris', 'cod'])],
        ]);

        $userId = $request->user()->id;

        $cartItems = CartItem::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Keranjang kosong, tidak ada yang bisa di-checkout.',
            ], 422);
        }

        $transaction = DB::transaction(function () use ($cartItems, $validated, $userId) {
            $total = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);

            $trx = Transaction::create([
                'user_id'        => $userId,
                'code'           => $this->generateCode(),
                'total_amount'   => $total,
                'payment_method' => $validated['payment_method'],
                // Validasi manual: transaksi mulai 'pending'. User mengunggah bukti
                // bayar, lalu admin memverifikasi & set status 'paid' (lewat DB/web).
                'status'         => 'pending',
                'paid_at'        => null,
            ]);

            foreach ($cartItems as $item) {
                $product = $item->product;
                $trx->items()->create([
                    'product_id'        => $product->id,
                    'product_title'     => $product->title,
                    'product_type'      => $product->type,
                    'product_image_url' => $product->image_url,
                    'price'             => $product->price,
                    'quantity'          => $item->quantity,
                    'subtotal'          => $product->price * $item->quantity,
                ]);
            }

            CartItem::where('user_id', $userId)->delete();

            return $trx->load('items');
        });

        // Generate token Snap Midtrans agar mobile/web bisa langsung membayar.
        // Kalau gagal (mis. key belum diisi), transaksi tetap dibuat — user bisa
        // retry lewat endpoint /pay, atau pakai flow QRIS manual sebagai fallback.
        try {
            app(MidtransService::class)->createSnap($transaction);
            $transaction->refresh()->load('items');
        } catch (\Throwable $e) {
            Log::warning('Midtrans createSnap gagal saat checkout: '.$e->getMessage());
        }

        return response()->json([
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    /**
     * POST /api/transactions/{transaction}/pay
     * Generate (atau regenerate) token Snap untuk transaksi pending.
     * Dipakai untuk retry pembayaran dari halaman detail.
     */
    public function pay(Request $request, Transaction $transaction, MidtransService $midtrans)
    {
        abort_if($transaction->user_id !== $request->user()->id, 403, 'Tidak diizinkan.');
        abort_if($transaction->status === 'paid', 422, 'Transaksi sudah lunas.');

        // Reuse token Snap yang sudah ada agar tidak kena error duplicate order_id
        // di Midtrans (order_id = code, tidak boleh dipakai ulang untuk transaksi
        // baru). Hanya generate bila belum pernah dibuat — mis. checkout-nya gagal.
        if (! $transaction->payment_url) {
            $midtrans->createSnap($transaction);
            $transaction->refresh();
        }

        return response()->json([
            'data' => [
                'snap_token'  => $transaction->snap_token,
                'payment_url' => $transaction->payment_url,
            ],
        ]);
    }

    /**
     * POST /api/midtrans/notification
     * Webhook server-to-server dari Midtrans. TANPA auth — diamankan lewat
     * verifikasi signature. Satu-satunya sumber kebenaran status pembayaran.
     */
    public function notification(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        if (! $midtrans->isValidSignature($payload)) {
            Log::warning('Midtrans webhook: signature tidak valid', ['order_id' => $payload['order_id'] ?? null]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $trx = Transaction::where('code', $payload['order_id'] ?? '')->first();
        if (! $trx) {
            return response()->json(['message' => 'Transaksi tidak ditemukan.'], 404);
        }

        $applied = $midtrans->applyStatus($trx, $payload);

        if (! $applied) {
            Log::info('Midtrans webhook diabaikan (sudah paid)', [
                'order_id' => $trx->code,
                'incoming' => $payload['transaction_status'] ?? null,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * POST /api/transactions/{transaction}/sync-status
     * Tarik status transaksi langsung dari Midtrans (Status API) dan update DB.
     * Fallback bila notifikasi webhook meleset — dipakai oleh tombol
     * "Cek Status Pembayaran" di app, jadi status tidak bergantung webhook saja.
     */
    public function syncStatus(Request $request, Transaction $transaction, MidtransService $midtrans)
    {
        abort_if($transaction->user_id !== $request->user()->id, 403, 'Tidak diizinkan.');

        // Sudah final 'paid' → tidak perlu tanya Midtrans lagi.
        if ($transaction->status !== 'paid') {
            $payload = $midtrans->fetchStatus($transaction->code);
            if ($payload) {
                $midtrans->applyStatus($transaction, $payload);
            }
        }

        $transaction->refresh()->load('items');

        return response()->json([
            'data' => new TransactionResource($transaction),
        ]);
    }

    /**
     * GET /api/transactions/{transaction}
     * Detail transaksi termasuk item-itemnya.
     */
    public function show(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $transaction->load('items');

        return response()->json([
            'data' => new TransactionResource($transaction),
        ]);
    }

    /**
     * GET /api/my-products
     * Daftar product_id yang sudah dibeli user (transaksi paid).
     * Ringan — hanya array integer, bukan resource penuh.
     */
    public function myProductIds(Request $request)
    {
        $ids = TransactionItem::whereHas('transaction', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id)
              ->where('status', 'paid');
        })
        ->whereNotNull('product_id')
        ->pluck('product_id')
        ->unique()
        ->values();

        return response()->json(['data' => $ids]);
    }

    /**
     * GET /api/my-learning
     * Produk yang sudah dimiliki user (dari transaksi paid), lengkap dengan
     * judul/tipe/gambar untuk halaman "Produk Saya". Dedup per product_id dan
     * TIDAK bergantung pada pagination riwayat — sumber kebenaran sendiri.
     */
    public function myProducts(Request $request)
    {
        $items = TransactionItem::whereHas('transaction', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id)
              ->where('status', 'paid');
        })
        ->whereNotNull('product_id')
        ->latest('id')          // ambil snapshot terbaru per produk
        ->get()
        ->unique('product_id')  // satu entri per produk
        ->values();

        return response()->json([
            'data' => $items->map(fn ($item) => [
                'product_id'        => $item->product_id,
                'product_title'     => $item->product_title,
                'product_type'      => $item->product_type,
                'product_image_url' => $item->product_image_url,
            ])->all(),
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
