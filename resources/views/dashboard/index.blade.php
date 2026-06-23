@extends('layouts.dashboard')

@section('title', 'Produk Saya')
@section('page-title', 'Produk Saya')
@section('page-subtitle', \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y'))

@php
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
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $tonePalette[$stat['tone']] ?? 'bg-slate-50 text-slate-600' }}">
                        <i class="fa-solid {{ $stat['icon'] }} text-xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-medium text-slate-400 truncate">{{ $stat['label'] }}</span>
                        <span class="block text-xl font-bold text-navy-600 mt-0.5">{{ $stat['value'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Section 2: Banner --}}
    <section class="mb-8">
        <div class="relative overflow-hidden rounded-3xl bg-navy-600 px-6 py-8 text-white sm:px-8">
            <div class="absolute -right-10 -top-20 h-40 w-40 rounded-full bg-navy-500/30 blur-2xl"></div>
            <div class="absolute -bottom-20 -right-20 h-60 w-60 rounded-full bg-[#A855F7]/20 blur-3xl"></div>

            <div class="relative z-10 max-w-xl">
                <span
                    class="inline-block rounded-full bg-yellow-brand/20 px-3 py-1 text-xs font-semibold text-yellow-brand">
                    ✨ Pembaruan Akun
                </span>
                <h2 class="mt-3 text-xl font-bold sm:text-2xl">Lengkapi Profil & Mulai Mentoring!</h2>
                <p class="mt-2 text-sm text-navy-100 leading-relaxed">
                    Buka akses penuh ke forum diskusi eksklusif, materi PDF terintegrasi, dan jadwalkan sesi 1-on-1 bersama
                    mentor pilihanmu.
                </p>
                <div class="mt-6">
                    <a href="{{ route('dashboard.profile.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-yellow-brand px-5 py-2.5 text-sm font-bold text-navy-600 shadow-sm transition hover:scale-[1.02] active:scale-[0.98]">
                        Lengkapi Profil Saya
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- KONTEN UTAMA DASHBOARD: FULL WIDTH GRID --}}
    <div class="grid grid-cols-1 gap-8">

        {{-- TEMPAT DAFTAR PRODUK / EMPTY STATE --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-navy-600 mb-4 border-b border-slate-50 pb-2">Program Mentoring Aktif</h3>

                @if ($purchasedProducts->isEmpty())
                    <x-empty-state icon="fa-cube" title="Belum ada program mentoring aktif"
                        subtitle="Kamu belum membeli program mentoring apa pun. Silakan jelajahi katalog produk kami untuk memulai."
                        action-label="Eksplor Program Mentoring" :action-url="route('produk')" />
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($purchasedProducts as $product)
                            <div onclick="openProductModal('{{ str_replace(["\r", "\n", "'", '"'], ['', '', "\'", '\"'], e($product->title)) }}', '{{ str_replace(["\r", "\n", "'", '"'], ['', '', "\'", '\"'], e($product->description)) }}', '{{ $product->video_url }}', '{{ $product->whatsapp_link }}', '{{ asset($product->image_url ?? 'img/63815.png') }}')"
                                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-150 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <div class="h-40 w-full overflow-hidden bg-slate-200 relative">
                                    <img src="{{ asset($product->image_url ?? 'img/63815.png') }}"
                                        alt="{{ $product->title }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                    <div
                                        class="absolute top-3 right-3 rounded-full bg-purple-500/90 px-3 py-1 text-[10px] font-bold text-white shadow-sm">
                                        {{ ucfirst($product->type) }}
                                    </div>
                                </div>
                                <div class="flex flex-grow flex-col p-5">
                                    <h4
                                        class="mb-2 text-base font-bold leading-tight text-navy-600 group-hover:text-purple-600 transition line-clamp-2">
                                        {{ $product->title }}</h4>
                                    <p class="text-xs leading-relaxed text-slate-500 line-clamp-2 mb-4">
                                        {{ $product->description }}
                                    </p>
                                    <div
                                        class="mt-auto pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-purple-600 font-bold">
                                        <span>Buka Materi Pembelajaran</span>
                                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- MODAL POP-UP DETAIL PRODUK EKSKLUSIF --}}
    <div id="productDetailModal"
        class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-md transition-all">
        <div
            class="relative flex w-full max-w-4xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl md:flex-row animate-in fade-in zoom-in duration-300">

            {{-- Close Button --}}
            <button onclick="closeProductModal()"
                class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 text-slate-800 shadow-md hover:bg-red-500 hover:text-white transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            {{-- Modal Left (Video / Image player) --}}
            <div class="w-full bg-slate-900 md:w-5/12 flex items-center justify-center min-h-[250px] md:min-h-0">
                <div id="modalVideoContainer" class="w-full h-full flex items-center justify-center p-4">
                </div>
            </div>

            {{-- Modal Right (Content) --}}
            <div class="flex w-full flex-col p-8 md:w-7/12 justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#A855F7] mb-2 block">Akses Pembelajaran
                        Premium</span>
                    <h2 id="modalProductTitle" class="mb-3 text-2xl font-black text-navy-600 leading-tight">Nama Produk</h2>

                    <div class="mb-6">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Materi</h4>
                        <p id="modalProductDesc" class="text-sm leading-relaxed text-slate-600">
                            Deskripsi mengenai produk bimbingan akan ditampilkan secara dinamis di sini.
                        </p>
                    </div>
                </div>

                <div class="space-y-3 pt-6 border-t border-slate-100">
                    <div id="whatsappContainer">
                        <a id="modalWhatsappLink" href="#" target="_blank"
                            class="flex h-12 w-full items-center justify-center rounded-xl bg-green-500 text-sm font-bold text-white shadow-md transition hover:bg-green-600 hover:scale-[1.02] active:scale-95">
                            <i class="fa-brands fa-whatsapp mr-2 text-lg"></i> Gabung Grup WhatsApp Diskusi
                        </a>
                    </div>
                    <button onclick="closeProductModal()"
                        class="w-full h-12 rounded-xl border border-slate-200 text-sm font-bold text-slate-500 hover:bg-slate-50 transition">
                        Tutup Halaman
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // LOGIKA DYNAMIC POP-UP DETAIL PRODUK
        const productModal = document.getElementById('productDetailModal');

        function getYoutubeEmbedUrl(url) {
            if (!url) return '';
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            if (match && match[2].length === 11) {
                return 'https://www.youtube.com/embed/' + match[2];
            }
            return url;
        }

        function openProductModal(title, desc, videoUrl, whatsappLink, imageSrc) {
            document.getElementById('modalProductTitle').innerText = title;
            document.getElementById('modalProductDesc').innerText = desc;

            const videoContainer = document.getElementById('modalVideoContainer');
            const embedUrl = getYoutubeEmbedUrl(videoUrl);

            if (embedUrl) {
                videoContainer.innerHTML = `
                    <iframe class="w-full aspect-video rounded-xl shadow-lg border-0" 
                        src="${embedUrl}" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>`;
            } else {
                videoContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center text-center p-6 text-slate-400">
                        <img src="${imageSrc}" class="max-h-48 w-full object-cover rounded-xl mb-4 opacity-80">
                        <i class="fa-solid fa-video-slash text-2xl mb-2"></i>
                        <p class="text-xs">Materi video bimbingan belum diunggah oleh mentor.</p>
                    </div>`;
            }

            const whatsappContainer = document.getElementById('whatsappContainer');
            const whatsappBtn = document.getElementById('modalWhatsappLink');
            if (whatsappLink) {
                whatsappBtn.href = whatsappLink;
                whatsappContainer.classList.remove('hidden');
            } else {
                whatsappContainer.classList.add('hidden');
            }

            productModal.classList.remove('hidden');
            productModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeProductModal() {
            document.getElementById('modalVideoContainer').innerHTML = '';
            productModal.classList.add('hidden');
            productModal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        productModal.addEventListener('click', function(e) {
            if (e.target === productModal) {
                closeProductModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            console.log("Dashboard product view initialized without milestone tracking.");
        });
    </script>
@endpush
