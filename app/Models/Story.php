<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'cover_image_path',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(
            StoryChapter::class
        )->orderBy('sort_order');
    }

    public function publishedChapters(): HasMany
    {
        return $this->hasMany(
            StoryChapter::class
        )
            ->where('is_published', true)
            ->orderBy('sort_order');
    }
}
