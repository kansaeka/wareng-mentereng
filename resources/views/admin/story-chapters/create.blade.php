@extends('layouts.app')

@section('title', 'Tambah Bab Jelajah Wareng | WebGIS Dusun Wareng')

@section('content')
    <section class="admin-story-form-page">
        <div class="container">
            <header class="admin-story-form-header">
                <div>
                    <p class="section-label">
                        JELAJAH WARENG
                    </p>

                    <h1>Tambah Bab Cerita</h1>

                    <p>
                        Tambahkan bagian baru pada
                        {{ $story->title }}.
                    </p>
                </div>

                <a href="{{ route('admin.stories.index') }}" class="button button-secondary">
                    Kembali
                </a>
            </header>

            <form
                action="{{ route('admin.stories.chapters.store', $story) }}"
                method="POST" enctype="multipart/form-data" class="admin-story-form">
                @csrf

                @include('admin.story-chapters._form', [
                    'submitLabel' => 'Tambah Bab',
                ])
            </form>
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

    <script src="{{ asset('js/admin-story-chapter-map.js') }}"></script>
@endpush
