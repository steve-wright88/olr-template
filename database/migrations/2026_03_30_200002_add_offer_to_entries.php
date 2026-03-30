<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->unsignedBigInteger('offer_id')->nullable()->after('season_year');
            $table->decimal('total_fee', 10, 2)->nullable()->after('offer_id');

            $table->foreign('offer_id')
                ->references('id')
                ->on('entry_offers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->dropColumn(['offer_id', 'total_fee']);
        });
    }
};
