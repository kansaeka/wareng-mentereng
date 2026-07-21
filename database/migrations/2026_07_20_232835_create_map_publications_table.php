<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('map_publications', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table
                ->string('collection_group')
                ->default('Peta Dasar');

            $table->string('category');

            $table
                ->unsignedSmallInteger('year')
                ->nullable();

            $table
                ->text('description')
                ->nullable();

            $table
                ->text('keywords')
                ->nullable();

            $table
                ->string('thumbnail_path')
                ->nullable();

            $table
                ->string('file_path')
                ->nullable();

            $table
                ->string('format', 20)
                ->default('PDF');

            $table
                ->string('status', 50)
                ->default('Dalam penyusunan');

            $table
                ->boolean('is_published')
                ->default(true);

            $table
                ->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->index([
                'collection_group',
                'category',
                'is_published',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_publications');
    }
};
