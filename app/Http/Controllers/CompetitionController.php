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
     * Web Admin Index
     */
    public function adminIndex(Request $request)
    {
        $competitions = Competition::orderBy('id', 'desc')->get();
        return view('admin.competitions.index', compact('competitions'));
    }

    /**
     * Web Store
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Business Case,Business Plan,Business Model Canvas,UI/UX,LKTI',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'target_audience' => 'required|in:Mahasiswa,Umum',
            'registration_fee' => 'required|integer|min:0',
            'total_prize' => 'required|integer|min:0',
            'organizer' => 'required|string|max:255',
            'link_pendaftaran' => 'nullable|url',
            'poster' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $data['image_url'] = 'storage/' . $path;
        } else {
            $data['image_url'] = 'img/iyref.jpeg';
        }

        Competition::create($data);

        return redirect()->back()->with('success', 'Info lomba berhasil ditambahkan.');
    }

    /**
     * Web Update
     */
    public function update(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Business Case,Business Plan,Business Model Canvas,UI/UX,LKTI',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'target_audience' => 'required|in:Mahasiswa,Umum',
            'registration_fee' => 'required|integer|min:0',
            'total_prize' => 'required|integer|min:0',
            'organizer' => 'required|string|max:255',
            'link_pendaftaran' => 'nullable|url',
            'poster' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $data['image_url'] = 'storage/' . $path;
        }

        $competition->update($data);

        return redirect()->back()->with('success', 'Info lomba berhasil diperbarui.');
    }

    /**
     * Web Destroy
     */
    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->delete();

        return redirect()->back()->with('success', 'Info lomba berhasil dihapus.');
    }
}
