<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lofts', function (Blueprint $t) {
            $t->unsignedBigInteger('id')->primary();
            $t->string('name');
            $t->string('avatar')->nullable();
            $t->string('country', 5)->nullable();
            $t->string('city')->nullable();
            $t->string('operator')->nullable();
            $t->string('homepage')->nullable();
            $t->timestamp('synced_at')->nullable();
            $t->timestamps();
        });

        Schema::create('seasons', function (Blueprint $t) {
            $t->unsignedBigInteger('id')->primary();
            $t->unsignedBigInteger('loft_id');
            $t->string('name');
            $t->boolean('is_active')->default(false);
            $t->boolean('completed')->default(false);
            $t->unsignedInteger('pigeon_count')->default(0);
            $t->unsignedInteger('team_count')->default(0);
            $t->unsignedInteger('distance')->nullable();
            $t->decimal('pricepool', 12, 2)->nullable();
            $t->string('currency', 10)->nullable();
            $t->timestamp('synced_at')->nullable();
            $t->timestamps();
            $t->foreign('loft_id')->references('id')->on('lofts')->cascadeOnDelete();
        });

        Schema::create('teams', function (Blueprint $t) {
            $t->unsignedBigInteger('id')->primary();
            $t->unsignedBigInteger('season_id');
            $t->string('name');
            $t->string('country', 5)->nullable();
            $t->timestamps();
            $t->foreign('season_id')->references('id')->on('seasons')->cascadeOnDelete();
        });

        Schema::create('flights', function (Blueprint $t) {
            $t->unsignedBigInteger('id')->primary();
            $t->unsignedBigInteger('season_id');
            $t->string('name');
            $t->string('flight_type')->nullable();
            $t->unsignedInteger('distance')->nullable();
            $t->timestamp('release_time')->nullable();
            $t->string('release_time_local')->nullable();
            $t->unsignedInteger('arrivals_count')->default(0);
            $t->unsignedInteger('basketings_count')->default(0);
            $t->decimal('average_speed', 10, 4)->nullable();
            $t->string('status')->default('stopped');
            $t->timestamp('synced_at')->nullable();
            $t->timestamps();
            $t->foreign('season_id')->references('id')->on('seasons')->cascadeOnDelete();
        });

        Schema::create('pigeons', function (Blueprint $t) {
            $t->unsignedBigInteger('id')->primary();
            $t->unsignedBigInteger('season_id');
            $t->unsignedBigInteger('team_id')->nullable();
            $t->string('ring_number')->nullable()->index();
            $t->string('name')->nullable();
            $t->string('color')->nullable();
            $t->string('sex', 10)->nullable();
            $t->timestamps();
            $t->foreign('season_id')->references('id')->on('seasons')->cascadeOnDelete();
            $t->foreign('team_id')->references('id')->on('teams')->nullOnDelete();
        });

        Schema::create('results', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('flight_id');
            $t->unsignedBigInteger('pigeon_id');
            $t->unsignedInteger('arrival_order')->nullable();
            $t->decimal('speed', 10, 4)->nullable();
            $t->string('arrival_time')->nullable();
            $t->timestamps();
            $t->unique(['flight_id', 'pigeon_id']);
            $t->index('pigeon_id');
            $t->foreign('flight_id')->references('id')->on('flights')->cascadeOnDelete();
            $t->foreign('pigeon_id')->references('id')->on('pigeons')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
        Schema::dropIfExists('pigeons');
        Schema::dropIfExists('flights');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('seasons');
        Schema::dropIfExists('lofts');
    }
};
