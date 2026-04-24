<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', [
                'Business Case',
                'Business Plan',
                'Business Model Canvas',
                'UI/UX',
                'LKTI',
            ]);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('target_audience', ['Mahasiswa', 'Umum']);
            $table->unsignedBigInteger('registration_fee')->default(0);
            $table->unsignedBigInteger('total_prize')->default(0);
            $table->string('organizer');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
