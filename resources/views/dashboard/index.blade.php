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
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $tonePalette[$stat['tone']] }}">
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

    {{-- KONTEN UTAMA DASHBOARD DIBAGI 3 KOLOM --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- KOLOM KIRI (Lebar 2/3): Tempat Daftar Produk / Empty State --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-navy-600 mb-4 border-b border-slate-50 pb-2">Program Mentoring Aktif</h3>

                <x-empty-state icon="fa-cube" title="Belum ada program mentoring aktif"
                    subtitle="Kamu belum membeli program mentoring apa pun. Silakan jelajahi katalog produk kami untuk memulai."
                    action-label="Eksplor Program Mentoring" :action-url="route('produk')" />
            </div>
        </div>

        {{-- KOLOM KANAN (Lebar 1/3): WIDGET JAVASCRIPT (TUGAS 2 & TUGAS 4) --}}
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

                <ul id="todo-list" class="space-y-2 max-h-[250px] overflow-y-auto pr-1">
                </ul>

                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-xs text-slate-400">
                    <span>Total Target: <span id="todo-count" class="font-bold text-slate-700">0</span></span>
                    <button onclick="clearCompletedTodos()" class="text-purple-600 hover:underline font-medium">Hapus
                        Selesai</button>
                </div>
            </div>

            {{-- WIDGET TUGAS 4: FETCH API DATA (Quote of the Day) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-navy-600 mb-1 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-lightbulb text-yellow-500"></i> Mentoring Quote
                </h3>
                <p class="text-[11px] text-slate-400 mb-4">Inspirasi harian eksternal via REST API Fetching.</p>

                <div id="quote-loading" class="text-xs text-slate-400 animate-pulse py-2">
                    <i class="fa-solid fa-spinner animate-spin mr-1"></i> Menghubungi server API...
                </div>

                <div id="quote-content" class="hidden">
                    <p id="quote-text" class="text-sm italic text-slate-600 font-medium leading-relaxed">"Quote"</p>
                    <p id="quote-author" class="text-xs text-purple-600 font-bold mt-2 text-right">- Author</p>
                </div>

                <div id="quote-error" class="hidden text-xs text-red-500 py-2">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Gagal memuat inspirasi eksternal.
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // LOGIKA INTERACTIVE TO-DO LIST
        const todoForm = document.getElementById('todo-form');
        const todoInput = document.getElementById('todo-input');
        const todoList = document.getElementById('todo-list');
        const todoCount = document.getElementById('todo-count');

        let todos = [];

        try {
            const savedTodos = localStorage.getItem('markup_todos');
            if (savedTodos) {
                todos = JSON.parse(savedTodos);
            } else {
                todos = [{
                        text: "Membentuk Kelompok & Cari Nama Tim",
                        completed: true
                    },
                    {
                        text: "Analisis Kasus dengan Framework SWOT/BCG",
                        completed: false
                    },
                    {
                        text: "Asistensi Pitch Deck Bersama Mentor Mark-Up",
                        completed: false
                    }
                ];
            }
        } catch (error) {
            console.error("Gagal membaca storage data:", error);
            todos = [];
        }

        function renderTodos() {
            if (!todoList) return;
            todoList.innerHTML = '';

            if (todos.length === 0) {
                todoList.innerHTML =
                    `<li class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-xl">Belum ada target persiapan lomba.</li>`;
                if (todoCount) todoCount.innerText = 0;
                return;
            }

            todos.forEach((todo, index) => {
                const li = document.createElement('li');
                li.className =
                    `flex items-center justify-between p-3 rounded-xl border border-slate-100 transition-all duration-200 ${todo.completed ? 'bg-slate-50 border-transparent' : 'bg-white shadow-sm'}`;

                li.innerHTML = `
                    <div class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer" onclick="toggleTodo(${index})">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md border ${todo.completed ? 'bg-purple-500 border-purple-500 text-white' : 'border-slate-300'} text-[10px]">
                            ${todo.completed ? '<i class="fa-solid fa-check"></i>' : ''}
                        </span>
                        <span class="text-sm truncate ${todo.completed ? 'line-through text-slate-400' : 'text-slate-700 font-medium'}">
                            ${todo.text}
                        </span>
                    </div>
                    <button onclick="deleteTodo(${index})" class="text-slate-300 hover:text-red-500 p-1 transition ml-2 shrink-0">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                `;
                todoList.appendChild(li);
            });

            if (todoCount) todoCount.innerText = todos.length;

            try {
                localStorage.setItem('markup_todos', JSON.stringify(todos));
            } catch (e) {
                console.error("Gagal memperbarui penyimpanan:", e);
            }
        }

        if (todoForm) {
            todoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const text = todoInput.value.trim();

                if (text) {
                    todos.push({
                        text: text,
                        completed: false
                    });
                    todoInput.value = '';
                    renderTodos();
                }
            });
        }

        window.toggleTodo = function(index) {
            todos[index].completed = !todos[index].completed;
            renderTodos();
        }

        window.deleteTodo = function(index) {
            todos.splice(index, 1);
            renderTodos();
        }

        window.clearCompletedTodos = function() {
            todos = todos.filter(todo => !todo.completed);
            renderTodos();
        }

        // LOGIKA FETCH DATA PUBLIC API (TUGAS 4)
        async function fetchDailyQuote() {
            const loadingEl = document.getElementById('quote-loading');
            const contentEl = document.getElementById('quote-content');
            const errorEl = document.getElementById('quote-error');
            const textEl = document.getElementById('quote-text');
            const authorEl = document.getElementById('quote-author');

            try {
                const response = await fetch('https://api.allorigins.win/get?url=' + encodeURIComponent(
                    'https://zenquotes.io/api/random'));

                if (!response.ok) throw new Error('Network error');

                const data = await response.json();
                const actualQuoteData = JSON.parse(data.contents);
                const quote = actualQuoteData[0];

                if (textEl && authorEl) {
                    textEl.innerText = `"${quote.q}"`;
                    authorEl.innerText = `- ${quote.a}`;
                }

                if (loadingEl) loadingEl.classList.add('hidden');
                if (contentEl) contentEl.classList.remove('hidden');
            } catch (error) {
                console.error("Gagal Fetching API:", error);
                if (loadingEl) loadingEl.classList.add('hidden');
                if (errorEl) errorEl.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderTodos();
            fetchDailyQuote();
        });
    </script>
@endpush
