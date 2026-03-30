<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('syndicate_name')->nullable();
            $table->string('flyer_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('team_name')->nullable();
            $table->unsignedInteger('number_of_birds')->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->string('season_year');
            $table->timestamps();
        });

        Schema::create('entry_birds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained()->cascadeOnDelete();
            $table->string('ring_number');
            $table->string('pigeon_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_birds');
        Schema::dropIfExists('entries');
    }
};
