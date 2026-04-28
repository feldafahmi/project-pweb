@extends('layouts.admin')

@section('title', 'Manajemen Info Lomba')

@php
    $competitions = [
        ['id' => 1, 'poster' => 'img/ascend.jpg', 'name' => 'ASCEND National Business Case Competition 2026', 'category' => 'Business Case', 'deadline' => '2026-05-15', 'fee' => 250000, 'level' => 'Nasional'],
        ['id' => 2, 'poster' => 'img/ignite.jpeg', 'name' => 'IGNITE Asia Business Plan Olympiad', 'category' => 'Business Plan', 'deadline' => '2026-06-01', 'fee' => 500000, 'level' => 'Internasional'],
        ['id' => 3, 'poster' => 'img/iyref.jpeg', 'name' => 'IYREF Indonesia Debate Championship', 'category' => 'Debat', 'deadline' => '2026-04-30', 'fee' => 0, 'level' => 'Nasional'],
        ['id' => 4, 'poster' => 'img/nbcc.jpeg', 'name' => 'NBCC Marketing Strategy Challenge', 'category' => 'Marketing', 'deadline' => '2026-05-22', 'fee' => 150000, 'level' => 'Nasional'],
    ];

    $categoryTones = [
        'Business Case' => 'blue',
        'Business Plan' => 'orange',
        'Debat' => 'purple',
        'Marketing' => 'green',
    ];

    $competitionCategories = ['Business Case', 'Business Plan', 'Debat', 'Marketing', 'UI/UX', 'Hackathon'];
    $targetOptions = ['Mahasiswa', 'Siswa', 'Mahasiswa & Siswa'];
    $levelOptions = ['Nasional', 'Internasional'];

    $idr = fn ($v) => $v > 0 ? 'Rp ' . number_format($v, 0, ',', '.') : 'Gratis';
    $date = fn ($v) => \Carbon\Carbon::parse($v)->isoFormat('D MMM Y');
@endphp

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Info Lomba</h1>
            <p class="mt-1 text-sm text-slate-500">Daftar lomba yang ditampilkan ke peserta MARK-UP.</p>
        </div>
        <x-admin.button variant="primary" icon="fa-plus"
            x-on:click="$dispatch('open-modal', { name: 'competition-form' })">
            Tambah Lomba
        </x-admin.button>
    </div>

    <x-admin.table :columns="['Poster', 'Nama Lomba', 'Kategori', 'Tenggat', 'Biaya', 'Tingkat', 'Aksi']">
        @foreach ($competitions as $competition)
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4">
                    <img src="{{ asset($competition['poster']) }}" alt="{{ $competition['name'] }}"
                        class="h-14 w-10 rounded-md object-cover ring-1 ring-slate-200">
                </td>
                <td class="max-w-xs px-6 py-4">
                    <p class="font-semibold text-slate-800">{{ $competition['name'] }}</p>
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$categoryTones[$competition['category']] ?? 'slate'">
                        {{ $competition['category'] }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $date($competition['deadline']) }}</td>
                <td class="px-6 py-4 font-semibold text-slate-700">{{ $idr($competition['fee']) }}</td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$competition['level'] === 'Internasional' ? 'orange' : 'slate'">
                        {{ $competition['level'] }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <x-admin.button variant="ghost-blue" size="icon" icon="fa-pen"
                            x-on:click="$dispatch('open-modal', { name: 'competition-form' })" />
                        <x-admin.button variant="ghost-danger" size="icon" icon="fa-trash" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table>

    {{-- Modal: Tambah/Edit Lomba --}}
    <x-admin.modal name="competition-form" title="Tambah / Edit Info Lomba" size="xl">
        <form class="space-y-5">
            @csrf

            <x-admin.field label="Nama Lomba" name="comp_name" :required="true">
                <input type="text" id="comp_name" name="name" placeholder="Contoh: ASCEND National Business Case 2026"
                    class="admin-input">
            </x-admin.field>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Kategori" name="category" :required="true">
                    <select id="category" name="category" class="admin-input-select">
                        <option value="" disabled selected>Pilih kategori</option>
                        @foreach ($competitionCategories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Tingkat" name="level" :required="true">
                    <select id="level" name="level" class="admin-input-select">
                        <option value="" disabled selected>Pilih tingkat</option>
                        @foreach ($levelOptions as $lvl)
                            <option value="{{ $lvl }}">{{ $lvl }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Waktu Pelaksanaan" name="event_date" :required="true">
                    <input type="date" id="event_date" name="event_date" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Tenggat Pendaftaran" name="deadline" :required="true">
                    <input type="date" id="deadline" name="deadline" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Biaya Pendaftaran" name="fee" hint="Isi 0 jika gratis.">
                    <input type="number" id="fee" name="fee" min="0" placeholder="250000" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Total Hadiah" name="prize">
                    <input type="number" id="prize" name="prize" min="0" placeholder="50000000" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Target Peserta" name="target" :required="true">
                    <select id="target" name="target" class="admin-input-select">
                        <option value="" disabled selected>Pilih target</option>
                        @foreach ($targetOptions as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Penyelenggara" name="organizer">
                    <input type="text" id="organizer" name="organizer" placeholder="Universitas / Lembaga"
                        class="admin-input">
                </x-admin.field>
            </div>

            <x-admin.field label="Upload Poster" name="poster" hint="Rekomendasi rasio 3:4, maks 2 MB.">
                <input type="file" id="poster" name="poster" accept="image/*"
                    class="admin-input file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-blue-600 hover:file:bg-blue-100">
            </x-admin.field>

            <x-admin.field label="Info Selengkapnya" name="info_url" hint="URL ke landing page lomba atau guide book.">
                <input type="url" id="info_url" name="info_url" placeholder="https://..." class="admin-input">
            </x-admin.field>

            <x-admin.field label="Deskripsi Singkat" name="description">
                <textarea id="description" name="description" rows="4"
                    placeholder="Tulis ringkasan lomba, syarat, dan benefit..."
                    class="admin-input"></textarea>
            </x-admin.field>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" icon="fa-floppy-disk">Simpan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
@endsection
