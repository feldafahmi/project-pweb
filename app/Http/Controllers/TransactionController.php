<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\CartItem;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return response()->json([
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    /**
     * POST /api/transactions/{transaction}/proof
     * User mengunggah bukti bayar untuk transaksinya yang masih 'pending'.
     * Status TIDAK berubah otomatis — admin yang memverifikasi manual.
     */
    public function uploadProof(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'message' => 'Bukti hanya bisa diunggah saat transaksi menunggu pembayaran.',
            ], 422);
        }

        $request->validate([
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $file = $request->file('proof');
        $dir = public_path('uploads/payment_proofs');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Hapus bukti lama bila user mengunggah ulang.
        if ($transaction->payment_proof) {
            $old = public_path(ltrim($transaction->payment_proof, '/'));
            if (is_file($old)) {
                @unlink($old);
            }
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = 'proof_'.$transaction->id.'_'.time().'.'.$ext;
        $file->move($dir, $filename);

        $transaction->update([
            'payment_proof' => '/uploads/payment_proofs/'.$filename,
        ]);

        $transaction->load('items');

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

    private function generateCode(): string
    {
        do {
            $code = 'TRX-'.strtoupper(Str::random(8));
        } while (Transaction::where('code', $code)->exists());

        return $code;
    }
}
