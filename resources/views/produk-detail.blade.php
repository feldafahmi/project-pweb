@extends('layouts.app')

@section('title', $product->title)

@section('content')
    @php
        // Label & warna badge per tipe produk.
        $typeMeta = [
            'kelas'    => ['label' => 'Kelas',        'icon' => 'fa-chalkboard-user'],
            'bootcamp' => ['label' => 'Live Bootcamp', 'icon' => 'fa-tower-broadcast'],
            'modul'    => ['label' => 'Modul',         'icon' => 'fa-book-open'],
        ][$product->type] ?? ['label' => 'Produk', 'icon' => 'fa-box'];

        // Ikon FontAwesome untuk tiap tipe item kurikulum.
        $itemIcon = ['video' => 'fa-circle-play', 'pdf' => 'fa-file-pdf', 'live' => 'fa-tower-broadcast'];

        // Pemetaan ikon "includes" dari seeder ke FontAwesome.
        $includeIcon = [
            'video' => 'fa-video', 'file' => 'fa-file-lines', 'check' => 'fa-circle-check',
            'trophy' => 'fa-trophy', 'users' => 'fa-users', 'clock' => 'fa-clock',
            'book' => 'fa-book', 'play' => 'fa-play', 'down' => 'fa-download',
            'cal' => 'fa-calendar', 'bolt' => 'fa-bolt',
        ];

        // Diskon HANYA ditampilkan bila benar-benar ada original_price yang lebih
        // tinggi — tidak lagi mengarang coretan harga (praktik menyesatkan).
        $hasDiscount = $product->original_price && $product->original_price > $product->price;
        $discountPct = $hasDiscount
            ? round(($product->original_price - $product->price) / $product->original_price * 100)
            : 0;

        // Ringkasan ulasan.
        $reviewCount = $product->reviews->count();
        $reviewAvg   = $reviewCount ? round($product->reviews->avg('stars'), 1) : 0;
    @endphp

    <div class="mx-auto max-w-5xl px-4 pt-8 pb-28 md:pb-8">

        {{-- BREADCRUMB --}}
        <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
            <a href="/" class="transition hover:text-[#1A2B56]"><i class="fa-solid fa-house"></i></a>
            <span>></span>
            <a href="{{ route('produk') }}" class="transition hover:text-[#1A2B56]">Produk</a>
            <span>></span>
            <span class="text-slate-400 line-clamp-1">{{ $product->title }}</span>
        </div>

        {{-- HEADER PRODUK --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="h-64 w-full overflow-hidden rounded-2xl bg-slate-200 md:h-80">
                <img src="{{ asset($product->image_url ?? 'img/63815.png') }}" alt="{{ $product->title }}"
                    class="h-full w-full object-cover">
            </div>

            <div class="flex flex-col">
                <span class="mb-3 inline-flex w-fit items-center gap-2 rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-[#A855F7]">
                    <i class="fa-solid {{ $typeMeta['icon'] }}"></i> {{ $typeMeta['label'] }}
                </span>

                <h1 class="mb-3 text-2xl font-extrabold leading-tight text-[#1A2B56] md:text-3xl">{{ $product->title }}</h1>

                {{-- STAT: rating, siswa, win rate --}}
                <div class="mb-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-600">
                    @if($product->rating)
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <span class="font-bold text-[#1A2B56]">{{ number_format($product->rating, 1) }}</span>
                        </span>
                    @endif
                    @if($product->students)
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-users text-slate-400"></i>
                            {{ number_format($product->students, 0, ',', '.') }} peserta
                        </span>
                    @endif
                    @if($product->win_rate)
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-trophy text-slate-400"></i>
                            {{ $product->win_rate }}% win rate
                        </span>
                    @endif
                    @if($product->duration)
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-slate-400"></i>
                            {{ $product->duration }}
                        </span>
                    @endif
                </div>

                @if($product->description)
                    <p class="mb-5 text-sm leading-relaxed text-slate-600">{{ $product->description }}</p>
                @endif

                {{-- HARGA + CTA --}}
                <div class="mt-auto border-t border-slate-100 pt-5">
                    <div class="mb-4 flex items-end gap-3">
                        <span class="text-3xl font-extrabold text-[#A855F7]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @if($hasDiscount)
                            <span class="mb-1 text-sm text-slate-400 line-through">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
                            <span class="mb-1 rounded-full bg-purple-100 px-2 py-0.5 text-xs font-extrabold text-[#A855F7]">Hemat {{ $discountPct }}%</span>
                        @endif
                    </div>

                    @if($purchased)
                        <div class="flex items-center gap-2 rounded-xl bg-green-50 px-4 py-3 text-sm font-bold text-green-700">
                            <i class="fa-solid fa-circle-check"></i> Kamu sudah memiliki produk ini
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="addToCart()" aria-label="Tambah ke keranjang"
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border-2 border-[#A855F7] text-[#A855F7] transition hover:bg-purple-50">
                                <i class="fa-solid fa-cart-shopping text-xl"></i>
                            </button>
                            <button type="button" onclick="addToCart()"
                                class="flex h-12 w-full items-center justify-center rounded-xl bg-[#A855F7] text-lg font-bold text-white shadow-lg shadow-purple-200 transition hover:bg-purple-600">
                                Beli Sekarang
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- YANG KAMU PELAJARI --}}
        @if(!empty($product->learnings))
            <div class="mt-12">
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-6 w-1.5 rounded-full bg-yellow-400"></div>
                    <h2 class="text-xl font-bold text-[#1A2B56]">Yang Kamu Pelajari</h2>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach($product->learnings as $point)
                        <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-white p-4 text-sm text-slate-700">
                            <i class="fa-solid fa-circle-check mt-0.5 text-[#A855F7]"></i>
                            <span>{{ $point }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- KURIKULUM --}}
        @if(collect($sections)->flatMap(fn($s) => $s['items'])->isNotEmpty())
            <div class="mt-12">
                <div class="mb-1 flex items-center gap-2">
                    <div class="h-6 w-1.5 rounded-full bg-yellow-400"></div>
                    <h2 class="text-xl font-bold text-[#1A2B56]">
                        {{ $product->type === 'modul' ? 'Daftar Isi' : 'Kurikulum' }}
                    </h2>
                </div>
                <p class="mb-4 pl-3.5 text-sm text-slate-500">
                    {{ count($sections) }} bagian &middot; {{ $totalLessons }} materi
                </p>

                <div class="space-y-3">
                    @foreach($sections as $sIndex => $section)
                        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white">
                            {{-- Header section (toggle) --}}
                            <button type="button" onclick="toggleSection({{ $sIndex }})"
                                id="section-btn-{{ $sIndex }}" aria-controls="section-{{ $sIndex }}"
                                aria-expanded="{{ $sIndex === 0 ? 'true' : 'false' }}"
                                class="flex w-full items-center justify-between gap-3 px-5 py-4 text-left transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#A855F7]">
                                <div>
                                    <h3 class="font-bold text-[#1A2B56]">{{ $section['title'] }}</h3>
                                    @if($section['subtitle'])
                                        <p class="text-xs text-slate-400">{{ $section['subtitle'] }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-400">
                                    <span class="hidden sm:inline">{{ count($section['items']) }} materi</span>
                                    <i id="chevron-{{ $sIndex }}" class="fa-solid fa-chevron-down transition-transform"></i>
                                </div>
                            </button>

                            {{-- Items (default terbuka untuk section pertama) --}}
                            <div id="section-{{ $sIndex }}" class="{{ $sIndex === 0 ? '' : 'hidden' }} border-t border-slate-100">
                                @foreach($section['items'] as $item)
                                    @php
                                        $free       = $item['is_free'] ?? false;
                                        $unlocked   = $purchased || $free;
                                        $hasContent = !empty($item['content_url']);
                                        $openLabel  = $item['type'] === 'pdf' ? 'Buka' : ($item['type'] === 'live' ? 'Detail' : 'Putar');
                                    @endphp
                                    <div class="flex items-center gap-3 px-5 py-3 {{ !$loop->last ? 'border-b border-slate-50' : '' }}">
                                        <i class="fa-solid {{ $itemIcon[$item['type']] ?? 'fa-circle-play' }} {{ $unlocked ? 'text-[#A855F7]' : 'text-slate-300' }}"></i>
                                        <span class="flex-grow text-sm {{ $unlocked ? 'text-slate-700' : 'text-slate-500' }}">{{ $item['title'] }}</span>
                                        @if($item['meta'])
                                            <span class="shrink-0 text-xs text-slate-400">{{ $item['meta'] }}</span>
                                        @endif

                                        @if($free && $hasContent)
                                            {{-- Gratis + ada materi → bisa langsung dibuka siapa saja (preview). --}}
                                            <a href="{{ $item['content_url'] }}" target="_blank" rel="noopener"
                                                class="shrink-0 inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-[11px] font-bold text-green-700 transition hover:bg-green-200">
                                                <i class="fa-solid fa-unlock text-[10px]"></i> Gratis
                                            </a>
                                        @elseif($free)
                                            {{-- Ditandai gratis tapi materinya belum tersedia. --}}
                                            <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-0.5 text-[11px] font-bold text-green-600">Gratis · Segera</span>
                                        @elseif($purchased && $hasContent)
                                            {{-- Sudah dibeli → materi berbayar ikut terbuka. --}}
                                            <a href="{{ $item['content_url'] }}" target="_blank" rel="noopener"
                                                class="shrink-0 rounded-lg bg-purple-50 px-3 py-1 text-xs font-bold text-[#A855F7] transition hover:bg-purple-100">
                                                {{ $openLabel }}
                                            </a>
                                        @elseif(!$unlocked)
                                            <i class="fa-solid fa-lock shrink-0 text-xs text-slate-300" title="Beli untuk membuka"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- BATCH (khusus bootcamp) --}}
        @if($batches->isNotEmpty())
            <div class="mt-12">
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-6 w-1.5 rounded-full bg-yellow-400"></div>
                    <h2 class="text-xl font-bold text-[#1A2B56]">Jadwal Batch</h2>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach($batches as $batch)
                        @php
                            $statusMeta = [
                                'open'   => ['label' => 'Dibuka',  'class' => 'bg-green-100 text-green-700'],
                                'soon'   => ['label' => 'Segera',  'class' => 'bg-yellow-100 text-yellow-700'],
                                'closed' => ['label' => 'Ditutup', 'class' => 'bg-slate-100 text-slate-500'],
                            ][$batch->status] ?? ['label' => $batch->status, 'class' => 'bg-slate-100 text-slate-500'];
                        @endphp
                        <div class="rounded-2xl border border-slate-100 bg-white p-5">
                            <div class="mb-2 flex items-center justify-between gap-2">
                                <h3 class="font-bold text-[#1A2B56]">{{ $batch->label }}</h3>
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</span>
                            </div>
                            <p class="mb-1 text-sm text-slate-600"><i class="fa-regular fa-calendar mr-1.5 text-slate-400"></i>{{ $batch->date_range }}</p>
                            <p class="text-sm text-slate-500"><i class="fa-solid fa-chair mr-1.5 text-slate-400"></i>{{ $batch->spots }} kursi tersisa</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- TERMASUK DALAM PAKET --}}
        @if(!empty($product->includes))
            <div class="mt-12">
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-6 w-1.5 rounded-full bg-yellow-400"></div>
                    <h2 class="text-xl font-bold text-[#1A2B56]">Termasuk dalam Paket</h2>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach($product->includes as $inc)
                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-white p-4 text-sm text-slate-700">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-purple-50 text-[#A855F7]">
                                <i class="fa-solid {{ $includeIcon[$inc['icon'] ?? ''] ?? 'fa-circle-check' }}"></i>
                            </span>
                            <span>{{ $inc['text'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ULASAN --}}
        @if($product->reviews->isNotEmpty())
            <div class="mt-12">
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-6 w-1.5 rounded-full bg-yellow-400"></div>
                    <h2 class="text-xl font-bold text-[#1A2B56]">Ulasan Peserta</h2>
                </div>

                {{-- Ringkasan rating --}}
                <div class="mb-5 flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5">
                    <div class="text-center">
                        <div class="text-4xl font-extrabold leading-none text-[#1A2B56]">{{ number_format($reviewAvg, 1) }}</div>
                        <div class="mt-1 text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa-{{ $i < round($reviewAvg) ? 'solid' : 'regular' }} fa-star text-xs"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="border-l border-slate-100 pl-4 text-sm text-slate-500">
                        Berdasarkan <span class="font-bold text-[#1A2B56]">{{ $reviewCount }}</span> ulasan peserta
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($product->reviews as $review)
                        <div class="rounded-2xl border border-slate-100 bg-white p-5">
                            <div class="mb-2 flex items-center justify-between gap-2">
                                <span class="font-bold text-[#1A2B56]">{{ $review->user->name ?? 'Peserta' }}</span>
                                <span class="text-sm text-yellow-400">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fa-{{ $i < $review->stars ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed text-slate-600">{{ $review->text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- KEMBALI --}}
        <div class="mt-12">
            <a href="{{ route('produk') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#A855F7] transition hover:underline">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke katalog produk
            </a>
        </div>
    </div>

    {{-- STICKY BUY BAR (mobile) — jaga CTA tetap terlihat saat scroll panjang --}}
    @unless($purchased)
        <div class="fixed inset-x-0 bottom-0 z-40 flex items-center gap-3 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-[0_-4px_12px_rgba(0,0,0,0.06)] backdrop-blur md:hidden">
            <div class="shrink-0">
                <span class="block text-lg font-extrabold leading-none text-[#A855F7]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @if($hasDiscount)
                    <span class="text-xs text-slate-400 line-through">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
                @endif
            </div>
            <button type="button" onclick="addToCart()"
                class="ml-auto flex h-11 grow items-center justify-center rounded-xl bg-[#A855F7] px-6 text-base font-bold text-white shadow-lg shadow-purple-200 transition hover:bg-purple-600">
                Beli Sekarang
            </button>
        </div>
    @endunless
@endsection

@push('scripts')
    <script>
        const IS_LOGGED_IN = @json(auth()->check());

        // Data produk untuk keranjang (localStorage) — sama seperti katalog.
        const PRODUCT = {
            id: {{ $product->id }},
            title: @json($product->title),
            price: {{ (int) $product->price }},
            image: @json(asset($product->image_url ?? 'img/63815.png')),
        };

        function toggleSection(index) {
            const panel = document.getElementById('section-' + index);
            const chevron = document.getElementById('chevron-' + index);
            const btn = document.getElementById('section-btn-' + index);
            const open = !panel.classList.contains('hidden');
            panel.classList.toggle('hidden');
            chevron.style.transform = open ? '' : 'rotate(180deg)';
            if (btn) btn.setAttribute('aria-expanded', open ? 'false' : 'true');
        }

        // Set rotasi awal chevron untuk section yang default terbuka.
        document.addEventListener('DOMContentLoaded', () => {
            const first = document.getElementById('chevron-0');
            if (first) first.style.transform = 'rotate(180deg)';
        });

        function addToCart() {
            if (!IS_LOGGED_IN) {
                sessionStorage.setItem('markup.flash', 'Silakan masuk dulu untuk menambahkan ke keranjang.');
                window.location.href = '{{ route('login') }}?redirect=%2Fproduk%2F{{ $product->id }}';
                return;
            }

            let cart = JSON.parse(localStorage.getItem('markup_cart')) || [];
            if (cart.find(item => item.id === PRODUCT.id)) {
                alert('Produk ini sudah ada di keranjang kamu.');
                return;
            }

            cart.push(PRODUCT);
            localStorage.setItem('markup_cart', JSON.stringify(cart));
            window.markupUpdateCartBadge?.();
            alert('Berhasil ditambahkan ke keranjang!');
        }
    </script>
@endpush
