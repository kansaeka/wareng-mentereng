@extends('layouts.app')

@section('title', 'Edit Jelajah Wareng | WebGIS Dusun Wareng')

@section('content')
    <section class="admin-story-form-page">
        <div class="container">
            <header class="admin-story-form-header">
                <div>
                    <p class="section-label">
                        ADMINISTRASI KONTEN
                    </p>

                    <h1>Edit Cerita Utama</h1>

                    <p>
                        Perbarui judul, ringkasan, foto sampul,
                        dan status publikasi Jelajah Wareng.
                    </p>
                </div>

                <a href="{{ route('admin.stories.index') }}" class="button button-secondary">
                    Kembali
                </a>
            </header>

            <form
                action="{{ route('admin.stories.update', $story) }}"
                method="POST" enctype="multipart/form-data" class="admin-story-form">
                @csrf
                @method('PUT')

                <div class="admin-story-form-grid">
                    <div class="admin-story-form-main">
                        <div class="admin-form-field">
                            <label for="title">
                                Judul Cerita
                            </label>

                            <input id="title" name="title" type="text"
                                value="{{ old('title', $story->title) }}"
                                maxlength="150" required>

                            @error('title')
                                <small class="admin-form-error">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="admin-form-field">
                            <label for="slug">
                                Slug
                            </label>

                            <input id="slug" type="text" value="{{ $story->slug }}" readonly>

                            <small class="admin-form-help">
                                Slug dipertahankan sebagai identitas
                                cerita pada sistem.
                            </small>
                        </div>

                        <div class="admin-form-field">
                            <label for="summary">
                                Ringkasan Cerita
                            </label>

                            <textarea id="summary" name="summary" rows="7">{{ old('summary', $story->summary) }}</textarea>

                            @error('summary')
                                <small class="admin-form-error">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <aside class="admin-story-form-sidebar">
                        <div class="admin-story-cover-card">
                            <h2>Foto Sampul</h2>

                            @if ($story->cover_image_path)
                                <img class="admin-story-cover-preview"
                                    src="{{ asset('storage/' . $story->cover_image_path) }}"
                                    alt="Sampul {{ $story->title }}">
                            @else
                                <div class="admin-story-cover-placeholder">
                                    Sampul belum tersedia
                                </div>
                            @endif

                            <div class="admin-form-field">
                                <label for="cover_image">
                                    Pilih Gambar Baru
                                </label>

                                <input id="cover_image" name="cover_image" type="file" accept="image/*">

                                <small class="admin-form-help">
                                    Maksimal 5 MB.
                                </small>

                                @error('cover_image')
                                    <small class="admin-form-error">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            @if ($story->cover_image_path)
                                <label class="admin-form-checkbox">
                                    <input name="remove_cover_image" type="checkbox" value="1"
                                        @checked(old('remove_cover_image'))>

                                    <span>
                                        Hapus foto sampul saat ini
                                    </span>
                                </label>
                            @endif
                        </div>

                        <div class="admin-story-publish-card">
                            <h2>Status Cerita</h2>

                            <label class="admin-form-checkbox">
                                <input name="is_published" type="checkbox" value="1" @checked(old('is_published', $story->is_published))>

                                <span>
                                    Publikasikan cerita
                                </span>
                            </label>

                            <p>
                                Jika dinonaktifkan, halaman publik
                                Jelajah Wareng tidak dapat dibuka.
                            </p>
                        </div>
                    </aside>
                </div>

                <footer class="admin-story-form-actions">
                    <a href="{{ route('admin.stories.index') }}"
                        class="button button-secondary">
                        Batal
                    </a>

                    <button type="submit" class="button button-primary">
                        Simpan Perubahan
                    </button>
                </footer>
            </form>
        </div>
    </section>
@endsection
