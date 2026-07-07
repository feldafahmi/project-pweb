<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display user dashboard index.
     */
    public function index()
    {
        $user = auth()->user();

        // Retrieve products bought by the user
        $purchasedProducts = $user->products()->get();

        // Retrieve milestones from database. Milestone bawaan di-seed saat
        // registrasi (lihat User::seedDefaultMilestones), bukan di sini, agar
        // request GET tidak melakukan operasi tulis.
        $milestones = $user->milestones()->orderBy('created_at', 'asc')->get();

        // Calculate statistics
        $totalProducts = $purchasedProducts->count();
        $completedMilestones = $milestones->where('completed', true)->count();
        $totalMilestones = $milestones->count();

        $stats = [
            ['label' => 'Total Produk', 'value' => $totalProducts, 'icon' => 'fa-cube', 'tone' => 'navy'],
            ['label' => 'Sedang Berjalan', 'value' => $totalMilestones - $completedMilestones, 'icon' => 'fa-play', 'tone' => 'green'],
            ['label' => 'Selesai', 'value' => $completedMilestones, 'icon' => 'fa-circle-check', 'tone' => 'purple'],
            ['label' => 'Wishlist', 'value' => 0, 'icon' => 'fa-heart', 'tone' => 'pink'],
        ];

        return view('dashboard.index', compact('purchasedProducts', 'milestones', 'stats'));
    }

    /**
     * Halaman belajar untuk produk yang SUDAH DIMILIKI user.
     * Menampilkan kurikulum sesuai tipe (modul → Daftar Isi, kelas/bootcamp →
     * sections) dengan konten terbuka (file_url / content_url) karena sudah dibeli.
     * Inilah "sinkron dengan kurikulum" setelah pembelian.
     */
    public function learn(Product $product)
    {
        $user = auth()->user();

        // Wajib sudah memiliki produk (transaksi paid). Kalau belum, arahkan ke
        // halaman jual produk agar bisa membeli dulu.
        $owned = $user->products()->where('products.id', $product->id)->exists();
        abort_unless($owned, 403, 'Kamu belum memiliki akses ke produk ini.');

        if ($product->type === 'modul') {
            // Modul: satu section "Daftar Isi" berisi semua bab (dengan file PDF).
            $sections = [[
                'title'    => 'Daftar Isi',
                'subtitle' => $product->total_pages ? $product->total_pages . ' halaman' : null,
                'items'    => $product->chapters()->get()->map(fn ($ch) => [
                    'title'       => trim($ch->chapter_number . '  ' . $ch->title),
                    'meta'        => $ch->page_range,
                    'type'        => 'pdf',
                    'content_url' => $ch->file_url,
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
                    'content_url' => $it->content_url,
                ])->all(),
            ])->all();
        }

        $batches = $product->type === 'bootcamp'
            ? $product->batches()->get()
            : collect();

        $totalLessons = collect($sections)->sum(fn ($s) => count($s['items']));

        return view('dashboard.learn', [
            'product'      => $product,
            'sections'     => $sections,
            'batches'      => $batches,
            'totalLessons' => $totalLessons,
        ]);
    }

    /**
     * Riwayat transaksi user (header + item), terbaru di atas.
     */
    public function transactions()
    {
        $transactions = auth()->user()
            ->transactions()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('dashboard.transactions', compact('transactions'));
    }
}
