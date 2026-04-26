<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * GET /api/products
     *
     * Query params:
     *   search (string, optional) – LIKE %search% pada title
     *   type   (string, optional) – modul | kelas | bootcamp
     *   sort   (string, default 'terpopuler') – terpopuler | terbaru | termurah
     *   page   (int)              – pagination Laravel default
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        switch ($request->input('sort', 'terpopuler')) {
            case 'terbaru':
                $query->orderBy('created_at', 'desc');
                break;
            case 'termurah':
                $query->orderBy('price', 'asc');
                break;
            case 'terpopuler':
            default:
                $query->orderBy('students', 'desc');
                break;
        }

        return response()->json($query->paginate(10));
    }

    /**
     * GET /api/products/{product}
     *
     * Return single product lengkap dengan author, curriculum, chapters,
     * batches, reviews, plus rating_distribution & total_reviews untuk
     * RatingSummaryCard di Flutter.
     */
    public function show(Product $product)
    {
        $product->load([
            'author',
            'curriculumSections.items',
            'chapters',
            'batches',
            'reviews' => fn ($q) => $q->limit(10),
        ]);

        $allReviews = $product->reviews()->get();
        $totalReviews = $allReviews->count();

        $distribution = collect([5, 4, 3, 2, 1])->map(function ($s) use ($allReviews, $totalReviews) {
            if ($totalReviews === 0) {
                return 0;
            }
            $count = $allReviews->where('stars', $s)->count();
            return (int) round(($count / $totalReviews) * 100);
        })->values();

        return response()->json([
            'data' => array_merge($product->toArray(), [
                'rating_distribution' => $distribution,
                'total_reviews'       => $totalReviews,
            ]),
        ]);
    }
}
