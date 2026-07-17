@extends('layouts.app')

@section('title', 'Kelola Jelajah Wareng | WebGIS Dusun Wareng')

@section('content')
    <section class="admin-story-page">
        <div class="container">
            <header class="admin-story-page-header">
                <div>
                    <p class="section-label">
                        ADMINISTRASI KONTEN
                    </p>

                    <h1>Kelola Jelajah Wareng</h1>

                    <p>
                        Lihat struktur cerita, urutan bab,
                        lokasi, serta status publikasi
                        Jelajah Wareng.
                    </p>
                </div>

                <a href="{{ route('stories.wareng') }}" class="button button-primary" target="_blank" rel="noopener">
                    Lihat Halaman Publik
                </a>
            </header>

            @if (session('success'))
                <div class="admin-success-message" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($stories as $story)
                <article class="admin-story-card">
                    <header class="admin-story-card-header">
                        <div>
                            <div class="admin-story-title-row">
                                <h2>
                                    {{ $story->title }}
                                </h2>

                                <span class="admin-story-status {{ $story->is_published ? 'is-published' : 'is-draft' }}">
                                    {{ $story->is_published ? 'Dipublikasikan' : 'Draft' }}
                                </span>
                            </div>

                            <p>
                                {{ $story->summary ?: 'Ringkasan cerita belum tersedia.' }}
                            </p>
                        </div>

                        <div class="admin-story-card-actions">
                            <div class="admin-story-statistic">
                                <strong>
                                    {{ $story->chapters_count }}
                                </strong>

                                <span>Bab cerita</span>
                            </div>

                            <a href="{{ route('admin.stories.chapters.create', $story) }}" class="button button-primary">
                                Tambah Bab
                            </a>

                            <a href="{{ route('admin.stories.edit', $story) }}" class="button admin-story-edit-button">
                                Edit Cerita
                            </a>
                        </div>
                    </header>

                    <div class="admin-story-meta">
                        <span>
                            <strong>Slug:</strong>
                            {{ $story->slug }}
                        </span>

                        <span>
                            <strong>Publikasi:</strong>

                            {{ $story->published_at ? $story->published_at->format('d M Y H:i') : 'Belum ditentukan' }}
                        </span>
                    </div>

                    <div class="admin-story-table-wrapper">
                        <table class="admin-story-table">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Bab</th>
                                    <th>Lokasi</th>
                                    <th>Koordinat</th>
                                    <th>Zoom</th>
                                    <th>Gambar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($story->chapters
                                                                as $chapter)
                                    <tr>
                                        <td data-label="Urutan">
                                            <span class="admin-story-order">
                                                {{ str_pad((string) $chapter->sort_order, 2, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>

                                        <td data-label="Bab">
                                            <strong>
                                                {{ $chapter->title }}
                                            </strong>

                                            <small>
                                                {{ $chapter->slug }}
                                            </small>
                                        </td>

                                        <td data-label="Lokasi">
                                            {{ $chapter->location_name ?: 'Belum tersedia' }}
                                        </td>

                                        <td data-label="Koordinat">
                                            @if ($chapter->latitude !== null && $chapter->longitude !== null)
                                                <code>
                                                    {{ number_format((float) $chapter->latitude, 6) }},
                                                    {{ number_format((float) $chapter->longitude, 6) }}
                                                </code>
                                            @else
                                                <span class="admin-story-empty-value">
                                                    Belum ada titik
                                                </span>
                                            @endif
                                        </td>

                                        <td data-label="Zoom">
                                            {{ $chapter->map_zoom }}
                                        </td>

                                        <td data-label="Gambar">
                                            @if ($chapter->image_path)
                                                <img class="admin-story-thumbnail"
                                                    src="{{ asset('storage/' . $chapter->image_path) }}"
                                                    alt="Gambar {{ $chapter->title }}" loading="lazy">
                                            @else
                                                <span class="admin-story-empty-value">
                                                    Belum ada
                                                </span>
                                            @endif
                                        </td>

                                        <td data-label="Status">
                                            <span
                                                class="admin-story-status {{ $chapter->is_published ? 'is-published' : 'is-draft' }}">
                                                {{ $chapter->is_published ? 'Aktif' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div class="admin-story-row-actions">
                                                <form
                                                    action="{{ route('admin.stories.chapters.move-up', [$story->id, $chapter->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit" class="admin-story-move-button"
                                                        title="Naikkan urutan bab"
                                                        aria-label="Naikkan urutan {{ $chapter->title }}"
                                                        @disabled($loop->first)>
                                                        ↑
                                                    </button>
                                                </form>

                                                <a href="{{ route('admin.stories.chapters.edit', [$story->id, $chapter->id]) }}"
                                                    class="admin-story-action-link">
                                                    Edit Bab
                                                </a>

                                                <form
                                                    action="{{ route('admin.stories.chapters.destroy', [$story->id, $chapter->id]) }}"
                                                    method="POST"
                                                    onsubmit="
                return confirm(
                    'Yakin ingin menghapus bab ini?'
                );
            ">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="admin-story-delete-button">
                                                        Hapus
                                                    </button>
                                                </form>

                                                <form
                                                    action="{{ route('admin.stories.chapters.move-down', [$story->id, $chapter->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit" class="admin-story-move-button"
                                                        title="Turunkan urutan bab"
                                                        aria-label="Turunkan urutan {{ $chapter->title }}"
                                                        @disabled($loop->last)>
                                                        ↓
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="admin-story-empty-row">
                                            Bab cerita belum tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>
            @empty
                <div class="admin-story-empty-state">
                    <h2>Cerita belum tersedia</h2>

                    <p>
                        Data Jelajah Wareng belum ditemukan
                        pada database.
                    </p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
