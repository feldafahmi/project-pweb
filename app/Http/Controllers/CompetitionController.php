<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * GET /api/competitions
     *
     * Query params:
     *   search   (string)  – filter by title (partial, case-insensitive)
     *   category (string)  – exact match: "Business Case", "Business Plan",
     *                        "Business Model Canvas", "UI/UX", "LKTI"
     *   per_page (int)     – items per page, default 10
     */
    public function index(Request $request)
    {
        $query = Competition::query()->orderBy('start_date', 'asc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $perPage = (int) $request->input('per_page', 10);
        $competitions = $query->paginate($perPage);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data lomba berhasil diambil',
            'data'    => $competitions,
        ], 200);
    }

    /**
     * GET /api/competitions/{id}
     */
    public function show($id)
    {
        $competition = Competition::findOrFail($id);

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail lomba berhasil diambil',
            'data'    => $competition,
        ], 200);
    }
}
