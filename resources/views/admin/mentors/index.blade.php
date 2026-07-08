@extends('layouts.admin')

@section('title', 'On Demand Mentoring')

@php
    $idr = fn ($v) => $v > 0 ? 'Rp ' . number_format($v, 0, ',', '.') : 'Gratis';
    $blankMentor = [
        'id' => '', 'name' => '', 'title' => '', 'about' => '', 'response_time' => '',
        'rating' => 5, 'sessions' => 0, 'available' => '1', 'price_per_session' => 0,
        'tags' => '', 'highlights' => '',
    ];
@endphp

@section('content')
<div x-data="{
    isEdit: false,
    selectedMentor: @js($blankMentor),
    openCreateModal() {
        this.isEdit = false;
        this.selectedMentor = @js($blankMentor);
        $dispatch('open-modal', { name: 'mentor-form' });
    },
    openEditModal(mentor) {
        this.isEdit = true;
        this.selectedMentor = { ...mentor };
        $dispatch('open-modal', { name: 'mentor-form' });
    }
}">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">On Demand Mentoring</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola mentor yang bisa dipesan peserta secara on demand (web &amp; app mobile).</p>
        </div>
        <x-admin.button variant="primary" icon="fa-plus" x-on:click="openCreateModal()">
            Tambah Mentor
        </x-admin.button>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <x-admin.table :columns="['Mentor', 'Keahlian', 'Rating', 'Sesi', 'Harga/Sesi', 'Status', 'Aksi']">
        @forelse ($mentors as $mentor)
            @php
                // Payload untuk modal edit. Dihitung di sini (bukan inline di @js)
                // agar direktif @js tidak gagal parse ekspresi kompleks.
                $editData = [
                    'id'                => $mentor->id,
                    'name'              => $mentor->name,
                    'title'             => $mentor->title,
                    'about'             => $mentor->about,
                    'response_time'     => $mentor->response_time,
                    'rating'            => $mentor->rating,
                    'sessions'          => $mentor->sessions,
                    'available'         => $mentor->available ? '1' : '0',
                    'price_per_session' => $mentor->price_per_session,
                    'tags'              => implode(', ', $mentor->tags ?? []),
                    // highlights disimpan sbg objek {icon, text}; ambil teksnya saja.
                    'highlights'        => collect($mentor->highlights ?? [])
                        ->map(fn ($h) => is_array($h) ? ($h['text'] ?? '') : $h)
                        ->filter()->implode(PHP_EOL),
                ];
            @endphp
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset($mentor->avatar_url ?? 'img/mentor1.png') }}" alt="{{ $mentor->name }}"
                            class="h-11 w-11 rounded-xl object-cover ring-1 ring-slate-200">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800">{{ $mentor->name }}</p>
                            <p class="max-w-[220px] truncate text-xs text-slate-500">{{ $mentor->title }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex max-w-[200px] flex-wrap gap-1">
                        @foreach (array_slice($mentor->tags ?? [], 0, 3) as $tag)
                            <x-admin.badge tone="purple">{{ $tag }}</x-admin.badge>
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-500">
                        <i class="fas fa-star text-xs"></i> {{ number_format($mentor->rating, 1) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $mentor->sessions }}</td>
                <td class="px-6 py-4 font-semibold text-slate-700">{{ $idr($mentor->price_per_session) }}</td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$mentor->available ? 'green' : 'slate'">
                        {{ $mentor->available ? 'Tersedia' : 'Penuh' }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        {{-- NB: @js tidak dikompilasi di dalam atribut komponen Blade;
                             pakai Js::from lewat echo {{ }} yang tetap ter-render. --}}
                        <x-admin.button variant="ghost-blue" size="icon" icon="fa-pen"
                            x-on:click="openEditModal({{ \Illuminate\Support\Js::from($editData) }})" />
                        <form action="{{ route('admin.mentors.destroy', $mentor->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Hapus mentor ini?')">
                            @csrf
                            @method('DELETE')
                            <x-admin.button variant="ghost-danger" size="icon" icon="fa-trash" type="submit" />
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                    <i class="fas fa-chalkboard-user mb-3 block text-4xl text-slate-300"></i>
                    <p class="font-semibold text-slate-500">Belum ada mentor.</p>
                    <p class="text-sm">Tambahkan mentor pertama untuk fitur On Demand Mentoring.</p>
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    {{-- Modal: Tambah/Edit Mentor --}}
    <x-admin.modal name="mentor-form" ::title="isEdit ? 'Edit Mentor' : 'Tambah Mentor'" size="xl">
        <form id="mentor-form" method="POST"
            :action="isEdit ? '{{ url('admin/mentors') }}/' + selectedMentor.id : '{{ url('admin/mentors') }}'"
            enctype="multipart/form-data" class="space-y-5">
            @csrf
            <template x-if="isEdit">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Nama Mentor" name="name" :required="true">
                    <input type="text" id="name" name="name" x-model="selectedMentor.name"
                        placeholder="Contoh: Rizka Nabilah" class="admin-input" required>
                </x-admin.field>

                <x-admin.field label="Titel / Jabatan" name="title" :required="true">
                    <input type="text" id="title" name="title" x-model="selectedMentor.title"
                        placeholder="Contoh: 3x Juara Nasional Business Case" class="admin-input" required>
                </x-admin.field>
            </div>

            <x-admin.field label="Tentang Mentor" name="about" hint="Deskripsi singkat pengalaman mentor.">
                <textarea id="about" name="about" x-model="selectedMentor.about" rows="3"
                    placeholder="Ceritakan latar belakang & pengalaman mentor..." class="admin-input"></textarea>
            </x-admin.field>

            <x-admin.field label="Keunggulan" name="highlights" hint="Satu poin per baris.">
                <textarea id="highlights" name="highlights" x-model="selectedMentor.highlights" rows="3"
                    placeholder="Juara 1 ASCEND 2025&#10;Ex-consultant BCG&#10;Mentor 100+ tim" class="admin-input"></textarea>
            </x-admin.field>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Bidang Keahlian (Tags)" name="tags" hint="Pisahkan dengan koma.">
                    <input type="text" id="tags" name="tags" x-model="selectedMentor.tags"
                        placeholder="Business Case, LKTI, Pitch Deck" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Waktu Respon" name="response_time" hint="Contoh: < 1 jam">
                    <input type="text" id="response_time" name="response_time" x-model="selectedMentor.response_time"
                        placeholder="< 1 jam" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Rating" name="rating" hint="Skala 0 - 5.">
                    <input type="number" id="rating" name="rating" x-model="selectedMentor.rating"
                        min="0" max="5" step="0.1" placeholder="5.0" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Jumlah Sesi" name="sessions">
                    <input type="number" id="sessions" name="sessions" x-model="selectedMentor.sessions"
                        min="0" placeholder="0" class="admin-input">
                </x-admin.field>

                <x-admin.field label="Harga per Sesi" name="price_per_session" :required="true" hint="Dalam Rupiah.">
                    <input type="number" id="price_per_session" name="price_per_session" x-model="selectedMentor.price_per_session"
                        min="0" placeholder="150000" class="admin-input" required>
                </x-admin.field>

                <x-admin.field label="Status Ketersediaan" name="available" :required="true">
                    <select id="available" name="available" x-model="selectedMentor.available" class="admin-input-select">
                        <option value="1">Tersedia</option>
                        <option value="0">Penuh</option>
                    </select>
                </x-admin.field>
            </div>

            <x-admin.field label="Foto Mentor" name="avatar" hint="Rekomendasi rasio 1:1, maks 2 MB. Kosongkan jika tidak ingin mengubah.">
                <input type="file" id="avatar" name="avatar" accept="image/*"
                    class="admin-input file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-blue-600 hover:file:bg-blue-100">
            </x-admin.field>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" form="mentor-form" icon="fa-floppy-disk">Simpan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
</div>
@endsection
