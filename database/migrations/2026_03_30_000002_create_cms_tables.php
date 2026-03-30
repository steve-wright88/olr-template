<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role')->default('user')->after('email');
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->longText('body')->nullable();
            $t->string('featured_image')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->longText('body')->nullable();
            $t->string('excerpt')->nullable();
            $t->string('featured_image')->nullable();
            $t->string('post_type')->default('news'); // news, update, livestream
            $t->string('livestream_url')->nullable();
            $t->boolean('is_pinned')->default(false);
            $t->boolean('is_published')->default(true);
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('pages');
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn('role');
        });
    }
};
