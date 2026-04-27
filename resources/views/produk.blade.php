@extends('layouts.app')

@section('title', 'Produk')

@php
    $categories = ['Semua', 'Kelas', 'Live Bootcamp', 'Modul'];

    $products = array_fill(0, 4, [
        'image' => 'img/63815.png',
        'title' => 'Bundle: Winner Class dan Module (Debate)',
        'desc' =>
            'Gabungan materi Winner Class (Video on Demand) + modul eksklusif Tips & Trik Juara Lomba UI/UX yang dirancang langsung untuk kamu yang ingin menang, bukan sekadar ikut lomba.',
        'old_price' => '149.000',
        'new_price' => '99.000',
    ]);
@endphp

@section('content')
    <x-breadcrumb current="Produk" />

    {{-- Hero --}}
    <section class="relative flex h-[500px] items-center justify-center">
        <div class="absolute inset-0 z-[1] bg-slate-800">
            <img src="{{ asset('img/Hero-Info-Produk.png') }}" alt="" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        <div class="relative z-[2] px-5 text-center text-white">
            <h1 class="mb-3 text-4xl font-extrabold">Produk Mark-Up</h1>
            <p class="text-base opacity-90">Pilih program yang sudah terbukti membantu memenangkan berbagai kompetisi</p>
        </div>
    </section>

    <section class="mu-container py-16">
        {{-- Filter pills --}}
        <div class="mb-12 flex flex-wrap justify-center gap-4" data-product-filters>
            @foreach ($categories as $i => $cat)
                <button type="button" data-filter="{{ $cat }}"
                    class="rounded-full px-6 py-2 text-sm font-bold transition-colors
                        {{ $i === 0 ? 'bg-purple-500 text-white' : 'bg-slate-100 text-navy-600 hover:bg-slate-200' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        {{-- Product grid --}}
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-8 md:grid-cols-2">
            @foreach ($products as $product)
                <article
                    class="group flex flex-col overflow-hidden rounded-xl border border-slate-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.05)]">
                    <div class="h-56 w-full overflow-hidden bg-slate-200">
                        <img src="{{ asset($product['image']) }}" alt="{{ $product['title'] }}"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="mb-3 text-lg font-extrabold leading-snug text-navy-600">{{ $product['title'] }}</h3>
                        <p class="mb-5 flex-1 text-[13px] leading-relaxed text-slate-500">{{ $product['desc'] }}</p>
                        <div class="flex flex-col border-t border-slate-100 pt-4">
                            <span class="text-xs text-slate-400 line-through">Rp {{ $product['old_price'] }}</span>
                            <span class="text-lg font-extrabold text-purple-500">Rp {{ $product['new_price'] }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
