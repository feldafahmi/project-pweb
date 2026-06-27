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
            $data['image_url'] = $this->storeImage($request->file('poster'), 'posters');
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
            $data['image_url'] = $this->storeImage($request->file('poster'), 'posters');
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

    /**
     * Simpan file gambar langsung ke /public/uploads/{dir} dan kembalikan
     * path relatif (cocok dipakai sebagai image_url via asset()).
     *
     * Sengaja menyimpan langsung ke public/ alih-alih disk 'public', supaya
     * tidak bergantung pada `php artisan storage:link` — symlink sering
     * dinonaktifkan di shared hosting cPanel. Konsisten dengan UploadController.
     */
    private function storeImage(\Illuminate\Http\UploadedFile $file, string $dir): string
    {
        $target = public_path('uploads/' . $dir);
        if (! is_dir($target)) {
            @mkdir($target, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = $dir . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $file->move($target, $filename);

        return 'uploads/' . $dir . '/' . $filename;
    }
}
