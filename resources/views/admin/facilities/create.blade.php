@extends('layouts.app')

@section('title', 'Tambah Fasilitas | Admin WebGIS Wareng')

@section('content')
    <section class="admin-page-header">
        <div class="container">
            <p class="section-label">
                ADMIN WEBGIS
            </p>

            <h1>Tambah Data Fasilitas</h1>

            <p>
                Tambahkan objek baru beserta kategori,
                atribut, dan koordinat lokasinya.
            </p>
        </div>
    </section>

    <section class="admin-content-section">
        <div class="container">
            <div class="admin-form-card">
                <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data"
                    class="admin-data-form">
                    @csrf

                    @include('admin.facilities._form')

                    <div class="admin-form-actions">
                        <a href="{{ route('admin.facilities.index') }}" class="admin-secondary-button">
                            Batal
                        </a>

                        <button type="submit" class="admin-primary-button">
                            Simpan Fasilitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
