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

        // Retrieve milestones from database
        $milestones = $user->milestones()->orderBy('created_at', 'asc')->get();

        // Auto-seed default milestones if database is empty
        if ($milestones->isEmpty()) {
            $defaultMilestones = [
                ['text' => 'Membentuk Kelompok & Cari Nama Tim', 'completed' => true],
                ['text' => 'Analisis Kasus dengan Framework SWOT/BCG', 'completed' => false],
                ['text' => 'Asistensi Pitch Deck Bersama Mentor Mark-Up', 'completed' => false],
            ];

            foreach ($defaultMilestones as $dm) {
                $user->milestones()->create($dm);
            }

            // Reload milestones
            $milestones = $user->milestones()->orderBy('created_at', 'asc')->get();
        }

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
}
