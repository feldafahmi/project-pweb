@extends('dashboard.profile.layout')

@php
    $profile = [
        ['label' => 'Username', 'value' => auth()->user()->username, 'icon' => 'fa-at'],
        ['label' => 'Email', 'value' => auth()->user()->email, 'icon' => 'fa-envelope'],
        ['label' => 'Nama Depan', 'value' => auth()->user()->first_name, 'icon' => 'fa-user'],
        ['label' => 'Nama Belakang', 'value' => auth()->user()->last_name, 'icon' => 'fa-user'],
        ['label' => 'Institusi', 'value' => auth()->user()->institution ?? '-', 'icon' => 'fa-building-columns'],
    ];
@endphp

@section('profile-content')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

    <div x-data="{ open: false }">
        @if (session('success'))
            <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700 flex items-center gap-2">
                <i class="fas fa-circle-check text-green-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            
            <script>
                (function() {
                    const user = {
                        name: @json(auth()->user()->name),
                        email: @json(auth()->user()->email),
                        role: @json(auth()->user()->role)
                    };
                    localStorage.setItem('markup.auth.user', JSON.stringify(user));
                })();
            </script>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-circle-xmark text-red-600"></i>
                    <span>Terjadi kesalahan validasi:</span>
                </div>
                <ul class="list-disc pl-5 space-y-1 font-medium text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-100 bg-white p-6 sm:p-8">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-extrabold text-navy-600">Informasi Akun</h2>
                    <p class="mt-1 text-sm text-slate-500">Detail dasar profil yang terdaftar.</p>
                </div>
                <button type="button" @click="open = true"
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-xs font-bold text-white transition hover:-translate-y-0.5 hover:bg-purple-700 hover:shadow-md hover:shadow-purple-600/20">
                    <i class="fas fa-pen"></i>
                    <span>Edit</span>
                </button>
            </div>

            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                @foreach ($profile as $item)
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400">
                            <i class="fas {{ $item['icon'] }} text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $item['label'] }}</dt>
                            <dd class="mt-0.5 truncate text-sm font-semibold text-navy-600">{{ $item['value'] }}</dd>
                        </div>
                    </div>
                @endforeach
            </dl>
        </div>

        <!-- Modal Edit Profil -->
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition>
            <div @click.away="open = false" class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-lg font-extrabold text-navy-600">Edit Informasi Akun</h3>
                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('dashboard.profile.update') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="mb-1 block text-xs font-bold text-slate-500 uppercase">Nama Depan</label>
                            <input type="text" id="first_name" name="first_name" value="{{ auth()->user()->first_name }}" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 transition">
                        </div>
                        <div>
                            <label for="last_name" class="mb-1 block text-xs font-bold text-slate-500 uppercase">Nama Belakang</label>
                            <input type="text" id="last_name" name="last_name" value="{{ auth()->user()->last_name }}" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 transition">
                        </div>
                    </div>

                    <div>
                        <label for="username" class="mb-1 block text-xs font-bold text-slate-500 uppercase">Username</label>
                        <input type="text" id="username" name="username" value="{{ auth()->user()->username }}" required
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 transition">
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-xs font-bold text-slate-500 uppercase">Email</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 transition">
                    </div>

                    <div>
                        <label for="institution" class="mb-1 block text-xs font-bold text-slate-500 uppercase">Institusi</label>
                        <input type="text" id="institution" name="institution" value="{{ auth()->user()->institution }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-purple-600 focus:ring-1 focus:ring-purple-600 transition">
                    </div>



                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="open = false"
                            class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-purple-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-purple-700 hover:shadow-lg hover:shadow-purple-600/20 transition-all duration-300">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
