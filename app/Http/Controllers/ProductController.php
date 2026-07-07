<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Controller WEB (Blade) untuk produk. Endpoint API ada di
 * App\Http\Controllers\Api\ProductController.
 */
class ProductController extends Controller
{
    /**
     * Halaman katalog produk publik.
     */
    public function index(Request $request)
    {
        $type   = $request->query('type');
        $search = trim((string) $request->query('search', ''));

        $products = Product::query()
            ->when(in_array($type, ['kelas', 'bootcamp', 'modul'], true), function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(8)
            ->withQueryString();

        return view('produk', [
            'products'     => $products,
            'activeType'   => in_array($type, ['kelas', 'bootcamp', 'modul'], true) ? $type : 'semua',
            'searchTerm'   => $search,
        ]);
    }

    /**
     * Halaman detail produk publik — menampilkan kurikulum sesuai tipe:
     *   modul    → chapters (Daftar Isi, per-bab PDF)
     *   kelas    → curriculum_sections → items (video/pdf)
     *   bootcamp → curriculum_sections (mingguan, live) + batches
     *
     * Meniru layar detail di aplikasi mobile.
     */
    public function show(Request $request, Product $product)
    {
        $product->load(['author', 'reviews.user']);

        // Produk yang sudah dimiliki user (transaksi paid) → item ikut terbuka.
        $purchased = false;
        if ($user = $request->user()) {
            $purchased = $user->products()->where('products.id', $product->id)->exists();
        }

        // Normalisasi kurikulum menjadi struktur seragam: [ {title, subtitle, items:[...]} ]
        // Aturan akses (sama dengan endpoint mobile /products/{id}/content):
        // item terbuka bila is_free ATAU produk sudah dibeli. content_url HANYA
        // dibocorkan untuk item yang terbuka — materi berbayar tetap terkunci.
        if ($product->type === 'modul') {
            // Modul: satu section "Daftar Isi" berisi semua bab.
            $sections = [[
                'title'    => 'Daftar Isi',
                'subtitle' => $product->total_pages ? $product->total_pages . ' halaman' : null,
                'items'    => $product->chapters()->get()->map(fn ($ch) => [
                    'title'       => trim($ch->chapter_number . '  ' . $ch->title),
                    'meta'        => $ch->page_range,
                    'type'        => 'pdf',
                    'is_free'     => (bool) $ch->is_free,
                    'content_url' => ($ch->is_free || $purchased) ? $ch->file_url : null,
                ])->all(),
            ]];
        } else {
            $sections = $product->curriculumSections()->with('items')->get()->map(fn ($sec) => [
                'title'    => $sec->title,
                'subtitle' => $sec->subtitle,
                'items'    => $sec->items->map(fn ($it) => [
                    'title'       => $it->title,
                    'meta'        => $it->duration,
                    'type'        => $it->type,
                    'is_free'     => (bool) $it->is_free,
                    'content_url' => ($it->is_free || $purchased) ? $it->content_url : null,
                ])->all(),
            ])->all();
        }

        $batches = $product->type === 'bootcamp'
            ? $product->batches()->get()
            : collect();

        $totalLessons = collect($sections)->sum(fn ($s) => count($s['items']));

        return view('produk-detail', [
            'product'      => $product,
            'sections'     => $sections,
            'batches'      => $batches,
            'purchased'    => $purchased,
            'totalLessons' => $totalLessons,
        ]);
    }

    /**
     * Halaman admin: daftar produk.
     */
    public function adminIndex(Request $request)
    {
        // Muat relasi kurikulum agar form edit bisa memuat data lengkap
        // (chapters / curriculum_sections+items / batches), seperti di mobile.
        $products = Product::with(['chapters', 'curriculumSections.items', 'batches'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($request, $data) {
            $base = $this->baseData($request, $data);

            $base['image_url'] = $request->hasFile('image')
                ? $this->storeImage($request->file('image'), 'products')
                : 'img/63815.png';

            $product = Product::create($base);
            $this->syncNested($product, $request);
        });

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $this->validateData($request);

        DB::transaction(function () use ($request, $data, $product) {
            $base = $this->baseData($request, $data);

            if ($request->hasFile('image')) {
                $base['image_url'] = $this->storeImage($request->file('image'), 'products');
            }

            $product->update($base);
            $this->syncNested($product, $request);
        });

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Validasi payload form (base + relasi bersarang), mengikuti skema yang
     * sama dengan API admin agar web & mobile setara.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'type'           => 'required|in:kelas,bootcamp,modul',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'original_price' => 'required|integer|min:0',
            'price'          => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
            'video_url'      => 'nullable|string|max:255',
            'whatsapp_link'  => 'nullable|string|max:255',
            'rating'         => 'nullable|numeric|between:0,5',
            'win_rate'       => 'nullable|integer|min:0|max:100',
            'total_pages'    => 'nullable|integer|min:0',
            'students'       => 'nullable|integer|min:0',
            'duration'       => 'nullable|string|max:255',
            'is_featured'    => 'nullable|boolean',
            'is_bestseller'  => 'nullable|boolean',

            'learnings'      => 'nullable|array',
            'learnings.*'    => 'nullable|string|max:255',
            'includes'       => 'nullable|array',
            'includes.*.icon' => 'nullable|string|max:50',
            'includes.*.text' => 'nullable|string|max:255',

            'chapters'                  => 'sometimes|array',
            'chapters.*.title'          => 'nullable|string|max:255',
            'chapters.*.chapter_number' => 'nullable|string|max:20',
            'chapters.*.page_range'     => 'nullable|string|max:50',
            'chapters.*.file_url'       => 'nullable|string|max:1000',
            'chapters.*.is_free'        => 'nullable|boolean',

            'curriculum_sections'                       => 'sometimes|array',
            'curriculum_sections.*.title'               => 'nullable|string|max:255',
            'curriculum_sections.*.subtitle'            => 'nullable|string|max:255',
            'curriculum_sections.*.items'               => 'nullable|array',
            'curriculum_sections.*.items.*.title'       => 'nullable|string|max:255',
            'curriculum_sections.*.items.*.duration'    => 'nullable|string|max:50',
            'curriculum_sections.*.items.*.type'        => 'nullable|in:video,pdf,live',
            'curriculum_sections.*.items.*.content_url' => 'nullable|string|max:1000',
            'curriculum_sections.*.items.*.is_free'     => 'nullable|boolean',

            'batches'              => 'sometimes|array',
            'batches.*.label'      => 'nullable|string|max:255',
            'batches.*.date_range' => 'nullable|string|max:255',
            'batches.*.spots'      => 'nullable|integer|min:0',
            'batches.*.status'     => 'nullable|in:open,soon,closed',
        ]);
    }

