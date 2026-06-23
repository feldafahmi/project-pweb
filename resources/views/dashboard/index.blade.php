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

    {{-- KONTEN UTAMA DASHBOARD DIBAGI 2 KOLOM --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- KOLOM KIRI (Lebar 2/3): Tempat Daftar Produk / Empty State --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-navy-600 mb-4 border-b border-slate-50 pb-2">Program Mentoring Aktif</h3>

                @if($purchasedProducts->isEmpty())
                    <x-empty-state icon="fa-cube" title="Belum ada program mentoring aktif"
                        subtitle="Kamu belum membeli program mentoring apa pun. Silakan jelajahi katalog produk kami untuk memulai."
                        action-label="Eksplor Program Mentoring" :action-url="route('produk')" />
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        @foreach($purchasedProducts as $product)
                            <div onclick="openProductModal('{{ str_replace(["\r", "\n", "'", '"'], ["", "", "\'", '\"'], e($product->title)) }}', '{{ str_replace(["\r", "\n", "'", '"'], ["", "", "\'", '\"'], e($product->description)) }}', '{{ $product->video_url }}', '{{ $product->whatsapp_link }}', '{{ asset($product->image_url ?? 'img/63815.png') }}')"
                                class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-150 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <div class="h-40 w-full overflow-hidden bg-slate-200 relative">
                                    <img src="{{ asset($product->image_url ?? 'img/63815.png') }}" alt="{{ $product->title }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                    <div class="absolute top-3 right-3 rounded-full bg-purple-500/90 px-3 py-1 text-[10px] font-bold text-white shadow-sm">
                                        {{ ucfirst($product->type) }}
                                    </div>
                                </div>
                                <div class="flex flex-grow flex-col p-5">
                                    <h4 class="mb-2 text-base font-bold leading-tight text-navy-600 group-hover:text-purple-600 transition line-clamp-2">{{ $product->title }}</h4>
                                    <p class="text-xs leading-relaxed text-slate-500 line-clamp-2 mb-4">
                                        {{ $product->description }}
                                    </p>
                                    <div class="mt-auto pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-purple-600 font-bold">
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

        {{-- KOLOM KANAN (Lebar 1/3): HANYA MILESTONE TRACKER SEKARANG --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- WIDGET TUGAS 2: INTERACTIVE TO-DO LIST (Milestone Tracker) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-navy-600 mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-purple-500"></i> Milestone Tracker
                </h3>
                <p class="text-xs text-slate-400 mb-4">Kelola daftar persiapan sebelum tenggat waktu kompetisi tiba.</p>

                <form id="todo-form" class="flex gap-2 mb-4">
                    <input type="text" id="todo-input" placeholder="Contoh: Brainstorming Ide..." required
                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-[#A855F7] focus:outline-none focus:ring-1 focus:ring-[#A855F7]">
                    <button type="submit"
                        class="rounded-xl bg-[#A855F7] px-4 py-2 text-sm font-bold text-white transition hover:bg-purple-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </form>

                <ul id="todo-list" class="space-y-2 max-h-[350px] overflow-y-auto pr-1">
                </ul>

                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
                    <span>Total Target: <span id="todo-count" class="font-bold text-slate-700">0</span></span>
                    <button onclick="clearCompletedTodos()" class="text-purple-600 hover:underline font-medium">Hapus
                        Selesai</button>
                </div>
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
                    <!-- YouTube iframe or Fallback Poster gets injected here -->
                </div>
            </div>

            {{-- Modal Right (Content) --}}
            <div class="flex w-full flex-col p-8 md:w-7/12 justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#A855F7] mb-2 block">Akses Pembelajaran Premium</span>
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
                // Fallback jika tidak ada link video
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
            // Stop playing video on modal close
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

        // LOGIKA DATABASE-SYNCHRONIZED MILESTONE TRACKER
        const todoForm = document.getElementById('todo-form');
        const todoInput = document.getElementById('todo-input');
        const todoList = document.getElementById('todo-list');
        const todoCount = document.getElementById('todo-count');

        // Mengambil data langsung dari blade JSON
        let todos = @json($milestones);

        function renderTodos() {
            if (!todoList) return;
            todoList.innerHTML = '';

            if (todos.length === 0) {
                todoList.innerHTML =
                    `<li class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-xl">Belum ada target persiapan bimbingan.</li>`;
                if (todoCount) todoCount.innerText = 0;
                return;
            }

            todos.forEach((todo, index) => {
                const li = document.createElement('li');
                li.className =
                    `flex flex-col p-3 rounded-xl border border-slate-100 transition-all duration-200 ${todo.completed ? 'bg-slate-50 border-transparent' : 'bg-white shadow-sm'}`;

                let feedbackHTML = '';
                if (todo.feedback) {
                    feedbackHTML = `
                        <div class="mt-2 rounded-lg bg-purple-50 p-2.5 text-[11px] text-purple-800 border border-purple-100 flex gap-1.5 items-start">
                            <i class="fa-solid fa-comment-dots mt-0.5 shrink-0 text-purple-500"></i>
                            <div>
                                <span class="font-bold">Feedback Mentor:</span> ${todo.feedback}
                            </div>
                        </div>
                    `;
                }

                li.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer" onclick="toggleTodo(${todo.id}, ${index})">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border ${todo.completed ? 'bg-purple-500 border-purple-500 text-white' : 'border-slate-300'} text-[10px]">
                                ${todo.completed ? '<i class="fa-solid fa-check"></i>' : ''}
                            </span>
                            <span class="text-sm truncate ${todo.completed ? 'line-through text-slate-400' : 'text-slate-700 font-medium'}">
                                ${todo.text}
                            </span>
                        </div>
                        <button onclick="deleteTodo(${todo.id}, ${index})" class="text-slate-300 hover:text-red-500 p-1 transition ml-2 shrink-0">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                    ${feedbackHTML}
                `;
                todoList.appendChild(li);
            });

            if (todoCount) todoCount.innerText = todos.length;
        }

        if (todoForm) {
            todoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const text = todoInput.value.trim();

                if (text) {
                    fetch('/dashboard/milestones', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ text: text })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            todos.push(data.milestone);
                            todoInput.value = '';
                            renderTodos();
                        } else {
                            alert('Gagal menambahkan milestone.');
                        }
                    })
                    .catch(err => console.error('Error:', err));
                }
            });
        }

        window.toggleTodo = function(id, index) {
            fetch('/dashboard/milestones/' + id + '/toggle', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    todos[index].completed = data.milestone.completed;
                    renderTodos();
                } else {
                    alert('Gagal merubah status milestone.');
                }
            })
            .catch(err => console.error('Error:', err));
        }

        window.deleteTodo = function(id, index) {
            if (!confirm('Apakah Anda yakin ingin menghapus milestone ini?')) return;
            
            fetch('/dashboard/milestones/' + id, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    todos.splice(index, 1);
                    renderTodos();
                } else {
                    alert('Gagal menghapus milestone.');
                }
            })
            .catch(err => console.error('Error:', err));
        }

        window.clearCompletedTodos = function() {
            const completedTodos = todos.filter(todo => todo.completed);
            if (completedTodos.length === 0) {
                alert('Tidak ada milestone selesai yang perlu dihapus.');
                return;
            }

            if (!confirm('Hapus seluruh milestone yang sudah selesai?')) return;
            
            Promise.all(completedTodos.map(todo => {
                return fetch('/dashboard/milestones/' + todo.id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            }))
            .then(() => {
                todos = todos.filter(todo => !todo.completed);
                renderTodos();
            })
            .catch(err => console.error('Gagal menghapus milestone:', err));
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderTodos();
        });
    </script>
@endpush
