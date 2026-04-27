@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@php
    $founders = [
        [
            'name' => 'Gibran Rakabuming',
            'role' => 'Founder & CEO',
            'photo' => 'img/4624.jpg',
            'quote' =>
                'Akademi Competition memberikan akses dan kesempatan yang sama bagi semua mahasiswa. Bersama kami, siapapun bisa jadi juara!',
        ],
        [
            'name' => 'Gibran Rakabuming',
            'role' => 'Co-Founder & COO',
            'photo' => 'img/4624.jpg',
            'quote' =>
                'Akademi Competition memberikan akses dan kesempatan yang sama bagi semua mahasiswa. Bersama kami, siapapun bisa jadi juara!',
        ],
    ];

    $executives = [
        ['name' => 'Gibran Rakabuming', 'role' => 'Wakil Presiden Wakanda', 'badge' => 'CEO', 'photo' => 'img/4624.jpg'],
        ['name' => 'Gibran Rakabuming', 'role' => 'Wakil Presiden Wakanda', 'badge' => 'CTO', 'photo' => 'img/4624.jpg'],
        ['name' => 'Gibran Rakabuming', 'role' => 'Wakil Presiden Wakanda', 'badge' => 'CMO', 'photo' => 'img/4624.jpg'],
    ];
@endphp

@section('content')
    <x-breadcrumb current="Profil Perusahaan" />

    {{-- Hero --}}
    <section class="relative bg-cover bg-center px-5 py-44 text-center text-white"
        style="background-image: linear-gradient(rgba(26,43,86,0.75), rgba(26,43,86,0.75)), url('{{ asset('img/About-Hero.png') }}');">
        <div class="mu-container">
            <h1 class="mb-4 text-4xl font-extrabold md:text-5xl">Mencetak Juara Indonesia</h1>
            <p class="mx-auto max-w-2xl text-base leading-relaxed text-white/90">
                Berdiri sejak 2025, Mark-Up berkomitmen mencetak juara-juara lomba bergengsi
                secara inklusif untuk seluruh mahasiswa Indonesia
            </p>
        </div>
    </section>

    {{-- Founders --}}
    <section class="mu-container py-20">
        <div class="mb-10 text-center">
            <h3 class="mb-1 text-xl font-semibold italic text-navy-600">Founder & Co-Founder</h3>
            <h2 class="text-3xl font-extrabold text-navy-600">Mark-Up Indonesia</h2>
        </div>

        <div class="grid grid-cols-1 gap-7 md:grid-cols-2">
            @foreach ($founders as $founder)
                <div
                    class="flex flex-col items-center gap-5 rounded-2xl border border-slate-50 bg-white p-7 shadow-[0_4px_24px_rgba(0,0,0,0.06)] md:flex-row md:items-center md:text-left">
                    <div
                        class="relative flex h-32 w-32 shrink-0 items-center justify-center rounded-full border-4 border-yellow-brand bg-white p-1">
                        <img src="{{ asset($founder['photo']) }}" alt="{{ $founder['name'] }}"
                            class="h-full w-full rounded-full object-cover">
                    </div>
                    <div class="text-center md:text-left">
                        <h4 class="text-lg font-extrabold text-navy-600">{{ $founder['name'] }}</h4>
                        <p class="mb-3 text-sm text-slate-500">{{ $founder['role'] }}</p>
                        <div class="flex gap-3 text-[13px] leading-relaxed text-slate-600">
                            <i class="fa-solid fa-quote-left mt-1 shrink-0 text-navy-600"></i>
                            <p>{{ $founder['quote'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Executive Team --}}
    <section class="bg-slate-50 py-20">
        <div class="mu-container">
            <div class="mb-12 text-center">
                <h3 class="text-xl font-semibold italic text-navy-600">Our Executive Team</h3>
            </div>

            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($executives as $exec)
                    <div
                        class="rounded-2xl border border-slate-50 bg-white p-8 text-center shadow-[0_4px_24px_rgba(0,0,0,0.06)]">
                        <div class="relative mx-auto mb-5 h-32 w-32">
                            <div
                                class="flex h-full w-full items-center justify-center rounded-full border-4 border-yellow-brand bg-white p-1">
                                <img src="{{ asset($exec['photo']) }}" alt="{{ $exec['name'] }}"
                                    class="h-full w-full rounded-full object-cover">
                            </div>
                            <span
                                class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 rounded-full bg-yellow-brand px-4 py-1 text-[11px] font-extrabold text-navy-600 shadow">
                                {{ $exec['badge'] }}
                            </span>
                        </div>
                        <h4 class="text-lg font-extrabold text-navy-600">{{ $exec['name'] }}</h4>
                        <p class="mb-4 text-sm text-slate-500">{{ $exec['role'] }}</p>
                        <div class="flex justify-center gap-2.5">
                            <a href="#" aria-label="Email"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-500 text-sm text-white transition-transform hover:-translate-y-0.5">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="#" aria-label="LinkedIn"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm text-white transition-transform hover:-translate-y-0.5">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" aria-label="Instagram"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-orange-400 via-pink-500 to-fuchsia-700 text-sm text-white transition-transform hover:-translate-y-0.5">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
