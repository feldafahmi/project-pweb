@extends('layouts.dashboard') {{-- Sesuaikan dengan nama layout dashboard kamu --}}

@section('title', 'Keranjang')
@section('page-title', 'Keranjang Belanja')
@section('page-subtitle', 'Selesaikan pembelian program pilihanmu sebelum kehabisan kuota.')

@section('content')
    <div class="flex flex-col gap-8 lg:flex-row">

        {{-- BAGIAN KIRI: Daftar Item Keranjang --}}
        <div class="w-full lg:w-2/3">
            <div id="cart-items-container" class="space-y-4">
                <div id="empty-cart-msg"
                    class="hidden rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                    <i class="fa-solid fa-cart-shopping mb-4 text-4xl text-slate-300"></i>
                    <h3 class="mb-2 text-lg font-bold text-navy-600">Keranjang masih kosong</h3>
                    <p class="mb-6 text-sm text-slate-500">Kamu belum memasukkan program apapun ke keranjang.</p>
                    <a href="/produk"
                        class="rounded-xl bg-[#A855F7] px-6 py-2.5 font-bold text-white transition hover:bg-purple-600">
                        Eksplor Produk
                    </a>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: Ringkasan Belanja --}}
        <div class="w-full lg:w-1/3">
            <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-bold text-navy-600">Ringkasan Belanja</h3>

                <div class="mb-4 space-y-3 border-b border-slate-100 pb-4 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Total Harga (<span id="summary-count">0</span> item)</span>
                        <span id="summary-price" class="font-bold text-slate-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diskon</span>
                        <span class="font-bold text-green-500">- Rp 0</span>
                    </div>
                </div>

                <div class="mb-6 flex items-center justify-between">
                    <span class="font-bold text-navy-600">Total Tagihan</span>
                    <span id="summary-total" class="text-2xl font-extrabold text-[#A855F7]">Rp 0</span>
                </div>

                <button id="btn-checkout"
                    class="w-full rounded-xl bg-[#A855F7] py-3.5 font-bold text-white shadow-lg shadow-purple-200 transition hover:bg-purple-600 disabled:cursor-not-allowed disabled:opacity-50">
                    Beli Sekarang
                </button>
            </div>
        </div>

    </div>

    <script>
        // LOGIKA RENDER KERANJANG DARI LOCAL STORAGE
        const cartContainer = document.getElementById('cart-items-container');
        const emptyMsg = document.getElementById('empty-cart-msg');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        function loadCart() {
            // Ambil data dari local storage (jika kosong, array [])
            let cart = JSON.parse(localStorage.getItem('markup_cart')) || [];

            // Reset kontainer
            cartContainer.innerHTML = '';

            if (cart.length === 0) {
                cartContainer.appendChild(emptyMsg);
                emptyMsg.classList.remove('hidden');
                document.getElementById('btn-checkout').disabled = true;
                updateSummary(0, 0);
                return;
            }

            emptyMsg.classList.add('hidden');
            document.getElementById('btn-checkout').disabled = false;

            let totalPrice = 0;

            // Render setiap item di keranjang
            cart.forEach((item, index) => {
                totalPrice += item.price;

                const itemHTML = `
                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                    <img src="${item.image}" alt="${item.title}" class="h-24 w-full rounded-xl object-cover sm:w-32">
                    <div class="flex-grow">
                        <span class="mb-1 inline-block rounded bg-purple-50 px-2 py-0.5 text-[10px] font-bold uppercase text-purple-600">Program</span>
                        <h4 class="mb-1 font-bold text-navy-600 line-clamp-2">${item.title}</h4>
                        <p class="font-extrabold text-[#A855F7]">${formatRupiah(item.price)}</p>
                    </div>
                    <button onclick="removeFromCart(${index})" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;
                cartContainer.insertAdjacentHTML('beforeend', itemHTML);
            });

            updateSummary(cart.length, totalPrice);
        }

        function updateSummary(count, total) {
            document.getElementById('summary-count').innerText = count;
            document.getElementById('summary-price').innerText = formatRupiah(total);
            document.getElementById('summary-total').innerText = formatRupiah(total);
        }

        function removeFromCart(index) {
            let cart = JSON.parse(localStorage.getItem('markup_cart')) || [];
            cart.splice(index, 1); // Hapus item berdasarkan index
            localStorage.setItem('markup_cart', JSON.stringify(cart));
            loadCart(); // Render ulang
        }

        // Jalankan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
@endsection
