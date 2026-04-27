@extends('dashboard.profile.layout')

@php
    $profile = [
        ['label' => 'Username', 'value' => 'faisalfahmi', 'icon' => 'fa-at'],
        ['label' => 'Email', 'value' => 'faisal@markup.id', 'icon' => 'fa-envelope'],
        ['label' => 'Nama Depan', 'value' => 'Faisal', 'icon' => 'fa-user'],
        ['label' => 'Nama Belakang', 'value' => 'Fahmi', 'icon' => 'fa-user'],
        ['label' => 'Institusi', 'value' => 'Universitas Airlangga', 'icon' => 'fa-building-columns'],
        ['label' => 'Jurusan', 'value' => 'Sistem Informasi', 'icon' => 'fa-graduation-cap'],
    ];
@endphp

@section('profile-content')
    <div class="rounded-2xl border border-slate-100 bg-white p-6 sm:p-8">

        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-navy-600">Informasi Akun</h2>
                <p class="mt-1 text-sm text-slate-500">Detail dasar profil yang terdaftar.</p>
            </div>
            <a href="#"
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-xs font-bold text-white transition hover:-translate-y-0.5 hover:bg-purple-700 hover:shadow-md hover:shadow-purple-600/20">
                <i class="fas fa-pen"></i>
                <span>Edit</span>
            </a>
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
@endsection
