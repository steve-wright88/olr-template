<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prize_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'positions' or 'award'
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prize_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prize_category_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // e.g. "1st", "6th-10th"
            $table->string('amount'); // e.g. "TBC", "£5,000"
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prize_positions');
        Schema::dropIfExists('prize_categories');
    }
};
