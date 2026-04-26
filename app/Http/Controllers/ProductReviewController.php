<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductReviewResource;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductReviewController extends Controller
{
    /**
     * GET /api/products/{product}/reviews
     *
     * Semua review untuk satu produk. Response menyertakan product_type
     * yang diambil dari product.type (modul / kelas / bootcamp) —
     * tidak perlu kolom duplikat di tabel reviews.
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json([
            'product_type' => $product->type,
            'product_id'   => $product->id,
            'data'         => ProductReviewResource::collection($reviews->items()),
            'meta'         => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'total'        => $reviews->total(),
                'per_page'     => $reviews->perPage(),
            ],
        ]);
    }

    /**
     * POST /api/products/{product}/reviews
     *
     * Simpan review baru. Satu user hanya boleh review satu kali per produk
     * (dijaga oleh unique constraint DB + validasi unique Eloquent).
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        // Cegah duplicate sebelum menyentuh DB (pesan error yang ramah)
        $alreadyReviewed = ProductReview::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' => 'Kamu sudah memberikan ulasan untuk produk ini.',
            ], 422);
        }

        $review = ProductReview::create([
            'product_id' => $product->id,
            'user_id'    => $request->user()->id,
            'stars'      => $validated['rating'],
            'text'       => $validated['comment'],
        ]);

        $review->load('user:id,name');

        return response()->json(['data' => new ProductReviewResource($review)], 201);
    }

    /**
     * PATCH /api/products/{product}/reviews/{review}
     *
     * Update review milik sendiri. Rating dan/atau comment bisa dikirim parsial.
     */
    public function update(Request $request, Product $product, ProductReview $review)
    {
        Gate::authorize('update', $review);

        $validated = $request->validate([
            'rating'  => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|min:5|max:1000',
        ]);

        $review->update([
            'stars' => $validated['rating'] ?? $review->stars,
            'text'  => $validated['comment'] ?? $review->text,
        ]);

        $review->load('user:id,name');

        return response()->json(['data' => new ProductReviewResource($review)]);
    }

    /**
     * DELETE /api/products/{product}/reviews/{review}
     *
     * Hapus review milik sendiri.
     */
    public function destroy(Request $request, Product $product, ProductReview $review)
    {
        Gate::authorize('delete', $review);
        $review->delete();

        return response()->json(null, 204);
    }
}
