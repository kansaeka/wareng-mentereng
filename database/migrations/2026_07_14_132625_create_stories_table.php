<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();

            $table->string('title', 150);
            $table->string('slug', 170)->unique();

            $table->text('summary')->nullable();

            /*
             * Path file gambar sampul.
             * File fisik disimpan di storage public.
             */
            $table->string('cover_image_path')
                ->nullable();

            $table->boolean('is_published')
                ->default(false);

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'is_published',
                'published_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
