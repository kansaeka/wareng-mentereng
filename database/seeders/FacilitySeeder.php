<?php

namespace Database\Seeders;

use App\Models\FacilityCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'category_slug' => 'pemerintahan',
                'name' => 'Pusat Dusun Wareng',
                'description' =>
                'Titik sementara pusat kegiatan Dusun Wareng.',
                'address' =>
                'Dusun Wareng, Sumberarum, Tempuran, Magelang',
                'longitude' => 110.18639,
                'latitude' => -7.57167,
            ],
            [
                'category_slug' => 'keagamaan',
                'name' => 'Tempat Ibadah',
                'description' =>
                'Contoh lokasi fasilitas keagamaan.',
                'address' =>
                'Dusun Wareng, Sumberarum',
                'longitude' => 110.18715,
                'latitude' => -7.57125,
            ],
            [
                'category_slug' => 'pertanian',
                'name' => 'Kawasan Pertanian',
                'description' =>
                'Contoh lokasi kawasan pertanian masyarakat.',
                'address' =>
                'Dusun Wareng, Sumberarum',
                'longitude' => 110.18555,
                'latitude' => -7.57215,
            ],
            [
                'category_slug' => 'ekonomi',
                'name' => 'Lokasi UMKM',
                'description' =>
                'Contoh lokasi kegiatan usaha masyarakat.',
                'address' =>
                'Dusun Wareng, Sumberarum',
                'longitude' => 110.18755,
                'latitude' => -7.57205,
            ],
        ];

        foreach ($facilities as $facility) {
            $category = FacilityCategory::query()
                ->where('slug', $facility['category_slug'])
                ->firstOrFail();

            $slug = Str::slug($facility['name']);

            /*
             * updateOrInsert belum dapat langsung mengolah
             * geometri PostGIS dengan parameter terpisah.
             * Karena itu, cek data lalu gunakan query
             * berparameter untuk INSERT atau UPDATE.
             */
            $existingId = DB::table('facilities')
                ->where('slug', $slug)
                ->value('id');

            if ($existingId) {
                DB::update(
                    <<<'SQL'
                    UPDATE facilities
                    SET
                        facility_category_id = ?,
                        name = ?,
                        description = ?,
                        address = ?,
                        geom = ST_SetSRID(
                            ST_MakePoint(?, ?),
                            4326
                        ),
                        source = ?,
                        verification_status = ?,
                        is_published = ?,
                        updated_at = NOW()
                    WHERE id = ?
                    SQL,
                    [
                        $category->id,
                        $facility['name'],
                        $facility['description'],
                        $facility['address'],
                        $facility['longitude'],
                        $facility['latitude'],
                        'Data simulasi',
                        'unverified',
                        true,
                        $existingId,
                    ]
                );

                continue;
            }

            DB::insert(
                <<<'SQL'
                INSERT INTO facilities (
                    facility_category_id,
                    name,
                    slug,
                    description,
                    address,
                    geom,
                    source,
                    verification_status,
                    is_published,
                    created_at,
                    updated_at
                )
                VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ST_SetSRID(
                        ST_MakePoint(?, ?),
                        4326
                    ),
                    ?,
                    ?,
                    ?,
                    NOW(),
                    NOW()
                )
                SQL,
                [
                    $category->id,
                    $facility['name'],
                    $slug,
                    $facility['description'],
                    $facility['address'],
                    $facility['longitude'],
                    $facility['latitude'],
                    'Data simulasi',
                    'unverified',
                    true,
                ]
            );
        }
    }
}
