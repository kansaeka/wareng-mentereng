@extends('layouts.app')

@section('title', $story->title . ' | WebGIS Dusun Wareng')

@section('content')
    <section class="story-hero story-video-hero">
        <video class="story-hero-background-video" autoplay muted loop playsinline preload="auto"
            poster="{{ asset('images/wareng/jelajah-wareng-drone.jpg') }}" aria-hidden="true">
            <source src="{{ asset('videos/wareng/jelajah-wareng-drone.mp4') }}" type="video/mp4">
        </video>

        <div class="story-hero-video-overlay" aria-hidden="true"></div>

        <div class="container story-hero-content">
            <a href="{{ route('home') }}" class="story-back-link">
                ← Kembali ke Beranda
            </a>

            <p class="eyebrow">
                NARASI SPASIAL DUSUN WARENG
            </p>

            <h1>{{ $story->title }}</h1>

            @if ($story->summary)
                <p class="story-hero-summary">
                    {{ $story->summary }}
                </p>
            @endif

            <a href="#dokumenter-wareng" class="button button-primary">
                Tonton Dokumenter
            </a>
        </div>
    </section>

    {{-- ==================================================
     VIDEO DOKUMENTER DUSUN WARENG
================================================== --}}
    <section id="dokumenter-wareng" class="story-documentary-section">
        <div class="story-documentary-pattern" aria-hidden="true"></div>

        <div class="container story-documentary-container">
            <header class="story-documentary-heading">
                <div class="story-documentary-heading-copy">
                    <p class="section-label">
                        DOKUMENTASI DUSUN
                    </p>

                    <h2>
                        Wareng dalam
                        <span>Bingkai Kehidupan</span>
                    </h2>

                    <p>
                        Saksikan lebih dekat lingkungan, aktivitas,
                        potensi, dan kehidupan masyarakat Dusun Wareng
                        melalui dokumenter singkat berikut.
                    </p>
                </div>

                <div class="story-documentary-status">
                    <span class="story-documentary-status-dot" aria-hidden="true"></span>

                    <div>
                        <small>DOKUMENTER WILAYAH</small>

                        <strong>
                            Dusun Wareng
                        </strong>
                    </div>
                </div>
            </header>

            <div class="story-documentary-video-shell">
                <div class="story-documentary-video-frame">
                    <iframe src="https://www.youtube-nocookie.com/embed/VIDEO_ID?rel=0"
                        title="Video dokumenter Dusun Wareng" loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allow="
                        accelerometer;
                        autoplay;
                        clipboard-write;
                        encrypted-media;
                        gyroscope;
                        picture-in-picture;
                        web-share
                    "
                        allowfullscreen></iframe>
                </div>

                <div class="story-documentary-video-footer">
                    <div class="story-documentary-information">
                        <span>
                            DOKUMENTER
                        </span>

                        <strong>
                            Menelusuri Kehidupan Dusun Wareng
                        </strong>

                        <small>
                            Produksi Tim PKL Dusun Wareng · 2026
                        </small>
                    </div>

                    <a href="#perjalanan-wareng" class="story-documentary-next">
                        Mulai Jelajah Peta

                        <span aria-hidden="true">↓</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="perjalanan-wareng" class="story-experience">
        <div class="story-map-column">
            <div class="story-map-sticky">
                <div id="story-map" class="story-map" aria-label="Peta perjalanan Jelajah Dusun Wareng"></div>

                @php
                    $chapterCount = max($chapters->count(), 1);

                    $initialProgress = $chapters->isNotEmpty() ? round(100 / $chapterCount) : 0;
                @endphp

                <div class="story-map-information">
                    <strong id="active-story-title">
                        {{ $chapters->first()?->title ?? 'Jelajah Dusun Wareng' }}
                    </strong>

                    <span id="active-story-location">
                        {{ $chapters->first()?->location_name ?? 'Dusun Wareng' }}
                    </span>

                    <div class="story-progress" aria-label="Progres perjalanan Jelajah Wareng">
                        <div class="story-progress-header">
                            <span id="story-progress-label">
                                Bab 01 dari
                                {{ str_pad((string) $chapterCount, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <span id="story-progress-percent">
                                {{ $initialProgress }}%
                            </span>
                        </div>

                        <div id="story-progress-track" class="story-progress-track" role="progressbar"
                            aria-label="Bab yang sedang dibaca" aria-valuemin="1" aria-valuemax="{{ $chapterCount }}"
                            aria-valuenow="1">
                            <span id="story-progress-fill" class="story-progress-fill"
                                style="width: {{ $initialProgress }}%;"></span>
                        </div>
                    </div>
                </div>

                <p class="story-map-note">
                    Titik lokasi yang ditampilkan masih berupa data
                    simulasi dan belum merupakan hasil survei resmi.
                </p>
            </div>
        </div>

        <div class="story-chapter-column">
            <header class="story-chapter-heading">
                <p class="section-label">
                    PERJALANAN WARENG
                </p>

                <h2>Menelusuri Ruang dan Kehidupan Dusun</h2>

                <p>
                    Gulir halaman atau pilih salah satu bab.
                    Peta akan mengikuti lokasi yang sedang diceritakan.
                </p>
            </header>

            @forelse ($chapters as $chapter)
                <article id="{{ $chapter->slug }}" class="story-chapter-card {{ $loop->first ? 'is-active' : '' }}"
                    data-story-chapter data-chapter-index="{{ $loop->index }}" data-latitude="{{ $chapter->latitude }}"
                    data-longitude="{{ $chapter->longitude }}" data-zoom="{{ $chapter->map_zoom }}"
                    data-title="{{ $chapter->title }}" data-location="{{ $chapter->location_name }}" tabindex="0">
                    <div class="story-chapter-number">
                        {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </div>

                    @if ($chapter->image_path)
                        <figure class="story-chapter-image">
                            <img src="{{ asset('storage/' . $chapter->image_path) }}"
                                alt="Dokumentasi {{ $chapter->title }}" loading="lazy">
                        </figure>
                    @else
                        <div class="story-chapter-image-placeholder">
                            <span>
                                Dokumentasi bab belum tersedia
                            </span>
                        </div>
                    @endif

                    <div class="story-chapter-content">
                        @if ($chapter->location_name)
                            <p class="story-location-label">
                                {{ $chapter->location_name }}
                            </p>
                        @endif

                        <h3>{{ $chapter->title }}</h3>

                        <p>{{ $chapter->body }}</p>

                        @if ($chapter->latitude !== null && $chapter->longitude !== null)
                            <button type="button" class="story-focus-button" data-focus-chapter>
                                Lihat lokasi pada peta →
                            </button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="story-empty-state">
                    <h3>Bab cerita belum tersedia</h3>

                    <p>
                        Konten Jelajah Wareng sedang dipersiapkan.
                    </p>
                </div>
            @endforelse

            <div class="story-ending">
                <p class="section-label">
                    LANJUTKAN PENJELAJAHAN
                </p>

                <h2>Lihat Informasi Spasial Secara Lengkap</h2>

                <p>
                    Buka peta interaktif untuk melihat fasilitas,
                    kategori lokasi, dan informasi geografis lainnya.
                </p>

                <a href="{{ route('map.index') }}" class="button button-primary">
                    Buka Peta Interaktif
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

    <script src="{{ asset('js/story-map.js') }}"></script>
@endpush
