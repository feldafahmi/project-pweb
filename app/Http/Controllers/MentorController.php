<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    /**
     * GET /api/mentors
     *
     * Query params:
     *   available (0|1, optional) – filter ketersediaan mentor
     *   limit     (int, default 10)
     */
    public function index(Request $request)
    {
        $query = Mentor::query();

        if ($request->filled('available')) {
            $query->where('available', $request->boolean('available'));
        }

        $limit = (int) $request->input('limit', 10);

        return response()->json([
            'data' => $query->limit($limit)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * GET /api/mentors/{mentor}
     *
     * Return single mentor lengkap dengan slots (yang available),
     * reviews, plus rating_distribution & total_reviews.
     */
    public function show(Mentor $mentor)
    {
        $mentor->load([
            'slots'   => fn ($q) => $q->limit(10),
            'reviews' => fn ($q) => $q->limit(10),
        ]);

        $allReviews = $mentor->reviews()->get();
        $totalReviews = $allReviews->count();

        $distribution = collect([5, 4, 3, 2, 1])->map(function ($s) use ($allReviews, $totalReviews) {
            if ($totalReviews === 0) {
                return 0;
            }
            $count = $allReviews->where('stars', $s)->count();
            return (int) round(($count / $totalReviews) * 100);
        })->values();

        return response()->json([
            'data' => array_merge($mentor->toArray(), [
                'rating_distribution' => $distribution,
                'total_reviews'       => $totalReviews,
            ]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mentor $mentor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mentor $mentor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mentor $mentor)
    {
        //
    }
}
