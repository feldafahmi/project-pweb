@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@php
    $roleTones = [
        'admin' => 'red',
        'mentor' => 'orange',
        'user' => 'blue',
    ];

    $roleOptions = ['admin', 'mentor', 'user'];

    $date = fn ($v) => $v ? \Carbon\Carbon::parse($v)->isoFormat('D MMM Y') : '-';
@endphp

@section('content')
<div x-data="{ 
    selectedUser: { id: '', name: '', email: '', role: '' },
    openUserModal(user) {
        this.selectedUser = { ...user };
        $dispatch('open-modal', { name: 'user-form' });
    }
}">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola role dan status akun pengguna platform.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <x-admin.table :columns="['Nama Lengkap', 'Email', 'Role', 'Bergabung', 'Aksi']">
        @foreach ($users as $user)
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">
                            {{ collect(explode(' ', $user->name))->map(fn ($p) => $p[0])->take(2)->implode('') }}
                        </span>
                        <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$roleTones[$user->role] ?? 'slate'">
                        <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : ($user->role === 'mentor' ? 'fa-user-tie' : 'fa-graduation-cap') }} text-[10px]"></i>
                        {{ ucfirst($user->role) }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $date($user->created_at) }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <x-admin.button variant="ghost-blue" size="sm" icon="fa-user-pen"
                            x-on:click="openUserModal({ id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', role: '{{ $user->role }}' })">
                            Edit Role
                        </x-admin.button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table>

    {{-- Modal: Edit Role --}}
    <x-admin.modal name="user-form" title="Edit Role Pengguna" size="md">
        <form id="edit-user-form" method="POST" :action="'/admin/users/' + selectedUser.id + '/role'" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pengguna</p>
                <p class="mt-0.5 font-bold text-slate-800" x-text="selectedUser.name">Nama Pengguna</p>
                <p class="text-xs text-slate-500" x-text="selectedUser.email">email@domain.com</p>
            </div>

            <x-admin.field label="Role" name="role" :required="true">
                <select id="role" name="role" class="admin-input-select" x-model="selectedUser.role" required>
                    @foreach ($roleOptions as $r)
                        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </x-admin.field>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" form="edit-user-form" icon="fa-check">Simpan Perubahan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
</div>
@endsection
