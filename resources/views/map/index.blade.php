@extends('layouts.app')

@section('title', 'Peta Interaktif | WebGIS Dusun Wareng')

@section('content')
    <section class="map-page-hero">
        <div class="container">
            <p class="section-label">WEBGIS DUSUN WARENG</p>

            <h1>Peta Interaktif</h1>

            <p>
                Jelajahi lokasi fasilitas, potensi, kegiatan ekonomi,
                pertanian, dan informasi geografis Dusun Wareng.
            </p>
        </div>
    </section>

    <section class="map-page-section">
        <div class="container map-page-layout">
            <aside class="map-page-sidebar">
                <div class="map-sidebar-section">
                    <div class="map-search-section">
                        <label for="map-location-search" class="map-search-label">
                            Cari Lokasi
                        </label>

                        <div class="map-search-wrapper">
                            <input id="map-location-search" class="map-search-input" type="search"
                                placeholder="Nama, kategori, atau alamat..." autocomplete="off">

                            <button id="clear-map-search" class="map-search-clear" type="button"
                                aria-label="Hapus pencarian" title="Hapus pencarian">
                                ×
                            </button>
                        </div>

                        <p class="map-search-help">
                            Pencarian dapat dilakukan berdasarkan nama,
                            kategori, deskripsi, dan alamat objek.
                        </p>
                    </div>

                    <div class="map-filter-section">
                        <p class="section-label">FILTER DATA</p>

                        <h2>Pilih Kategori</h2>

                        <p class="map-sidebar-description">
                            Centang kategori yang ingin ditampilkan
                            pada peta dan daftar lokasi.
                        </p>

                        <div id="map-page-filters" class="filter-list">
                            <div id="map-page-filters" class="filter-list">
                                <p class="facility-filter-loading">
                                    Memuat kategori...
                                </p>
                            </div>
                        </div>

                        <button id="map-page-show-all" class="filter-reset-button" type="button">
                            Tampilkan Semua
                        </button>

                        <div class="map-object-count">
                            <strong id="map-page-visible-count">
                                0
                            </strong>

                            <span>objek ditampilkan</span>
                        </div>

                        <p id="map-page-data-status" class="map-data-status" aria-live="polite">
                            Memuat data peta...
                        </p>

                        <div class="map-tools-panel">
                            <p class="section-label">
                                ALAT PETA
                            </p>

                            <h3>Pengukuran dan Lokasi</h3>

                            <p class="map-tools-description">
                                Gunakan alat berikut untuk mengukur jarak,
                                menghitung luas, atau melihat lokasi perangkat.
                            </p>

                            <div class="map-tools-grid">
                                <button id="measure-distance-button" class="map-tool-button" type="button">
                                    Ukur Jarak
                                </button>

                                <button id="measure-area-button" class="map-tool-button" type="button">
                                    Ukur Luas
                                </button>

                                <button id="find-user-location-button" class="map-tool-button" type="button">
                                    Lokasi Saya
                                </button>

                                <button id="clear-measurements-button" class="map-tool-button map-tool-button-danger"
                                    type="button">
                                    Hapus Hasil
                                </button>
                            </div>

                            <div id="measurement-result" class="measurement-result" aria-live="polite">
                                <strong>Belum ada pengukuran</strong>

                                <span>
                                    Pilih alat ukur, lalu gambar pada peta.
                                </span>
                            </div>

                            <p id="geolocation-status" class="geolocation-status" aria-live="polite">
                                Lokasi perangkat belum diminta.
                            </p>

                            <p class="map-tools-note">
                                Lokasi perangkat hanya ditampilkan pada browser
                                dan tidak disimpan ke database.
                            </p>
                        </div>

                        <div class="coordinate-tools-panel">
                            <p class="section-label">
                                KOORDINAT PETA
                            </p>

                            <h3>Pilih Titik Koordinat</h3>

                            <p class="coordinate-tools-description">
                                Gerakkan kursor untuk melihat koordinat atau
                                aktifkan mode pilih titik untuk menandai lokasi.
                            </p>

                            <div class="coordinate-information">
                                <div class="coordinate-information-row">
                                    <span>Posisi kursor</span>

                                    <strong id="cursor-coordinate">
                                        Belum tersedia
                                    </strong>
                                </div>

                                <div class="coordinate-information-row">
                                    <span>Titik terpilih</span>

                                    <strong id="selected-coordinate">
                                        Belum ada titik dipilih
                                    </strong>
                                </div>
                            </div>

                            <div class="coordinate-tools-grid">
                                <button id="select-coordinate-button" class="map-tool-button" type="button">
                                    Pilih Titik
                                </button>

                                <button id="copy-coordinate-button" class="map-tool-button" type="button" disabled>
                                    Salin Koordinat
                                </button>

                                <button id="clear-coordinate-button"
                                    class="map-tool-button map-tool-button-danger coordinate-clear-button" type="button"
                                    disabled>
                                    Hapus Titik
                                </button>
                            </div>

                            <p id="coordinate-status" class="coordinate-status" aria-live="polite">
                                Belum ada titik koordinat yang dipilih.
                            </p>

                            <p class="coordinate-tools-note">
                                Koordinat ditampilkan dalam format latitude,
                                longitude dengan enam angka desimal.
                            </p>
                        </div>

                        <div class="basemap-information">
                            <p class="section-label">
                                PETA DASAR
                            </p>

                            <p>
                                Gunakan kontrol layer di kanan atas peta
                                untuk memilih tampilan jalan, topografi,
                                atau Rupabumi Indonesia.
                            </p>
                        </div>

                        <div class="geometry-legend">
                            <p class="section-label">
                                LAYER WILAYAH
                            </p>

                            <div class="geometry-legend-item">
                                <span class="boundary-legend-symbol"></span>
                                <span>Batas Dusun</span>
                            </div>

                            <div class="geometry-legend-item">
                                <span class="main-road-legend-symbol"></span>
                                <span>Jalan Utama</span>
                            </div>

                            <div class="geometry-legend-item">
                                <span class="local-road-legend-symbol"></span>
                                <span>Jalan Lingkungan</span>
                            </div>

                            <p class="geometry-legend-note">
                                Bentuk batas dan jaringan jalan masih berupa
                                data simulasi, bukan hasil pemetaan resmi.
                            </p>
                        </div>

                        <div class="land-use-legend-panel">
                            <p class="section-label">
                                PENGGUNAAN LAHAN
                            </p>

                            <div id="land-use-legend" class="land-use-legend">
                                <p class="land-use-legend-loading">
                                    Memuat legenda...
                                </p>
                            </div>

                            <p class="land-use-legend-note">
                                Kategori dan bentuk penggunaan lahan masih
                                berupa data simulasi dan harus diverifikasi.
                            </p>
                        </div>
                    </div>

                    <div class="location-list-section">
                        <div class="location-list-heading">
                            <p class="section-label">DAFTAR LOKASI</p>

                            <h3>Objek pada Peta</h3>
                        </div>

                        <div id="map-location-list" class="location-list" aria-live="polite">
                            <p class="location-list-empty">
                                Memuat daftar lokasi...
                            </p>
                        </div>
                    </div>
                </div>

                <div class="selected-feature-panel">
                    <p class="section-label">INFORMASI OBJEK</p>

                    <section id="map-route-panel" class="map-route-panel" aria-labelledby="map-route-title">
                        <header class="map-route-panel-header">
                            <div>
                                <p class="section-label">
                                    NAVIGASI RUTE
                                </p>

                                <h3 id="map-route-title">
                                    Rute Menuju Lokasi
                                </h3>
                            </div>

                            <span id="map-route-status-badge" class="map-route-status-badge">
                                Belum aktif
                            </span>
                        </header>

                        <p id="map-route-message" class="map-route-message" aria-live="polite">
                            Pilih salah satu fasilitas pada peta untuk
                            melihat pilihan rute dari posisi Anda.
                        </p>

                        <div id="map-route-destination" class="map-route-destination" hidden>
                            <span>Tujuan</span>

                            <strong id="map-route-destination-name">
                                Belum dipilih
                            </strong>
                        </div>

                        <div id="map-route-summary" class="map-route-summary" hidden>
                            <div class="map-route-summary-item">
                                <span>Jarak</span>

                                <strong id="map-route-distance">
                                    —
                                </strong>
                            </div>

                            <div id="map-route-alternatives" class="map-route-alternatives" hidden>
                                <div class="map-route-alternatives-header">
                                    <div>
                                        <span>
                                            OPSI RUTE
                                        </span>

                                        <strong>
                                            Pilih Jalur Perjalanan
                                        </strong>
                                    </div>

                                    <small id="map-route-alternative-count">
                                        0 rute
                                    </small>
                                </div>

                                <div id="map-route-alternative-list" class="map-route-alternative-list"></div>
                            </div>

                            <div id="map-route-directions" class="map-route-directions" hidden>
                                <div class="map-route-directions-header">
                                    <div>
                                        <span class="map-route-directions-label">
                                            PETUNJUK PERJALANAN
                                        </span>

                                        <strong>Langkah dan Belokan</strong>
                                    </div>

                                    <span id="map-route-step-count">
                                        0 langkah
                                    </span>
                                </div>

                                <ol id="map-route-step-list" class="map-route-step-list"
                                    aria-label="Daftar petunjuk perjalanan"></ol>
                            </div>

                            <div class="map-route-summary-item">
                                <span>Estimasi waktu</span>

                                <strong id="map-route-duration">
                                    —
                                </strong>
                            </div>

                            <div class="map-route-summary-item">
                                <span>Jarak ke tujuan</span>

                                <strong id="map-route-remaining-distance">
                                    —
                                </strong>
                            </div>
                        </div>

                        <div class="map-route-actions">
                            <button id="map-route-start-button" type="button" class="button button-primary" disabled>
                                Rute ke Lokasi
                            </button>

                            <button id="map-route-clear-button" type="button" class="button map-route-clear-button"
                                hidden>
                                Hapus Rute
                            </button>

                            <button id="map-route-navigation-start-button" type="button"
                                class="button map-route-navigation-start-button" hidden>
                                Mulai Navigasi
                            </button>

                            <button id="map-route-navigation-stop-button" type="button"
                                class="button map-route-navigation-stop-button" hidden>
                                Hentikan Navigasi
                            </button>
                        </div>

                        <p class="map-route-note">
                            Lokasi perangkat hanya digunakan untuk menghitung
                            perjalanan dan tidak disimpan oleh aplikasi.
                        </p>
                    </section>

                    <h3 id="selected-feature-title">
                        Belum ada objek dipilih
                    </h3>

                    <span id="selected-feature-category" class="selected-feature-category">
                        Pilih salah satu titik pada peta
                    </span>

                    <p id="selected-feature-description">
                        Informasi objek akan ditampilkan setelah
                        kamu mengeklik salah satu simbol pada peta.
                    </p>

                    <p id="selected-feature-address" class="selected-feature-address"></p>
                </div>
            </aside>

            <div class="map-page-content">
                <div id="main-wareng-map" class="main-map" aria-label="Peta utama Dusun Wareng"></div>

                <p class="map-note">
                    Data yang ditampilkan masih berupa data contoh.
                    Koordinat dan atribut akan diperbarui setelah
                    proses verifikasi lapangan.
                </p>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7/turf.min.js"></script>

    <script src="{{ asset('js/map-page.js') }}"></script>
@endpush
