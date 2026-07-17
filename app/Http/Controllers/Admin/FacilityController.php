<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::query()
            ->with([
                'category:id,name,marker_color',
            ])
            ->select([
                'id',
                'facility_category_id',
                'name',
                'slug',
                'description',
                'address',
                'photo_path',
                'source',
                'verification_status',
                'is_published',
                'created_at',
                'updated_at',
            ])
            ->selectRaw('ST_Y(geom) AS latitude')
            ->selectRaw('ST_X(geom) AS longitude')
            ->orderBy('name')
            ->get();

        return view(
            'admin.facilities.index',
            compact('facilities')
        );
    }

    public function create(): View
    {
        return view('admin.facilities.create', [
            'facility' => new Facility(),

            'categories' => FacilityCategory::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $request->merge([
            'slug' => Str::slug(
                (string) $request->input('name')
            ),

            'is_published' =>
            $request->boolean('is_published'),
        ]);

        $validated = $this->validateFacility(
            $request
        );

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request
                ->file('photo')
                ->store(
                    'facilities',
                    'public'
                );
        }

        DB::transaction(
            function () use (
                $validated,
                $photoPath
            ) {
                DB::insert(
                    <<<'SQL'
                    INSERT INTO facilities (
                        facility_category_id,
                        name,
                        slug,
                        description,
                        address,
                        photo_path,
                        geom,
                        source,
                        verification_status,
                        is_published,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ST_SetSRID(
                            ST_MakePoint(?, ?),
                            4326
                        ),
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                    )
                    SQL,
                    [
                        $validated['facility_category_id'],
                        $validated['name'],
                        $validated['slug'],
                        $validated['description'] ?? null,
                        $validated['address'] ?? null,
                        $photoPath,

                        // Urutan PostGIS:
                        // longitude, latitude.
                        $validated['longitude'],
                        $validated['latitude'],

                        $validated['source'] ?? null,
                        $validated['verification_status'],
                        $validated['is_published'],
                        now(),
                        now(),
                    ]
                );
            }
        );

        return redirect()
            ->route('admin.facilities.index')
            ->with(
                'success',
                'Data fasilitas berhasil ditambahkan.'
            );
    }

    public function edit(
        Facility $facility
    ): View {
        $facility = Facility::query()
            ->select([
                'id',
                'facility_category_id',
                'name',
                'slug',
                'description',
                'address',
                'photo_path',
                'source',
                'verification_status',
                'is_published',
            ])
            ->selectRaw('ST_Y(geom) AS latitude')
            ->selectRaw('ST_X(geom) AS longitude')
            ->findOrFail($facility->id);

        return view('admin.facilities.edit', [
            'facility' => $facility,

            'categories' => FacilityCategory::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(
        Request $request,
        Facility $facility
    ): RedirectResponse {
        $request->merge([
            'slug' => Str::slug(
                (string) $request->input('name')
            ),

            'is_published' =>
            $request->boolean('is_published'),
        ]);

        $validated = $this->validateFacility(
            $request,
            $facility
        );

        $oldPhotoPath = $facility->photo_path;
        $photoPath = $oldPhotoPath;

        if ($request->boolean('remove_photo')) {
            $photoPath = null;
        }

        if ($request->hasFile('photo')) {
            $photoPath = $request
                ->file('photo')
                ->store(
                    'facilities',
                    'public'
                );
        }

        DB::transaction(
            function () use (
                $validated,
                $facility,
                $photoPath
            ) {
                DB::update(
                    <<<'SQL'
                    UPDATE facilities
                    SET
                        facility_category_id = ?,
                        name = ?,
                        slug = ?,
                        description = ?,
                        address = ?,
                        photo_path = ?,
                        geom = ST_SetSRID(
                            ST_MakePoint(?, ?),
                            4326
                        ),
                        source = ?,
                        verification_status = ?,
                        is_published = ?,
                        updated_at = ?
                    WHERE id = ?
                    SQL,
                    [
                        $validated['facility_category_id'],
                        $validated['name'],
                        $validated['slug'],
                        $validated['description'] ?? null,
                        $validated['address'] ?? null,
                        $photoPath,

                        // Urutan PostGIS:
                        // longitude, latitude.
                        $validated['longitude'],
                        $validated['latitude'],

                        $validated['source'] ?? null,
                        $validated['verification_status'],
                        $validated['is_published'],
                        now(),
                        $facility->id,
                    ]
                );
            }
        );

        if (
            $oldPhotoPath &&
            $oldPhotoPath !== $photoPath
        ) {
            Storage::disk('public')->delete(
                $oldPhotoPath
            );
        }

        return redirect()
            ->route('admin.facilities.index')
            ->with(
                'success',
                'Data fasilitas berhasil diperbarui.'
            );
    }

    public function destroy(
        Facility $facility
    ): RedirectResponse {
        if ($facility->photo_path) {
            Storage::disk('public')->delete(
                $facility->photo_path
            );
        }

        $facility->delete();

        return redirect()
            ->route('admin.facilities.index')
            ->with(
                'success',
                'Data fasilitas berhasil dihapus.'
            );
    }

    private function validateFacility(
        Request $request,
        ?Facility $facility = null
    ): array {
        $facilityId = $facility?->id;

        return $request->validate(
            [
                'facility_category_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'facility_categories',
                        'id'
                    ),
                ],

                'name' => [
                    'required',
                    'string',
                    'max:150',

                    Rule::unique(
                        'facilities',
                        'name'
                    )->ignore($facilityId),
                ],

                'slug' => [
                    'required',
                    'string',
                    'max:170',

                    Rule::unique(
                        'facilities',
                        'slug'
                    )->ignore($facilityId),
                ],

                'description' => [
                    'nullable',
                    'string',
                ],

                'address' => [
                    'nullable',
                    'string',
                ],

                'photo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:3072',
                ],

                'latitude' => [
                    'required',
                    'numeric',
                    'between:-90,90',
                ],

                'longitude' => [
                    'required',
                    'numeric',
                    'between:-180,180',
                ],

                'source' => [
                    'nullable',
                    'string',
                    'max:150',
                ],

                'verification_status' => [
                    'required',
                    Rule::in([
                        'unverified',
                        'verified',
                    ]),
                ],

                'is_published' => [
                    'required',
                    'boolean',
                ],
            ],
            [
                'facility_category_id.required' =>
                'Kategori fasilitas wajib dipilih.',

                'facility_category_id.exists' =>
                'Kategori yang dipilih tidak tersedia.',

                'name.required' =>
                'Nama fasilitas wajib diisi.',

                'name.unique' =>
                'Nama fasilitas sudah digunakan.',

                'slug.unique' =>
                'Slug fasilitas sudah digunakan.',

                'photo.image' =>
                'File foto harus berupa gambar.',

                'photo.mimes' =>
                'Foto harus berformat JPG, JPEG, PNG, atau WebP.',

                'photo.max' =>
                'Ukuran foto maksimal 3 MB.',

                'latitude.required' =>
                'Latitude wajib diisi.',

                'latitude.between' =>
                'Latitude harus berada antara -90 dan 90.',

                'longitude.required' =>
                'Longitude wajib diisi.',

                'longitude.between' =>
                'Longitude harus berada antara -180 dan 180.',

                'verification_status.required' =>
                'Status verifikasi wajib dipilih.',
            ]
        );
    }
}
