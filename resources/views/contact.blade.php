@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    <x-breadcrumb current="Kontak" />

    <section class="mu-container py-20">
        <div class="mb-12 text-center">
            <h1 class="mb-3 text-3xl font-extrabold text-navy-600 md:text-4xl">Hubungi Kami</h1>
            <p class="mx-auto max-w-2xl text-base text-slate-500">
                Ada pertanyaan atau ingin berkolaborasi? Tim MARK-UP siap membantu kamu.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-[1fr_1.2fr]">

            {{-- Contact info --}}
            <div class="space-y-6">
                @foreach ([
                    ['icon' => 'fa-envelope', 'title' => 'Email', 'value' => 'hello@markup.id'],
                    ['icon' => 'fa-phone-alt', 'title' => 'Telepon', 'value' => '+62 812-3456-7890'],
                    ['icon' => 'fa-map-marker-alt', 'title' => 'Alamat', 'value' => 'Surabaya, Indonesia'],
                ] as $item)
                    <div class="flex items-start gap-4 rounded-xl border border-slate-100 bg-white p-6 shadow-sm">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-navy-50 text-navy-600">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 class="mb-1 text-sm font-bold text-navy-600">{{ $item['title'] }}</h3>
                            <p class="text-sm text-slate-500">{{ $item['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Form --}}
            <form method="POST" action="" class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm">
                @csrf

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-xs font-semibold text-slate-500">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required
                            class="w-full rounded-xl border-[1.5px] border-slate-200 px-4 py-3 text-[15px] outline-none transition-colors focus:border-navy-600">
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-xs font-semibold text-slate-500">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full rounded-xl border-[1.5px] border-slate-200 px-4 py-3 text-[15px] outline-none transition-colors focus:border-navy-600">
                    </div>
                </div>

                <div class="mt-5">
                    <label for="subject" class="mb-2 block text-xs font-semibold text-slate-500">Subjek</label>
                    <input type="text" id="subject" name="subject" required
                        class="w-full rounded-xl border-[1.5px] border-slate-200 px-4 py-3 text-[15px] outline-none transition-colors focus:border-navy-600">
                </div>

                <div class="mt-5">
                    <label for="message" class="mb-2 block text-xs font-semibold text-slate-500">Pesan</label>
                    <textarea id="message" name="message" rows="5" required
                        class="w-full rounded-xl border-[1.5px] border-slate-200 px-4 py-3 text-[15px] outline-none transition-colors focus:border-navy-600"></textarea>
                </div>

                <button type="submit" class="btn-primary mt-6 w-full sm:w-auto">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </section>
@endsection
