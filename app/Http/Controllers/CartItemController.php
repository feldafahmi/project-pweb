<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CartItemController extends Controller
{
    /**
     * GET /api/cart
     * Semua cart item milik user yang sedang login.
     */
    public function index(Request $request)
    {
        $items = CartItem::where('user_id', $request->user()->id)
            ->with('product')
            ->get();

        return response()->json(['data' => CartItemResource::collection($items)]);
    }

    /**
     * POST /api/cart
     * Tambah produk ke cart. Jika sudah ada, tambah quantity-nya.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $item = DB::transaction(function () use ($request, $validated) {
            $item = CartItem::firstOrCreate(
                [
                    'user_id'    => $request->user()->id,
                    'product_id' => $validated['product_id'],
                ],
                ['quantity' => $validated['quantity']]
            );

            if (! $item->wasRecentlyCreated) {
                $item->increment('quantity', $validated['quantity']);
                $item->refresh();
            }

            $item->load('product');

            return $item;
        });

        return response()->json(['data' => new CartItemResource($item)], 201);
    }

    /**
     * PATCH /api/cart/{cartItem}
     * Set quantity eksplisit (bukan increment).
     */
    public function update(Request $request, CartItem $cartItem)
    {
        Gate::authorize('update', $cartItem);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);
        $cartItem->load('product');

        return response()->json(['data' => new CartItemResource($cartItem)]);
    }

    /**
     * DELETE /api/cart/{cartItem}
     * Hapus satu item dari cart.
     */
    public function destroy(Request $request, CartItem $cartItem)
    {
        Gate::authorize('delete', $cartItem);
        $cartItem->delete();

        return response()->json(null, 204);
    }

    /**
     * DELETE /api/cart/clear
     * Hapus semua item cart milik user yang sedang login.
     * Route ini harus didaftarkan SEBELUM apiResource agar "clear"
     * tidak di-bind sebagai {cartItem}.
     */
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return response()->json(null, 204);
    }
}
