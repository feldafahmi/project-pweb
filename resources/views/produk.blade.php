@extends('layouts.app')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative mb-10 flex h-[300px] items-center justify-center overflow-hidden bg-slate-800">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/Hero-Info-Produk.png') }}" alt="Background Hero Produk"
                class="h-full w-full object-cover opacity-40">
        </div>

        <div class="relative z-10 px-4 text-center">
            <h1 class="mb-3 text-4xl font-extrabold text-white md:text-5xl">Produk Mark-Up</h1>
            <p class="text-base text-slate-200 md:text-lg">
                Pilih program yang sudah terbukti membantu memenangkan berbagai kompetisi
            </p>
        </div>
    </section>
    {{-- AKHIR HERO SECTION --}}


    <div class="mx-auto max-w-5xl px-4 pb-20">

        <div class="mb-8 mt-2 flex items-center gap-2 text-sm text-slate-500">
            <a href="/" class="transition hover:text-[#1A2B56]"><i class="fa-solid fa-house"></i></a>
            <span>></span>
            <span class="text-slate-400">Produk</span>
        </div>

        {{-- SEARCH BAR --}}
        <form method="GET" action="{{ route('produk') }}" class="mx-auto mb-8 max-w-xl">
            @if($activeType !== 'semua')
                <input type="hidden" name="type" value="{{ $activeType }}">
            @endif
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $searchTerm }}"
                    placeholder="Cari produk..."
                    class="w-full rounded-full border border-slate-200 bg-white py-3 pl-11 pr-28 text-sm text-slate-700 shadow-sm outline-none transition focus:border-[#A855F7] focus:ring-2 focus:ring-purple-100">
                <button type="submit"
                    class="absolute inset-y-1.5 right-1.5 rounded-full bg-[#A855F7] px-5 text-sm font-bold text-white transition hover:bg-purple-600">
                    Cari
                </button>
            </div>
        </form>

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

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            @foreach($products as $product)
            {{-- CARD --}}
            <a href="{{ route('produk.show', $product->id) }}"
                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="h-56 w-full overflow-hidden bg-slate-200">
                    <img src="{{ asset($product->image_url ?? 'img/63815.png') }}" alt="{{ $product->title }}"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                </div>
                <div class="flex flex-grow flex-col p-6">
                    <h3 class="mb-2 text-lg font-bold leading-tight text-[#1A2B56] line-clamp-2">{{ $product->title }}</h3>
                    <p class="mb-5 flex-grow text-sm leading-relaxed text-slate-500 line-clamp-3">
                        {{ $product->description }}
                    </p>
                    <div class="border-t border-slate-100 pt-4">
                        @if($product->original_price)
                            <span class="block text-xs text-slate-400 line-through">{{ number_format($product->original_price, 0, ',', '.') }}</span>
                        @else
                            <span class="block text-xs text-slate-400 line-through">{{ number_format($product->price * 1.5, 0, ',', '.') }}</span>
                        @endif
                        <span class="block text-xl font-extrabold text-[#A855F7]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
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
