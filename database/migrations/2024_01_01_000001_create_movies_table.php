<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->string('title');
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->date('release_date')->nullable();
            $table->decimal('vote_average', 3, 1)->default(0);
            $table->unsignedInteger('vote_count')->default(0);
            $table->decimal('popularity', 8, 3)->default(0);
            $table->json('genre_ids')->nullable();
            $table->string('original_language', 10)->default('en');
            $table->string('original_title');
            $table->boolean('adult')->default(false);
            $table->boolean('video')->default(false);
            $table->timestamps();

            $table->index(['tmdb_id']);
            $table->index(['title']);
            $table->index(['popularity']);
            $table->index(['release_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
}; 