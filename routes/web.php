<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FacilityGeoJsonController;
use App\Http\Controllers\Api\FacilityCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\FacilityCategoryController as AdminFacilityCategoryController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\JelajahWarengController;
use App\Http\Controllers\Admin\StoryController as AdminStoryController;
use App\Http\Controllers\Admin\StoryChapterController as AdminStoryChapterController;

Route::view('/', 'home')
    ->name('home');

Route::view('/peta', 'map.index')
    ->name('map.index');

Route::get(
    '/jelajah-wareng',
    JelajahWarengController::class
)->name('stories.wareng');

Route::get(
    '/api/map/facilities',
    FacilityGeoJsonController::class
)->name('api.map.facilities');
Route::get(
    '/api/map/facility-categories',
    FacilityCategoryController::class
)->name('api.map.facility-categories');
Route::middleware('guest')->group(function () {
    Route::get(
        '/login',
        [LoginController::class, 'create']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'store']
    )->name('login.store');
});

Route::post(
    '/logout',
    [LoginController::class, 'destroy']
)
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'auth',
        'admin',
    ])
    ->group(function () {
        Route::get(
            '/',
            AdminDashboardController::class
        )->name('dashboard');

        Route::resource(
            'facility-categories',
            AdminFacilityCategoryController::class
        )
            ->except([
                'show',
            ])
            ->parameters([
                'facility-categories' =>
                'facilityCategory',
            ]);
        Route::resource(
            'facilities',
            AdminFacilityController::class
        )->except([
            'show',
        ]);
        Route::get(
            '/stories',
            [AdminStoryController::class, 'index']
        )->name('stories.index');
        Route::get(
            '/stories/{story}/edit',
            [AdminStoryController::class, 'edit']
        )->name('stories.edit');

        Route::put(
            '/stories/{story}',
            [AdminStoryController::class, 'update']
        )->name('stories.update');

        Route::get(
            '/stories/{story}/chapters/create',
            [AdminStoryChapterController::class, 'create']
        )->name('stories.chapters.create');

        Route::post(
            '/stories/{story}/chapters',
            [AdminStoryChapterController::class, 'store']
        )->name('stories.chapters.store');

        Route::get(
            '/stories/{story}/chapters/{chapter}/edit',
            [AdminStoryChapterController::class, 'edit']
        )->name('stories.chapters.edit');

        Route::put(
            '/stories/{story}/chapters/{chapter}',
            [AdminStoryChapterController::class, 'update']
        )->name('stories.chapters.update');

        Route::delete(
            '/stories/{story}/chapters/{chapter}',
            [AdminStoryChapterController::class, 'destroy']
        )->name('stories.chapters.destroy');

        Route::patch(
            '/stories/{story}/chapters/{chapter}/move-up',
            [
                AdminStoryChapterController::class,
                'moveUp',
            ]
        )->name('stories.chapters.move-up');

        Route::patch(
            '/stories/{story}/chapters/{chapter}/move-down',
            [
                AdminStoryChapterController::class,
                'moveDown',
            ]
        )->name('stories.chapters.move-down');
    });
