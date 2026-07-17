<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facility extends Model
{
    protected $fillable = [
        'facility_category_id',
        'name',
        'slug',
        'description',
        'address',
        'photo_path',
        'source',
        'verification_status',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            FacilityCategory::class,
            'facility_category_id'
        );
    }
}
