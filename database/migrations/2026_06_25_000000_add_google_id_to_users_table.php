<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Identitas Google (claim `sub`) — unik & nullable agar user
            // email/password lama tetap valid tanpa google_id.
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('avatar')->nullable()->after('google_id');

            // User yang mendaftar lewat Google tidak punya password lokal.
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar']);
            // Catatan: kolom password dikembalikan menjadi NOT NULL. Bila ada
            // baris dengan password NULL (user Google), rollback akan gagal —
            // bersihkan dulu sebelum down() di lingkungan tsb.
            $table->string('password')->nullable(false)->change();
        });
    }
};
