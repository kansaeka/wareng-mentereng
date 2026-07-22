@extends('layouts.app')

@section('title', 'Peta Interaktif | WebGIS Dusun Wareng')

@section('content')
    <section class="map-page-hero">
        <video class="map-page-hero-video" autoplay muted loop playsinline preload="metadata"
            poster="{{ asset('images/wareng/peta-interaktif-drone.jpg') }}" aria-hidden="true">
            <source src="{{ asset('videos/wareng/peta-interaktif-drone.mp4') }}" type="video/mp4">
        </video>

        <div class="map-page-hero-overlay" aria-hidden="true"></div>

        <div class="map-page-hero-pattern" aria-hidden="true"></div>

        <div class="container map-page-hero-content">
            <div class="map-page-hero-copy">
                <p class="map-page-hero-label">
                    WEBGIS DUSUN WARENG
                </p>

                <h1>
                    Peta Interaktif
                    <span>Dusun Wareng</span>
                </h1>

                <p>
                    Jelajahi fasilitas, potensi wilayah, penggunaan
                    lahan, jaringan jalan, serta navigasi menuju
                    berbagai lokasi di Dusun Wareng.
                </p>

                <div class="map-page-hero-actions">
                    <a href="#map-workspace" class="map-page-hero-button map-page-hero-button-primary">
                        Mulai Eksplorasi

                        <span aria-hidden="true">↓</span>
                    </a>

                    <a href="{{ route('maps.gallery') }}" class="map-page-hero-button map-page-hero-button-secondary">
                        Galeri Peta

                        <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </div>

            <aside class="map-page-hero-summary">
                <p>FITUR UTAMA</p>

                <div class="map-page-hero-feature">
                    <span aria-hidden="true">01</span>

                    <div>
                        <strong>Eksplorasi Wilayah</strong>

                        <small>
                            Telusuri fasilitas dan informasi spasial.
                        </small>
                    </div>
                </div>

                <div class="map-page-hero-feature">
                    <span aria-hidden="true">02</span>

                    <div>
                        <strong>Pengukuran Peta</strong>

                        <small>
                            Mengukur jarak, luas, dan koordinat.
                        </small>
                    </div>
                </div>

                <div class="map-page-hero-feature">
                    <span aria-hidden="true">03</span>

                    <div>
                        <strong>Navigasi Lokasi</strong>

                        <small>
                            Menampilkan rute menuju fasilitas.
                        </small>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <section id="map-workspace" class="map-page-section">
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
                            <p class="facility-filter-loading">
                                Memuat kategori...
                            </p>
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

                        {{-- ==================================================
     CHECKPOINT 35A — PANEL PETA TEMATIK
================================================== --}}
                        <section class="thematic-map-panel" aria-labelledby="thematic-map-title">
                            <header class="thematic-map-header">
                                <div>
                                    <p class="section-label">
                                        PETA TEMATIK
                                    </p>

                                    <h3 id="thematic-map-title">
                                        Jelajahi Data Wilayah
                                    </h3>
                                </div>

                                <span id="thematic-map-status" class="thematic-map-status is-idle">
                                    Belum aktif
                                </span>
                            </header>

                            <p class="thematic-map-description">
                                Pilih tema untuk melihat keterangan,
                                kesiapan data, dan legenda yang nantinya
                                digunakan pada peta.
                            </p>

                            <div class="thematic-map-options" role="list" aria-label="Pilihan peta tematik">
                                <button type="button" class="thematic-map-option" data-thematic-option
                                    data-theme="administrasi-rt" aria-pressed="false">
                                    <span class="thematic-map-option-number">
                                        01
                                    </span>

                                    <span class="thematic-map-option-copy">
                                        <strong>Administrasi RT</strong>

                                        <small>
                                            Menunggu poligon bangunan
                                        </small>
                                    </span>
                                </button>

                                <button type="button" class="thematic-map-option" data-thematic-option
                                    data-theme="kepadatan-penduduk" aria-pressed="false">
                                    <span class="thematic-map-option-number">
                                        02
                                    </span>

                                    <span class="thematic-map-option-copy">
                                        <strong>Kepadatan Penduduk</strong>

                                        <small>
                                            Menunggu data agregat
                                        </small>
                                    </span>
                                </button>

                                <button type="button" class="thematic-map-option" data-thematic-option
                                    data-theme="kelayakan-hunian" aria-pressed="false">
                                    <span class="thematic-map-option-number">
                                        03
                                    </span>

                                    <span class="thematic-map-option-copy">
                                        <strong>Kelayakan Hunian</strong>

                                        <small>
                                            Menunggu atribut bangunan
                                        </small>
                                    </span>
                                </button>

                                <button type="button" class="thematic-map-option" data-thematic-option
                                    data-theme="daya-listrik" aria-pressed="false">
                                    <span class="thematic-map-option-number">
                                        04
                                    </span>

                                    <span class="thematic-map-option-copy">
                                        <strong>Daya Listrik</strong>

                                        <small>
                                            Menunggu data terverifikasi
                                        </small>
                                    </span>
                                </button>

                                <button type="button" class="thematic-map-option" data-thematic-option
                                    data-theme="mata-pencaharian" aria-pressed="false">
                                    <span class="thematic-map-option-number">
                                        05
                                    </span>

                                    <span class="thematic-map-option-copy">
                                        <strong>Mata Pencaharian</strong>

                                        <small>
                                            Menunggu data agregat RT
                                        </small>
                                    </span>
                                </button>

                                <button type="button" class="thematic-map-option" data-thematic-option
                                    data-theme="persebaran-umkm" aria-pressed="false">
                                    <span class="thematic-map-option-number">
                                        06
                                    </span>

                                    <span class="thematic-map-option-copy">
                                        <strong>Persebaran UMKM</strong>

                                        <small class="is-ready">
                                            Titik usaha tersedia
                                        </small>
                                    </span>
                                </button>

                                <button type="button" class="thematic-map-option" data-thematic-option
                                    data-theme="jangkauan-internet" aria-pressed="false">
                                    <span class="thematic-map-option-number">
                                        07
                                    </span>

                                    <span class="thematic-map-option-copy">
                                        <strong>Jangkauan Internet</strong>

                                        <small>
                                            Menunggu data cakupan
                                        </small>
                                    </span>
                                </button>
                            </div>

                            <div id="thematic-map-detail" class="thematic-map-detail" aria-live="polite">
                                <span class="thematic-map-detail-label">
                                    TEMA TERPILIH
                                </span>

                                <h4 id="thematic-map-active-title">
                                    Belum ada tema aktif
                                </h4>

                                <p id="thematic-map-active-description">
                                    Pilih salah satu tema di atas untuk
                                    melihat informasi dan status datanya.
                                </p>

                                <div id="thematic-map-legend" class="thematic-map-legend">
                                    <div class="thematic-map-empty-legend">
                                        Legenda akan muncul setelah
                                        tema dipilih.
                                    </div>
                                </div>
                            </div>

                            <button id="thematic-map-clear-button" class="thematic-map-clear-button" type="button"
                                disabled>
                                Nonaktifkan Tema
                            </button>

                            <p class="thematic-map-note">
                                Panel ini merupakan struktur awal.
                                Layer tematik akan dihubungkan setelah
                                data spasial tersedia dan diverifikasi.
                            </p>
                        </section>

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
                <header class="map-workspace-toolbar">
                    <div>
                        <p>PETA DIGITAL</p>

                        <h2>Eksplorasi Dusun Wareng</h2>
                    </div>

                    <div class="map-workspace-status">
                        <span aria-hidden="true"></span>

                        <strong>WebGIS aktif</strong>
                    </div>
                </header>

                <div class="map-canvas-shell">
                    <div id="main-wareng-map" class="main-map" aria-label="Peta utama Dusun Wareng"></div>
                </div>

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
    <style>
        /* ==================================================
                           HERO PETA INTERAKTIF
                        ================================================== */

        .map-page-hero {
            position: relative;
            isolation: isolate;
            min-height: clamp(620px, 84vh, 860px);
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            padding:
                clamp(8rem, 13vw, 11rem) 0 clamp(4.5rem, 8vw, 7rem);
            background: #0b2f20;
            color: #ffffff;
        }

        .map-page-hero-video {
            position: absolute;
            z-index: 0;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 48%;
            filter: brightness(1.06) saturate(1.05);
            pointer-events: none;
        }

        .map-page-hero-overlay {
            position: absolute;
            z-index: 1;
            inset: 0;
            background:
                linear-gradient(90deg,
                    rgba(4, 25, 15, 0.76) 0%,
                    rgba(8, 39, 25, 0.52) 48%,
                    rgba(9, 44, 28, 0.17) 100%),
                linear-gradient(180deg,
                    rgba(4, 23, 14, 0.1) 0%,
                    rgba(4, 23, 14, 0.12) 58%,
                    rgba(4, 23, 14, 0.62) 100%);
            pointer-events: none;
        }

        .map-page-hero-pattern {
            position: absolute;
            z-index: 2;
            inset: 0;
            opacity: 0.09;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.08) 1px,
                    transparent 1px),
                linear-gradient(90deg,
                    rgba(255, 255, 255, 0.08) 1px,
                    transparent 1px);
            background-size: 54px 54px;
            pointer-events: none;
        }

        .map-page-hero-content {
            position: relative;
            z-index: 3;
            display: grid;
            width: min(1180px, calc(100% - 2rem));
            grid-template-columns:
                minmax(0, 1.25fr) minmax(280px, 0.75fr);
            gap: clamp(3rem, 8vw, 7rem);
            align-items: end;
            margin-inline: auto;
        }

        .map-page-hero-copy {
            max-width: 780px;
        }

        .map-page-hero-label {
            margin: 0 0 1rem;
            color: #edc477;
            font-size: 0.7rem;
            font-weight: 900;
            letter-spacing: 0.16em;
        }

        .map-page-hero h1 {
            max-width: 780px;
            margin: 0;
            color: #ffffff;
            font-size: clamp(3.5rem, 7.5vw, 7rem);
            line-height: 0.9;
            letter-spacing: -0.065em;
        }

        .map-page-hero h1 span {
            display: block;
            color: rgba(255, 255, 255, 0.58);
        }

        .map-page-hero-copy>p:not(.map-page-hero-label) {
            max-width: 680px;
            margin: 1.5rem 0 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: clamp(0.9rem, 1.4vw, 1.05rem);
            line-height: 1.8;
        }

        .map-page-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.8rem;
        }

        .map-page-hero-button {
            display: inline-flex;
            min-height: 48px;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            padding: 0.7rem 1.1rem;
            border-radius: 14px;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 850;
            text-decoration: none;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition:
                transform 180ms ease,
                background 180ms ease;
        }

        .map-page-hero-button-primary {
            border: 1px solid rgba(255, 255, 255, 0.16);
            background:
                linear-gradient(135deg,
                    rgba(44, 130, 86, 0.94),
                    rgba(18, 81, 51, 0.96));
        }

        .map-page-hero-button-secondary {
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.09);
        }

        .map-page-hero-button:hover {
            color: #ffffff;
            transform: translateY(-2px);
        }

        .map-page-hero-summary {
            overflow: hidden;
            padding: 1.15rem;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 22px;
            background: rgba(7, 32, 21, 0.32);
            box-shadow: 0 22px 60px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(20px) saturate(135%);
            -webkit-backdrop-filter: blur(20px) saturate(135%);
        }

        .map-page-hero-summary>p {
            margin: 0 0 0.8rem;
            color: rgba(255, 255, 255, 0.47);
            font-size: 0.58rem;
            font-weight: 900;
            letter-spacing: 0.14em;
        }

        .map-page-hero-feature {
            display: grid;
            grid-template-columns: 40px minmax(0, 1fr);
            gap: 0.8rem;
            align-items: center;
            padding: 0.85rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.11);
        }

        .map-page-hero-feature>span {
            display: inline-flex;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(237, 196, 119, 0.12);
            color: #edc477;
            font-size: 0.62rem;
            font-weight: 900;
        }

        .map-page-hero-feature div {
            display: grid;
            gap: 0.08rem;
        }

        .map-page-hero-feature strong {
            color: #ffffff;
            font-size: 0.78rem;
        }

        .map-page-hero-feature small {
            color: rgba(255, 255, 255, 0.54);
            font-size: 0.63rem;
            line-height: 1.45;
        }


        /* ==================================================
                           WORKSPACE PETA
                        ================================================== */

        .map-page-section {
            padding: clamp(3.5rem, 7vw, 6rem) 0;
            scroll-margin-top: 90px;
            background:
                radial-gradient(circle at 8% 10%,
                    rgba(49, 119, 78, 0.07),
                    transparent 28%),
                linear-gradient(180deg,
                    #eef4f0 0%,
                    #f9fbf9 100%);
        }

        .map-page-layout {
            display: grid;
            width: min(1380px, calc(100% - 2rem));
            grid-template-columns:
                minmax(330px, 390px) minmax(0, 1fr);
            gap: 1.25rem;
            align-items: start;
            margin-inline: auto;
        }

        .map-page-sidebar {
            display: grid;
            max-height: calc(100vh - 100px);
            gap: 1rem;
            overflow-y: auto;
            position: sticky;
            top: 82px;
            padding-right: 0.3rem;
            scrollbar-width: thin;
            scrollbar-color:
                rgba(40, 108, 73, 0.3) transparent;
        }

        .map-sidebar-section {
            display: grid;
            gap: 1rem;
        }

        .map-search-section,
        .map-filter-section,
        .location-list-section,
        .selected-feature-panel {
            padding: 1.15rem;
            border: 1px solid rgba(38, 91, 63, 0.13);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow:
                0 16px 42px rgba(20, 62, 40, 0.07);
        }

        .map-filter-section {
            display: grid;
            gap: 1rem;
        }

        .map-page-sidebar .section-label {
            margin: 0 0 0.45rem;
            color: #b17d2d;
            font-size: 0.57rem;
            font-weight: 900;
            letter-spacing: 0.13em;
        }

        .map-page-sidebar h2,
        .map-page-sidebar h3 {
            margin: 0;
            color: #173f2c;
            letter-spacing: -0.025em;
        }

        .map-page-sidebar h2 {
            font-size: 1.2rem;
        }

        .map-page-sidebar h3 {
            font-size: 0.96rem;
        }

        .map-sidebar-description,
        .map-tools-description,
        .coordinate-tools-description,
        .map-tools-note,
        .coordinate-tools-note,
        .geometry-legend-note,
        .land-use-legend-note,
        .map-route-note {
            color: #748178;
            font-size: 0.67rem;
            line-height: 1.65;
        }


        /* PENCARIAN */

        .map-search-label {
            display: block;
            margin-bottom: 0.55rem;
            color: #214c35;
            font-size: 0.72rem;
            font-weight: 850;
        }

        .map-search-wrapper {
            position: relative;
        }

        .map-search-input {
            width: 100%;
            min-height: 45px;
            padding: 0.7rem 2.7rem 0.7rem 0.85rem;
            border: 1px solid rgba(38, 91, 63, 0.15);
            border-radius: 13px;
            outline: none;
            background: #f7faf8;
            color: #173f2c;
            font-size: 0.72rem;
        }

        .map-search-input:focus {
            border-color: rgba(40, 116, 76, 0.46);
            box-shadow: 0 0 0 4px rgba(40, 116, 76, 0.09);
        }

        .map-search-clear {
            position: absolute;
            top: 50%;
            right: 0.55rem;
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #68786f;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .map-search-clear:hover {
            background: #e9f1eb;
            color: #246946;
        }

        .map-search-help {
            margin: 0.55rem 0 0;
            color: #849087;
            font-size: 0.62rem;
            line-height: 1.55;
        }


        /* FILTER */

        .filter-list {
            display: grid;
            gap: 0.5rem;
        }

        .filter-list label,
        .filter-list>div:not(.facility-filter-loading) {
            border-radius: 11px;
        }

        .filter-reset-button {
            min-height: 39px;
            border: 1px solid rgba(38, 91, 63, 0.15);
            border-radius: 11px;
            background: #eff5f1;
            color: #286c49;
            cursor: pointer;
            font-size: 0.68rem;
            font-weight: 850;
        }

        .filter-reset-button:hover {
            background: #e4eee7;
        }

        .map-object-count {
            display: flex;
            align-items: baseline;
            gap: 0.4rem;
            padding: 0.8rem;
            border-radius: 12px;
            background:
                linear-gradient(135deg,
                    #173f2c,
                    #246747);
            color: #ffffff;
        }

        .map-object-count strong {
            font-size: 1.25rem;
        }

        .map-object-count span {
            color: rgba(255, 255, 255, 0.68);
            font-size: 0.63rem;
        }

        .map-data-status {
            margin: 0;
            color: #718078;
            font-size: 0.64rem;
        }


        /* PANEL ALAT */

        .map-tools-panel,
        .coordinate-tools-panel,
        .basemap-information,
        .geometry-legend,
        .land-use-legend-panel,
        .map-route-panel {
            padding: 1rem;
            border: 1px solid rgba(38, 91, 63, 0.12);
            border-radius: 16px;
            background: #f7faf8;
        }

        .map-tools-grid,
        .coordinate-tools-grid {
            display: grid;
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
            gap: 0.55rem;
            margin-top: 0.85rem;
        }

        .map-tool-button {
            min-height: 38px;
            padding: 0.55rem;
            border: 1px solid rgba(38, 91, 63, 0.15);
            border-radius: 10px;
            background: #ffffff;
            color: #286c49;
            cursor: pointer;
            font-size: 0.64rem;
            font-weight: 800;
        }

        .map-tool-button:hover:not(:disabled) {
            border-color: rgba(38, 91, 63, 0.3);
            background: #eaf2ec;
        }

        .map-tool-button:disabled {
            opacity: 0.48;
            cursor: not-allowed;
        }

        .map-tool-button-danger {
            color: #9a433c;
        }

        .measurement-result,
        .coordinate-information {
            display: grid;
            gap: 0.45rem;
            margin-top: 0.85rem;
            padding: 0.75rem;
            border-radius: 11px;
            background: #eaf2ec;
        }

        .measurement-result strong,
        .coordinate-information strong {
            color: #214e36;
            font-size: 0.68rem;
        }

        .measurement-result span,
        .coordinate-information-row>span {
            color: #748178;
            font-size: 0.61rem;
        }

        .coordinate-information-row {
            display: grid;
            gap: 0.1rem;
        }

        .geolocation-status,
        .coordinate-status {
            color: #65766b;
            font-size: 0.64rem;
        }


        /* LEGENDA DAN DAFTAR */

        .geometry-legend-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-top: 0.55rem;
            color: #52645a;
            font-size: 0.67rem;
        }

        .location-list {
            display: grid;
            max-height: 300px;
            gap: 0.55rem;
            overflow-y: auto;
            margin-top: 0.8rem;
            padding-right: 0.2rem;
        }

        .location-list>* {
            border-radius: 11px;
        }

        .location-list-empty {
            margin: 0;
            color: #7b897f;
            font-size: 0.68rem;
        }


        /* OBJEK DAN ROUTING */

        .selected-feature-panel {
            display: grid;
            gap: 0.85rem;
        }

        .selected-feature-category {
            width: fit-content;
            padding: 0.35rem 0.6rem;
            border-radius: 999px;
            background: #e7f0e9;
            color: #286c49;
            font-size: 0.61rem;
            font-weight: 850;
        }

        #selected-feature-description,
        .selected-feature-address {
            margin: 0;
            color: #6c7b71;
            font-size: 0.68rem;
            line-height: 1.65;
        }

        .map-route-panel {
            display: grid;
            gap: 0.85rem;
        }

        .map-route-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.7rem;
        }

        .map-route-status-badge {
            padding: 0.35rem 0.55rem;
            border-radius: 999px;
            background: #e8eee9;
            color: #637268;
            font-size: 0.56rem;
            font-weight: 850;
            white-space: nowrap;
        }

        .map-route-message {
            margin: 0;
            color: #6f7e74;
            font-size: 0.66rem;
            line-height: 1.6;
        }

        .map-route-destination,
        .map-route-summary-item {
            display: grid;
            gap: 0.15rem;
            padding: 0.7rem;
            border-radius: 10px;
            background: #eaf2ec;
        }

        .map-route-destination span,
        .map-route-summary-item span {
            color: #75837a;
            font-size: 0.58rem;
        }

        .map-route-destination strong,
        .map-route-summary-item strong {
            color: #214d35;
            font-size: 0.7rem;
        }

        .map-route-actions {
            display: grid;
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .map-route-actions .button {
            min-height: 39px;
            padding: 0.55rem;
            border-radius: 10px;
            font-size: 0.62rem;
        }

        .map-route-alternatives,
        .map-route-directions {
            margin-top: 0.7rem;
            padding: 0.75rem;
            border: 1px solid rgba(38, 91, 63, 0.12);
            border-radius: 11px;
            background: #ffffff;
        }


        /* ==================================================
                           AREA LEAFLET
                        ================================================== */

        .map-page-content {
            position: sticky;
            top: 82px;
            min-width: 0;
            align-self: start;
        }

        .map-workspace-toolbar {
            display: flex;
            min-height: 74px;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 1.1rem;
            border: 1px solid rgba(38, 91, 63, 0.13);
            border-bottom: 0;
            border-radius: 20px 20px 0 0;
            background:
                linear-gradient(135deg,
                    #103b28,
                    #1d5d3e);
            color: #ffffff;
        }

        .map-workspace-toolbar p {
            margin: 0;
            color: #edc477;
            font-size: 0.55rem;
            font-weight: 900;
            letter-spacing: 0.13em;
        }

        .map-workspace-toolbar h2 {
            margin: 0.16rem 0 0;
            color: #ffffff;
            font-size: 1.05rem;
        }

        .map-workspace-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.65rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
        }

        .map-workspace-status span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #70d395;
            box-shadow: 0 0 0 6px rgba(112, 211, 149, 0.12);
        }

        .map-workspace-status strong {
            font-size: 0.62rem;
        }

        .map-canvas-shell {
            overflow: hidden;
            border: 1px solid rgba(38, 91, 63, 0.14);
            border-top: 0;
            border-radius: 0 0 20px 20px;
            background: #dfe8e1;
            box-shadow:
                0 22px 60px rgba(19, 59, 38, 0.12);
        }

        .main-map {
            width: 100%;
            height: calc(100vh - 180px);
            min-height: 620px;
            max-height: 820px;
        }

        .map-note {
            margin: 0.9rem 0 0;
            padding: 0.8rem 1rem;
            border: 1px solid rgba(181, 134, 53, 0.2);
            border-radius: 13px;
            background: #fff9ed;
            color: #756445;
            font-size: 0.66rem;
            line-height: 1.6;
        }


        /* LEAFLET CONTROLS */

        .main-map .leaflet-bar,
        .main-map .leaflet-control-layers {
            overflow: hidden;
            border: 1px solid rgba(23, 72, 46, 0.18);
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(15, 50, 31, 0.14);
        }

        .main-map .leaflet-bar a {
            color: #245f42;
        }

        .main-map .leaflet-popup-content-wrapper {
            border-radius: 15px;
            box-shadow: 0 16px 40px rgba(11, 42, 26, 0.2);
        }


        /* ==================================================
                           RESPONSIVE
                        ================================================== */

        @media (max-width: 1100px) {
            .map-page-hero-content {
                grid-template-columns: 1fr;
            }

            .map-page-hero-summary {
                max-width: 680px;
            }

            .map-page-layout {
                grid-template-columns: 340px minmax(0, 1fr);
            }

            .main-map {
                min-height: 580px;
            }
        }

        @media (max-width: 860px) {
            .map-page-layout {
                grid-template-columns: 1fr;
            }

            .map-page-sidebar,
            .map-page-content {
                position: static;
                max-height: none;
                overflow: visible;
            }

            .map-page-content {
                grid-row: 1;
            }

            .map-page-sidebar {
                grid-row: 2;
            }

            .main-map {
                height: 68vh;
                min-height: 480px;
                max-height: 700px;
            }
        }

        @media (max-width: 720px) {
            .map-page-hero {
                min-height: 720px;
                align-items: center;
                padding: 7.2rem 0 4rem;
            }

            .map-page-hero-content {
                width: calc(100% - 1.5rem);
                gap: 2.4rem;
            }

            .map-page-hero h1 {
                font-size: clamp(2.9rem, 15vw, 4.6rem);
                line-height: 0.96;
            }

            .map-page-hero-copy>p:not(.map-page-hero-label) {
                font-size: 0.85rem;
            }

            .map-page-hero-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .map-page-hero-button {
                width: 100%;
            }

            .map-page-layout {
                width: calc(100% - 1rem);
            }

            .map-workspace-toolbar {
                min-height: 66px;
            }

            .map-workspace-status strong {
                display: none;
            }

            .main-map {
                height: 62vh;
                min-height: 430px;
            }

            .map-tools-grid,
            .coordinate-tools-grid,
            .map-route-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 440px) {
            .map-page-hero-summary {
                padding: 0.9rem;
            }

            .map-search-section,
            .map-filter-section,
            .location-list-section,
            .selected-feature-panel {
                padding: 0.95rem;
                border-radius: 16px;
            }

            .map-workspace-toolbar {
                padding: 0.8rem;
                border-radius: 16px 16px 0 0;
            }

            .map-canvas-shell {
                border-radius: 0 0 16px 16px;
            }

            .main-map {
                min-height: 390px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .map-page-hero-video {
                display: none;
            }

            .map-page-hero {
                background:
                    linear-gradient(90deg,
                        rgba(4, 25, 15, 0.75),
                        rgba(8, 39, 25, 0.42)),
                    url("/images/wareng/peta-interaktif-drone.jpg") center / cover no-repeat;
            }
        }

        /* ==================================================
           CHECKPOINT 35A — PANEL PETA TEMATIK
        ================================================== */

        .thematic-map-panel {
            display: grid;
            gap: 0.9rem;
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid rgba(38, 91, 63, 0.14);
            border-radius: 17px;
            background:
                linear-gradient(145deg,
                    #123c29 0%,
                    #1d5e3e 100%);
            box-shadow:
                0 16px 38px rgba(18, 59, 39, 0.12);
        }

        .thematic-map-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .thematic-map-panel .section-label {
            margin-bottom: 0.35rem;
            color: #edc477;
        }

        .thematic-map-panel h3,
        .thematic-map-panel h4 {
            margin: 0;
            color: #ffffff;
        }

        .thematic-map-panel h3 {
            font-size: 1rem;
        }

        .thematic-map-panel h4 {
            font-size: 0.88rem;
            line-height: 1.4;
        }

        .thematic-map-status {
            display: inline-flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            padding: 0.32rem 0.52rem;
            border-radius: 999px;
            font-size: 0.52rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .thematic-map-status.is-idle {
            background: rgba(255, 255, 255, 0.11);
            color: rgba(255, 255, 255, 0.65);
        }

        .thematic-map-status.is-pending {
            background: rgba(237, 196, 119, 0.15);
            color: #edc477;
        }

        .thematic-map-status.is-partial {
            background: rgba(112, 211, 149, 0.15);
            color: #88dfa7;
        }

        .thematic-map-description {
            margin: 0;
            color: rgba(255, 255, 255, 0.58);
            font-size: 0.65rem;
            line-height: 1.65;
        }


        /* PILIHAN TEMA */

        .thematic-map-options {
            display: grid;
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .thematic-map-option {
            display: grid;
            min-width: 0;
            grid-template-columns: 30px minmax(0, 1fr);
            gap: 0.55rem;
            align-items: center;
            padding: 0.65rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.07);
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            text-align: left;
            transition:
                border-color 180ms ease,
                background 180ms ease,
                transform 180ms ease;
        }

        .thematic-map-option:hover {
            border-color: rgba(237, 196, 119, 0.35);
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
        }

        .thematic-map-option.is-active {
            border-color: rgba(237, 196, 119, 0.58);
            background: rgba(237, 196, 119, 0.14);
            box-shadow:
                inset 3px 0 0 #edc477;
        }

        .thematic-map-option-number {
            display: inline-flex;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.1);
            color: #edc477;
            font-size: 0.52rem;
            font-weight: 900;
        }

        .thematic-map-option-copy {
            display: grid;
            min-width: 0;
            gap: 0.08rem;
        }

        .thematic-map-option-copy strong {
            overflow-wrap: anywhere;
            color: #ffffff;
            font-size: 0.61rem;
            line-height: 1.35;
        }

        .thematic-map-option-copy small {
            color: rgba(255, 255, 255, 0.43);
            font-size: 0.5rem;
            line-height: 1.4;
        }

        .thematic-map-option-copy small.is-ready {
            color: #8bdfa8;
        }


        /* DETAIL TEMA */

        .thematic-map-detail {
            display: grid;
            gap: 0.45rem;
            padding: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 13px;
            background: rgba(3, 25, 15, 0.2);
        }

        .thematic-map-detail-label {
            color: #edc477;
            font-size: 0.5rem;
            font-weight: 900;
            letter-spacing: 0.12em;
        }

        .thematic-map-detail>p {
            margin: 0;
            color: rgba(255, 255, 255, 0.58);
            font-size: 0.61rem;
            line-height: 1.6;
        }

        .thematic-map-legend {
            display: grid;
            gap: 0.4rem;
            padding-top: 0.3rem;
        }

        .thematic-map-legend-item {
            display: grid;
            grid-template-columns: 15px minmax(0, 1fr);
            gap: 0.5rem;
            align-items: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.58rem;
        }

        .thematic-map-legend-symbol {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.7);
            border-radius: 4px;
        }

        .thematic-map-empty-legend {
            padding: 0.65rem;
            border: 1px dashed rgba(255, 255, 255, 0.16);
            border-radius: 9px;
            color: rgba(255, 255, 255, 0.42);
            font-size: 0.56rem;
            line-height: 1.5;
        }


        /* TOMBOL NONAKTIFKAN */

        .thematic-map-clear-button {
            min-height: 38px;
            border: 1px solid rgba(255, 255, 255, 0.17);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 0.61rem;
            font-weight: 850;
        }

        .thematic-map-clear-button:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.14);
        }

        .thematic-map-clear-button:disabled {
            cursor: not-allowed;
            opacity: 0.4;
        }

        .thematic-map-note {
            margin: 0;
            padding-top: 0.7rem;
            border-top: 1px solid rgba(255, 255, 255, 0.11);
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.52rem;
            line-height: 1.55;
        }

        @media (max-width: 420px) {
            .thematic-map-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7/turf.min.js"></script>

    <script src="{{ asset('js/map-page.js') }}"></script>

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {
                const thematicOptions =
                    Array.from(
                        document.querySelectorAll(
                            '[data-thematic-option]'
                        )
                    );

                const statusElement =
                    document.getElementById(
                        'thematic-map-status'
                    );

                const titleElement =
                    document.getElementById(
                        'thematic-map-active-title'
                    );

                const descriptionElement =
                    document.getElementById(
                        'thematic-map-active-description'
                    );

                const legendElement =
                    document.getElementById(
                        'thematic-map-legend'
                    );

                const clearButton =
                    document.getElementById(
                        'thematic-map-clear-button'
                    );

                if (
                    !thematicOptions.length ||
                    !statusElement ||
                    !titleElement ||
                    !descriptionElement ||
                    !legendElement ||
                    !clearButton
                ) {
                    return;
                }

                const themes = {
                    'administrasi-rt': {
                        title: 'Administrasi Bangunan Berdasarkan RT',

                        description: 'Menampilkan pembagian bangunan berdasarkan wilayah RT. Tema ini membutuhkan poligon bangunan dan atribut RT.',

                        status: 'Data dipersiapkan',

                        statusClass: 'is-pending',

                        legend: [{
                            color: '#4d83b8',
                            label: 'Wilayah RT',
                        }, ],
                    },

                    'kepadatan-penduduk': {
                        title: 'Kepadatan Penduduk',

                        description: 'Menampilkan tingkat kepadatan penduduk menggunakan data agregat agar informasi pribadi warga tetap terlindungi.',

                        status: 'Data dipersiapkan',

                        statusClass: 'is-pending',

                        legend: [{
                                color: '#d9e9ce',
                                label: 'Kepadatan rendah',
                            },
                            {
                                color: '#85b96a',
                                label: 'Kepadatan sedang',
                            },
                            {
                                color: '#376f42',
                                label: 'Kepadatan tinggi',
                            },
                        ],
                    },

                    'kelayakan-hunian': {
                        title: 'Kelayakan Hunian',

                        description: 'Menggambarkan kategori kelayakan hunian tanpa menampilkan identitas pemilik maupun penghuni bangunan.',

                        status: 'Data dipersiapkan',

                        statusClass: 'is-pending',

                        legend: [{
                                color: '#57a773',
                                label: 'Layak',
                            },
                            {
                                color: '#e3b453',
                                label: 'Perlu perhatian',
                            },
                            {
                                color: '#c9685b',
                                label: 'Belum memenuhi indikator',
                            },
                        ],
                    },

                    'daya-listrik': {
                        title: 'Persebaran Golongan Daya Listrik',

                        description: 'Menampilkan kategori daya listrik secara spasial tanpa menyertakan nama maupun informasi pelanggan.',

                        status: 'Data dipersiapkan',

                        statusClass: 'is-pending',

                        legend: [{
                                color: '#f0d66a',
                                label: 'Daya rendah',
                            },
                            {
                                color: '#e7a543',
                                label: 'Daya menengah',
                            },
                            {
                                color: '#b96b35',
                                label: 'Daya tinggi',
                            },
                        ],
                    },

                    'mata-pencaharian': {
                        title: 'Mata Pencaharian Penduduk',

                        description: 'Menampilkan komposisi atau kategori mata pencaharian dalam bentuk agregasi wilayah RT.',

                        status: 'Data dipersiapkan',

                        statusClass: 'is-pending',

                        legend: [{
                                color: '#609b63',
                                label: 'Pertanian',
                            },
                            {
                                color: '#4f82ad',
                                label: 'Jasa',
                            },
                            {
                                color: '#b7794a',
                                label: 'Perdagangan dan usaha',
                            },
                        ],
                    },

                    'persebaran-umkm': {
                        title: 'Persebaran Usaha Milik Warga',

                        description: 'Titik UMKM yang telah dimasukkan melalui fasilitas dapat menjadi dasar visualisasi usaha lokal, termasuk UMKM tempe.',

                        status: 'Titik tersedia',

                        statusClass: 'is-partial',

                        legend: [{
                            color: '#c98a3c',
                            label: 'Lokasi UMKM terdata',
                        }, ],
                    },

                    'jangkauan-internet': {
                        title: 'Area Jangkauan Akses Internet',

                        description: 'Menampilkan lokasi BTS, provider, serta perkiraan cakupan jaringan setelah data radius dan kualitas sinyal tersedia.',

                        status: 'Data dipersiapkan',

                        statusClass: 'is-pending',

                        legend: [{
                                color: '#4d84bf',
                                label: 'Jangkauan kuat',
                            },
                            {
                                color: '#79acd0',
                                label: 'Jangkauan sedang',
                            },
                            {
                                color: '#b8d8e8',
                                label: 'Jangkauan terbatas',
                            },
                        ],
                    },
                };

                function renderLegend(items) {
                    legendElement.replaceChildren();

                    items.forEach(function(item) {
                        const row =
                            document.createElement('div');

                        row.className =
                            'thematic-map-legend-item';

                        const symbol =
                            document.createElement('span');

                        symbol.className =
                            'thematic-map-legend-symbol';

                        symbol.style.backgroundColor =
                            item.color;

                        const label =
                            document.createElement('span');

                        label.textContent =
                            item.label;

                        row.append(
                            symbol,
                            label
                        );

                        legendElement.appendChild(row);
                    });
                }

                function resetThematicPanel() {
                    thematicOptions.forEach(
                        function(option) {
                            option.classList.remove(
                                'is-active'
                            );

                            option.setAttribute(
                                'aria-pressed',
                                'false'
                            );
                        }
                    );

                    statusElement.className =
                        'thematic-map-status is-idle';

                    statusElement.textContent =
                        'Belum aktif';

                    titleElement.textContent =
                        'Belum ada tema aktif';

                    descriptionElement.textContent =
                        'Pilih salah satu tema di atas untuk melihat informasi dan status datanya.';

                    legendElement.innerHTML = `
                    <div class="thematic-map-empty-legend">
                        Legenda akan muncul setelah
                        tema dipilih.
                    </div>
                `;

                    clearButton.disabled = true;
                }

                thematicOptions.forEach(
                    function(option) {
                        option.addEventListener(
                            'click',
                            function() {
                                const themeKey =
                                    option.dataset.theme;

                                const theme =
                                    themes[themeKey];

                                if (!theme) {
                                    return;
                                }

                                thematicOptions.forEach(
                                    function(item) {
                                        const isActive =
                                            item === option;

                                        item.classList.toggle(
                                            'is-active',
                                            isActive
                                        );

                                        item.setAttribute(
                                            'aria-pressed',
                                            String(isActive)
                                        );
                                    }
                                );

                                statusElement.className =
                                    'thematic-map-status ' +
                                    theme.statusClass;

                                statusElement.textContent =
                                    theme.status;

                                titleElement.textContent =
                                    theme.title;

                                descriptionElement.textContent =
                                    theme.description;

                                renderLegend(
                                    theme.legend
                                );

                                clearButton.disabled = false;
                            }
                        );
                    }
                );

                clearButton.addEventListener(
                    'click',
                    resetThematicPanel
                );
            }
        );
    </script>
@endpush
