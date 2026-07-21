<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapPublication extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'collection_group',
        'category',
        'year',
        'description',
        'keywords',
        'thumbnail_path',
        'file_path',
        'format',
        'status',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
