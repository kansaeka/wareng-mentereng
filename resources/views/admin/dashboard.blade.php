@extends('layouts.app')

@section('title', 'Dashboard Admin | WebGIS Dusun Wareng')

@section('content')
    <section class="admin-dashboard-hero">
        <div class="container">
            <div class="admin-dashboard-heading">
                <div>
                    <p class="section-label">
                        ADMIN WEBGIS
                    </p>

                    <h1>Dashboard Pengelolaan</h1>

                    <p>
                        Selamat datang,
                        <strong>{{ auth()->user()->name }}</strong>.
                    </p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="admin-logout-button">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="admin-dashboard-section">
        <div class="container">
            <div class="admin-stat-grid">
                <article class="admin-stat-card">
                    <span>Kategori Aktif dan Nonaktif</span>

                    <strong>{{ $categoryCount }}</strong>

                    <small>Total kategori fasilitas</small>
                </article>

                <article class="admin-stat-card">
                    <span>Seluruh Fasilitas</span>

                    <strong>{{ $facilityCount }}</strong>

                    <small>Total data dalam database</small>
                </article>

                <article class="admin-stat-card">
                    <span>Dipublikasikan</span>

                    <strong>{{ $publishedFacilityCount }}</strong>

                    <small>Tampil pada peta publik</small>
                </article>

                <article class="admin-stat-card">
                    <span>Belum Diverifikasi</span>

                    <strong>{{ $unverifiedFacilityCount }}</strong>

                    <small>Memerlukan pemeriksaan data</small>
                </article>
            </div>

            <div class="admin-menu-grid">
                <article class="admin-menu-card">
                    <h2>Kategori Fasilitas</h2>

                    <p>
                        Kelola nama kategori, warna marker,
                        urutan, dan status aktif.
                    </p>

                    <a href="{{ route('admin.facility-categories.index') }}" class="admin-menu-link">
                        Kelola Kategori
                    </a>
                </article>

                <article class="admin-menu-card">
                    <h2>Data Fasilitas</h2>

                    <p>
                        Kelola nama objek, atribut, koordinat,
                        status verifikasi, dan publikasi.
                    </p>

                    <a href="{{ route('admin.facilities.index') }}" class="admin-menu-link">
                        Kelola Fasilitas
                    </a>
                </article>

                <article class="admin-menu-card">
                    <h2>Peta Publik</h2>

                    <p>
                        Lihat hasil kategori, fasilitas,
                        marker, foto, dan informasi yang
                        sudah dipublikasikan.
                    </p>

                    <a href="{{ route('map.index') }}" class="admin-menu-link">
                        Buka Peta Publik
                    </a>
                </article>
            </div>
        </div>
    </section>
@endsection
