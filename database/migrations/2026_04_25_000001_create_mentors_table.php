<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('sessions')->default(0);
            $table->boolean('available')->default(true);
            $table->unsignedInteger('price_per_session');
            $table->json('tags')->nullable();
            $table->string('avatar_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentors');
    }
};
