<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'story_chapters',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('story_id')
                    ->constrained('stories')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->string('title', 150);

                $table->string('slug', 170);

                $table->string(
                    'location_name',
                    150
                )->nullable();

                $table->text('body');

                /*
                 * Satu gambar utama per bab.
                 */
                $table->string('image_path')
                    ->nullable();

                /*
                 * Zoom peta ketika bab aktif.
                 */
                $table->unsignedTinyInteger(
                    'map_zoom'
                )->default(16);

                $table->unsignedInteger(
                    'sort_order'
                )->default(0);

                $table->boolean('is_published')
                    ->default(true);

                $table->timestamps();

                $table->unique([
                    'story_id',
                    'slug',
                ]);

                $table->index([
                    'story_id',
                    'sort_order',
                ]);

                $table->index([
                    'story_id',
                    'is_published',
                ]);
            }
        );

        /*
         * Lokasi bab disimpan sebagai Point WGS 84.
         * Nullable karena beberapa bab mungkin tidak
         * memerlukan satu lokasi khusus.
         */
        DB::statement(
            <<<'SQL'
            ALTER TABLE story_chapters
            ADD COLUMN geom geometry(Point, 4326)
            NULL
            SQL
        );

        /*
         * Indeks spasial untuk mempercepat
         * operasi berdasarkan lokasi.
         */
        DB::statement(
            <<<'SQL'
            CREATE INDEX story_chapters_geom_gix
            ON story_chapters
            USING GIST (geom)
            WHERE geom IS NOT NULL
            SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'story_chapters'
        );
    }
};
