<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pool_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('pool_type'); // hotspot or race
            $table->string('syndicate_name');
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->string('race_point')->nullable();
            $table->date('race_date')->nullable();
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('season_year');
            $table->timestamps();
        });

        Schema::create('pool_entry_birds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_entry_id')->constrained()->cascadeOnDelete();
            $table->string('ring_number');
            $table->json('stakes')->nullable(); // {label: amount} pairs e.g. {"50p": true, "£1": true}
            $table->decimal('bird_total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pool_entry_birds');
        Schema::dropIfExists('pool_entries');
    }
};
