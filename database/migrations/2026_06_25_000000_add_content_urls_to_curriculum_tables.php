<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan URL konten asli ke baris yang benar-benar dikonsumsi user
 * setelah membeli produk:
 *
 * - curriculum_items (kelas/bootcamp) : content_url (link video/pdf/live) + is_free (preview)
 * - product_chapters (modul)          : file_url   (link PDF chapter); is_free sudah ada
 *
 * URL ini disembunyikan dari endpoint publik (lihat $hidden di model) dan hanya
 * dikeluarkan lewat GET /products/{id}/content untuk user yang sudah membayar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('curriculum_items', function (Blueprint $table) {
            $table->string('content_url', 1000)->nullable()->after('type');
            $table->boolean('is_free')->default(false)->after('content_url');
        });

        Schema::table('product_chapters', function (Blueprint $table) {
            $table->string('file_url', 1000)->nullable()->after('page_range');
        });
    }

    public function down(): void
    {
        Schema::table('curriculum_items', function (Blueprint $table) {
            $table->dropColumn(['content_url', 'is_free']);
        });

        Schema::table('product_chapters', function (Blueprint $table) {
            $table->dropColumn('file_url');
        });
    }
};
