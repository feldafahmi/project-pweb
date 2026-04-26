<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * GET /api/products/{product}/reviews?page=1&per_page=10
     *
     * Public — tidak perlu token.
     * Kalau ada token, can_delete di-set berdasarkan kepemilikan.
     */
    public function index(Request $request, Product $product)
    {
        $perPage = max(1, (int) $request->input('per_page', 10));

        // Hitung distribusi rating dengan satu query GROUP BY
        $counts = DB::table('reviews')
            ->where('product_id', $product->id)
            ->selectRaw('rating, COUNT(*) as cnt')
            ->groupBy('rating')
            ->pluck('cnt', 'rating');

        $total   = (int) $counts->sum();
        $avg     = $total > 0
            ? round(DB::table('reviews')->where('product_id', $product->id)->avg('rating'), 2)
            : 0.0;

        $distribution = collect([5, 4, 3, 2, 1])->map(function ($star) use ($counts, $total) {
            $count = (int) $counts->get($star, 0);
            return $total > 0 ? (int) round(($count / $total) * 100) : 0;
        })->values();

        $reviews = Review::where('product_id', $product->id)
            ->with('user:id,name')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'summary' => [
                'rating_avg'   => $avg,
                'total'        => $total,
                'distribution' => $distribution,
            ],
            'data' => ReviewResource::collection($reviews->items()),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }

    /**
     * POST /api/products/{product}/reviews
     *
     * Buat atau timpa review (updateOrCreate — satu user satu review per produk).
     * Setelah simpan, recompute products.rating.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        $review = Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            ['rating' => $validated['rating'], 'comment' => $validated['comment']]
        );

        $this->recomputeProductRating($product->id);

        $review->load('user:id,name');

        return response()->json(['data' => new ReviewResource($review)], 201);
    }

    /**
     * DELETE /api/reviews/{review}
     *
     * Hanya pemilik review yang boleh menghapus.
     * Setelah hapus, recompute products.rating.
     */
    public function destroy(Request $request, Review $review)
    {
        if ($request->user()->id !== $review->user_id) {
            return response()->json([
                'message' => 'Kamu tidak memiliki izin untuk menghapus ulasan ini.',
            ], 403);
        }

        $productId = $review->product_id;
        $review->delete();

        $this->recomputeProductRating($productId);

        return response()->json(null, 204);
    }

    private function recomputeProductRating(int $productId): void
    {
        $avg = DB::table('reviews')->where('product_id', $productId)->avg('rating') ?? 0;
        DB::table('products')->where('id', $productId)->update(['rating' => round($avg, 2)]);
    }
}
