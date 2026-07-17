@extends('layouts.app')

@section('title', 'Beranda | WebGIS Dusun Wareng')

@section('content')
    {{-- HERO --}}
    <section class="hero">
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
                <a href="#profil-dusun">
                    <span class="home-navigation-number">
                        01
                    </span>

                    <span>Profil Dusun</span>
                </a>

                <a href="#potensi-dusun">
                    <span class="home-navigation-number">
                        02
                    </span>

                    <span>Potensi</span>
                </a>

                <a href="#peta-ringkas">
                    <span class="home-navigation-number">
                        03
                    </span>

                    <span>Peta Ringkas</span>
                </a>
            </nav>
        </div>
    </div>

    {{-- PROFIL DUSUN --}}
    <section id="profil-dusun" class="section">
        <div class="container two-columns">
            <div>
                <p class="section-label">
                    TENTANG WARENG
                </p>

                <h2>Mengenal Dusun Wareng</h2>

                <p>
                    Dusun Wareng merupakan wilayah dengan kehidupan
                    masyarakat, karakter ruang, serta potensi lokal yang
                    saling berkaitan.
                </p>

                <p>
                    Portal ini dikembangkan untuk menyajikan informasi
                    wilayah secara terbuka melalui peta, data statistik,
                    dokumentasi, dan narasi spasial.
                </p>

                <a href="#informasi-wilayah" class="text-link">
                    Lihat informasi wilayah →
                </a>
            </div>

            <div class="image-placeholder">
                <span>Foto Dusun Wareng</span>
            </div>
        </div>
    </section>

    {{-- STATISTIK WILAYAH --}}
    <section id="informasi-wilayah" class="section section-light">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">
                    WARENG DALAM ANGKA
                </p>

                <h2>Informasi Singkat Wilayah</h2>
            </div>

            <div class="statistics-grid">
                <article class="statistic-card">
                    <strong>—</strong>
                    <span>Luas Wilayah</span>
                </article>

                <article class="statistic-card">
                    <strong>—</strong>
                    <span>Jumlah Penduduk</span>
                </article>

                <article class="statistic-card">
                    <strong>—</strong>
                    <span>Jumlah RT</span>
                </article>

                <article class="statistic-card">
                    <strong>—</strong>
                    <span>Fasilitas Publik</span>
                </article>
            </div>
        </div>
    </section>

    {{-- POTENSI DUSUN --}}
    <section id="potensi-dusun" class="section">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">
                    POTENSI DUSUN
                </p>

                <h2>Potensi yang Akan Dipetakan</h2>

                <p>
                    Informasi mengenai potensi pertanian, kegiatan
                    ekonomi, serta budaya masyarakat Dusun Wareng.
                </p>
            </div>

            <div class="cards-grid">
                <article class="content-card">
                    <div class="card-image">
                        Pertanian
                    </div>

                    <div class="card-body">
                        <h3>Pertanian</h3>

                        <p>
                            Informasi mengenai lahan, komoditas, dan
                            kegiatan pertanian masyarakat.
                        </p>
                    </div>
                </article>

                <article class="content-card">
                    <div class="card-image">
                        UMKM
                    </div>

                    <div class="card-body">
                        <h3>UMKM</h3>

                        <p>
                            Pemetaan usaha dan produk lokal yang
                            dikembangkan masyarakat.
                        </p>
                    </div>
                </article>

                <article class="content-card">
                    <div class="card-image">
                        Budaya
                    </div>

                    <div class="card-body">
                        <h3>Budaya dan Tradisi</h3>

                        <p>
                            Dokumentasi tradisi, kegiatan sosial, dan
                            kearifan lokal Dusun Wareng.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- PETA RINGKAS --}}
    <section id="peta-ringkas" class="section section-light">
        <div class="container">
            <div class="section-heading">
                <p class="section-label">
                    INFORMASI SPASIAL
                </p>

                <h2>Peta Ringkas Dusun Wareng</h2>

                <p>
                    Lihat gambaran awal fasilitas, potensi, dan informasi
                    geografis Dusun Wareng sebelum membuka peta interaktif
                    secara lengkap.
                </p>
            </div>

            <div class="map-layout">
                <aside class="map-filter-panel">
                    <div class="map-filter-heading">
                        <p class="section-label">
                            FILTER PETA
                        </p>

                        <h3>Pilih Kategori</h3>

                        <p>
                            Aktifkan atau nonaktifkan kategori untuk
                            mengatur objek yang ditampilkan pada peta.
                        </p>
                    </div>

                    <div id="facility-filters" class="facility-filter-list">
                        <span class="facility-filter-loading">
                            Memuat kategori...
                        </span>
                    </div>

                    <button id="show-all-facilities" class="filter-reset-button" type="button">
                        Tampilkan Semua
                    </button>

                    <div class="map-object-count">
                        <strong id="visible-facility-count">
                            0
                        </strong>

                        <span>objek ditampilkan</span>
                    </div>

                    <p id="map-data-status" class="map-data-status" aria-live="polite">
                        Memuat data peta...
                    </p>
                </aside>

                <div class="map-content">
                    <div id="wareng-map" class="home-map" aria-label="Peta interaktif Dusun Wareng"></div>

                    <p class="map-note">
                        Data dan koordinat yang ditampilkan masih
                        berupa contoh sementara. Data akan diperbarui
                        setelah proses verifikasi lapangan.
                    </p>

                    <a href="{{ route('map.index') }}" class="button button-primary">
                        Buka Peta Lengkap
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- JELAJAH WARENG --}}
    <section id="jelajah-wareng" class="section story-promotion">
        <div class="container">
            <div class="story-content">
                <p class="section-label">
                    NARASI SPASIAL
                </p>

                <h2>Jelajah Dusun Wareng</h2>

                <p>
                    Ikuti perjalanan visual untuk mengenal bentang wilayah,
                    kehidupan masyarakat, potensi, tradisi, serta perubahan
                    yang berlangsung di Dusun Wareng.
                </p>

                <a href="{{ route('stories.wareng') }}" class="button button-primary">
                    Mulai Menjelajah
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
