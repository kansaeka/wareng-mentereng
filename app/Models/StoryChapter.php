<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryChapter extends Model
{
    protected $fillable = [
        'story_id',
        'title',
        'slug',
        'location_name',
        'body',
        'image_path',
        'map_zoom',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'map_zoom' => 'integer',
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(
            Story::class
        );
    }
}
