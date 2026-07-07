@extends('layouts.dashboard')

@section('title', 'Riwayat Pembelian')
@section('page-title', 'Riwayat Pembelian')
@section('page-subtitle', \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y'))

@php
    $badge = [
        'paid'      => ['Lunas', 'bg-green-50 text-green-600'],
        'pending'   => ['Menunggu Pembayaran', 'bg-amber-50 text-amber-600'],
        'failed'    => ['Gagal', 'bg-red-50 text-red-600'],
        'cancelled' => ['Dibatalkan', 'bg-slate-100 text-slate-500'],
    ];
@endphp

@section('content')
    @if ($transactions->isEmpty())
        <div class="rounded-2xl border border-slate-100 bg-white">
            <x-empty-state icon="fa-receipt" title="Belum Ada Transaksi"
                description="Riwayat transaksi kamu akan muncul di sini setelah pembelian pertama. Mulai jelajahi produk untuk membuka akses materi premium kami." />
        </div>
    @else
        <div class="space-y-4">
            @foreach ($transactions as $trx)
                @php [$label, $cls] = $badge[$trx->status] ?? [$trx->status, 'bg-slate-100 text-slate-500']; @endphp
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <p class="font-bold text-navy-600">{{ $trx->code }}</p>
                            <p class="text-xs text-slate-400">{{ $trx->created_at->isoFormat('D MMM Y • HH:mm') }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $cls }}">{{ $label }}</span>
                    </div>

                    <div class="space-y-2 py-3">
                        @foreach ($trx->items as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">{{ $item->product_title }} <span class="text-slate-400">× {{ $item->quantity }}</span></span>
                                <span class="font-semibold text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-sm font-bold text-navy-600">Total</span>
                        <span class="text-lg font-extrabold text-[#A855F7]">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                    </div>

                    @if ($trx->status === 'pending')
                        <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                            @if ($trx->snap_token)
                                <button onclick="payAgain('{{ $trx->snap_token }}', {{ $trx->id }})"
                                    class="flex-1 rounded-xl bg-[#A855F7] py-2.5 text-sm font-bold text-white transition hover:bg-purple-600 active:scale-95">
                                    <i class="fa-solid fa-wallet mr-1"></i> Lanjutkan Pembayaran
                                </button>
                            @endif
                            <button onclick="checkStatus({{ $trx->id }}, this)"
                                class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-bold text-navy-600 transition hover:bg-slate-50 active:scale-95">
                                <i class="fa-solid fa-rotate mr-1"></i> Cek Status Pembayaran
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif

        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            // Tarik status otoritatif dari Midtrans lewat server (webhook tak bisa
            // menjangkau localhost), lalu muat ulang agar badge status update.
            function syncThenReload(id, btn) {
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengecek...';
                }
                fetch(`/dashboard/transactions/${id}/sync`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).finally(() => location.reload());
            }

            function payAgain(token, id) {
                snap.pay(token, {
                    onSuccess: () => syncThenReload(id),
                    onPending: () => syncThenReload(id),
                    onError: (e) => alert('Pembayaran gagal: ' + (e?.status_message || 'coba lagi.')),
                });
            }

            function checkStatus(id, btn) {
                syncThenReload(id, btn);
            }
        </script>
    @endif
@endsection
