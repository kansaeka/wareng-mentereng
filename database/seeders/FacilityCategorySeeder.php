<?php

namespace Database\Seeders;

use App\Models\FacilityCategory;
use Illuminate\Database\Seeder;

class FacilityCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pemerintahan',
                'slug' => 'pemerintahan',
                'marker_color' => '#2f5f98',
                'sort_order' => 1,
            ],
            [
                'name' => 'Keagamaan',
                'slug' => 'keagamaan',
                'marker_color' => '#7857a8',
                'sort_order' => 2,
            ],
            [
                'name' => 'Pertanian',
                'slug' => 'pertanian',
                'marker_color' => '#4f7f42',
                'sort_order' => 3,
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'ekonomi',
                'marker_color' => '#b3752d',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            FacilityCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
