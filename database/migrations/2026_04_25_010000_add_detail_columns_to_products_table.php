<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->json('learnings')->nullable()->after('description');
            $table->json('includes')->nullable()->after('learnings');
            $table->unsignedInteger('win_rate')->nullable()->after('rating');
            $table->unsignedInteger('total_pages')->nullable()->after('win_rate');
            $table->foreignId('author_id')
                ->nullable()
                ->after('image_url')
                ->constrained('mentors')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn([
                'description',
                'learnings',
                'includes',
                'win_rate',
                'total_pages',
                'author_id',
            ]);
        });
    }
};
