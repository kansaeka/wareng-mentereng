<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use JsonException;

class FacilityGeoJsonController extends Controller
{
    /**
     * Menampilkan fasilitas publik dalam format GeoJSON.
     *
     * @throws JsonException
     */
    public function __invoke(): JsonResponse
    {
        $facilities = DB::table('facilities as f')
            ->join(
                'facility_categories as fc',
                'fc.id',
                '=',
                'f.facility_category_id'
            )
            ->where('f.is_published', true)
            ->where('fc.is_active', true)
            ->orderBy('fc.sort_order')
            ->orderBy('f.name')
            ->select([
                'f.id',
                'f.name',
                'f.slug',
                'f.description',
                'f.address',
                'f.photo_path',
                'f.source',
                'f.verification_status',

                'fc.id as category_id',
                'fc.name as category',
                'fc.slug as category_slug',
                'fc.marker_color',
            ])
            ->selectRaw(
                'ST_AsGeoJSON(f.geom)::json AS geometry'
            )
            ->get();

        $features = $facilities
            ->map(function (object $facility): array {
                /*
                 * Driver PostgreSQL biasanya mengirim kolom JSON
                 * sebagai string. Karena itu, data diubah kembali
                 * menjadi array PHP.
                 */
                $geometry = is_string($facility->geometry)
                    ? json_decode(
                        $facility->geometry,
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    )
                    : $facility->geometry;

                return [
                    'type' => 'Feature',

                    /*
                     * ID tingkat Feature membantu aplikasi
                     * mengidentifikasi objek secara stabil.
                     */
                    'id' => (int) $facility->id,

                    'properties' => [
                        'id' => (int) $facility->id,
                        'name' => $facility->name,
                        'slug' => $facility->slug,
                        'category_id' =>
                        (int) $facility->category_id,
                        'category' => $facility->category,
                        'category_slug' =>
                        $facility->category_slug,
                        'marker_color' =>
                        $facility->marker_color,
                        'description' =>
                        $facility->description,
                        'address' => $facility->address,
                        'photo_url' =>
                        $facility->photo_path
                            ? asset(
                                'storage/' .
                                    $facility->photo_path
                            )
                            : null,

                        'source' => $facility->source,
                        'verification_status' =>
                        $facility->verification_status,
                    ],

                    'geometry' => $geometry,
                ];
            })
            ->values();

        return response()->json(
            [
                'type' => 'FeatureCollection',
                'features' => $features,
            ],
            200,
            [
                /*
                 * Saat pengembangan, data tidak disimpan
                 * dalam cache browser agar perubahan database
                 * segera terlihat.
                 */
                'Cache-Control' => 'no-store',
            ]
        );
    }
}
