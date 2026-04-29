@extends('layouts.app')

@section('title', 'Profil Mentor')

@php
    // Data Mentor per Kategori
    $categories = [
        [
            'title' => 'Business Case Competition',
            'subtitle' => 'Business strategy and case analysis experts',
            'mentors' => [
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Consultant at BCG', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Consultant at BCG', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Consultant at BCG', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Consultant at BCG', 'photo' => 'img/mentor1.png'],
            ],
        ],
        [
            'title' => 'Debate Competition',
            'subtitle' => 'Debate masters with international experience',
            'mentors' => [
                ['name' => 'Gibran Rakabuming', 'role' => 'WUDC Grand Finalist', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'WUDC Grand Finalist', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'WUDC Grand Finalist', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'WUDC Grand Finalist', 'photo' => 'img/mentor1.png'],
            ],
        ],
        [
            'title' => 'UI/UX Design',
            'subtitle' => 'Designer with global competition portfolio',
            'mentors' => [
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Product Designer', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Product Designer', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Product Designer', 'photo' => 'img/mentor1.png'],
                ['name' => 'Gibran Rakabuming', 'role' => 'Senior Product Designer', 'photo' => 'img/mentor1.png'],
            ],
        ],
    ];
@endphp

@section('content')
    {{-- Breadcrumb --}}
    <x-breadcrumb current="Profil Mentor" />

    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center px-5 py-44 text-center text-white"
        style="background-image: linear-gradient(rgba(26,43,86,0.7), rgba(26,43,86,0.7)), url('{{ asset('img/hero-profil-mentor.png') }}');">
        <div class="mu-container">
            <h1 class="mb-4 text-4xl font-extrabold md:text-6xl tracking-tight">Meet The Expert</h1>
            <p class="mx-auto max-w-2xl text-lg leading-relaxed text-white/90 font-medium">
                Belajar langsung dari mentor berpengalaman yang telah memenangkan berbagai kompetisi tingkat nasional dan
                internasional.
            </p>
        </div>
    </section>

    {{-- Mentor Lists --}}
    <div class="mu-container py-20 space-y-24">

        @foreach ($categories as $category)
            <section>
                {{-- Category Header --}}
                <div class="mb-12 text-center">
                    <h2 class="text-3xl font-extrabold text-navy-600 md:text-4xl">{{ $category['title'] }}</h2>
                    <p class="mt-2 text-lg italic text-slate-500 font-medium">{{ $category['subtitle'] }}</p>
                    <div class="mx-auto mt-4 h-1.5 w-20 rounded-full bg-yellow-brand"></div>
                </div>

                {{-- Mentor Grid --}}
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($category['mentors'] as $mentor)
                        <div
                            class="group rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-[0_4px_24px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">

                            {{-- Mentor Photo --}}
                            <div class="relative mx-auto mb-6 h-36 w-36">
                                <div
                                    class="flex h-full w-full items-center justify-center rounded-full border-4 border-yellow-brand bg-white p-1 transition-transform group-hover:scale-105">
                                    <img src="{{ asset($mentor['photo']) }}" alt="{{ $mentor['name'] }}"
                                        class="h-full w-full rounded-full object-cover">
                                </div>
                            </div>

                            {{-- Mentor Identity --}}
                            <h4 class="text-xl font-extrabold text-navy-600 leading-tight">{{ $mentor['name'] }}</h4>
                            <p class="mt-2 text-sm font-medium text-slate-500">{{ $mentor['role'] }}</p>

                            {{-- Social Icons --}}
                            <div class="mt-6 flex justify-center gap-3">
                                <a href="#" aria-label="Email"
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-red-500 text-sm text-white transition hover:scale-110">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <a href="#" aria-label="LinkedIn"
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm text-white transition hover:scale-110">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" aria-label="Instagram"
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-orange-400 via-pink-500 to-fuchsia-700 text-sm text-white transition hover:scale-110">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

    </div>

    {{-- CTA Section (Opsional, agar page lebih panjang dan berisi) --}}
    <section class="bg-navy-600 py-16 text-center text-white">
        <div class="mu-container px-5">
            <h2 class="text-3xl font-bold">Siap Menjadi Juara Selanjutnya?</h2>
            <p class="mt-4 text-navy-100">Dapatkan bimbingan intensif dari mentor pilihanmu sekarang.</p>
            <div class="mt-8">
                <a href="{{ route('produk') }}"
                    class="rounded-full bg-yellow-brand px-8 py-3.5 text-sm font-extrabold text-navy-600 shadow-lg transition hover:scale-105">
                    Lihat Produk Mentoring
                </a>
            </div>
        </div>
    </section>
@endsection
