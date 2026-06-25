<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

/**
 * Controller WEB (Blade) untuk info lomba. Endpoint API ada di
 * App\Http\Controllers\Api\CompetitionController.
 */
class CompetitionController extends Controller
{
    /**
     * Halaman publik daftar info lomba.
     */
    public function publicIndex()
    {
        $competitions = Competition::latest()->get();
        return view('info-lomba', compact('competitions'));
    }

    /**
     * Halaman admin: daftar info lomba.
     */
    public function adminIndex(Request $request)
    {
        $competitions = Competition::orderBy('id', 'desc')->get();
        return view('admin.competitions.index', compact('competitions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'category'         => 'required|in:Business Case,Business Plan,Business Model Canvas,UI/UX,LKTI',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date',
            'target_audience'  => 'required|in:Mahasiswa,Umum',
            'registration_fee' => 'required|integer|min:0',
            'total_prize'      => 'required|integer|min:0',
            'organizer'        => 'required|string|max:255',
            'link_pendaftaran' => 'nullable|url',
            'poster'           => 'nullable|image|max:2048',
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

    public function update(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'category'         => 'required|in:Business Case,Business Plan,Business Model Canvas,UI/UX,LKTI',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date',
            'target_audience'  => 'required|in:Mahasiswa,Umum',
            'registration_fee' => 'required|integer|min:0',
            'total_prize'      => 'required|integer|min:0',
            'organizer'        => 'required|string|max:255',
            'link_pendaftaran' => 'nullable|url',
            'poster'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $data['image_url'] = 'storage/' . $path;
        }

        $competition->update($data);

        return redirect()->back()->with('success', 'Info lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->delete();

        return redirect()->back()->with('success', 'Info lomba berhasil dihapus.');
    }
}
