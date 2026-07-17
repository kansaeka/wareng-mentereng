@if ($errors->any())
    <div class="admin-alert admin-alert-error" role="alert">
        <strong>Data belum dapat disimpan.</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="admin-form-group">
    <label for="facility_category_id">
        Kategori Fasilitas
    </label>

    <select id="facility_category_id" name="facility_category_id" required>
        <option value="">
            Pilih kategori
        </option>

        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) old('facility_category_id', $facility->facility_category_id) === (string) $category->id)>
                {{ $category->name }}

                @unless ($category->is_active)
                    — Nonaktif
                @endunless
            </option>
        @endforeach
    </select>
</div>

<div class="admin-form-group">
    <label for="name">
        Nama Fasilitas
    </label>

    <input id="name" name="name" type="text" value="{{ old('name', $facility->name) }}" maxlength="150"
        required autofocus>

    <small>
        Slug akan dibuat otomatis dari nama fasilitas.
    </small>
</div>

<div class="admin-form-group">
    <label for="description">
        Deskripsi
    </label>

    <textarea id="description" name="description" rows="4">{{ old('description', $facility->description) }}</textarea>
</div>

<div class="admin-form-group">
    <label for="address">
        Alamat
    </label>

    <textarea id="address" name="address" rows="3">{{ old('address', $facility->address) }}</textarea>
</div>

<div class="admin-form-group">
    <label for="photo">
        Foto Utama Fasilitas
    </label>

    <input id="photo" name="photo" type="file" accept=".jpg,.jpeg,.png,.webp">

    <small>
        Format JPG, JPEG, PNG, atau WebP.
        Ukuran maksimal 3 MB.
    </small>
</div>

@if ($facility->photo_path)
    <div class="admin-current-photo">
        <p>Foto saat ini</p>

        <img src="{{ asset('storage/' . $facility->photo_path) }}" alt="Foto {{ $facility->name }}">

        <label class="admin-checkbox-option">
            <input type="checkbox" name="remove_photo" value="1">

            <span>
                Hapus foto saat ini
            </span>
        </label>
    </div>
@endif

<div class="admin-form-row">
    <div class="admin-form-group">
        <label for="latitude">
            Latitude
        </label>

        <input id="latitude" name="latitude" type="number" step="any"
            value="{{ old('latitude', $facility->latitude ?? '') }}" placeholder="-7.571670" min="-90"
            max="90" required>
    </div>

    <div class="admin-form-group">
        <label for="longitude">
            Longitude
        </label>

        <input id="longitude" name="longitude" type="number" step="any"
            value="{{ old('longitude', $facility->longitude ?? '') }}" placeholder="110.186390" min="-180"
            max="180" required>
    </div>
</div>

<div class="admin-coordinate-map-section">
    <div class="admin-coordinate-map-heading">
        <div>
            <h3>Pilih Lokasi pada Peta</h3>

            <p>
                Klik lokasi pada peta atau geser marker
                untuk mengisi latitude dan longitude.
            </p>
        </div>

        <div class="admin-coordinate-map-actions">
            <button id="sync-coordinate-map-button" type="button" class="admin-secondary-button">
                Tampilkan Koordinat Input
            </button>

            <button id="reset-coordinate-map-button" type="button" class="admin-secondary-button">
                Pusatkan ke Wareng
            </button>
        </div>
    </div>

    <div id="admin-facility-map" class="admin-facility-map" data-default-latitude="-7.57167"
        data-default-longitude="110.18639" aria-label="Peta pemilih lokasi fasilitas"></div>

    <p id="admin-coordinate-map-status" class="admin-coordinate-map-status" aria-live="polite">
        Klik salah satu lokasi pada peta untuk memilih koordinat.
    </p>
</div>

<p class="admin-coordinate-note">
    Kamu dapat memilih lokasi melalui peta atau mengisi
    koordinat secara manual. Format form menggunakan
    latitude terlebih dahulu, kemudian longitude.
    Sistem akan menyimpannya sebagai Point dengan SRID 4326.
</p>

<div class="admin-form-group">
    <label for="source">
        Sumber Data
    </label>

    <input id="source" name="source" type="text" value="{{ old('source', $facility->source) }}" maxlength="150"
        placeholder="Contoh: Survei lapangan 2026">
</div>

<div class="admin-form-group">
    <label for="verification_status">
        Status Verifikasi
    </label>

    <select id="verification_status" name="verification_status" required>
        <option value="unverified" @selected(old('verification_status', $facility->verification_status ?? 'unverified') === 'unverified')>
            Belum Diverifikasi
        </option>

        <option value="verified" @selected(old('verification_status', $facility->verification_status ?? 'unverified') === 'verified')>
            Terverifikasi
        </option>
    </select>
</div>

<label class="admin-checkbox-option">
    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $facility->exists ? $facility->is_published : true))>

    <span>
        Publikasikan fasilitas pada peta publik
    </span>
</label>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="{{ asset('js/admin-facility-map.js') }}"></script>
@endpush
