<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Token Snap Midtrans (untuk SDK / embed) dan URL halaman bayar (redirect_url).
            // Diisi saat checkout; webhook mengisi midtrans_status mentah dari Midtrans.
            $table->string('snap_token')->nullable()->after('payment_proof');
            $table->string('payment_url')->nullable()->after('snap_token');
            $table->string('midtrans_status')->nullable()->after('payment_url');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_url', 'midtrans_status']);
        });
    }
};
