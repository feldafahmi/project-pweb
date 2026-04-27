@extends('layouts.dashboard')

@section('title', 'Produk Saya')
@section('page-title', 'Produk Saya')
@section('page-subtitle', \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y'))

@php
    $stats = [
        ['label' => 'Total Produk', 'value' => 0, 'icon' => 'fa-cube', 'tone' => 'navy'],
        ['label' => 'Sedang Berjalan', 'value' => 0, 'icon' => 'fa-play', 'tone' => 'green'],
        ['label' => 'Selesai', 'value' => 0, 'icon' => 'fa-circle-check', 'tone' => 'purple'],
        ['label' => 'Wishlist', 'value' => 0, 'icon' => 'fa-heart', 'tone' => 'pink'],
    ];

    $tonePalette = [
        'navy' => 'bg-navy-50 text-navy-600',
        'green' => 'bg-green-50 text-green-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'pink' => 'bg-pink-50 text-pink-600',
    ];
@endphp

@section('content')
    {{-- Section 1: Stats grid --}}
    <section class="mb-8">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $tonePalette[$stat['tone']] }}">
                        <i class="fas {{ $stat['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-extrabold text-navy-600">{{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Section 2: Search & filter --}}
    <section class="mb-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="search" placeholder="Cari produk kamu..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none transition-colors focus:border-navy-600">
            </div>
            <div class="relative sm:w-64">
                <select
                    class="w-full appearance-none rounded-xl border border-slate-200 bg-white py-3 pl-4 pr-10 text-sm font-semibold text-navy-600 outline-none transition-colors focus:border-navy-600">
                    <option>Semua Produk Saya</option>
                    <option>Sedang Berjalan</option>
                    <option>Selesai</option>
                    <option>Wishlist</option>
                </select>
                <i class="fas fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
            </div>
        </div>
    </section>

    {{-- Section 3: Empty state --}}
    <section class="rounded-2xl border border-slate-100 bg-white">
        <x-empty-state icon="fa-book-open" title="Tidak Ada Produk Ditemukan"
            description="Kamu belum punya produk aktif. Jelajahi katalog kami dan mulai perjalanan menuju juara.">
            <x-slot:cta>
                <a href="{{ route('produk') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-6 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-purple-700 hover:shadow-lg hover:shadow-purple-600/20">
                    <i class="fas fa-compass"></i>
                    <span>Jelajahi Produk</span>
                </a>
            </x-slot:cta>
        </x-empty-state>
    </section>
@endsection
