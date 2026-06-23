<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Process checkout transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|integer|exists:products,id',
        ]);

        $userId = auth()->id();

        foreach ($request->product_ids as $productId) {
            // Ensure no duplicate active products for the user
            $exists = Transaction::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('status', 'completed')
                ->exists();

            if (!$exists) {
                Transaction::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'status' => 'completed',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat!',
        ]);
    }
}
