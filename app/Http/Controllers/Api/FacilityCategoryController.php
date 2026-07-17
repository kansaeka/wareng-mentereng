<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FacilityCategoryController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $categories = DB::table('facility_categories')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'marker_color',
            ])
            ->map(function (object $category): array {
                return [
                    'id' => (int) $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'marker_color' => $category->marker_color,
                ];
            })
            ->values();

        return response()->json([
            'data' => $categories,
        ], 200, [
            'Cache-Control' => 'no-store',
        ]);
    }
}
