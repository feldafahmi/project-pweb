@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@php
    $users = [
        ['id' => 1, 'name' => 'Faisal Fahmi', 'email' => 'faisal@markup.id', 'role' => 'Student', 'status' => 'Aktif', 'joined_at' => '2026-02-15'],
        ['id' => 2, 'name' => 'Sarah Putri', 'email' => 'sarah@markup.id', 'role' => 'Mentor', 'status' => 'Aktif', 'joined_at' => '2026-01-20'],
        ['id' => 3, 'name' => 'Admin MARK-UP', 'email' => 'admin@markup.id', 'role' => 'Admin', 'status' => 'Aktif', 'joined_at' => '2026-01-01'],
        ['id' => 4, 'name' => 'Ryan Mahendra', 'email' => 'ryan@markup.id', 'role' => 'Student', 'status' => 'Suspend', 'joined_at' => '2026-03-10'],
        ['id' => 5, 'name' => 'Michelle Tan', 'email' => 'michelle@markup.id', 'role' => 'Student', 'status' => 'Aktif', 'joined_at' => '2026-04-02'],
    ];

    $roleTones = [
        'Admin' => 'red',
        'Mentor' => 'orange',
        'Student' => 'blue',
    ];

    $statusTones = [
        'Aktif' => 'green',
        'Suspend' => 'red',
    ];

    $roleOptions = ['Admin', 'Mentor', 'Student'];
    $statusOptions = ['Aktif', 'Suspend'];

    $date = fn ($v) => \Carbon\Carbon::parse($v)->isoFormat('D MMM Y');
@endphp

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola role dan status akun pengguna platform.</p>
        </div>

        {{-- Optional: simple filter dropdown --}}
        <div class="relative">
            <select class="admin-input-select min-w-[180px]">
                <option>Semua Role</option>
                @foreach ($roleOptions as $r)
                    <option>{{ $r }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <x-admin.table :columns="['Nama Lengkap', 'Email', 'Role', 'Status', 'Bergabung', 'Aksi']">
        @foreach ($users as $user)
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">
                            {{ collect(explode(' ', $user['name']))->map(fn ($p) => $p[0])->take(2)->implode('') }}
                        </span>
                        <p class="font-semibold text-slate-800">{{ $user['name'] }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $user['email'] }}</td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$roleTones[$user['role']] ?? 'slate'">
                        <i class="fas {{ $user['role'] === 'Admin' ? 'fa-crown' : ($user['role'] === 'Mentor' ? 'fa-user-tie' : 'fa-graduation-cap') }} text-[10px]"></i>
                        {{ $user['role'] }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$statusTones[$user['status']] ?? 'slate'">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        {{ $user['status'] }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $date($user['joined_at']) }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <x-admin.button variant="ghost-blue" size="sm" icon="fa-user-pen"
                            x-on:click="$dispatch('open-modal', { name: 'user-form' })">
                            Edit Role
                        </x-admin.button>
                        <x-admin.button :variant="$user['status'] === 'Aktif' ? 'ghost-danger' : 'ghost-blue'"
                            size="icon"
                            :icon="$user['status'] === 'Aktif' ? 'fa-ban' : 'fa-rotate-left'" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table>

    {{-- Modal: Edit Role / Status --}}
    <x-admin.modal name="user-form" title="Edit Role & Status Pengguna" size="md">
        <form class="space-y-5">
            @csrf

            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pengguna</p>
                <p class="mt-0.5 font-bold text-slate-800">Faisal Fahmi</p>
                <p class="text-xs text-slate-500">faisal@markup.id</p>
            </div>

            <x-admin.field label="Role" name="role" :required="true">
                <select id="role" name="role" class="admin-input-select">
                    @foreach ($roleOptions as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
            </x-admin.field>

            <x-admin.field label="Status" name="status" :required="true">
                <select id="status" name="status" class="admin-input-select">
                    @foreach ($statusOptions as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </x-admin.field>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" icon="fa-check">Simpan Perubahan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
@endsection
