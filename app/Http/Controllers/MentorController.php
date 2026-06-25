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
     * POST /api/admin/mentors (admin only)
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $mentor = Mentor::create($data);

        return response()->json([
            'message' => 'Mentor berhasil dibuat.',
            'data'    => $mentor,
        ], 201);
    }

    /**
     * GET /api/mentors/{mentor}
     *
     * Return single mentor beserta detail profilnya.
     */
    public function show(Mentor $mentor)
    {
        return response()->json([
            'data' => $mentor,
        ]);
    }

    /**
     * PUT/PATCH /api/admin/mentors/{mentor} (admin only)
     */
    public function update(Request $request, Mentor $mentor)
    {
        $data = $this->validateData($request, $mentor->id);
        $mentor->update($data);

        return response()->json([
            'message' => 'Mentor berhasil diperbarui.',
            'data'    => $mentor,
        ]);
    }

    /**
     * DELETE /api/admin/mentors/{mentor} (admin only)
     */
    public function destroy(Mentor $mentor)
    {
        $mentor->delete();

        return response()->json([
            'message' => 'Mentor berhasil dihapus.',
        ]);
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $req = $id !== null ? 'sometimes' : 'required';

        return $request->validate([
            'name'              => [$req, 'string', 'max:255'],
            'title'             => [$req, 'string', 'max:255'],
            'about'             => ['nullable', 'string'],
            'highlights'        => ['nullable', 'array'],
            'response_time'     => ['nullable', 'string', 'max:50'],
            'rating'            => ['nullable', 'numeric', 'between:0,5'],
            'sessions'          => ['nullable', 'integer', 'min:0'],
            'available'         => ['nullable', 'boolean'],
            'price_per_session' => [$req, 'integer', 'min:0'],
            'tags'              => ['nullable', 'array'],
            'avatar_url'        => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
