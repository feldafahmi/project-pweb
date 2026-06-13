@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@php
    $typeTones = [
        'kelas' => 'blue',
        'bootcamp' => 'orange',
        'modul' => 'purple',
    ];

    $productTypes = ['kelas', 'bootcamp', 'modul'];

    $idr = fn ($v) => $v ? 'Rp ' . number_format($v, 0, ',', '.') : '-';
@endphp

@section('content')
<div x-data="{
    selectedProduct: { id: '', title: '', type: '', description: '', original_price: '', price: '' },
    isEdit: false,
    openCreateModal() {
        this.isEdit = false;
        this.selectedProduct = { id: '', title: '', type: '', description: '', original_price: '', price: '' };
        $dispatch('open-modal', { name: 'product-form' });
    },
    openEditModal(prod) {
        this.isEdit = true;
        this.selectedProduct = { ...prod };
        $dispatch('open-modal', { name: 'product-form' });
    }
}">
    {{-- Page header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Produk</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola katalog produk pembelajaran MARK-UP.</p>
        </div>
        <x-admin.button variant="primary" icon="fa-plus"
            x-on:click="openCreateModal()">
            Tambah Produk
        </x-admin.button>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <x-admin.table :columns="['Gambar', 'Nama Produk', 'Tipe', 'Harga Asli', 'Harga Diskon', 'Aksi']">
        @foreach ($products as $product)
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
                <td class="px-6 py-4 text-slate-400 line-through">{{ $idr($product->original_price) }}</td>
                <td class="px-6 py-4 font-bold text-blue-600">{{ $idr($product->price) }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <x-admin.button variant="ghost-blue" size="icon" icon="fa-pen"
                            x-on:click="openEditModal({
                                id: {{ $product->id }},
                                title: '{{ addslashes($product->title) }}',
                                type: '{{ $product->type }}',
                                description: '{{ addslashes($product->description) }}',
                                original_price: {{ $product->original_price ?? 0 }},
                                price: {{ $product->price ?? 0 }}
                            })" />
                        <form action="{{ (request()->is('admin/*') ? '/admin' : '/mentor') }}/products/{{ $product->id }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
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
    <x-admin.modal name="product-form" ::title="isEdit ? 'Edit Produk' : 'Tambah Produk'" size="lg">
        <form id="prod-form" method="POST" :action="isEdit ? '{{ request()->is('admin/*') ? '/admin' : '/mentor' }}/products/' + selectedProduct.id : '{{ request()->is('admin/*') ? '/admin' : '/mentor' }}/products'" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <template x-if="isEdit">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <x-admin.field label="Nama Produk" name="title" :required="true">
                <input type="text" id="title" name="title" x-model="selectedProduct.title" placeholder="Contoh: Bundle Winner Class"
                    class="admin-input" required>
            </x-admin.field>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Tipe" name="type" :required="true">
                    <select id="type" name="type" x-model="selectedProduct.type" class="admin-input-select" required>
                        <option value="" disabled selected>Pilih tipe produk</option>
                        @foreach ($productTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Upload Gambar" name="image" hint="Format JPG/PNG, maks 2 MB.">
                    <input type="file" id="image" name="image" accept="image/*"
                        class="admin-input file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-blue-600 hover:file:bg-blue-100">
                </x-admin.field>
            </div>

            <x-admin.field label="Deskripsi" name="description">
                <textarea id="description" name="description" x-model="selectedProduct.description" rows="4"
                    placeholder="Jelaskan manfaat & isi produk secara singkat..."
                    class="admin-input"></textarea>
            </x-admin.field>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Harga Asli" name="original_price" :required="true">
                    <input type="number" id="original_price" name="original_price" x-model="selectedProduct.original_price" min="0" placeholder="299000"
                        class="admin-input" required>
                </x-admin.field>

                <x-admin.field label="Harga Diskon" name="price" :required="true">
                    <input type="number" id="price" name="price" x-model="selectedProduct.price" min="0" placeholder="199000"
                        class="admin-input" required>
                </x-admin.field>
            </div>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" form="prod-form" icon="fa-floppy-disk">Simpan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
</div>
@endsection
