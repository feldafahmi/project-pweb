@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    {{-- Tetap gunakan h-screen dan overflow-hidden agar tidak bisa di-scroll tembus ke bawah --}}
    <div class="flex h-screen w-full overflow-hidden bg-white">

        {{-- Sisi Form (Diubah menjadi flex-col agar scroll responsif dan tidak terpotong) --}}
        <div class="relative flex flex-1 flex-col items-center overflow-y-auto p-10 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">

            {{-- Back to home --}}
            <a href="{{ url('/') }}"
                class="absolute left-6 top-6 inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-gray-500 transition hover:bg-gray-50 hover:text-navy-600">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            {{-- Menggunakan my-auto dan shrink-0 agar form selalu di tengah tapi tetap bisa di-scroll penuh jika layar sempit --}}
            <div class="w-full max-w-sm my-auto shrink-0 py-6">

                {{-- Header & Logo (Rata Tengah) --}}
                <div class="mb-8 flex flex-col items-center text-center">
                    <a href="{{ url('/') }}" class="mb-6 flex items-center gap-2 text-xl font-extrabold text-navy-800">
                        <img src="{{ asset('img/Markup-Logo.png') }}" alt="MARK-UP" class="h-8">
                        <span>MARK-UP</span>
                    </a>
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">Welcome Back</h1>
                    <p class="text-sm text-gray-400">Welcome Back, Please enter Your details</p>
                </div>

                {{-- Alert area (diisi via JS) --}}
                <div data-form-alert class="mb-5 hidden rounded-xl px-4 py-3 text-sm"></div>

                {{-- Segmented Control (Sign In / Sign Up) --}}
                <div class="mb-8 flex gap-1 rounded-2xl bg-gray-50 p-1">
                    <a href="{{ route('login') }}"
                        class="flex-1 rounded-xl bg-white py-2.5 text-center text-sm font-semibold text-gray-900 shadow-sm">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex-1 rounded-xl py-2.5 text-center text-sm font-medium text-gray-400 transition hover:text-gray-900">
                        Signup
                    </a>
                </div>

                {{-- Form Utama --}}
                <form method="POST" action="" class="space-y-5" data-auth-form="login" novalidate>
                    @csrf

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="mb-1.5 block text-xs font-medium text-gray-400">Email Address</label>
                        <div class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 transition-colors focus-within:border-navy-600"
                            data-field-wrapper>
                            <i class="far fa-envelope text-gray-400"></i>
                            <input type="email" id="email" name="email" required
                                placeholder="nama@email.com"
                                class="w-full bg-transparent text-sm outline-none placeholder:text-gray-400"
                                data-validate="email"
                                value="{{ old('email') }}">
                        </div>
                        <p class="mt-1 hidden text-xs text-red-500" data-field-error></p>
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-xs font-medium text-gray-400">Password</label>
                        <div class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 transition-colors focus-within:border-navy-600"
                            data-field-wrapper>
                            <i class="fas fa-lock text-gray-400"></i>
                            <input type="password" id="password" name="password" required minlength="8"
                                placeholder="Min. 8 karakter"
                                class="w-full bg-transparent text-sm outline-none placeholder:text-gray-400"
                                data-validate="password">
                            <button type="button" class="text-gray-400 transition hover:text-navy-600"
                                data-toggle-password aria-label="Tampilkan password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="mt-1 hidden text-xs text-red-500" data-field-error></p>
                    </div>

                    {{-- UX Best Practice: Forgot Password --}}
                    <div class="flex justify-end pt-1">
                        <a href="#" class="text-xs font-semibold text-navy-600 hover:underline">Forgot password?</a>
                    </div>

                    {{-- Tombol Continue --}}
                    <button type="submit" data-submit-btn
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0b1b42] py-3.5 text-sm font-semibold text-white transition hover:bg-navy-900 disabled:cursor-not-allowed disabled:opacity-70">
                        <span data-btn-label>Continue</span>
                        <span class="hidden" data-btn-spinner>
                            <i class="fas fa-circle-notch animate-spin"></i>
                        </span>
                    </button>
                </form>

                {{-- Social Login --}}
                <div class="mt-8 text-center">
                    <p class="mb-4 text-xs text-gray-400">Or Continue With</p>
                    <div class="flex justify-center gap-4">
                        @foreach ([
                            ['icon' => 'https://www.svgrepo.com/show/475656/google-color.svg', 'alt' => 'Google'],
                            ['icon' => 'https://www.svgrepo.com/show/511330/apple-173.svg', 'alt' => 'Apple'],
                            ['icon' => 'https://www.svgrepo.com/show/475647/facebook-color.svg', 'alt' => 'Facebook'],
                        ] as $provider)
                            <button type="button" class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-100 bg-white shadow-sm transition hover:bg-gray-50">
                                <img src="{{ $provider['icon'] }}" alt="{{ $provider['alt'] }}" class="h-5 w-5 object-contain">
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- Sisi Gambar (Dengan Overlay Navy) --}}
        <div class="hidden flex-1 lg:block relative bg-slate-100">
            {{-- Mengembalikan overlay warna navy agar serasi dengan brand --}}
            <div class="absolute inset-0 bg-navy-800/40 mix-blend-multiply z-10"></div>
            <img src="{{ asset('img/login.webp') }}" alt="MARK-UP Platform" class="absolute inset-0 h-full w-full object-cover z-0">
        </div>

    </div>
@endsection