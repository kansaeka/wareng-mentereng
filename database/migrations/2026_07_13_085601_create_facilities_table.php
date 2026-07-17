<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facility_category_id')
                ->constrained('facility_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name', 150);
            $table->string('slug', 170)->unique();

            $table->text('description')->nullable();
            $table->text('address')->nullable();

            /*
             * Titik fasilitas disimpan sebagai geometri
             * Point dengan sistem koordinat WGS 84.
             */
            $table->geometry(
                'geom',
                subtype: 'point',
                srid: 4326
            );

            /*
             * Indeks spasial mempercepat pencarian
             * berdasarkan lokasi.
             */
            $table->spatialIndex('geom');

            $table->string('source', 150)->nullable();

            $table->string('verification_status', 30)
                ->default('unverified');

            $table->boolean('is_published')
                ->default(true);

            $table->timestamps();

            $table->index('facility_category_id');
            $table->index('verification_status');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
