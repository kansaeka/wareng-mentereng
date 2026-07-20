@extends('layouts.app')

@section('title', 'Beranda | WebGIS Dusun Wareng')

@section('content')
    {{-- HERO --}}
    <section class="hero">
        <video class="hero-video" autoplay muted loop playsinline preload="metadata"
            poster="{{ asset('images/wareng/hero-wareng.jpg') }}" aria-hidden="true">
            <source src="{{ asset('videos/wareng/hero-wareng.mp4') }}" type="video/mp4">
        </video>

        <div class="hero-overlay">
            <div class="container hero-content">
                <p class="eyebrow">
                    PORTAL INFORMASI DAN WEBGIS
                </p>

                <h1>Dusun Wareng</h1>

                <p class="hero-description">
                    Mengenal wilayah, masyarakat, potensi, dan kehidupan
                    Dusun Wareng melalui informasi berbasis geografis.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('map.index') }}" class="button button-primary">
                        Lihat Peta Wareng
                    </a>

                    <a href="#profil-dusun" class="button button-secondary">
                        Mengenal Wareng
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- NAVIGASI CEPAT BERANDA --}}
    <div class="home-section-navigation-wrapper">
        <div class="container">
            <nav class="home-section-navigation" aria-label="Navigasi bagian Beranda">
                <a href="#profil-dusun" class="home-navigation-link">
                    <span class="home-navigation-number">
                        01
                    </span>

                    <span class="home-navigation-content">
                        <strong>Profil Dusun</strong>

                        <small>
                            Mengenal Wareng
                        </small>
                    </span>

                    <span class="home-navigation-arrow" aria-hidden="true">
                        ↓
                    </span>
                </a>

                <a href="#informasi-wilayah" class="home-navigation-link">
                    <span class="home-navigation-number">
                        02
                    </span>

                    <span class="home-navigation-content">
                        <strong>Wareng dalam Angka</strong>

                        <small>
                            Statistik wilayah
                        </small>
                    </span>

                    <span class="home-navigation-arrow" aria-hidden="true">
                        ↓
                    </span>
                </a>

                <a href="#potensi-dusun" class="home-navigation-link">
                    <span class="home-navigation-number">
                        03
                    </span>

                    <span class="home-navigation-content">
                        <strong>Potensi Dusun</strong>

                        <small>
                            Potensi lokal
                        </small>
                    </span>

                    <span class="home-navigation-arrow" aria-hidden="true">
                        ↓
                    </span>
                </a>

                <a href="#peta-ringkas" class="home-navigation-link">
                    <span class="home-navigation-number">
                        04
                    </span>

                    <span class="home-navigation-content">
                        <strong>Peta Ringkas</strong>

                        <small>
                            Informasi spasial
                        </small>
                    </span>

                    <span class="home-navigation-arrow" aria-hidden="true">
                        ↓
                    </span>
                </a>

                <a href="#jelajah-wareng" class="home-navigation-link">
                    <span class="home-navigation-number">
                        05
                    </span>

                    <span class="home-navigation-content">
                        <strong>Jelajah Wareng</strong>

                        <small>
                            Narasi berbasis peta
                        </small>
                    </span>

                    <span class="home-navigation-arrow" aria-hidden="true">
                        ↓
                    </span>
                </a>
            </nav>
        </div>
    </div>

    {{-- PROFIL DUSUN --}}
    <section id="profil-dusun" class="section home-profile-section">
        <div class="container home-profile-layout">
            <div class="home-profile-visual">
                <div class="home-profile-image-wrapper">
                    <video class="home-profile-video" autoplay muted loop playsinline preload="metadata"
                        poster="{{ asset('images/wareng/profil-wareng.jpg') }}"
                        aria-label="Suasana lingkungan Dusun Wareng">
                        <source src="{{ asset('videos/wareng/profil-wareng.mp4') }}" type="video/mp4">
                    </video>

                    <div class="home-profile-image-overlay" aria-hidden="true"></div>

                    <div class="home-profile-location-card">
                        <span class="home-profile-location-icon" aria-hidden="true">
                            ●
                        </span>

                        <div>
                            <small>LOKASI WILAYAH</small>

                            <strong>
                                Dusun Wareng
                            </strong>

                            <span>
                                Desa Sumberarum, Tempuran
                            </span>
                        </div>
                    </div>
                </div>

                <div class="home-profile-decoration">
                    <span>WEBGIS</span>

                    <strong>
                        Informasi wilayah dalam satu portal
                    </strong>
                </div>
            </div>

            <div class="home-profile-content">
                <p class="section-label">
                    TENTANG WARENG
                </p>

                <h2>
                    Mengenal wilayah melalui
                    <span>data dan cerita lokal.</span>
                </h2>

                <p class="home-profile-lead">
                    Dusun Wareng merupakan wilayah dengan kehidupan
                    masyarakat, karakter ruang, serta potensi lokal yang
                    saling berkaitan.
                </p>

                <p>
                    Portal ini dikembangkan untuk menyajikan informasi
                    wilayah secara terbuka melalui peta, data statistik,
                    dokumentasi, dan narasi spasial.
                </p>

                <div class="home-profile-feature-list">
                    <article class="home-profile-feature">
                        <span class="home-profile-feature-number" aria-hidden="true">
                            01
                        </span>

                        <div>
                            <strong>Peta Digital</strong>

                            <p>
                                Menampilkan lokasi fasilitas dan
                                informasi geografis wilayah.
                            </p>
                        </div>
                    </article>

                    <article class="home-profile-feature">
                        <span class="home-profile-feature-number" aria-hidden="true">
                            02
                        </span>

                        <div>
                            <strong>Informasi Wilayah</strong>

                            <p>
                                Menyajikan profil, potensi, dan data
                                pendukung Dusun Wareng.
                            </p>
                        </div>
                    </article>

                    <article class="home-profile-feature">
                        <span class="home-profile-feature-number" aria-hidden="true">
                            03
                        </span>

                        <div>
                            <strong>Narasi Spasial</strong>

                            <p>
                                Menghubungkan cerita lokal dengan
                                lokasi yang ditampilkan pada peta.
                            </p>
                        </div>
                    </article>
                </div>

                <a href="#informasi-wilayah" class="button button-primary home-profile-button">
                    Lihat Informasi Wilayah

                    <span aria-hidden="true">
                        ↓
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- STATISTIK WILAYAH --}}
    <section id="informasi-wilayah" class="section home-statistics-section">
        <div class="home-statistics-pattern" aria-hidden="true"></div>

        <div class="container home-statistics-container">
            <div class="home-statistics-heading">
                <div>
                    <p class="section-label">
                        WARENG DALAM ANGKA
                    </p>

                    <h2>
                        Informasi singkat
                        <span>wilayah Dusun Wareng.</span>
                    </h2>

                    <p>
                        Ringkasan data wilayah akan disajikan
                        setelah melalui pemeriksaan dan verifikasi
                        sumber data.
                    </p>
                </div>

                <div class="home-statistics-status">
                    <span class="home-statistics-status-dot" aria-hidden="true"></span>

                    <div>
                        <small>STATUS DATA</small>

                        <strong>
                            Data Terverifikasi
                        </strong>
                    </div>
                </div>
            </div>

            <div class="home-statistics-grid">
                <article class="home-statistic-card">
                    <span class="home-statistic-number" aria-hidden="true">
                        01
                    </span>

                    <div class="home-statistic-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 7.5 9 4l6 2 5-2v12.5L15 20l-6-2-5 2V7.5Z"></path>

                            <path d="M9 4v14"></path>
                            <path d="M15 6v14"></path>
                        </svg>
                    </div>

                    <div class="home-statistic-value">
                        <strong id="stat-area-value">
                            19,75
                        </strong>

                        <span>
                            hektare
                        </span>
                    </div>

                    <div class="home-statistic-content">
                        <h3>Luas Wilayah</h3>

                        <p>
                            Luasan administratif Dusun Wareng
                            berdasarkan batas wilayah terverifikasi.
                        </p>
                    </div>
                </article>

                <article class="home-statistic-card">
                    <span class="home-statistic-number" aria-hidden="true">
                        02
                    </span>

                    <div class="home-statistic-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="9" cy="8" r="3"></circle>

                            <path d="M3.5 19c.4-3.5 2.3-5.5 5.5-5.5s5.1 2 5.5 5.5"></path>

                            <circle cx="17" cy="9" r="2.2"></circle>

                            <path d="M15.5 14.5c2.8 0 4.5 1.5 5 4.5"></path>
                        </svg>
                    </div>

                    <div class="home-statistic-value">
                        <strong id="stat-population-value">
                            267
                        </strong>

                        <span>
                            jiwa
                        </span>
                    </div>

                    <div class="home-statistic-content">
                        <h3>Jumlah Penduduk</h3>

                        <p>
                            Jumlah penduduk berdasarkan data
                            kependudukan yang telah diperiksa.
                        </p>
                    </div>
                </article>

                <article class="home-statistic-card">
                    <span class="home-statistic-number" aria-hidden="true">
                        03
                    </span>

                    <div class="home-statistic-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m3 11 9-7 9 7"></path>

                            <path d="M5 10v10h14V10"></path>

                            <path d="M9 20v-6h6v6"></path>
                        </svg>
                    </div>

                    <div class="home-statistic-value">
                        <strong id="stat-household-value">
                            84
                        </strong>

                        <span>
                            kepala keluarga
                        </span>
                    </div>

                    <div class="home-statistic-content">
                        <h3>Jumlah KK</h3>

                        <p>
                            Jumlah kepala keluarga yang tercatat
                            di wilayah Dusun Wareng.
                        </p>
                    </div>
                </article>

                <article class="home-statistic-card">
                    <span class="home-statistic-number" aria-hidden="true">
                        04
                    </span>

                    <div class="home-statistic-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 21s7-5.4 7-12a7 7 0 1 0-14 0c0 6.6 7 12 7 12Z"></path>

                            <circle cx="12" cy="9" r="2.5"></circle>
                        </svg>
                    </div>

                    <div class="home-statistic-value">
                        <strong id="stat-facility-value">
                            {{ number_format($facilityCount, 0, ',', '.') }}
                        </strong>

                        <span>
                            fasilitas terpublikasi
                        </span>
                    </div>

                    <div class="home-statistic-content">
                        <h3>Fasilitas Publik</h3>

                        <p>
                            Sarana dan prasarana yang telah dicatat
                            serta ditampilkan dalam WebGIS.
                        </p>
                    </div>
                </article>
            </div>

            <div class="home-statistics-note">
                <span class="home-statistics-note-icon" aria-hidden="true">
                    i
                </span>

                <p>
                    Data luas wilayah, jumlah penduduk,
                    jumlah kepala keluarga serta jumlah fasilitas menggunakan data
                    wilayah yang tersedia
                </p>
            </div>
        </div>
    </section>

    {{-- POTENSI DUSUN --}}
    <section id="potensi-dusun" class="section home-potential-section">
        <div class="container">
            <div class="home-potential-heading">
                <div>
                    <p class="section-label">
                        POTENSI DUSUN
                    </p>

                    <h2>
                        Mengenal potensi
                        <span>yang tumbuh di Wareng.</span>
                    </h2>
                </div>

                <div class="home-potential-introduction">
                    <p>
                        Informasi mengenai pertanian, kegiatan ekonomi,
                        pendidikan, serta kehidupan keagamaan masyarakat
                        akan dikembangkan secara bertahap melalui data
                        dan dokumentasi wilayah.
                    </p>

                    <a href="{{ route('map.index') }}" class="text-link home-potential-heading-link">
                        Lihat Peta Interaktif
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            <div class="home-potential-grid">
                <article class="home-potential-card">
                    <img src="{{ asset('images/wareng/potensi-pertanian.jpg') }}" alt="Potensi pertanian Dusun Wareng"
                        class="home-potential-card-image" loading="lazy">

                    <div class="home-potential-card-overlay" aria-hidden="true"></div>

                    <span class="home-potential-card-number">
                        01
                    </span>

                    <div class="home-potential-card-content">
                        <span class="home-potential-card-category">
                            POTENSI ALAM
                        </span>

                        <h3>Pertanian</h3>

                        <p>
                            Informasi mengenai lahan, komoditas,
                            serta kegiatan pertanian masyarakat
                            Dusun Wareng.
                        </p>

                        <a href="{{ route('map.index') }}" class="home-potential-card-link">
                            Jelajahi pada peta

                            <span aria-hidden="true">
                                ↗
                            </span>
                        </a>
                    </div>
                </article>

                <article class="home-potential-card">
                    <img src="{{ asset('images/wareng/potensi-umkm.png') }}" alt="Kegiatan usaha masyarakat Dusun Wareng"
                        class="home-potential-card-image" loading="lazy">

                    <div class="home-potential-card-overlay" aria-hidden="true"></div>

                    <span class="home-potential-card-number">
                        02
                    </span>

                    <div class="home-potential-card-content">
                        <span class="home-potential-card-category">
                            EKONOMI LOKAL
                        </span>

                        <h3>UMKM</h3>

                        <p>
                            Pemetaan usaha, kegiatan ekonomi,
                            dan produk lokal yang dikembangkan
                            oleh masyarakat.
                        </p>

                        <a href="{{ route('map.index') }}" class="home-potential-card-link">
                            Jelajahi pada peta

                            <span aria-hidden="true">
                                ↗
                            </span>
                        </a>
                    </div>
                </article>

                <article class="home-potential-card">
                    <img src="{{ asset('images/wareng/potensi-pendidikan-keagamaan.jpg') }}"
                        alt="Potensi pendidikan dan kegiatan keagamaan Dusun Wareng" class="home-potential-card-image"
                        loading="lazy">

                    <div class="home-potential-card-overlay" aria-hidden="true"></div>

                    <span class="home-potential-card-number">
                        03
                    </span>

                    <div class="home-potential-card-content">
                        <span class="home-potential-card-category">
                            SOSIAL DAN PELAYANAN
                        </span>

                        <h3>
                            Pendidikan dan Keagamaan
                        </h3>

                        <p>
                            Informasi mengenai sarana pendidikan,
                            tempat ibadah, kegiatan pembelajaran,
                            serta kehidupan keagamaan masyarakat
                            Dusun Wareng.
                        </p>

                        <a href="{{ route('map.index') }}" class="home-potential-card-link">
                            Jelajahi pada peta

                            <span aria-hidden="true">
                                ↗
                            </span>
                        </a>
                    </div>
                </article>
            </div>

            <div class="home-potential-note">
                <span aria-hidden="true">
                    ✦
                </span>

                <p>
                    Informasi potensi akan terus diperbarui berdasarkan
                    hasil pendataan, dokumentasi, dan verifikasi wilayah.
                </p>
            </div>
        </div>
    </section>

    {{-- PETA RINGKAS --}}
    <section id="peta-ringkas" class="section home-map-section">
        <video class="home-map-background-video" autoplay muted loop playsinline preload="metadata"
            poster="{{ asset('images/wareng/peta-ringkas-drone.jpg') }}" aria-hidden="true">
            <source src="{{ asset('videos/wareng/peta-ringkas-drone.mp4') }}" type="video/mp4">
        </video>

        <div class="home-map-video-overlay" aria-hidden="true"></div>

        <div class="home-map-section-pattern" aria-hidden="true"></div>

        <div class="container home-map-container">
            <div class="home-map-heading">
                <div class="home-map-heading-content">
                    <p class="section-label">
                        INFORMASI SPASIAL
                    </p>

                    <h2>
                        Peta ringkas
                        <span>Dusun Wareng.</span>
                    </h2>

                    <p>
                        Lihat persebaran sarana dan prasarana
                        Dusun Wareng secara ringkas sebelum
                        membuka seluruh fitur pada Peta Interaktif.
                    </p>
                </div>

                <div class="home-map-heading-action">
                    <span class="home-map-heading-badge">
                        PETA DIGITAL
                    </span>

                    <a href="{{ route('map.index') }}" class="button button-primary">
                        Buka Peta Interaktif

                        <span aria-hidden="true">
                            →
                        </span>
                    </a>
                </div>
            </div>

            <div class="home-map-shell">
                <aside class="home-map-sidebar">
                    <div class="home-map-sidebar-heading">
                        <div>
                            <p class="section-label">
                                FILTER PETA
                            </p>

                            <h3>Pilih Kategori</h3>
                        </div>

                        <span class="home-map-sidebar-icon" aria-hidden="true">
                            ⌘
                        </span>
                    </div>

                    <p class="home-map-sidebar-description">
                        Aktifkan atau nonaktifkan kategori untuk
                        mengatur fasilitas yang ditampilkan.
                    </p>

                    <div id="facility-filters"
                        class="facility-filter-list
                           home-map-filter-list">
                        <span class="facility-filter-loading">
                            Memuat kategori...
                        </span>
                    </div>

                    <button id="show-all-facilities"
                        class="filter-reset-button
                           home-map-reset-filter" type="button">
                        <span aria-hidden="true">
                            ↻
                        </span>

                        Tampilkan Semua
                    </button>

                    <div class="home-map-object-statistic">
                        <div>
                            <strong id="visible-facility-count">
                                0
                            </strong>

                            <span>
                                objek ditampilkan
                            </span>
                        </div>

                        <span class="home-map-object-indicator" aria-hidden="true"></span>
                    </div>

                    <p id="map-data-status" class="map-data-status
                           home-map-data-status"
                        aria-live="polite">
                        Memuat data peta...
                    </p>
                </aside>

                <div class="home-map-content">
                    <div class="home-map-toolbar">
                        <div>
                            <span class="home-map-toolbar-label">
                                PRATINJAU PETA
                            </span>

                            <strong>
                                Sarana dan Prasarana Wareng
                            </strong>
                        </div>

                        <span class="home-map-live-status">
                            <span aria-hidden="true"></span>

                            Interaktif
                        </span>
                    </div>

                    <div class="home-map-frame">
                        <div id="wareng-map" class="home-map"
                            aria-label="Peta ringkas sarana dan prasarana Dusun Wareng"></div>
                    </div>

                    <div class="home-map-footer">
                        <div class="home-map-warning">
                            <span class="home-map-warning-icon" aria-hidden="true">
                                i
                            </span>

                            <p>
                                Koordinat dan cakupan wilayah yang
                                ditampilkan masih dalam proses
                                verifikasi lapangan.
                            </p>
                        </div>

                        <a href="{{ route('map.index') }}" class="home-map-footer-link">
                            Eksplorasi peta lengkap

                            <span aria-hidden="true">
                                ↗
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JELAJAH WARENG --}}
    <section id="jelajah-wareng" class="section home-story-section">
        <div class="home-story-pattern" aria-hidden="true"></div>

        <div class="container home-story-container">
            <div class="home-story-content">
                <p class="section-label">
                    NARASI SPASIAL
                </p>

                <h2>
                    Kenali Wareng melalui
                    <span>cerita yang terhubung dengan peta.</span>
                </h2>

                <p class="home-story-description">
                    Ikuti perjalanan visual untuk mengenal
                    lingkungan, kehidupan masyarakat, potensi
                    wilayah, serta sarana dan prasarana yang
                    berada di Dusun Wareng.
                </p>

                <div class="home-story-actions">
                    <a href="{{ route('stories.wareng') }}" class="button home-story-primary-button">
                        Mulai Menjelajah

                        <span aria-hidden="true">
                            →
                        </span>
                    </a>

                    <a href="{{ route('map.index') }}" class="button home-story-secondary-button">
                        Buka Peta Interaktif
                    </a>
                </div>

                <div class="home-story-status">
                    <span class="home-story-status-dot" aria-hidden="true"></span>

                    <span>
                        Cerita dan lokasi dapat dikelola melalui
                        halaman admin.
                    </span>
                </div>
            </div>

            <div class="home-story-visual">
                <div class="home-story-visual-header">
                    <div>
                        <span>ALUR PENJELAJAHAN</span>

                        <strong>
                            Jelajah Dusun Wareng
                        </strong>
                    </div>

                    <span class="home-story-visual-badge">
                        Interaktif
                    </span>
                </div>

                <div class="home-story-steps">
                    <article class="home-story-step is-active">
                        <span class="home-story-step-number">
                            01
                        </span>

                        <div>
                            <strong>Baca Cerita</strong>

                            <p>
                                Kenali informasi wilayah melalui
                                narasi singkat setiap bab.
                            </p>
                        </div>

                        <span class="home-story-step-arrow" aria-hidden="true">
                            →
                        </span>
                    </article>

                    <article class="home-story-step">
                        <span class="home-story-step-number">
                            02
                        </span>

                        <div>
                            <strong>Ikuti Lokasi</strong>

                            <p>
                                Peta bergerak mengikuti lokasi yang
                                sedang dibahas.
                            </p>
                        </div>

                        <span class="home-story-step-arrow" aria-hidden="true">
                            →
                        </span>
                    </article>

                    <article class="home-story-step">
                        <span class="home-story-step-number">
                            03
                        </span>

                        <div>
                            <strong>Temukan Fasilitas</strong>

                            <p>
                                Buka fasilitas terkait langsung pada
                                Peta Interaktif.
                            </p>
                        </div>

                        <span class="home-story-step-arrow" aria-hidden="true">
                            ↗
                        </span>
                    </article>
                </div>

                <a href="{{ route('stories.wareng') }}" class="home-story-visual-link">
                    Lihat seluruh cerita

                    <span aria-hidden="true">
                        →
                    </span>
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="{{ asset('js/home-map.js') }}"></script>
@endpush
