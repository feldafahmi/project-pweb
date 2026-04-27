@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun dan keamanan akunmu di sini.')

@php
    $tabs = [
        ['route' => 'dashboard.profile.index', 'label' => 'Profil Saya', 'icon' => 'fa-id-card'],
        ['route' => 'dashboard.profile.password', 'label' => 'Password', 'icon' => 'fa-lock'],
    ];
@endphp

@section('content')
    {{-- Sub-navigation pills --}}
    <nav class="mb-6 flex flex-wrap gap-2 rounded-xl bg-white p-1.5 shadow-sm ring-1 ring-slate-100 sm:inline-flex">
        @foreach ($tabs as $tab)
            @php $active = request()->routeIs($tab['route']); @endphp
            <a href="{{ route($tab['route']) }}"
                @class([
                    'inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-bold transition-all',
                    'bg-purple-600 text-white shadow-sm shadow-purple-600/30' => $active,
                    'text-slate-500 hover:bg-slate-50 hover:text-navy-600' => ! $active,
                ])>
                <i class="fas {{ $tab['icon'] }} text-xs"></i>
                <span>{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </nav>

    @yield('profile-content')
@endsection
