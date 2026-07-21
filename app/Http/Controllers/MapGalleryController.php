<?php

namespace App\Http\Controllers;

use App\Models\MapPublication;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MapGalleryController extends Controller
{
    public function __invoke(): View
    {
        $maps = MapPublication::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->map(function (MapPublication $map): array {
                $searchText = implode(' ', [
                    $map->title,
                    $map->collection_group,
                    $map->category,
                    $map->description,
                    $map->keywords,
                ]);

                $thumbnailExists =
                    filled($map->thumbnail_path)
                    && file_exists(
                        public_path($map->thumbnail_path)
                    );

                $downloadExists =
                    filled($map->file_path)
                    && file_exists(
                        public_path($map->file_path)
                    );

                return [
                    'id' => $map->id,
                    'title' => $map->title,
                    'group' => $map->collection_group,
                    'category' => $map->category,
                    'year' => $map->year,
                    'description' => $map->description,
                    'keywords' => $map->keywords,
                    'thumbnail' => $map->thumbnail_path,
                    'download' => $map->file_path,
                    'format' => $map->format,
                    'status' => $map->status,

                    'category_slug' =>
                    Str::slug($map->category),

                    'group_slug' =>
                    Str::slug($map->collection_group),

                    'search_text' =>
                    Str::of($searchText)
                        ->lower()
                        ->squish()
                        ->toString(),

                    'thumbnail_exists' =>
                    $thumbnailExists,

                    'download_exists' =>
                    $downloadExists,
                ];
            });

        return view(
            'maps.gallery',
            compact('maps')
        );
    }
}
