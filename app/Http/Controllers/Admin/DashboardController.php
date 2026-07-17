<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityCategory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'categoryCount' =>
            FacilityCategory::query()->count(),

            'facilityCount' =>
            Facility::query()->count(),

            'publishedFacilityCount' =>
            Facility::query()
                ->where('is_published', true)
                ->count(),

            'unverifiedFacilityCount' =>
            Facility::query()
                ->where(
                    'verification_status',
                    'unverified'
                )
                ->count(),
        ]);
    }
}
