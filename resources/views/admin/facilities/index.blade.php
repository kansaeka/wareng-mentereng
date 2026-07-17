@extends('layouts.app')

@section('title', 'Data Fasilitas | Admin WebGIS Wareng')

@section('content')
    <section class="admin-page-header">
        <div class="container">
            <div class="admin-page-heading">
                <div>
                    <p class="section-label">
                        ADMIN WEBGIS
                    </p>

                    <h1>Data Fasilitas</h1>

                    <p>
                        Kelola atribut, koordinat,
                        verifikasi, dan publikasi objek.
                    </p>
                </div>

                <a href="{{ route('admin.facilities.create') }}" class="admin-primary-link">
                    Tambah Fasilitas
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

            <div class="admin-table-card">
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Fasilitas</th>
                                <th>Kategori</th>
                                <th>Koordinat</th>
                                <th>Verifikasi</th>
                                <th>Publikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($facilities as $facility)
                                <tr>
                                    <td>
                                        <div class="admin-facility-summary">
                                            @if ($facility->photo_path)
                                                <img src="{{ asset('storage/' . $facility->photo_path) }}"
                                                    alt="Foto {{ $facility->name }}" class="admin-facility-thumbnail">
                                            @else
                                                <div class="admin-facility-thumbnail-placeholder">
                                                    Tidak ada foto
                                                </div>
                                            @endif

                                            <div>
                                                <strong>
                                                    {{ $facility->name }}
                                                </strong>

                                                <small class="admin-table-slug">
                                                    {{ $facility->address ?: 'Alamat belum tersedia' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="admin-color-value">
                                            <span class="admin-color-preview"
                                                style="
                                                    background-color:
                                                    {{ $facility->category?->marker_color ?? '#66746b' }};
                                                "></span>

                                            <span>
                                                {{ $facility->category?->name ?? 'Tanpa kategori' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <code class="admin-coordinate-value">
                                            {{ number_format((float) $facility->latitude, 6, '.', '') }},
                                            {{ number_format((float) $facility->longitude, 6, '.', '') }}
                                        </code>
                                    </td>

                                    <td>
                                        @if ($facility->verification_status === 'verified')
                                            <span class="admin-status admin-status-active">
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="admin-status admin-status-warning">
                                                Belum Diverifikasi
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($facility->is_published)
                                            <span class="admin-status admin-status-active">
                                                Tampil
                                            </span>
                                        @else
                                            <span class="admin-status admin-status-inactive">
                                                Disembunyikan
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="admin-table-actions">
                                            <a href="{{ route('admin.facilities.edit', $facility) }}"
                                                class="admin-edit-link">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.facilities.destroy', $facility) }}"
                                                method="POST"
                                                onsubmit="
                                                    return confirm(
                                                        'Hapus fasilitas ini?'
                                                    );
                                                ">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="admin-delete-button">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="admin-table-empty">
                                        Belum ada data fasilitas.
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
