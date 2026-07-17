@extends('layouts.app')

@section('title', 'Kategori Fasilitas | Admin WebGIS Wareng')

@section('content')
    <section class="admin-page-header">
        <div class="container">
            <div class="admin-page-heading">
                <div>
                    <p class="section-label">
                        ADMIN WEBGIS
                    </p>

                    <h1>Kategori Fasilitas</h1>

                    <p>
                        Kelola kategori, warna marker,
                        urutan, dan status tampil.
                    </p>
                </div>

                <a href="{{ route('admin.facility-categories.create') }}"
                    class="admin-primary-link">
                    Tambah Kategori
                </a>
            </div>
        </div>
    </section>

    <section class="admin-content-section">
        <div class="container">
            @if (session('success'))
                <div class="admin-alert admin-alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="admin-alert admin-alert-error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="admin-table-card">
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Kategori</th>
                                <th>Warna</th>
                                <th>Status</th>
                                <th>Fasilitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>
                                        {{ $category->sort_order }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $category->name }}
                                        </strong>

                                        <small class="admin-table-slug">
                                            {{ $category->slug }}
                                        </small>
                                    </td>

                                    <td>
                                        <div class="admin-color-value">
                                            <span class="admin-color-preview"
                                                style="
                                                    background-color:
                                                    {{ $category->marker_color }};
                                                "></span>

                                            <code>
                                                {{ $category->marker_color }}
                                            </code>
                                        </div>
                                    </td>

                                    <td>
                                        @if ($category->is_active)
                                            <span class="admin-status admin-status-active">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="admin-status admin-status-inactive">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $category->facilities_count }}
                                        objek
                                    </td>

                                    <td>
                                        <div class="admin-table-actions">
                                            <a href="{{ route('admin.facility-categories.edit', $category) }}"
                                                class="admin-edit-link">
                                                Edit
                                            </a>

                                            @if ($category->facilities_count === 0)
                                                <form
                                                    action="{{ route('admin.facility-categories.destroy', $category) }}"
                                                    method="POST"
                                                    onsubmit="
                                                        return confirm(
                                                            'Hapus kategori ini?'
                                                        );
                                                    ">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="admin-delete-button">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="admin-delete-disabled"
                                                    title="Kategori masih digunakan fasilitas">
                                                    Tidak dapat dihapus
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="admin-table-empty">
                                        Belum ada kategori fasilitas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="admin-back-link">
                Kembali ke dashboard
            </a>
        </div>
    </section>
@endsection
