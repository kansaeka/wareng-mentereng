<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\StoryChapter;
use Illuminate\Contracts\View\View;

class JelajahWarengController extends Controller
{
    public function __invoke(): View
    {
        $story = Story::query()
            ->where('slug', 'jelajah-dusun-wareng')
            ->where('is_published', true)
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();

        $chapters = StoryChapter::query()
            ->where('story_id', $story->id)
            ->where('is_published', true)
            ->select([
                'id',
                'story_id',
                'title',
                'slug',
                'location_name',
                'body',
                'image_path',
                'map_zoom',
                'sort_order',
            ])
            ->selectRaw(
                'CASE
                    WHEN geom IS NULL THEN NULL
                    ELSE ST_Y(geom)
                END AS latitude'
            )
            ->selectRaw(
                'CASE
                    WHEN geom IS NULL THEN NULL
                    ELSE ST_X(geom)
                END AS longitude'
            )
            ->orderBy('sort_order')
            ->get();

        return view(
            'stories.wareng',
            compact('story', 'chapters')
        );
    }
}
