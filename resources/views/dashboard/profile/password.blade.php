@extends('dashboard.profile.layout')

@php
    $passwordFields = [
        ['name' => 'current_password', 'label' => 'Password Saat Ini', 'placeholder' => 'Masukkan password lama'],
        ['name' => 'new_password', 'label' => 'Password Baru', 'placeholder' => 'Minimal 8 karakter'],
        ['name' => 'new_password_confirmation', 'label' => 'Konfirmasi Password Baru', 'placeholder' => 'Ulangi password baru'],
    ];
@endphp

@section('profile-content')
    <div class="rounded-2xl border border-slate-100 bg-white p-6 sm:p-8">

        <div class="mb-6">
            <h2 class="text-lg font-extrabold text-navy-600">Ubah Password</h2>
            <p class="mt-1 text-sm text-slate-500">Pastikan password baru kuat dan tidak digunakan di akun lain.</p>
        </div>

        <form method="POST" action="" class="space-y-5">
            @csrf

            @foreach ($passwordFields as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="mb-1.5 block text-xs font-semibold text-slate-500">
                        {{ $field['label'] }}
                    </label>
                    <div class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 transition-colors focus-within:border-navy-600">
                        <i class="fas fa-lock text-slate-400"></i>
                        <input type="password" id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                            placeholder="{{ $field['placeholder'] }}" required
                            class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400">
                        <button type="button" data-toggle-password
                            class="text-slate-400 transition hover:text-navy-600" aria-label="Tampilkan password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-6 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-purple-700 hover:shadow-lg hover:shadow-purple-600/20">
                    <i class="fas fa-shield-halved"></i>
                    <span>Ubah Password</span>
                </button>
            </div>
        </form>
    </div>
@endsection
