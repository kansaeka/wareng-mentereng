<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Membuat atau memperbarui cerita utama.
         */
        $story = Story::updateOrCreate(
            [
                'slug' => 'jelajah-dusun-wareng',
            ],
            [
                'title' =>
                'Jelajah Dusun Wareng',

                'summary' =>
                'Perjalanan visual untuk mengenal ruang, kehidupan masyarakat, potensi lokal, serta cerita yang membentuk Dusun Wareng.',

                'cover_image_path' => null,

                'is_published' => true,

                'published_at' => now(),
            ]
        );

        /*
         * Seluruh koordinat masih berupa
         * data simulasi sementara.
         */
        $chapters = [
            [
                'title' =>
                'Memasuki Dusun Wareng',

                'slug' =>
                'memasuki-dusun-wareng',

                'location_name' =>
                'Wilayah Dusun Wareng',

                'body' =>
                'Perjalanan dimulai dengan mengenal posisi Dusun Wareng dalam wilayah Desa Sumberarum. Permukiman, jalan lingkungan, lahan pertanian, dan aktivitas masyarakat saling terhubung membentuk karakter ruang dusun.',

                'longitude' => 110.18639,
                'latitude' => -7.57167,

                'map_zoom' => 16,
                'sort_order' => 1,
            ],

            [
                'title' =>
                'Ruang Kehidupan Masyarakat',

                'slug' =>
                'ruang-kehidupan-masyarakat',

                'location_name' =>
                'Kawasan Permukiman',

                'body' =>
                'Permukiman bukan hanya kumpulan rumah, tetapi ruang berlangsungnya interaksi sosial, kegiatan keluarga, pertemuan warga, dan berbagai aktivitas sehari-hari masyarakat Dusun Wareng.',

                'longitude' => 110.18685,
                'latitude' => -7.57145,

                'map_zoom' => 18,
                'sort_order' => 2,
            ],

            [
                'title' =>
                'Pertanian sebagai Potensi Lokal',

                'slug' =>
                'pertanian-sebagai-potensi-lokal',

                'location_name' =>
                'Kawasan Pertanian',

                'body' =>
                'Lahan pertanian menjadi bagian penting dari bentang Dusun Wareng. Selain mempunyai nilai ekonomi, kawasan ini mencerminkan hubungan masyarakat dengan lingkungan dan sumber daya lokal.',

                'longitude' => 110.18555,
                'latitude' => -7.57215,

                'map_zoom' => 17,
                'sort_order' => 3,
            ],

            [
                'title' =>
                'Potensi Ekonomi Masyarakat',

                'slug' =>
                'potensi-ekonomi-masyarakat',

                'location_name' =>
                'Lokasi Kegiatan Usaha',

                'body' =>
                'Kegiatan usaha masyarakat berkembang dari kebutuhan lokal, keterampilan warga, dan pemanfaatan sumber daya sekitar. Pemetaan potensi ekonomi membantu memperkenalkan usaha tersebut secara lebih luas.',

                'longitude' => 110.18755,
                'latitude' => -7.57205,

                'map_zoom' => 18,
                'sort_order' => 4,
            ],
        ];

        foreach ($chapters as $chapter) {
            $existingId = DB::table(
                'story_chapters'
            )
                ->where(
                    'story_id',
                    $story->id
                )
                ->where(
                    'slug',
                    $chapter['slug']
                )
                ->value('id');

            /*
             * Perbarui bab ketika slug yang sama
             * sudah tersedia.
             */
            if ($existingId) {
                DB::update(
                    <<<'SQL'
                    UPDATE story_chapters
                    SET
                        title = ?,
                        location_name = ?,
                        body = ?,
                        geom = ST_SetSRID(
                            ST_MakePoint(?, ?),
                            4326
                        ),
                        map_zoom = ?,
                        sort_order = ?,
                        is_published = ?,
                        updated_at = NOW()
                    WHERE id = ?
                    SQL,
                    [
                        $chapter['title'],
                        $chapter['location_name'],
                        $chapter['body'],

                        // Urutan PostGIS:
                        // longitude, latitude.
                        $chapter['longitude'],
                        $chapter['latitude'],

                        $chapter['map_zoom'],
                        $chapter['sort_order'],
                        true,
                        $existingId,
                    ]
                );

                continue;
            }

            /*
             * Tambahkan bab ketika belum tersedia.
             */
            DB::insert(
                <<<'SQL'
                INSERT INTO story_chapters (
                    story_id,
                    title,
                    slug,
                    location_name,
                    body,
                    image_path,
                    geom,
                    map_zoom,
                    sort_order,
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
                    NULL,
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
                    $story->id,
                    $chapter['title'],
                    $chapter['slug'],
                    $chapter['location_name'],
                    $chapter['body'],

                    // Urutan PostGIS:
                    // longitude, latitude.
                    $chapter['longitude'],
                    $chapter['latitude'],

                    $chapter['map_zoom'],
                    $chapter['sort_order'],
                    true,
                ]
            );
        }
    }
}
