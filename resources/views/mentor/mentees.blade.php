@extends('layouts.admin')

@section('title', 'Daftar Mentee')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-slate-800">Daftar Mahasiswa Bimbingan (Mentee)</h1>
    <p class="mt-1 text-sm text-slate-500">Kelola dan pantau mahasiswa di bawah bimbingan Anda.</p>
</div>

@if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 animate-fade-in">
        {{ session('success') }}
    </div>
@endif

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <x-admin.table :columns="['Nama', 'Email', 'Institusi']">
        @forelse($mentees as $mentee)
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4 font-semibold text-slate-800">
                    {{ $mentee->name }}
                </td>
                <td class="px-6 py-4 text-slate-500 text-sm">
                    {{ $mentee->email }}
                </td>
                <td class="px-6 py-4 text-slate-500 text-sm">
                    {{ $mentee->institution ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                    <i class="fa-solid fa-graduation-cap text-3xl mb-2 text-slate-300 block"></i>
                    Belum ada mahasiswa bimbingan terdaftar.
                </td>
            </tr>
        @endforelse
    </x-admin.table>
</div>
@endsection
