@extends('layouts.dashboard')

@section('title', $product->title)
@section('page-title', $product->title)
@section('page-subtitle', 'Akses Pembelajaran Premium')

@section('content')
    @php
        $typeMeta = [
            'kelas'    => ['label' => 'Kelas',         'icon' => 'fa-chalkboard-user'],
            'bootcamp' => ['label' => 'Live Bootcamp', 'icon' => 'fa-tower-broadcast'],
            'modul'    => ['label' => 'Modul',          'icon' => 'fa-book-open'],
        ][$product->type] ?? ['label' => 'Produk', 'icon' => 'fa-box'];

        $itemIcon = ['video' => 'fa-circle-play', 'pdf' => 'fa-file-pdf', 'live' => 'fa-tower-broadcast'];

        // Ubah URL YouTube menjadi embed bila memungkinkan.
        $embedUrl = null;
        if ($product->video_url) {
            if (preg_match('/(?:youtu\.be\/|v\/|embed\/|watch\?v=|\&v=)([^#\&\?]{11})/', $product->video_url, $m)) {
                $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
            } else {
                $embedUrl = $product->video_url;
            }
        }
    @endphp

    <div class="mb-6 flex flex-wrap items-center gap-3">
        <a href="{{ route('dashboard.index') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 transition hover:text-navy-600">
            <i class="fa-solid fa-arrow-left"></i> Produk Saya
        </a>
        <span class="inline-flex items-center gap-2 rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-[#A855F7]">
            <i class="fa-solid {{ $typeMeta['icon'] }}"></i> {{ $typeMeta['label'] }}
        </span>
        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-600">
            <i class="fa-solid fa-circle-check"></i> Sudah kamu miliki
        </span>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- KIRI: kurikulum --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Video pengantar (opsional) --}}
            @if($embedUrl)
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-900 shadow-sm">
                    <iframe class="aspect-video w-full border-0" src="{{ $embedUrl }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            @endif

            {{-- Kurikulum --}}
            @if(collect($sections)->flatMap(fn($s) => $s['items'])->isNotEmpty())
                <div>
                    <div class="mb-1 flex items-center gap-2">
                        <div class="h-6 w-1.5 rounded-full bg-yellow-400"></div>
                        <h2 class="text-lg font-bold text-navy-600">
                            {{ $product->type === 'modul' ? 'Daftar Isi' : 'Kurikulum' }}
                        </h2>
                    </div>
                    <p class="mb-4 pl-3.5 text-sm text-slate-500">
                        {{ count($sections) }} bagian &middot; {{ $totalLessons }} materi &middot;
                        <span class="font-semibold text-green-600">semua terbuka</span>
                    </p>

                    <div class="space-y-3">
                        @foreach($sections as $sIndex => $section)
                            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                                <button type="button" onclick="toggleSection({{ $sIndex }})"
                                    class="flex w-full items-center justify-between gap-3 px-5 py-4 text-left transition hover:bg-slate-50">
                                    <div>
                                        <h3 class="font-bold text-navy-600">{{ $section['title'] }}</h3>
                                        @if($section['subtitle'])
                                            <p class="text-xs text-slate-400">{{ $section['subtitle'] }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-slate-400">
                                        <span class="hidden sm:inline">{{ count($section['items']) }} materi</span>
                                        <i id="chevron-{{ $sIndex }}" class="fa-solid fa-chevron-down transition-transform"></i>
                                    </div>
                                </button>

                                <div id="section-{{ $sIndex }}" class="{{ $sIndex === 0 ? '' : 'hidden' }} border-t border-slate-100">
                                    @foreach($section['items'] as $item)
                                        @php $hasContent = !empty($item['content_url']); @endphp
                                        <div class="flex items-center gap-3 px-5 py-3 {{ !$loop->last ? 'border-b border-slate-50' : '' }}">
                                            <i class="fa-solid {{ $itemIcon[$item['type']] ?? 'fa-circle-play' }} {{ $hasContent ? 'text-[#A855F7]' : 'text-slate-300' }}"></i>
                                            <span class="flex-grow text-sm text-slate-700">{{ $item['title'] }}</span>
                                            @if($item['meta'])
                                                <span class="shrink-0 text-xs text-slate-400">{{ $item['meta'] }}</span>
                                            @endif
                                            @if($hasContent)
                                                <a href="{{ $item['content_url'] }}" target="_blank" rel="noopener"
                                                    class="shrink-0 rounded-lg bg-purple-50 px-3 py-1 text-xs font-bold text-[#A855F7] transition hover:bg-purple-100">
                                                    {{ $item['type'] === 'pdf' ? 'Buka' : ($item['type'] === 'live' ? 'Detail' : 'Putar') }}
                                                </a>
                                            @else
                                                <span class="shrink-0 text-[11px] font-medium text-slate-400">Segera</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white py-12 text-center">
                    <div class="mb-2 text-3xl text-slate-300"><i class="fa-solid fa-book-open"></i></div>
                    <p class="font-bold text-navy-600">Materi sedang disiapkan</p>
                    <p class="mt-1 text-sm text-slate-500">Kurikulum untuk produk ini belum tersedia. Cek kembali nanti.</p>
                </div>
            @endif

            {{-- Jadwal batch (bootcamp) --}}
            @if($batches->isNotEmpty())
                <div>
                    <div class="mb-4 flex items-center gap-2">
                        <div class="h-6 w-1.5 rounded-full bg-yellow-400"></div>
                        <h2 class="text-lg font-bold text-navy-600">Jadwal Batch</h2>
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
                            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <h3 class="font-bold text-navy-600">{{ $batch->label }}</h3>
                                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</span>
                                </div>
                                <p class="mb-1 text-sm text-slate-600"><i class="fa-regular fa-calendar mr-1.5 text-slate-400"></i>{{ $batch->date_range }}</p>
                                <p class="text-sm text-slate-500"><i class="fa-solid fa-chair mr-1.5 text-slate-400"></i>{{ $batch->spots }} kursi</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- KANAN: info + aksi --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 h-40 w-full overflow-hidden rounded-xl bg-slate-200">
                    <img src="{{ asset($product->image_url ?? 'img/63815.png') }}" alt="{{ $product->title }}"
                        class="h-full w-full object-cover">
                </div>

                @if($product->description)
                    <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Materi</h4>
                    <p class="mb-4 text-sm leading-relaxed text-slate-600">{{ $product->description }}</p>
                @endif

                @if(!empty($product->learnings))
                    <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Yang Kamu Pelajari</h4>
                    <ul class="mb-2 space-y-2">
                        @foreach($product->learnings as $point)
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <i class="fa-solid fa-circle-check mt-0.5 text-[#A855F7]"></i>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            @if($product->whatsapp_link)
                <a href="{{ $product->whatsapp_link }}" target="_blank" rel="noopener"
                    class="flex h-12 w-full items-center justify-center rounded-xl bg-green-500 text-sm font-bold text-white shadow-md transition hover:bg-green-600 active:scale-95">
                    <i class="fa-brands fa-whatsapp mr-2 text-lg"></i> Gabung Grup WhatsApp Diskusi
                </a>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleSection(index) {
            const panel = document.getElementById('section-' + index);
            const chevron = document.getElementById('chevron-' + index);
            const open = !panel.classList.contains('hidden');
            panel.classList.toggle('hidden');
            chevron.style.transform = open ? '' : 'rotate(180deg)';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const first = document.getElementById('chevron-0');
            if (first) first.style.transform = 'rotate(180deg)';
        });
    </script>
@endpush
