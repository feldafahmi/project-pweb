<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah opsi 'midtrans' agar checkout (web & mobile) lewat Snap bisa
        // mencatat metode pembayaran dengan jujur, bukan dipaksa ke nilai lain.
        DB::statement(
            "ALTER TABLE transactions MODIFY payment_method "
            . "ENUM('transfer','e_wallet','qris','cod','midtrans') NOT NULL"
        );
    }

    public function down(): void
    {
        DB::statement(
            "ALTER TABLE transactions MODIFY payment_method "
            . "ENUM('transfer','e_wallet','qris','cod') NOT NULL"
        );
    }
};
