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

    /**
     * POST /api/admin/competitions (admin only)
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $competition = Competition::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Lomba berhasil dibuat.',
            'data'    => $competition,
        ], 201);
    }

    /**
     * PUT/PATCH /api/admin/competitions/{competition} (admin only)
     */
    public function update(Request $request, Competition $competition)
    {
        $data = $this->validateData($request, $competition->id);
        $competition->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Lomba berhasil diperbarui.',
            'data'    => $competition,
        ]);
    }

    /**
     * DELETE /api/admin/competitions/{competition} (admin only)
     */
    public function destroy(Competition $competition)
    {
        $competition->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Lomba berhasil dihapus.',
        ]);
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $req = $id !== null ? 'sometimes' : 'required';

        return $request->validate([
            'title'            => [$req, 'string', 'max:255'],
            'category'         => [$req, 'in:Business Case,Business Plan,Business Model Canvas,UI/UX,LKTI'],
            'start_date'       => [$req, 'date'],
            'end_date'         => [$req, 'date', 'after_or_equal:start_date'],
            'target_audience'  => [$req, 'in:Mahasiswa,Umum'],
            'registration_fee' => ['nullable', 'integer', 'min:0'],
            'total_prize'      => ['nullable', 'integer', 'min:0'],
            'organizer'        => [$req, 'string', 'max:255'],
            'image_url'        => ['nullable', 'string', 'max:1000'],
            'link_pendaftaran' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