    /**
     * Susun kolom-kolom tabel products (skalar + learnings/includes), termasuk
     * boolean yang harus selalu di-set (checkbox tak tercentang = false).
     */
    private function baseData(Request $request, array $data): array
    {
        $base = Arr::only($data, [
            'type', 'title', 'description', 'original_price', 'price',
            'video_url', 'whatsapp_link', 'rating', 'win_rate', 'total_pages',
            'students', 'duration',
        ]);

        $base['is_featured']   = $request->boolean('is_featured');
        $base['is_bestseller'] = $request->boolean('is_bestseller');

        // learnings: buang string kosong.
        $base['learnings'] = collect($request->input('learnings', []))
            ->map(fn ($v) => trim((string) $v))
            ->filter()
            ->values()
            ->all();

        // includes: buang baris tanpa teks.
        $base['includes'] = collect($request->input('includes', []))
            ->map(fn ($row) => [
                'icon' => trim((string) ($row['icon'] ?? '')),
                'text' => trim((string) ($row['text'] ?? '')),
            ])
            ->filter(fn ($row) => $row['text'] !== '')
            ->values()
            ->all();

        return $base;
    }

    /**
     * Sinkronkan relasi bersarang sesuai tipe produk. Relasi yang tak relevan
     * dengan tipe dibersihkan agar tak ada sisa data lintas-tipe saat admin
     * mengganti tipe produk.
     */
    private function syncNested(Product $product, Request $request): void
    {
        if ($product->type === 'modul') {
            $this->syncChapters($product, $request);
            $product->curriculumSections()->delete(); // items ikut terhapus (cascade FK)
            $product->batches()->delete();
            return;
        }

        // kelas & bootcamp → curriculum_sections
        $this->syncSections($product, $request);
        $product->chapters()->delete();

        if ($product->type === 'bootcamp') {
            $this->syncBatches($product, $request);
        } else {
            $product->batches()->delete();
        }
    }

    private function syncChapters(Product $product, Request $request): void
    {
        $product->chapters()->delete();

        foreach (array_values((array) $request->input('chapters', [])) as $i => $ch) {
            if (empty($ch['title'])) {
                continue;
            }
            $product->chapters()->create([
                'chapter_number' => $ch['chapter_number'] ?: str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'title'          => $ch['title'],
                'page_range'     => $ch['page_range'] ?? '',
                'file_url'       => $ch['file_url'] ?? null,
                'is_free'        => filter_var($ch['is_free'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'sort_order'     => $i,
            ]);
        }
    }

    private function syncSections(Product $product, Request $request): void
    {
        $product->curriculumSections()->delete(); // items ikut terhapus (cascade FK)

        foreach (array_values((array) $request->input('curriculum_sections', [])) as $i => $sec) {
            if (empty($sec['title'])) {
                continue;
            }
            $section = $product->curriculumSections()->create([
                'title'      => $sec['title'],
                'subtitle'   => $sec['subtitle'] ?? null,
                'sort_order' => $i,
            ]);

            foreach (array_values((array) ($sec['items'] ?? [])) as $j => $it) {
                if (empty($it['title'])) {
                    continue;
                }
                $section->items()->create([
                    'title'       => $it['title'],
                    'duration'    => $it['duration'] ?? '',
                    'type'        => $it['type'] ?? 'video',
                    'content_url' => $it['content_url'] ?? null,
                    'is_free'     => filter_var($it['is_free'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'sort_order'  => $j,
                ]);
            }
        }
    }

    private function syncBatches(Product $product, Request $request): void
    {
        $product->batches()->delete();

        foreach (array_values((array) $request->input('batches', [])) as $b) {
            if (empty($b['label'])) {
                continue;
            }
            $product->batches()->create([
                'label'      => $b['label'],
                'date_range' => $b['date_range'] ?? '',
                'spots'      => (int) ($b['spots'] ?? 0),
                'status'     => $b['status'] ?? 'open',
            ]);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
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
