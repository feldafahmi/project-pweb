@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@php
    $typeTones = [
        'kelas' => 'blue',
        'bootcamp' => 'orange',
        'modul' => 'purple',
    ];

    $productTypes = ['kelas', 'bootcamp', 'modul'];
    $base = request()->is('admin/*') ? '/admin' : '/mentor';

    $idr = fn ($v) => $v ? 'Rp ' . number_format($v, 0, ',', '.') : '-';
@endphp

@section('content')
<div x-data="{
    isEdit: false,
    selectedProduct: {},
    blank() {
        return {
            id: '', type: '', title: '', description: '',
            original_price: '', price: '', video_url: '', whatsapp_link: '',
            rating: '', win_rate: '', total_pages: '', students: '', duration: '',
            is_featured: false, is_bestseller: false,
            learnings: [], includes: [], chapters: [], curriculum_sections: [], batches: [],
        };
    },
    openCreateModal() {
        this.isEdit = false;
        this.selectedProduct = this.blank();
        $dispatch('open-modal', { name: 'product-form' });
    },
    openEditModal(prod) {
        this.isEdit = true;
        this.selectedProduct = Object.assign(this.blank(), JSON.parse(JSON.stringify(prod)));
        ['learnings','includes','chapters','curriculum_sections','batches'].forEach(k => {
            if (!Array.isArray(this.selectedProduct[k])) this.selectedProduct[k] = [];
        });
        $dispatch('open-modal', { name: 'product-form' });
    },
    addChapter() { this.selectedProduct.chapters.push({ chapter_number:'', title:'', page_range:'', file_url:'', is_free:false }); },
    addSection() { this.selectedProduct.curriculum_sections.push({ title:'', subtitle:'', items:[] }); },
    addItem(sec) { sec.items.push({ title:'', duration:'', type:'video', content_url:'', is_free:false }); },
    addBatch() { this.selectedProduct.batches.push({ label:'', date_range:'', spots:0, status:'open' }); },
    get isCurriculum() { return this.selectedProduct.type === 'kelas' || this.selectedProduct.type === 'bootcamp'; },
}">
    {{-- Page header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Produk</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola katalog produk pembelajaran MARK-UP, lengkap dengan kurikulum.</p>
        </div>
        <x-admin.button variant="primary" icon="fa-plus" x-on:click="openCreateModal()">
            Tambah Produk
        </x-admin.button>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Periksa kembali isian berikut:</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Table --}}
    <x-admin.table :columns="['Gambar', 'Nama Produk', 'Tipe', 'Kurikulum', 'Harga Diskon', 'Aksi']">
        @foreach ($products as $product)
            @php
                $curriculumCount = $product->type === 'modul'
                    ? $product->chapters->count() . ' bab'
                    : $product->curriculumSections->count() . ' bagian';
            @endphp
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4">
                    <img src="{{ asset($product->image_url ?? 'img/63815.png') }}" alt="{{ $product->title }}"
                        class="h-12 w-12 rounded-lg object-cover ring-1 ring-slate-200">
                </td>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-800">{{ $product->title }}</p>
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$typeTones[$product->type] ?? 'slate'">
                        {{ ucfirst($product->type) }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $curriculumCount }}</td>
                <td class="px-6 py-4 font-bold text-blue-600">{{ $idr($product->price) }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        @php
                            $productPayload = [
                                'id'             => $product->id,
                                'type'           => $product->type,
                                'title'          => $product->title,
                                'description'    => $product->description,
                                'original_price' => $product->original_price ?? 0,
                                'price'          => $product->price ?? 0,
                                'video_url'      => $product->video_url,
                                'whatsapp_link'  => $product->whatsapp_link,
                                'rating'         => $product->rating,
                                'win_rate'       => $product->win_rate,
                                'total_pages'    => $product->total_pages,
                                'students'       => $product->students,
                                'duration'       => $product->duration,
                                'is_featured'    => (bool) $product->is_featured,
                                'is_bestseller'  => (bool) $product->is_bestseller,
                                'learnings'      => $product->learnings ?? [],
                                'includes'       => $product->includes ?? [],
                                'chapters'       => $product->chapters->map(fn ($c) => [
                                    'chapter_number' => $c->chapter_number,
                                    'title'          => $c->title,
                                    'page_range'     => $c->page_range,
                                    'file_url'       => $c->file_url,
                                    'is_free'        => (bool) $c->is_free,
                                ])->all(),
                                'curriculum_sections' => $product->curriculumSections->map(fn ($s) => [
                                    'title'    => $s->title,
                                    'subtitle' => $s->subtitle,
                                    'items'    => $s->items->map(fn ($it) => [
                                        'title'       => $it->title,
                                        'duration'    => $it->duration,
                                        'type'        => $it->type,
                                        'content_url' => $it->content_url,
                                        'is_free'     => (bool) $it->is_free,
                                    ])->all(),
                                ])->all(),
                                'batches' => $product->batches->map(fn ($b) => [
                                    'label'      => $b->label,
                                    'date_range' => $b->date_range,
                                    'spots'      => $b->spots,
                                    'status'     => $b->status,
                                ])->all(),
                            ];
                        @endphp
                        <button type="button" x-on:click="openEditModal(@js($productPayload))"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg font-semibold text-blue-600 transition hover:bg-blue-50">
                            <i class="fas fa-pen"></i>
                        </button>
                        <form action="{{ $base }}/products/{{ $product->id }}" method="POST" class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <x-admin.button variant="ghost-danger" size="icon" icon="fa-trash" type="submit" />
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table>

    {{-- Modal: Tambah/Edit Produk --}}
    <x-admin.modal name="product-form" ::title="isEdit ? 'Edit Produk' : 'Tambah Produk'" size="xl">
        <form id="prod-form" method="POST"
            :action="isEdit ? '{{ $base }}/products/' + selectedProduct.id : '{{ $base }}/products'"
            enctype="multipart/form-data" class="space-y-6 max-h-[70vh] overflow-y-auto pr-1">
            @csrf
            <template x-if="isEdit">
                <input type="hidden" name="_method" value="PUT">
            </template>

            {{-- ============ INFO DASAR ============ --}}
            <section class="space-y-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Dasar</h3>

                <x-admin.field label="Nama Produk" name="title" :required="true">
                    <input type="text" name="title" x-model="selectedProduct.title"
                        placeholder="Contoh: Winner Class: Business Case Mastery" class="admin-input" required>
                </x-admin.field>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.field label="Tipe" name="type" :required="true">
                        <select name="type" x-model="selectedProduct.type" class="admin-input-select" required>
                            <option value="" disabled>Pilih tipe produk</option>
                            @foreach ($productTypes as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </x-admin.field>

                    <x-admin.field label="Upload Gambar" name="image" hint="JPG/PNG, maks 2 MB. Kosongkan untuk tetap.">
                        <input type="file" name="image" accept="image/*"
                            class="admin-input file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-blue-600 hover:file:bg-blue-100">
                    </x-admin.field>
                </div>

                <x-admin.field label="Deskripsi" name="description">
                    <textarea name="description" x-model="selectedProduct.description" rows="3"
                        placeholder="Jelaskan manfaat & isi produk..." class="admin-input"></textarea>
                </x-admin.field>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.field label="Harga Asli" name="original_price" :required="true">
                        <input type="number" name="original_price" x-model="selectedProduct.original_price" min="0"
                            placeholder="299000" class="admin-input" required>
                    </x-admin.field>
                    <x-admin.field label="Harga Diskon" name="price" :required="true">
                        <input type="number" name="price" x-model="selectedProduct.price" min="0"
                            placeholder="199000" class="admin-input" required>
                    </x-admin.field>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.field label="Link Video YouTube" name="video_url" hint="Video pengantar di halaman belajar.">
                        <input type="url" name="video_url" x-model="selectedProduct.video_url"
                            placeholder="https://www.youtube.com/watch?v=..." class="admin-input">
                    </x-admin.field>
                    <x-admin.field label="Link Grup WhatsApp" name="whatsapp_link">
                        <input type="url" name="whatsapp_link" x-model="selectedProduct.whatsapp_link"
                            placeholder="https://chat.whatsapp.com/..." class="admin-input">
                    </x-admin.field>
                </div>
            </section>

            {{-- ============ METADATA ============ --}}
            <section class="space-y-5 border-t border-slate-100 pt-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Statistik & Sorotan</h3>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <x-admin.field label="Rating (0–5)" name="rating">
                        <input type="number" step="0.1" min="0" max="5" name="rating" x-model="selectedProduct.rating"
                            placeholder="4.8" class="admin-input">
                    </x-admin.field>
                    <x-admin.field label="Win Rate (%)" name="win_rate">
                        <input type="number" min="0" max="100" name="win_rate" x-model="selectedProduct.win_rate"
                            placeholder="87" class="admin-input">
                    </x-admin.field>
                    <x-admin.field label="Jml Siswa" name="students">
                        <input type="number" min="0" name="students" x-model="selectedProduct.students"
                            placeholder="1200" class="admin-input">
                    </x-admin.field>
                    <x-admin.field label="Durasi" name="duration">
                        <input type="text" name="duration" x-model="selectedProduct.duration"
                            placeholder="8+ jam / 30 Hari" class="admin-input">
                    </x-admin.field>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.field label="Total Halaman (modul)" name="total_pages">
                        <input type="number" min="0" name="total_pages" x-model="selectedProduct.total_pages"
                            placeholder="145" class="admin-input">
                    </x-admin.field>
                    <div class="flex items-end gap-6 pb-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
                            <input type="checkbox" name="is_featured" value="1" x-model="selectedProduct.is_featured"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600">
                            Unggulan
                        </label>
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
                            <input type="checkbox" name="is_bestseller" value="1" x-model="selectedProduct.is_bestseller"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600">
                            Bestseller
                        </label>
                    </div>
                </div>
            </section>

            {{-- ============ LEARNINGS ============ --}}
            <section class="space-y-3 border-t border-slate-100 pt-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Yang Dipelajari</h3>
                    <button type="button" @click="selectedProduct.learnings.push('')"
                        class="text-xs font-bold text-blue-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Poin</button>
                </div>
                <template x-for="(item, idx) in selectedProduct.learnings" :key="idx">
                    <div class="flex items-center gap-2">
                        <input type="text" :name="'learnings['+idx+']'" x-model="selectedProduct.learnings[idx]"
                            placeholder="Analisis kasus dengan framework MECE..." class="admin-input flex-1">
                        <button type="button" @click="selectedProduct.learnings.splice(idx,1)"
                            class="shrink-0 text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </template>
                <p x-show="!selectedProduct.learnings.length" class="text-xs text-slate-400">Belum ada poin pembelajaran.</p>
            </section>

            {{-- ============ INCLUDES ============ --}}
            <section class="space-y-3 border-t border-slate-100 pt-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Termasuk dalam Paket</h3>
                    <button type="button" @click="selectedProduct.includes.push({ icon:'', text:'' })"
                        class="text-xs font-bold text-blue-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Item</button>
                </div>
                <template x-for="(row, idx) in selectedProduct.includes" :key="idx">
                    <div class="flex items-center gap-2">
                        <input type="text" :name="'includes['+idx+'][icon]'" x-model="row.icon" placeholder="ikon (video/file/check)"
                            class="admin-input w-40">
                        <input type="text" :name="'includes['+idx+'][text]'" x-model="row.text" placeholder="16 video materi (8+ jam)"
                            class="admin-input flex-1">
                        <button type="button" @click="selectedProduct.includes.splice(idx,1)"
                            class="shrink-0 text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </template>
                <p x-show="!selectedProduct.includes.length" class="text-xs text-slate-400">Belum ada item paket.</p>
            </section>

            {{-- ============ KURIKULUM: MODUL (chapters) ============ --}}
            <section class="space-y-3 border-t border-slate-100 pt-5" x-show="selectedProduct.type === 'modul'" x-cloak>
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Isi (Bab PDF)</h3>
                    <button type="button" @click="addChapter()"
                        class="text-xs font-bold text-blue-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Bab</button>
                </div>
                <template x-for="(ch, idx) in selectedProduct.chapters" :key="idx">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-3 space-y-2">
                        <div class="flex items-center gap-2">
                            <input type="text" :name="'chapters['+idx+'][chapter_number]'" x-model="ch.chapter_number"
                                placeholder="01" class="admin-input w-16">
                            <input type="text" :name="'chapters['+idx+'][title]'" x-model="ch.title"
                                placeholder="Judul bab" class="admin-input flex-1">
                            <input type="text" :name="'chapters['+idx+'][page_range]'" x-model="ch.page_range"
                                placeholder="1–18" class="admin-input w-24">
                            <button type="button" @click="selectedProduct.chapters.splice(idx,1)"
                                class="shrink-0 text-slate-400 hover:text-red-500"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="url" :name="'chapters['+idx+'][file_url]'" x-model="ch.file_url"
                                placeholder="Link PDF (https://...)" class="admin-input flex-1">
                            <label class="flex shrink-0 items-center gap-1.5 text-xs font-medium text-green-700">
                                <input type="checkbox" :name="'chapters['+idx+'][is_free]'" value="1" x-model="ch.is_free"
                                    class="h-4 w-4 rounded border-slate-300 text-green-600"> Gratis
                            </label>
                        </div>
                    </div>
                </template>
                <p x-show="!selectedProduct.chapters.length" class="text-xs text-slate-400">Belum ada bab.</p>
            </section>

            {{-- ============ KURIKULUM: KELAS/BOOTCAMP (sections + items) ============ --}}
            <section class="space-y-3 border-t border-slate-100 pt-5" x-show="isCurriculum" x-cloak>
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Kurikulum (Bagian & Materi)</h3>
                    <button type="button" @click="addSection()"
                        class="text-xs font-bold text-blue-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Bagian</button>
                </div>
                <template x-for="(sec, i) in selectedProduct.curriculum_sections" :key="i">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-3 space-y-3">
                        <div class="flex items-center gap-2">
                            <input type="text" :name="'curriculum_sections['+i+'][title]'" x-model="sec.title"
                                placeholder="Judul bagian (mis. Fondasi Business Case)" class="admin-input flex-1">
                            <input type="text" :name="'curriculum_sections['+i+'][subtitle]'" x-model="sec.subtitle"
                                placeholder="Sub (mis. Minggu 1)" class="admin-input w-40">
                            <button type="button" @click="selectedProduct.curriculum_sections.splice(i,1)"
                                class="shrink-0 text-slate-400 hover:text-red-500"><i class="fa-solid fa-trash"></i></button>
                        </div>

                        {{-- Items --}}
                        <template x-for="(it, j) in sec.items" :key="j">
                            <div class="flex flex-wrap items-center gap-2 rounded-lg border border-slate-150 bg-white p-2">
                                <input type="text" :name="'curriculum_sections['+i+'][items]['+j+'][title]'" x-model="it.title"
                                    placeholder="Judul materi" class="admin-input min-w-[10rem] flex-1">
                                <select :name="'curriculum_sections['+i+'][items]['+j+'][type]'" x-model="it.type"
                                    class="admin-input-select w-24">
                                    <option value="video">Video</option>
                                    <option value="pdf">PDF</option>
                                    <option value="live">Live</option>
                                </select>
                                <input type="text" :name="'curriculum_sections['+i+'][items]['+j+'][duration]'" x-model="it.duration"
                                    placeholder="12 mnt" class="admin-input w-20">
                                <input type="url" :name="'curriculum_sections['+i+'][items]['+j+'][content_url]'" x-model="it.content_url"
                                    placeholder="Link konten" class="admin-input min-w-[8rem] flex-1">
                                <label class="flex shrink-0 items-center gap-1.5 text-xs font-medium text-green-700">
                                    <input type="checkbox" :name="'curriculum_sections['+i+'][items]['+j+'][is_free]'" value="1"
                                        x-model="it.is_free" class="h-4 w-4 rounded border-slate-300 text-green-600"> Gratis
                                </label>
                                <button type="button" @click="sec.items.splice(j,1)"
                                    class="shrink-0 text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </template>
                        <button type="button" @click="addItem(sec)"
                            class="text-xs font-bold text-blue-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Materi</button>
                    </div>
                </template>
                <p x-show="!selectedProduct.curriculum_sections.length" class="text-xs text-slate-400">Belum ada bagian kurikulum.</p>
            </section>

            {{-- ============ BATCH (bootcamp) ============ --}}
            <section class="space-y-3 border-t border-slate-100 pt-5" x-show="selectedProduct.type === 'bootcamp'" x-cloak>
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal Batch</h3>
                    <button type="button" @click="addBatch()"
                        class="text-xs font-bold text-blue-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Batch</button>
                </div>
                <template x-for="(b, idx) in selectedProduct.batches" :key="idx">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="text" :name="'batches['+idx+'][label]'" x-model="b.label"
                            placeholder="Batch 5 — Mei 2026" class="admin-input min-w-[10rem] flex-1">
                        <input type="text" :name="'batches['+idx+'][date_range]'" x-model="b.date_range"
                            placeholder="5 Mei – 2 Jun 2026" class="admin-input min-w-[10rem] flex-1">
                        <input type="number" min="0" :name="'batches['+idx+'][spots]'" x-model="b.spots"
                            placeholder="20" class="admin-input w-20">
                        <select :name="'batches['+idx+'][status]'" x-model="b.status" class="admin-input-select w-28">
                            <option value="open">Dibuka</option>
                            <option value="soon">Segera</option>
                            <option value="closed">Ditutup</option>
                        </select>
                        <button type="button" @click="selectedProduct.batches.splice(idx,1)"
                            class="shrink-0 text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </template>
                <p x-show="!selectedProduct.batches.length" class="text-xs text-slate-400">Belum ada batch.</p>
            </section>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" form="prod-form" icon="fa-floppy-disk">Simpan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
</div>
@endsection
