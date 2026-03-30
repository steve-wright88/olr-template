<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('number_of_birds');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('bonus_birds')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_offers');
    }
};
