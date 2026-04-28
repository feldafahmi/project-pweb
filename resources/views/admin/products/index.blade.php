@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@php
    $products = [
        ['id' => 1, 'image' => 'img/63815.png', 'name' => 'Bundle: Winner Class Business Case', 'type' => 'Bundling', 'original_price' => 299000, 'discount_price' => 199000],
        ['id' => 2, 'image' => 'img/63815.png', 'name' => 'Live Bootcamp: Strategic Consulting', 'type' => 'Bootcamp', 'original_price' => 499000, 'discount_price' => 399000],
        ['id' => 3, 'image' => 'img/63815.png', 'name' => 'Module: Fundamental Business Case', 'type' => 'Module', 'original_price' => 99000, 'discount_price' => 79000],
        ['id' => 4, 'image' => 'img/63815.png', 'name' => 'Kelas: Public Speaking untuk Juara', 'type' => 'Kelas', 'original_price' => 149000, 'discount_price' => 119000],
    ];

    $typeTones = [
        'Kelas' => 'blue',
        'Bootcamp' => 'orange',
        'Module' => 'purple',
        'Bundling' => 'green',
    ];

    $productTypes = ['Kelas', 'Bootcamp', 'Module', 'Bundling'];

    $idr = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');
@endphp

@section('content')
    {{-- Page header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Manajemen Produk</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola katalog produk pembelajaran MARK-UP.</p>
        </div>
        <x-admin.button variant="primary" icon="fa-plus"
            x-on:click="$dispatch('open-modal', { name: 'product-form' })">
            Tambah Produk
        </x-admin.button>
    </div>

    {{-- Table --}}
    <x-admin.table :columns="['Gambar', 'Nama Produk', 'Tipe', 'Harga Asli', 'Harga Diskon', 'Aksi']">
        @foreach ($products as $product)
            <tr class="hover:bg-slate-50/60">
                <td class="px-6 py-4">
                    <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}"
                        class="h-12 w-12 rounded-lg object-cover ring-1 ring-slate-200">
                </td>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-800">{{ $product['name'] }}</p>
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge :tone="$typeTones[$product['type']] ?? 'slate'">
                        {{ $product['type'] }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4 text-slate-400 line-through">{{ $idr($product['original_price']) }}</td>
                <td class="px-6 py-4 font-bold text-blue-600">{{ $idr($product['discount_price']) }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <x-admin.button variant="ghost-blue" size="icon" icon="fa-pen"
                            x-on:click="$dispatch('open-modal', { name: 'product-form' })" />
                        <x-admin.button variant="ghost-danger" size="icon" icon="fa-trash" />
                    </div>
                </td>
            </tr>
        @endforeach
    </x-admin.table>

    {{-- Modal: Tambah/Edit Produk --}}
    <x-admin.modal name="product-form" title="Tambah / Edit Produk" size="lg">
        <form class="space-y-5">
            @csrf

            <x-admin.field label="Nama Produk" name="name" :required="true">
                <input type="text" id="name" name="name" placeholder="Contoh: Bundle Winner Class"
                    class="admin-input">
            </x-admin.field>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Tipe" name="type" :required="true">
                    <select id="type" name="type" class="admin-input-select">
                        <option value="" disabled selected>Pilih tipe produk</option>
                        @foreach ($productTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Upload Gambar" name="image" hint="Format JPG/PNG, maks 2 MB.">
                    <input type="file" id="image" name="image" accept="image/*"
                        class="admin-input file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-blue-600 hover:file:bg-blue-100">
                </x-admin.field>
            </div>

            <x-admin.field label="Deskripsi" name="description">
                <textarea id="description" name="description" rows="4"
                    placeholder="Jelaskan manfaat & isi produk secara singkat..."
                    class="admin-input"></textarea>
            </x-admin.field>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-admin.field label="Harga Asli" name="original_price" :required="true">
                    <input type="number" id="original_price" name="original_price" min="0" placeholder="299000"
                        class="admin-input">
                </x-admin.field>

                <x-admin.field label="Harga Diskon" name="discount_price">
                    <input type="number" id="discount_price" name="discount_price" min="0" placeholder="199000"
                        class="admin-input">
                </x-admin.field>
            </div>
        </form>

        <x-slot:footer>
            <x-admin.button variant="outline" x-on:click="open = false">Batal</x-admin.button>
            <x-admin.button variant="primary" type="submit" icon="fa-floppy-disk">Simpan</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
@endsection
