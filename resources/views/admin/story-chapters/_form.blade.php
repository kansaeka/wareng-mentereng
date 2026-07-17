@php
    $isEditing = isset($chapter);
@endphp

<div class="admin-story-form-grid">
    <div class="admin-story-form-main">
        <div class="admin-form-field">
            <label for="title">
                Judul Bab
            </label>

            <input id="title" name="title" type="text"
                value="{{ old('title', $chapter->title ?? '') }}"
                maxlength="150" required>

            @error('title')
                <small class="admin-form-error">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="admin-form-field">
            <label for="location_name">
                Nama Lokasi
            </label>

            <input id="location_name" name="location_name" type="text"
                value="{{ old('location_name', $chapter->location_name ?? '') }}"
                maxlength="150" placeholder="Contoh: Kawasan Permukiman">

            @error('location_name')
                <small class="admin-form-error">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="admin-form-field">
            <label for="body">
                Narasi Bab
            </label>

            <textarea id="body" name="body" rows="11" required>{{ old('body', $chapter->body ?? '') }}</textarea>

            @error('body')
                <small class="admin-form-error">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <div class="admin-story-location-section">
            <div>
                <h2>Lokasi Bab</h2>

                <p>
                    Klik peta untuk menentukan titik lokasi.
                    Marker dapat digeser untuk memperbaiki posisi.
                </p>
            </div>

            <div id="story-chapter-map" class="admin-story-chapter-map" aria-label="Peta pemilih lokasi bab"></div>

            <div class="admin-story-coordinate-grid">
                <div class="admin-form-field">
                    <label for="latitude">
                        Latitude
                    </label>

                    <input id="latitude" name="latitude" type="number" step="any"
                        value="{{ old('latitude', $chapter->latitude ?? '') }}"
                        placeholder="-7.571670">

                    @error('latitude')
                        <small class="admin-form-error">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="admin-form-field">
                    <label for="longitude">
                        Longitude
                    </label>

                    <input id="longitude" name="longitude" type="number" step="any"
                        value="{{ old('longitude', $chapter->longitude ?? '') }}"
                        placeholder="110.186390">

                    @error('longitude')
                        <small class="admin-form-error">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
            </div>

            <button id="clear-story-chapter-location" type="button" class="button button-secondary">
                Hapus Titik Lokasi
            </button>

            <small class="admin-form-help">
                Koordinat sementara masih dapat diganti
                setelah survei atau data ArcGIS tersedia.
            </small>
        </div>
    </div>

    <aside class="admin-story-form-sidebar">
        <div class="admin-story-cover-card">
            <h2>Gambar Bab</h2>

            @if ($isEditing && $chapter->image_path)
                <img class="admin-story-cover-preview"
                    src="{{ asset('storage/' . $chapter->image_path) }}"
                    alt="Gambar {{ $chapter->title }}">
            @else
                <div class="admin-story-cover-placeholder">
                    Gambar bab belum tersedia
                </div>
            @endif

            <div class="admin-form-field">
                <label for="image">
                    Pilih Gambar
                </label>

                <input id="image" name="image" type="file" accept="image/*">

                <small class="admin-form-help">
                    Maksimal 5 MB.
                </small>

                @error('image')
                    <small class="admin-form-error">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            @if ($isEditing && $chapter->image_path)
                <label class="admin-form-checkbox">
                    <input name="remove_image" type="checkbox" value="1" @checked(old('remove_image'))>

                    <span>Hapus gambar saat ini</span>
                </label>
            @endif
        </div>

        <div class="admin-story-publish-card">
            <h2>Pengaturan Bab</h2>

            <div class="admin-form-field">
                <label for="sort_order">
                    Urutan Bab
                </label>

                <input id="sort_order" name="sort_order" type="number" min="1"
                    value="{{ old('sort_order', $chapter->sort_order ?? ($nextOrder ?? 1)) }}"
                    required>

                @error('sort_order')
                    <small class="admin-form-error">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="admin-form-field">
                <label for="map_zoom">
                    Zoom Peta
                </label>

                <input id="map_zoom" name="map_zoom" type="number" min="1" max="20"
                    value="{{ old('map_zoom', $chapter->map_zoom ?? 16) }}"
                    required>

                @error('map_zoom')
                    <small class="admin-form-error">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <label class="admin-form-checkbox">
                <input name="is_published" type="checkbox" value="1" @checked(old('is_published', $chapter->is_published ?? true))>

                <span>
                    Tampilkan bab pada halaman publik
                </span>
            </label>
        </div>
    </aside>
</div>

<footer class="admin-story-form-actions">
    <a href="{{ route('admin.stories.index') }}" class="button button-secondary">
        Batal
    </a>

    <button type="submit" class="button button-primary">
        {{ $submitLabel }}
    </button>
</footer>
