<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
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
