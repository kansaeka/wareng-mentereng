@extends('layouts.app')

@section('title', 'Edit Kategori | Admin WebGIS Wareng')

@section('content')
    <section class="admin-page-header">
        <div class="container">
            <p class="section-label">
                ADMIN WEBGIS
            </p>

            <h1>Edit Kategori Fasilitas</h1>

            <p>
                Perbarui informasi kategori
                {{ $category->name }}.
            </p>
        </div>
    </section>

    <section class="admin-content-section">
        <div class="container">
            <div class="admin-form-card">
                <form
                    action="{{ route('admin.facility-categories.update', $category) }}"
                    method="POST" class="admin-data-form">
                    @csrf
                    @method('PUT')

                    @include('admin.facility-categories._form')

                    <div class="admin-form-actions">
                        <a href="{{ route('admin.facility-categories.index') }}"
                            class="admin-secondary-button">
                            Batal
                        </a>

                        <button type="submit" class="admin-primary-button">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
