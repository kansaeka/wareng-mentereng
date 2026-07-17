<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FacilityCategoryController extends Controller
{
    public function index(): View
    {
        $categories = FacilityCategory::query()
            ->withCount('facilities')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.facility-categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view(
            'admin.facility-categories.create',
            [
                'category' => new FacilityCategory(),
            ]
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $request->merge([
            'slug' => Str::slug(
                (string) $request->input('name')
            ),

            'is_active' =>
            $request->boolean('is_active'),
        ]);

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique(
                        'facility_categories',
                        'name'
                    ),
                ],

                'slug' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique(
                        'facility_categories',
                        'slug'
                    ),
                ],

                'marker_color' => [
                    'required',
                    'regex:/^#[0-9A-Fa-f]{6}$/',
                ],

                'sort_order' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:9999',
                ],

                'is_active' => [
                    'required',
                    'boolean',
                ],
            ],
            [
                'name.required' =>
                'Nama kategori wajib diisi.',

                'name.unique' =>
                'Nama kategori sudah digunakan.',

                'slug.unique' =>
                'Slug kategori sudah digunakan.',

                'marker_color.required' =>
                'Warna marker wajib dipilih.',

                'marker_color.regex' =>
                'Format warna marker tidak valid.',

                'sort_order.required' =>
                'Urutan kategori wajib diisi.',

                'sort_order.integer' =>
                'Urutan kategori harus berupa angka.',

                'sort_order.min' =>
                'Urutan kategori tidak boleh negatif.',
            ]
        );

        FacilityCategory::create($validated);

        return redirect()
            ->route(
                'admin.facility-categories.index'
            )
            ->with(
                'success',
                'Kategori fasilitas berhasil ditambahkan.'
            );
    }

    public function edit(
        FacilityCategory $facilityCategory
    ): View {
        return view(
            'admin.facility-categories.edit',
            [
                'category' => $facilityCategory,
            ]
        );
    }

    public function update(
        Request $request,
        FacilityCategory $facilityCategory
    ): RedirectResponse {
        $request->merge([
            'slug' => Str::slug(
                (string) $request->input('name')
            ),

            'is_active' =>
            $request->boolean('is_active'),
        ]);

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:100',

                    Rule::unique(
                        'facility_categories',
                        'name'
                    )->ignore(
                        $facilityCategory->id
                    ),
                ],

                'slug' => [
                    'required',
                    'string',
                    'max:100',

                    Rule::unique(
                        'facility_categories',
                        'slug'
                    )->ignore(
                        $facilityCategory->id
                    ),
                ],

                'marker_color' => [
                    'required',
                    'regex:/^#[0-9A-Fa-f]{6}$/',
                ],

                'sort_order' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:9999',
                ],

                'is_active' => [
                    'required',
                    'boolean',
                ],
            ],
            [
                'name.required' =>
                'Nama kategori wajib diisi.',

                'name.unique' =>
                'Nama kategori sudah digunakan.',

                'slug.unique' =>
                'Slug kategori sudah digunakan.',

                'marker_color.required' =>
                'Warna marker wajib dipilih.',

                'marker_color.regex' =>
                'Format warna marker tidak valid.',

                'sort_order.required' =>
                'Urutan kategori wajib diisi.',

                'sort_order.integer' =>
                'Urutan kategori harus berupa angka.',

                'sort_order.min' =>
                'Urutan kategori tidak boleh negatif.',
            ]
        );

        $facilityCategory->update($validated);

        return redirect()
            ->route(
                'admin.facility-categories.index'
            )
            ->with(
                'success',
                'Kategori fasilitas berhasil diperbarui.'
            );
    }

    public function destroy(
        FacilityCategory $facilityCategory
    ): RedirectResponse {
        /*
         * Kategori yang sudah dipakai fasilitas
         * tidak boleh dihapus.
         */
        if (
            $facilityCategory
            ->facilities()
            ->exists()
        ) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih digunakan oleh data fasilitas.'
            );
        }

        $facilityCategory->delete();

        return redirect()
            ->route(
                'admin.facility-categories.index'
            )
            ->with(
                'success',
                'Kategori fasilitas berhasil dihapus.'
            );
    }
}
