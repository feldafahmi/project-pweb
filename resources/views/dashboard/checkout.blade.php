@extends('layouts.dashboard')

@section('title', 'Checkout')
@section('page-title', 'Selesaikan Pembayaran')
@section('page-subtitle', 'Satu langkah lagi untuk memulai perjalanan kompetisimu bersama Mark-Up.')

@section('content')
    <div class="flex flex-col gap-8 lg:flex-row">

        {{-- KIRI: Ringkasan Item & Input Voucher --}}
        <div class="w-full lg:w-2/3 space-y-6">

            {{-- List Item --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-bold text-navy-600 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-box mr-2 text-purple-500"></i>Item yang akan dibeli
                </h3>
                <div id="checkout-items-list" class="space-y-4">
                </div>
            </div>

            {{-- Input Voucher --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-2 font-bold text-navy-600">
                    <i class="fa-solid fa-ticket mr-2 text-purple-500"></i>Punya Kode Voucher?
                </h3>
                <p class="text-xs text-slate-400 mb-3">Gunakan kode voucher promo Mark-Up untuk mendapatkan potongan harga
                    spesial.</p>
                <div class="flex gap-2">
                    <input type="text" id="voucher-code" placeholder="Contoh: MARKUPJUARA, MENTORINGMHS"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm uppercase focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                    <button onclick="applyVoucher()"
                        class="rounded-xl bg-[#A855F7] px-6 py-2.5 text-sm font-bold text-white transition hover:bg-purple-600">
                        Terapkan
                    </button>
                </div>
                <p id="voucher-message" class="mt-2 text-xs hidden"></p>
            </div>
        </div>

        {{-- KANAN: KALKULATOR KASIR (Billing Summary) --}}
        <div class="w-full lg:w-1/3">
            <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-bold text-navy-600 border-b border-slate-100 pb-2">Detail Pembayaran</h3>

                <div class="space-y-3 border-b border-slate-100 pb-4 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Subtotal Produk</span>
                        <span id="calc-subtotal" class="font-bold text-slate-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Diskon Voucher</span>
                        <span id="calc-discount">- Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Layanan</span>
                        <span id="calc-service" class="font-bold text-slate-800">Rp 0</span>
                    </div>
                </div>

                <div class="my-6 flex items-center justify-between">
                    <span class="font-bold text-navy-600">Total Tagihan</span>
                    <span id="calc-total" class="text-2xl font-extrabold text-[#A855F7]">Rp 0</span>
                </div>

                <button onclick="processPayment()"
                    class="w-full rounded-xl bg-[#A855F7] py-3.5 font-bold text-white shadow-lg shadow-purple-200 transition hover:bg-purple-600 active:scale-95">
                    Bayar Sekarang
                </button>
            </div>
        </div>

    </div>

    <script>
        // Database dummy voucher untuk keperluan logika praktikum laporan
        const AVAILABLE_VOUCHERS = {
            'MARKUPJUARA': 0.20, // Diskon 20%
            'MENTORINGMHS': 0.50 // Diskon 50%
        };

        const SERVICE_FEE = 5000; // Biaya layanan tetap Rp 5.000
        let discountPercent = 0;
        let subtotal = 0;

        // ID produk yang sudah dimiliki user (transaksi paid). Item ini dibuang
        // dari keranjang agar tidak ikut ditagih (server juga menolaknya).
        const OWNED_IDS = @json($ownedIds);

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        function initCheckout() {
            let cart = JSON.parse(localStorage.getItem('markup_cart')) || [];
            const container = document.getElementById('checkout-items-list');

            // Buang produk yang sudah dibeli (mis. dibayar dari transaksi lain)
            // agar tampilan & subtotal konsisten dengan yang benar-benar ditagih.
            const cleaned = cart.filter(item => !OWNED_IDS.includes(item.id));
            if (cleaned.length !== cart.length) {
                cart = cleaned;
                localStorage.setItem('markup_cart', JSON.stringify(cart));
                window.markupUpdateCartBadge?.();
            }

            if (cart.length === 0) {
                alert('Keranjang belanja kosong! Kamu akan dialihkan ke halaman produk.');
                window.location.href = '/produk';
                return;
            }

            // TUGAS LAPORAN 3: Manipulasi Data dengan Array Method (.reduce)
            subtotal = cart.reduce((total, item) => total + item.price, 0);

            // TUGAS LAPORAN 3: Manipulasi Data dengan Array Method (.map)
            container.innerHTML = cart.map(item => `
            <div class="flex items-center gap-4 rounded-xl border border-slate-50 bg-slate-50/50 p-3">
                <img src="${item.image}" class="h-14 w-20 rounded-lg object-cover bg-slate-200 shadow-sm">
                <div class="flex-grow min-w-0">
                    <h4 class="text-sm font-bold text-navy-600 truncate">${item.title}</h4>
                    <p class="text-xs text-purple-600 font-semibold mt-0.5">${formatRupiah(item.price)}</p>
                </div>
            </div>
        `).join('');

            calculateBilling();
        }

        // TUGAS LAPORAN 1: Logika Utama Perhitungan Kalkulator Aritmatika
        function calculateBilling() {
            // Operasi Perkalian untuk diskon
            const discountAmount = subtotal * discountPercent;

            // Operasi Pengurangan & Penjumlahan aritmatika kasir
            const totalFinal = (subtotal - discountAmount) + SERVICE_FEE;

            // Injeksi data hasil kalkulator ke dokumen web (DOM Manipulation)
            document.getElementById('calc-subtotal').innerText = formatRupiah(subtotal);
            document.getElementById('calc-discount').innerText = `- ${formatRupiah(discountAmount)}`;
            document.getElementById('calc-service').innerText = formatRupiah(SERVICE_FEE);
            document.getElementById('calc-total').innerText = formatRupiah(totalFinal);
        }

        let voucherCode = ''; // kode yang dipasang user (divalidasi ulang di server)

        // Estimasi tampilan voucher. Diskon SEBENARNYA dihitung server saat bayar.
        function applyVoucher() {
            const inputCode = document.getElementById('voucher-code').value.toUpperCase().trim();
            const messageBox = document.getElementById('voucher-message');

            messageBox.classList.remove('hidden', 'text-green-600', 'text-red-500');

            if (AVAILABLE_VOUCHERS[inputCode] !== undefined) {
                discountPercent = AVAILABLE_VOUCHERS[inputCode];
                voucherCode = inputCode;
                messageBox.innerText = `Berhasil! Voucher dipasang, kamu hemat ${discountPercent * 100}%`;
                messageBox.classList.add('text-green-600');
            } else {
                discountPercent = 0;
                voucherCode = '';
                messageBox.innerText = `Kode voucher salah atau tidak valid.`;
                messageBox.classList.add('text-red-500');
            }

            messageBox.classList.remove('hidden');
            calculateBilling(); // Hitung ulang kasir setelah voucher berubah
        }

        function processPayment() {
            const cart = JSON.parse(localStorage.getItem('markup_cart')) || [];
            if (cart.length === 0) {
                alert('Keranjang belanja kosong!');
                window.location.href = '/produk';
                return;
            }

            const btn = document.querySelector('button[onclick="processPayment()"]');
            if (btn) { btn.disabled = true; btn.innerText = 'Memproses...'; }

            const productIds = cart.map(item => item.id);

            fetch('{{ route('dashboard.checkout.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_ids: productIds, voucher_code: voucherCode })
            })
            .then(async (response) => ({ ok: response.ok, data: await response.json() }))
            .then(({ ok, data }) => {
                if (!ok || !data.success) {
                    throw new Error(data.message || 'Gagal memproses pembayaran.');
                }
                const trxId = data.transaction_id;
                // Buka popup pembayaran Midtrans Snap.
                snap.pay(data.snap_token, {
                    onSuccess: () => { afterPaid(trxId); },
                    onPending: () => { afterPaid(trxId); },
                    onError: () => { alert('Pembayaran gagal. Silakan coba lagi.'); resetButton(btn); },
                    onClose: () => {
                        // User menutup popup tanpa menyelesaikan — transaksi sudah
                        // dibuat (pending), bisa dilanjutkan dari Riwayat Pembelian.
                        resetButton(btn);
                        window.location.href = '{{ route('dashboard.transactions') }}';
                    }
                });
            })
            .catch((err) => {
                alert('Terjadi kesalahan: ' + err.message);
                resetButton(btn);
            });
        }

        async function afterPaid(trxId) {
            localStorage.removeItem('markup_cart'); // kosongkan keranjang

            // Webhook Midtrans tidak bisa menjangkau localhost, jadi tarik status
            // langsung dari server (yang memanggil Status API Midtrans) agar
            // dashboard langsung ter-update jadi "Lunas".
            if (trxId) {
                try {
                    await fetch(`/dashboard/transactions/${trxId}/sync`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                } catch (e) {
                    /* diabaikan — user tetap diarahkan; bisa "Cek Status" manual */
                }
            }

            window.location.href = '{{ route('dashboard.transactions') }}';
        }

        function resetButton(btn) {
            if (btn) { btn.disabled = false; btn.innerText = 'Bayar Sekarang'; }
        }

        // Eksekusi kalkulator billing kasir pertama kali saat dokumen dimuat
        document.addEventListener('DOMContentLoaded', initCheckout);
    </script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endsection
