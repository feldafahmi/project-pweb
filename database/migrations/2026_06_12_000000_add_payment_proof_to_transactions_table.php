<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Path relatif bukti bayar yang diunggah user (mis. /uploads/payment_proofs/x.jpg).
            // Diisi user setelah bayar QRIS; admin memverifikasi manual lalu set status=paid.
            $table->string('payment_proof')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }
};
