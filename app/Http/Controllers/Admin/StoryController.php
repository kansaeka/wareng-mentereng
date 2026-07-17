<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StoryController extends Controller
{
    public function index(): View
    {
        $stories = Story::query()
            ->with([
                'chapters' => function ($query) {
                    $query
                        ->select([
                            'id',
                            'story_id',
                            'title',
                            'slug',
                            'location_name',
                            'image_path',
                            'map_zoom',
                            'sort_order',
                            'is_published',
                            'created_at',
                            'updated_at',
                        ])
                        ->selectRaw(
                            'ST_Y(geom) AS latitude'
                        )
                        ->selectRaw(
                            'ST_X(geom) AS longitude'
                        )
                        ->orderBy('sort_order');
                },
            ])
            ->withCount('chapters')
            ->orderBy('title')
            ->get();

        return view(
            'admin.stories.index',
            compact('stories')
        );
    }

    public function edit(Story $story): View
    {
        return view(
            'admin.stories.edit',
            compact('story')
        );
    }

    public function update(
        Request $request,
        Story $story
    ): RedirectResponse {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'summary' => [
                'nullable',
                'string',
            ],

            'cover_image' => [
                'nullable',
                'image',
                'max:5120',
            ],

            'remove_cover_image' => [
                'nullable',
                'boolean',
            ],

            'is_published' => [
                'nullable',
                'boolean',
            ],
        ]);

        $coverImagePath =
            $story->cover_image_path;

        /*
         * Menghapus sampul lama apabila admin
         * memilih opsi hapus gambar.
         */
        if (
            $request->boolean(
                'remove_cover_image'
            ) &&
            $coverImagePath
        ) {
            Storage::disk('public')
                ->delete($coverImagePath);

            $coverImagePath = null;
        }

        /*
         * Mengganti sampul dengan gambar baru.
         */
        if ($request->hasFile('cover_image')) {
            if ($coverImagePath) {
                Storage::disk('public')
                    ->delete($coverImagePath);
            }

            $coverImagePath =
                $request
                ->file('cover_image')
                ->store(
                    'stories/covers',
                    'public'
                );
        }

        $isPublished =
            $request->boolean('is_published');

        $story->update([
            'title' => $validated['title'],

            'summary' =>
            $validated['summary']
                ?? null,

            'cover_image_path' =>
            $coverImagePath,

            'is_published' =>
            $isPublished,

            'published_at' =>
            $isPublished
                ? (
                    $story->published_at
                    ?? now()
                )
                : null,
        ]);

        return redirect()
            ->route('admin.stories.index')
            ->with(
                'success',
                'Cerita Jelajah Wareng berhasil diperbarui.'
            );
    }
}
