@extends('layouts.dashboard')

@section('title', 'Riwayat Pembelian')
@section('page-title', 'Riwayat Pembelian')
@section('page-subtitle', \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y'))

@section('content')
    <div class="rounded-2xl border border-slate-100 bg-white">
        <x-empty-state icon="fa-receipt" title="Belum Ada Transaksi"
            description="Riwayat transaksi kamu akan muncul di sini setelah pembelian pertama. Mulai jelajahi produk untuk membuka akses materi premium kami." />
    </div>
@endsection
