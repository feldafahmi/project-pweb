@extends('layouts.app')

@section('content')
    {{-- BREADCRUMB STRIP --}}
    <div class="border-b border-slate-100 bg-white">
        <div class="mx-auto flex max-w-5xl items-center gap-2 px-4 py-4 text-sm text-slate-500">
            <a href="/" class="transition hover:text-[#A855F7]"><i class="fa-solid fa-house"></i></a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="font-semibold text-[#1A2B56]">Program &amp; Produk</span>
        </div>
    </div>

    {{-- HERO SECTION --}}
    <section class="relative flex min-h-[380px] items-center justify-center overflow-hidden bg-navy-600 md:min-h-[440px]">
        {{-- HERO: foto marble + aurora animasi menyala di atasnya --}}
        <div class="absolute inset-0 z-0 overflow-hidden bg-navy-800">
            {{-- Foto latar berwarna --}}
            <img src="{{ asset('img/hero-section-bg.svg') }}" alt=""
                class="absolute inset-0 h-full w-full object-cover object-center">
            {{-- Peredup foto agar aurora & teks menonjol --}}
            <div class="absolute inset-0 bg-navy-900/70"></div>
            {{-- Blob aurora (mix-blend-screen → menyala di atas foto) --}}
            <span class="animate-aurora absolute -left-24 -top-28 h-96 w-96 rounded-full bg-[#A855F7] opacity-70 mix-blend-screen blur-3xl"
                style="animation-duration: 12s;"></span>
            <span class="animate-aurora absolute -right-24 -top-16 h-[30rem] w-[30rem] rounded-full bg-[#6366F1] opacity-65 mix-blend-screen blur-3xl"
                style="animation-duration: 16s; animation-delay: -5s;"></span>
            <span class="animate-aurora absolute -bottom-32 left-1/3 h-[28rem] w-[28rem] rounded-full bg-[#EC4899] opacity-60 mix-blend-screen blur-3xl"
                style="animation-duration: 14s; animation-delay: -9s;"></span>
            <span class="animate-aurora absolute -bottom-24 right-1/4 h-80 w-80 rounded-full bg-[#22D3EE] opacity-50 mix-blend-screen blur-3xl"
                style="animation-duration: 18s; animation-delay: -3s;"></span>
            {{-- Gradient bawah untuk kontras teks & search --}}
            <div class="absolute inset-0 bg-gradient-to-t from-navy-900/70 via-navy-900/10 to-transparent"></div>
        </div>

        <div class="relative z-10 w-full max-w-3xl px-4 py-14 text-center">
            <p class="mb-4 text-xs font-bold uppercase tracking-[0.25em] text-white/70 md:text-sm">Produk Unggulan</p>
            <h1 class="mb-4 text-4xl font-extrabold leading-tight text-white drop-shadow-sm md:text-5xl">Produk Mark-Up</h1>
            <p class="mx-auto mb-8 max-w-xl text-base leading-relaxed text-white/90 md:text-lg">
                Pilih program yang sudah terbukti membantu memenangkan berbagai kompetisi
            </p>

            {{-- SEARCH BAR (menyatu di hero) --}}
            <form method="GET" action="{{ route('produk') }}" class="mx-auto max-w-2xl">
                @if($activeType !== 'semua')
                    <input type="hidden" name="type" value="{{ $activeType }}">
                @endif
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ $searchTerm }}"
                        placeholder="Cari kelas, modul, atau bundling..."
                        class="w-full rounded-full border border-white/20 bg-white py-4 pl-12 pr-28 text-sm text-slate-700 shadow-xl outline-none transition focus:ring-2 focus:ring-[#A855F7] md:text-base">
                    <button type="submit"
                        class="absolute inset-y-2 right-2 rounded-full bg-[#A855F7] px-6 text-sm font-bold text-white transition hover:bg-purple-600">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </section>
    {{-- AKHIR HERO SECTION --}}

    <div class="mx-auto max-w-5xl px-4 pb-20 pt-10">

        {{-- FILTER KATEGORI --}}
        @php
            $filters = [
                'semua'    => 'Semua',
                'kelas'    => 'Kelas',
                'bootcamp' => 'Live Bootcamp',
                'modul'    => 'Modul',
            ];
        @endphp
        <div class="mb-10 flex flex-wrap justify-center gap-3">
            @foreach($filters as $value => $label)
                @php
                    $params = $value === 'semua' ? [] : ['type' => $value];
                    if ($searchTerm !== '') {
                        $params['search'] = $searchTerm;
                    }
                    $isActive = $activeType === $value;
                @endphp
                <a href="{{ route('produk', $params) }}"
                    class="rounded-full px-6 py-2 text-sm font-bold transition {{ $isActive ? 'bg-[#A855F7] text-white hover:bg-purple-600' : 'bg-slate-100 text-[#1A2B56] hover:bg-slate-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if($products->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white py-16 text-center">
                <div class="mb-3 text-4xl text-slate-300"><i class="fa-solid fa-magnifying-glass"></i></div>
                <p class="text-lg font-bold text-[#1A2B56]">Produk tidak ditemukan</p>
                <p class="mt-1 text-sm text-slate-500">
                    Coba kata kunci lain atau
                    <a href="{{ route('produk') }}" class="font-semibold text-[#A855F7] hover:underline">tampilkan semua produk</a>.
                </p>
            </div>
        @endif

        @php
            // Label & ikon badge per tipe produk (dipakai di tiap kartu).
            $typeMeta = [
                'kelas'    => ['label' => 'Kelas',         'icon' => 'fa-chalkboard-user'],
                'bootcamp' => ['label' => 'Live Bootcamp', 'icon' => 'fa-tower-broadcast'],
                'modul'    => ['label' => 'Modul',         'icon' => 'fa-book-open'],
            ];
        @endphp

        {{-- JUMLAH HASIL --}}
        @if($products->isNotEmpty())
            <p class="mb-5 text-sm text-slate-500">
                Menampilkan <span class="font-bold text-[#1A2B56]">{{ $products->total() }}</span> produk
                @if($searchTerm !== '')
                    untuk “<span class="font-semibold text-[#1A2B56]">{{ $searchTerm }}</span>”
                @endif
            </p>
        @endif

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
            @php
                $meta        = $typeMeta[$product->type] ?? ['label' => 'Produk', 'icon' => 'fa-box'];
                $hasDiscount = $product->original_price && $product->original_price > $product->price;
                $discountPct = $hasDiscount
                    ? round(($product->original_price - $product->price) / $product->original_price * 100)
                    : 0;
            @endphp
            {{-- CARD --}}
            <a href="{{ route('produk.show', $product->id) }}"
                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#A855F7] focus-visible:ring-offset-2">
                <div class="relative h-48 w-full overflow-hidden bg-slate-200">
                    <img src="{{ asset($product->image_url ?? 'img/63815.png') }}" alt="{{ $product->title }}"
                        loading="lazy"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                    {{-- Badge tipe --}}
                    <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-bold text-[#1A2B56] shadow-sm backdrop-blur">
                        <i class="fa-solid {{ $meta['icon'] }} text-[#A855F7]"></i> {{ $meta['label'] }}
                    </span>
                    {{-- Badge diskon (hanya bila benar-benar ada) --}}
                    @if($hasDiscount)
                        <span class="absolute right-3 top-3 rounded-full bg-[#A855F7] px-2.5 py-1 text-[11px] font-extrabold text-white shadow-sm">
                            -{{ $discountPct }}%
                        </span>
                    @endif
                </div>
                <div class="flex flex-grow flex-col p-5">
                    <h3 class="mb-2 text-lg font-bold leading-tight text-[#1A2B56] line-clamp-2">{{ $product->title }}</h3>

                    {{-- Sinyal kepercayaan: rating & peserta --}}
                    @if($product->rating || $product->students)
                        <div class="mb-3 flex items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                            @if($product->rating)
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    <span class="font-bold text-[#1A2B56]">{{ number_format($product->rating, 1) }}</span>
                                </span>
                            @endif
                            @if($product->students)
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-users text-slate-400"></i>
                                    {{ number_format($product->students, 0, ',', '.') }} peserta
                                </span>
                            @endif
                        </div>
                    @endif

                    <p class="mb-5 flex-grow text-sm leading-relaxed text-slate-500 line-clamp-2">
                        {{ $product->description }}
                    </p>
                    <div class="flex items-end justify-between border-t border-slate-100 pt-4">
                        <div>
                            @if($hasDiscount)
                                <span class="block text-xs text-slate-400 line-through">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
                            @endif
                            <span class="block text-xl font-extrabold text-[#A855F7]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1 text-sm font-bold text-[#1A2B56] transition group-hover:gap-2 group-hover:text-[#A855F7]">
                            Detail <i class="fa-solid fa-arrow-right text-xs"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if ($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
