<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;

/**
 * Controller WEB (Blade) untuk fitur On Demand Mentoring.
 *
 * Menyajikan halaman publik daftar mentor (sumber data yang sama dengan yang
 * dikonsumsi app Flutter lewat Api\MentorController) sekaligus CRUD mentor
 * untuk area admin. Endpoint API mentor tetap di App\Http\Controllers\MentorController.
 */
class MentorWebController extends Controller
{
    /**
     * Halaman publik: katalog mentor on demand.
     */
    public function publicIndex()
    {
        // Mentor yang tersedia tampil lebih dulu, lalu urut rating tertinggi.
        $mentors = Mentor::orderByDesc('available')
            ->orderByDesc('rating')
            ->get();

        return view('on-demand-mentoring', compact('mentors'));
    }

    /**
     * Halaman admin: manajemen mentor on demand.
     */
    public function adminIndex()
    {
        $mentors = Mentor::orderBy('id', 'desc')->get();

        return view('admin.mentors.index', compact('mentors'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data = $this->normalize($request, $data);

        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $this->storeImage($request->file('avatar'), 'mentors');
        }

        Mentor::create($data);

        return redirect()->back()->with('success', 'Mentor berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $mentor = Mentor::findOrFail($id);

        $data = $this->validateData($request);
        $data = $this->normalize($request, $data, $mentor);

        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $this->storeImage($request->file('avatar'), 'mentors');
        }

        $mentor->update($data);

        return redirect()->back()->with('success', 'Mentor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mentor = Mentor::findOrFail($id);
        $mentor->delete();

        return redirect()->back()->with('success', 'Mentor berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'              => 'required|string|max:255',
            'title'             => 'required|string|max:255',
            'about'             => 'nullable|string',
            'highlights'        => 'nullable|string',
            'response_time'     => 'nullable|string|max:50',
            'rating'            => 'nullable|numeric|between:0,5',
            'sessions'          => 'nullable|integer|min:0',
            'available'         => 'nullable|boolean',
            'price_per_session' => 'required|integer|min:0',
            'tags'              => 'nullable|string',
            'avatar'            => 'nullable|image|max:2048',
        ]);
    }

    /**
     * Ubah input string (tags & highlights) menjadi array dan pastikan flag
     * boolean/angka bertipe benar sebelum disimpan.
     *
     * $existing dipakai saat update untuk mempertahankan ikon highlight yang
     * sudah ada (highlights disimpan sbg objek {icon, text} — format yang sama
     * dgn yang dikonsumsi app Flutter).
     */
    private function normalize(Request $request, array $data, ?Mentor $existing = null): array
    {
        // Tags: dipisah koma.
        $data['tags'] = $this->splitList($request->input('tags'), ',');

        // Highlights: satu poin per baris → objek {icon, text}. Ikon lama
        // dipertahankan berdasarkan urutan; poin baru memakai ikon default.
        $prevIcons = collect($existing?->highlights ?? [])
            ->map(fn ($h) => is_array($h) ? ($h['icon'] ?? null) : null)
            ->all();

        $data['highlights'] = collect($this->splitList($request->input('highlights'), "\n"))
            ->map(fn ($text, $i) => [
                'icon' => $prevIcons[$i] ?? 'circle-check',
                'text' => $text,
            ])
            ->all();

        $data['available'] = $request->boolean('available');
        $data['rating'] = $request->filled('rating') ? (float) $request->input('rating') : 0;
        $data['sessions'] = (int) $request->input('sessions', 0);

        // Field avatar adalah file upload, bukan kolom tabel.
        unset($data['avatar']);

        return $data;
    }

    /**
     * Pecah string menjadi array bersih (trim, buang yang kosong).
     */
    private function splitList(?string $value, string $delimiter): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(explode($delimiter, $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Simpan file gambar langsung ke /public/uploads/{dir} dan kembalikan
     * path relatif. Konsisten dengan CompetitionController & UploadController
     * (tidak bergantung pada `php artisan storage:link`).
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
