<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /** Key relasi bersarang yang bukan kolom tabel products. */
    private const NESTED_KEYS = ['chapters', 'curriculum_sections', 'batches'];

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
     */
    public function show(Request $request, Product $product)
    {
        $product->load([
            'author',
            'curriculumSections.items',
            'chapters',
            'batches',
            'reviews' => fn ($q) => $q->limit(10),
        ]);

        // URL konten disembunyikan dari user biasa; admin perlu melihatnya.
        if ($request->user()?->role === 'admin') {
            $product->chapters->makeVisible('file_url');
            $product->curriculumSections->each(
                fn ($section) => $section->items->makeVisible('content_url')
            );
        }

        $allReviews = $product->reviews()->get();
        $totalReviews = $allReviews->count();

        $distribution = collect([5, 4, 3, 2, 1])->map(function ($s) use ($allReviews, $totalReviews) {
            if ($totalReviews === 0) {
                return 0;
            }
            $count = $allReviews->where('stars', $s)->count();
            return (int) round(($count / $totalReviews) * 100);
        })->values();

        $ratingAvg = $totalReviews > 0
            ? round((float) $allReviews->avg('stars'), 1)
            : 0.0;

        return response()->json([
            'data' => array_merge($product->toArray(), [
                'rating_distribution' => $distribution,
                'total_reviews'       => $totalReviews,
                'rating_avg'          => $ratingAvg,
            ]),
        ]);
    }

    /**
     * GET /api/products/{product}/content
     *
     * Konten belajar (sections → items). URL konten asli hanya untuk user yang
     * sudah membeli (transaksi paid) atau item is_free; selain itu dikunci.
     */
    public function content(Request $request, Product $product)
    {
        $purchased = TransactionItem::whereHas('transaction', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id)->where('status', 'paid');
        })->where('product_id', $product->id)->exists();

        $sections = $product->type === 'modul'
            ? $this->modulSections($product)
            : $this->curriculumSections($product);

        $sections = array_map(function (array $section) use ($purchased) {
            $section['items'] = array_map(function (array $item) use ($purchased) {
                $unlocked = $purchased || $item['is_free'];
                $item['locked']      = ! $unlocked;
                $item['content_url'] = $unlocked ? $item['content_url'] : null;
                return $item;
            }, $section['items']);
            return $section;
        }, $sections);

        $payload = [
            'id'        => $product->id,
            'type'      => $product->type,
            'title'     => $product->title,
            'purchased' => $purchased,
            'sections'  => $sections,
        ];

        if ($product->type === 'bootcamp') {
            $payload['batches'] = $product->batches()->get()
                ->map(fn ($b) => [
                    'label'      => $b->label,
                    'date_range' => $b->date_range,
                    'spots'      => $b->spots,
                    'status'     => $b->status,
                ])->all();
        }

        return response()->json(['data' => $payload]);
    }

    /** Modul: chapters dipetakan jadi satu section item bertipe pdf. */
    private function modulSections(Product $product): array
    {
        $items = $product->chapters()->get()->map(fn ($ch) => [
            'id'          => $ch->id,
            'title'       => trim($ch->chapter_number.'  '.$ch->title),
            'type'        => 'pdf',
            'duration'    => $ch->page_range,
            'is_free'     => (bool) $ch->is_free,
            'content_url' => $ch->file_url,
        ])->all();

        return [[
            'title'    => 'Daftar Isi',
            'subtitle' => null,
            'items'    => $items,
        ]];
    }

    /** Kelas/Bootcamp: curriculum_sections → items. */
    private function curriculumSections(Product $product): array
    {
        return $product->curriculumSections()->with('items')->get()->map(fn ($sec) => [
            'title'    => $sec->title,
            'subtitle' => $sec->subtitle,
            'items'    => $sec->items->map(fn ($it) => [
                'id'          => $it->id,
                'title'       => $it->title,
                'type'        => $it->type,
                'duration'    => $it->duration,
                'is_free'     => (bool) $it->is_free,
                'content_url' => $it->content_url,
            ])->all(),
        ])->all();
    }

    /**
     * POST /api/admin/products (admin only)
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $product = DB::transaction(function () use ($request, $data) {
            $product = Product::create(Arr::except($data, self::NESTED_KEYS));
            $this->syncNested($product, $request);
            return $product;
        });

        return response()->json([
            'message' => 'Produk berhasil dibuat.',
            'data'    => $product->load(['curriculumSections.items', 'chapters', 'batches']),
        ], 201);
    }

    /**
     * PUT/PATCH /api/admin/products/{product} (admin only)
     */
    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request, $product->id);

        DB::transaction(function () use ($request, $data, $product) {
            $product->update(Arr::except($data, self::NESTED_KEYS));
            $this->syncNested($product, $request);
        });

        return response()->json([
            'message' => 'Produk berhasil diperbarui.',
            'data'    => $product->load(['curriculumSections.items', 'chapters', 'batches']),
        ]);
    }

    /**
     * Sinkronkan relasi bersarang (chapters / curriculum / batches).
     */
    private function syncNested(Product $product, Request $request): void
    {
        if ($request->has('chapters')) {
            $product->chapters()->delete();
            foreach ((array) $request->input('chapters', []) as $i => $ch) {
                $product->chapters()->create([
                    'chapter_number' => $ch['chapter_number'] ?? str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'title'          => $ch['title'] ?? '-',
                    'page_range'     => $ch['page_range'] ?? '',
                    'file_url'       => $ch['file_url'] ?? null,
                    'is_free'        => filter_var($ch['is_free'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'sort_order'     => $i,
                ]);
            }
        }

        if ($request->has('curriculum_sections')) {
            $product->curriculumSections()->delete();
            foreach ((array) $request->input('curriculum_sections', []) as $i => $sec) {
                $section = $product->curriculumSections()->create([
                    'title'      => $sec['title'] ?? '-',
                    'subtitle'   => $sec['subtitle'] ?? null,
                    'sort_order' => $i,
                ]);
                foreach ((array) ($sec['items'] ?? []) as $j => $it) {
                    $section->items()->create([
                        'title'       => $it['title'] ?? '-',
                        'duration'    => $it['duration'] ?? '',
                        'type'        => $it['type'] ?? 'video',
                        'content_url' => $it['content_url'] ?? null,
                        'is_free'     => filter_var($it['is_free'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'sort_order'  => $j,
                    ]);
                }
            }
        }

        if ($request->has('batches')) {
            $product->batches()->delete();
            foreach ((array) $request->input('batches', []) as $b) {
                $product->batches()->create([
                    'label'      => $b['label'] ?? '-',
                    'date_range' => $b['date_range'] ?? '',
                    'spots'      => (int) ($b['spots'] ?? 0),
                    'status'     => $b['status'] ?? 'open',
                ]);
            }
        }
    }

    /**
     * DELETE /api/admin/products/{product} (admin only)
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus.',
        ]);
    }

    /**
     * Validasi payload create/update.
     */
    private function validateData(Request $request, ?int $id = null): array
    {
        $isUpdate = $id !== null;
        $req = $isUpdate ? 'sometimes' : 'required';

        return $request->validate([
            'type'           => [$req, 'in:modul,kelas,bootcamp'],
            'title'          => [$req, 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'learnings'      => ['nullable', 'array'],
            'includes'       => ['nullable', 'array'],
            'rating'         => ['nullable', 'numeric', 'between:0,5'],
            'win_rate'       => ['nullable', 'integer'],
            'total_pages'    => ['nullable', 'integer'],
            'students'       => ['nullable', 'integer'],
            'price'          => [$req, 'integer', 'min:0'],
            'original_price' => ['nullable', 'integer', 'min:0'],
            'duration'       => ['nullable', 'string', 'max:255'],
            'is_featured'    => ['nullable', 'boolean'],
            'is_bestseller'  => ['nullable', 'boolean'],
            'image_url'      => ['nullable', 'string', 'max:1000'],
            'author_id'      => ['nullable', 'integer', 'exists:mentors,id'],

            'learnings.*'     => ['string', 'max:255'],
            'includes.*.icon' => ['nullable', 'string', 'max:50'],
            'includes.*.text' => ['nullable', 'string', 'max:255'],

            'chapters'                  => ['sometimes', 'array'],
            'chapters.*.title'          => ['required', 'string', 'max:255'],
            'chapters.*.chapter_number' => ['nullable', 'string', 'max:20'],
            'chapters.*.page_range'     => ['nullable', 'string', 'max:50'],
            'chapters.*.file_url'       => ['nullable', 'string', 'max:1000'],
            'chapters.*.is_free'        => ['nullable', 'boolean'],

            'curriculum_sections'                 => ['sometimes', 'array'],
            'curriculum_sections.*.title'         => ['required', 'string', 'max:255'],
            'curriculum_sections.*.subtitle'      => ['nullable', 'string', 'max:255'],
            'curriculum_sections.*.items'         => ['nullable', 'array'],
            'curriculum_sections.*.items.*.title' => ['required', 'string', 'max:255'],
            'curriculum_sections.*.items.*.duration' => ['nullable', 'string', 'max:50'],
            'curriculum_sections.*.items.*.type'  => ['nullable', 'in:video,pdf,live'],
            'curriculum_sections.*.items.*.content_url' => ['nullable', 'string', 'max:1000'],
            'curriculum_sections.*.items.*.is_free' => ['nullable', 'boolean'],

            'batches'              => ['sometimes', 'array'],
            'batches.*.label'      => ['required', 'string', 'max:255'],
            'batches.*.date_range' => ['nullable', 'string', 'max:255'],
            'batches.*.spots'      => ['nullable', 'integer', 'min:0'],
            'batches.*.status'     => ['nullable', 'in:open,soon,closed'],
        ]);
    }
}
