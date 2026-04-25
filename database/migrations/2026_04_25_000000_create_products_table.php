<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['modul', 'kelas', 'bootcamp']);
            $table->string('title');
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('students')->default(0);
            $table->unsignedInteger('price');
            $table->unsignedInteger('original_price')->nullable();
            $table->string('duration')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
