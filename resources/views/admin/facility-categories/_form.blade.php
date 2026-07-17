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
    <label for="name">
        Nama Kategori
    </label>

    <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" maxlength="100" required
        autofocus>

    <small>
        Slug kategori akan dibuat otomatis
        berdasarkan nama.
    </small>
</div>

<div class="admin-form-row">
    <div class="admin-form-group">
        <label for="marker_color">
            Warna Marker
        </label>

        <input id="marker_color" name="marker_color" type="color"
            value="{{ old('marker_color', $category->marker_color ?: '#66746b') }}"
            required>

        <small>
            Warna ini digunakan pada marker,
            legenda, dan filter peta.
        </small>
    </div>

    <div class="admin-form-group">
        <label for="sort_order">
            Urutan Tampilan
        </label>

        <input id="sort_order" name="sort_order" type="number"
            value="{{ old('sort_order', $category->sort_order ?? 0) }}"
            min="0" max="9999" required>

        <small>
            Nilai lebih kecil akan ditampilkan
            lebih dahulu.
        </small>
    </div>
</div>

<label class="admin-checkbox-option">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true))>

    <span>
        Kategori aktif dan ditampilkan
        pada peta publik
    </span>
</label>
