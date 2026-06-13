@extends('layouts.admin')

@section('title', 'Manajemen Info Lomba')

@php
    $categoryTones = [
        'Business Case' => 'blue',
        'Business Plan' => 'orange',
        'Business Model Canvas' => 'orange',
        'Debat' => 'purple',
        'UI/UX' => 'purple',
        'LKTI' => 'green',
    ];

    $competitionCategories = ['Business Case', 'Business Plan', 'Business Model Canvas', 'UI/UX', 'LKTI'];
    $targetOptions = ['Mahasiswa', 'Umum'];

    $idr = fn ($v) => $v > 0 ? 'Rp ' . number_format($v, 0, ',', '.') : 'Gratis';
    $date = fn ($v) => $v ? \Carbon\Carbon::parse($v)->isoFormat('D MMM Y') : '-';
@endphp

@section('content')
<div x-data="{
    selectedComp: { id: '', title: '', category: '', target_audience: '', start_date: '', end_date: '', registration_fee: 0, total_prize: 0, organizer: '', link_pendaftaran: '' },
    isEdit: false,
    openCreateModal() {
        this.isEdit = false;
        this.selectedComp = { id: '', title: '', category: '', target_audience: '', start_date: '', end_date: '', registration_fee: 0, total_prize: 0, organizer: '', link_pendaftaran: '' };
        $dispatch('open-modal', { name: 'competition-form' });
    },
    openEditModal(comp) {
        this.isEdit = true;
        this.selectedComp = { ...comp };
        $dispatch('open-modal', { name: 'competition-form' });
    }
}">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Info Lomba</h1>
            <p class="mt-1 text-sm text-slate-500">Daftar lomba yang ditampilkan ke peserta MARK-UP.</p>
        </div>
        <x-admin.button variant="primary" icon="fa-plus"
            x-on:click="openCreateModal()">
            Tambah Lomba
        </x-admin.button>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <x-admin.table :columns="['Poster', 'Nama Lomba', 'Kategori', 'Tenggat', 'Biaya', 'Target Peserta', 'Aksi']">
        @foreach ($competitions as $competition)
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4">
                    <img src="{{ asset($competition->image_url ?? 'img/iyref.jpeg') }}" alt="{{ $competition->title }}"
                        class="h-14 w-10 rounded-md object-cover ring-1 ring-slate-200">
                </td>
                <td class="max-w-xs px-6 py-4">
                    <p class="font-semibold text-slate-800">{{ $competition->title }}</p>
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$categoryTones[$competition->category] ?? 'slate'">
                        {{ $competition->category }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $date($competition->end_date) }}</td>
                <td class="px-6 py-4 font-semibold text-slate-700">{{ $idr($competition->registration_fee) }}</td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$competition->target_audience === 'Umum' ? 'orange' : 'slate'">
                        {{ $competition->target_audience }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <x-admin.button variant="ghost-blue" size="icon" icon="fa-pen"
                            x-on:click="openEditModal({
                                id: {{ $competition->id }},
                                title: '{{ addslashes($competition->title) }}',
                                category: '{{ $competition->category }}',
                                target_audience: '{{ $competition->target_audience }}',
                                start_date: '{{ $competition->start_date ? $competition->start_date->format('Y-m-d') : '' }}',
                                end_date: '{{ $competition->end_date ? $competition->end_date->format('Y-m-d') : '' }}',
                                registration_fee: {{ $competition->registration_fee }},
                                total_prize: {{ $competition->total_prize }},
                                organizer: '{{ addslashes($competition->organizer) }}',
                                link_pendaftaran: '{{ $competition->link_pendaftaran }}'
                            })" />
                        <form action="{{ (request()->is('admin/*') ? '/admin' : '/mentor') }}/competitions/{{ $competition->id }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lomba ini?')">
                            @csrf
                            @method('DELETE')
                            <x-admin.button variant="ghost-danger" size="icon" icon="fa-trash" type="submit" />
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table>

    {{-- Modal: Tambah/Edit Lomba --}}
    <x-admin.modal name="competition-form" ::title="isEdit ? 'Edit Info Lomba' : 'Tambah Info Lomba'" size="xl">
        <form id="comp-form" method="POST" :action="isEdit ? '{{ request()->is('admin/*') ? '/admin' : '/mentor' }}/competitions/' + selectedComp.id : '{{ request()->is('admin/*') ? '/admin' : '/mentor' }}/competitions'" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <template x-if="isEdit">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <x-admin.field label="Nama Lomba" name="title" :required="true">
                <input type="text" id="title" name="title" x-model="selectedComp.title" placeholder="Contoh: ASCEND National Business Case 2026"
                    class="admin-input" required>
            </x-admin.field>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Kategori" name="category" :required="true">
                    <select id="category" name="category" x-model="selectedComp.category" class="admin-input-select" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        @foreach ($competitionCategories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Target Peserta" name="target_audience" :required="true">
                    <select id="target_audience" name="target_audience" x-model="selectedComp.target_audience" class="admin-input-select" required>
                        <option value="" disabled selected>Pilih target</option>
                        @foreach ($targetOptions as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Waktu Pelaksanaan" name="start_date" :required="true">
                    <input type="date" id="start_date" name="start_date" x-model="selectedComp.start_date" class="admin-input" required>
                </x-admin.field>

                <x-admin.field label="Tenggat Pendaftaran" name="end_date" :required="true">
                    <input type="date" id="end_date" name="end_date" x-model="selectedComp.end_date" class="admin-input" required>
                </x-admin.field>

                <x-admin.field label="Biaya Pendaftaran" name="registration_fee" hint="Isi 0 jika gratis.">
                    <input type="number" id="registration_fee" name="registration_fee" x-model="selectedComp.registration_fee" min="0" placeholder="250000" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Total Hadiah" name="total_prize">
                    <input type="number" id="total_prize" name="total_prize" x-model="selectedComp.total_prize" min="0" placeholder="50000000" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Penyelenggara" name="organizer" :required="true">
                    <input type="text" id="organizer" name="organizer" x-model="selectedComp.organizer" placeholder="Universitas / Lembaga"
                        class="admin-input" required>
                </x-admin.field>
            </div>

            <x-admin.field label="Upload Poster" name="poster" hint="Rekomendasi rasio 3:4, maks 2 MB.">
                <input type="file" id="poster" name="poster" accept="image/*"
                    class="admin-input file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-blue-600 hover:file:bg-blue-100">
            </x-admin.field>

            <x-admin.field label="Link Pendaftaran" name="link_pendaftaran" hint="URL ke landing page lomba atau guide book.">
                <input type="url" id="link_pendaftaran" name="link_pendaftaran" x-model="selectedComp.link_pendaftaran" placeholder="https://..." class="admin-input">
            </x-admin.field>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" form="comp-form" icon="fa-floppy-disk">Simpan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
</div>
@endsection
