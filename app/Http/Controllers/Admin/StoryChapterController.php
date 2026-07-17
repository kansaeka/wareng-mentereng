<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryChapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StoryChapterController extends Controller
{
    public function create(Story $story): View
    {
        return view(
            'admin.story-chapters.create',
            compact('story')
        );
    }

    public function store(
        Request $request,
        Story $story
    ): RedirectResponse {
        $validated = $this->validateChapter($request);

        $imagePath = $request->hasFile('image')
            ? $request
            ->file('image')
            ->store(
                'stories/chapters',
                'public'
            )
            : null;

        $chapter = StoryChapter::create([
            'story_id' => $story->id,

            'title' => $validated['title'],

            'slug' => $this->createUniqueSlug(
                $story,
                $validated['title']
            ),

            'location_name' =>
            $validated['location_name']
                ?? null,

            'body' => $validated['body'],

            'image_path' => $imagePath,

            'map_zoom' =>
            $validated['map_zoom'],

            'sort_order' =>
            $validated['sort_order'],

            'is_published' =>
            $request->boolean(
                'is_published'
            ),
        ]);

        $this->saveGeometry(
            $chapter,
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null
        );

        return redirect()
            ->route('admin.stories.index')
            ->with(
                'success',
                'Bab Jelajah Wareng berhasil ditambahkan.'
            );
    }

    public function edit(
        Story $story,
        StoryChapter $chapter
    ): View {
        $this->ensureChapterBelongsToStory(
            $story,
            $chapter
        );

        /*
         * Mengambil latitude dan longitude
         * dari kolom geom PostGIS.
         */
        $chapter = StoryChapter::query()
            ->whereKey($chapter->id)
            ->select('story_chapters.*')
            ->selectRaw(
                'ST_Y(geom) AS latitude'
            )
            ->selectRaw(
                'ST_X(geom) AS longitude'
            )
            ->firstOrFail();

        return view(
            'admin.story-chapters.edit',
            compact('story', 'chapter')
        );
    }

    public function update(
        Request $request,
        Story $story,
        StoryChapter $chapter
    ): RedirectResponse {
        $this->ensureChapterBelongsToStory(
            $story,
            $chapter
        );

        $validated = $this->validateChapter(
            $request
        );

        $imagePath = $chapter->image_path;

        /*
         * Menghapus gambar lama jika dipilih admin.
         */
        if (
            $request->boolean('remove_image') &&
            $imagePath
        ) {
            Storage::disk('public')
                ->delete($imagePath);

            $imagePath = null;
        }

        /*
         * Mengganti gambar dengan file baru.
         */
        if ($request->hasFile('image')) {
            $newImagePath = $request
                ->file('image')
                ->store(
                    'stories/chapters',
                    'public'
                );

            if ($imagePath) {
                Storage::disk('public')
                    ->delete($imagePath);
            }

            $imagePath = $newImagePath;
        }

        $chapter->update([
            'title' => $validated['title'],

            'slug' => $this->createUniqueSlug(
                $story,
                $validated['title'],
                $chapter->id
            ),

            'location_name' =>
            $validated['location_name']
                ?? null,

            'body' => $validated['body'],

            'image_path' => $imagePath,

            'map_zoom' =>
            $validated['map_zoom'],

            'sort_order' =>
            $validated['sort_order'],

            'is_published' =>
            $request->boolean(
                'is_published'
            ),
        ]);

        $this->saveGeometry(
            $chapter,
            $validated['latitude'] ?? null,
            $validated['longitude'] ?? null
        );

        return redirect()
            ->route('admin.stories.index')
            ->with(
                'success',
                'Bab Jelajah Wareng berhasil diperbarui.'
            );
    }

    public function destroy(
        Story $story,
        StoryChapter $chapter
    ): RedirectResponse {
        $this->ensureChapterBelongsToStory(
            $story,
            $chapter
        );

        if ($chapter->image_path) {
            Storage::disk('public')
                ->delete($chapter->image_path);
        }

        $chapter->delete();

        return redirect()
            ->route('admin.stories.index')
            ->with(
                'success',
                'Bab Jelajah Wareng berhasil dihapus.'
            );
    }

    public function moveUp(
        Story $story,
        StoryChapter $chapter
    ): RedirectResponse {
        $this->ensureChapterBelongsToStory(
            $story,
            $chapter
        );

        $moved = $this->moveChapter(
            $story,
            $chapter,
            -1
        );

        return redirect()
            ->route('admin.stories.index')
            ->with(
                'success',
                $moved
                    ? 'Urutan bab berhasil dinaikkan.'
                    : 'Bab sudah berada pada urutan pertama.'
            );
    }

    public function moveDown(
        Story $story,
        StoryChapter $chapter
    ): RedirectResponse {
        $this->ensureChapterBelongsToStory(
            $story,
            $chapter
        );

        $moved = $this->moveChapter(
            $story,
            $chapter,
            1
        );

        return redirect()
            ->route('admin.stories.index')
            ->with(
                'success',
                $moved
                    ? 'Urutan bab berhasil diturunkan.'
                    : 'Bab sudah berada pada urutan terakhir.'
            );
    }

    private function validateChapter(
        Request $request
    ): array {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'location_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'body' => [
                'required',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'max:5120',
            ],

            'remove_image' => [
                'nullable',
                'boolean',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
                'required_with:longitude',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
                'required_with:latitude',
            ],

            'map_zoom' => [
                'required',
                'integer',
                'between:1,20',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_published' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    private function saveGeometry(
        StoryChapter $chapter,
        mixed $latitude,
        mixed $longitude
    ): void {
        /*
         * Jika koordinat dikosongkan, lokasi bab
         * dihapus dari database.
         */
        if (
            $latitude === null ||
            $latitude === '' ||
            $longitude === null ||
            $longitude === ''
        ) {
            DB::statement(
                'UPDATE story_chapters
                 SET geom = NULL
                 WHERE id = ?',
                [$chapter->id]
            );

            return;
        }

        /*
         * Urutan ST_MakePoint:
         * longitude terlebih dahulu, lalu latitude.
         */
        DB::statement(
            'UPDATE story_chapters
             SET geom = ST_SetSRID(
                 ST_MakePoint(?, ?),
                 4326
             )
             WHERE id = ?',
            [
                (float) $longitude,
                (float) $latitude,
                $chapter->id,
            ]
        );
    }

    private function createUniqueSlug(
        Story $story,
        string $title,
        ?int $ignoredChapterId = null
    ): string {
        $baseSlug = Str::slug($title);

        if ($baseSlug === '') {
            $baseSlug = 'bab';
        }

        $slug = $baseSlug;
        $number = 2;

        while (
            StoryChapter::query()
            ->where(
                'story_id',
                $story->id
            )
            ->where('slug', $slug)
            ->when(
                $ignoredChapterId !== null,
                function ($query) use (
                    $ignoredChapterId
                ) {
                    $query->where(
                        'id',
                        '!=',
                        $ignoredChapterId
                    );
                }
            )
            ->exists()
        ) {
            $slug =
                $baseSlug . '-' . $number;

            $number++;
        }

        return $slug;
    }

    private function moveChapter(
        Story $story,
        StoryChapter $chapter,
        int $direction
    ): bool {
        return DB::transaction(
            function () use (
                $story,
                $chapter,
                $direction
            ): bool {
                /*
             * Mengambil seluruh ID bab sesuai
             * urutan yang sedang tersimpan.
             */
                $chapterIds = StoryChapter::query()
                    ->where(
                        'story_id',
                        $story->id
                    )
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->pluck('id')
                    ->map(
                        fn($id): int => (int) $id
                    )
                    ->values()
                    ->all();

                $currentIndex = array_search(
                    (int) $chapter->id,
                    $chapterIds,
                    true
                );

                if ($currentIndex === false) {
                    return false;
                }

                $targetIndex =
                    $currentIndex + $direction;

                /*
             * Tidak melakukan apa pun ketika
             * bab sudah berada di batas atas
             * atau batas bawah.
             */
                if (
                    $targetIndex < 0 ||
                    $targetIndex >= count($chapterIds)
                ) {
                    return false;
                }

                /*
             * Menukar posisi dua bab.
             */
                [
                    $chapterIds[$currentIndex],
                    $chapterIds[$targetIndex],
                ] = [
                    $chapterIds[$targetIndex],
                    $chapterIds[$currentIndex],
                ];

                /*
             * Menormalkan kembali urutan menjadi
             * 1, 2, 3, dan seterusnya.
             */
                foreach (
                    $chapterIds as $index => $chapterId
                ) {
                    StoryChapter::query()
                        ->whereKey($chapterId)
                        ->update([
                            'sort_order' => $index + 1,
                        ]);
                }

                return true;
            }
        );
    }

    private function ensureChapterBelongsToStory(
        Story $story,
        StoryChapter $chapter
    ): void {
        abort_unless(
            (int) $chapter->story_id ===
                (int) $story->id,
            404
        );
    }
}
