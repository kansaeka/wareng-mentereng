@extends('layouts.app')

@section('title', 'Galeri Peta | WebGIS Dusun Wareng')

@section('content')
    {{-- HERO GALERI PETA --}}
    <section class="map-gallery-hero">
        <video class="map-gallery-hero-video" autoplay muted loop playsinline preload="metadata"
            poster="{{ asset('images/wareng/galeri-peta-drone.jpg') }}" aria-hidden="true">
            <source src="{{ asset('videos/wareng/galeri-peta-drone.mp4') }}" type="video/mp4">
        </video>

        <div class="map-gallery-hero-overlay" aria-hidden="true"></div>

        <div class="map-gallery-hero-pattern" aria-hidden="true"></div>
        <div class="container map-gallery-hero-content">
            <div class="map-gallery-hero-copy">
                <p class="map-gallery-eyebrow">
                    PUBLIKASI INFORMASI SPASIAL
                </p>

                <h1>
                    Galeri Peta
                    <span>Dusun Wareng</span>
                </h1>

                <p>
                    Kumpulan peta yang menyajikan informasi
                    wilayah, fasilitas, infrastruktur, penggunaan
                    lahan, dan potensi Dusun Wareng dalam format
                    yang dapat dilihat dan diunduh.
                </p>
            </div>

            <div class="map-gallery-summary">
                <div>
                    <strong>
                        {{ $maps->count() }}
                    </strong>

                    <span>
                        Koleksi peta
                    </span>
                </div>

                <div>
                    <strong>
                        {{ $maps->pluck('category')->unique()->count() }}
                    </strong>

                    <span>
                        Kategori
                    </span>
                </div>

                <div>
                    <strong>
                        PDF
                    </strong>

                    <span>
                        Format publikasi
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- DAFTAR GALERI --}}
    <section class="map-gallery-section">
        <div class="container">
            <div class="map-gallery-heading">
                <div>
                    <p class="map-gallery-section-label">
                        KOLEKSI PETA
                    </p>

                    <h2>
                        Jelajahi Informasi Wilayah
                    </h2>

                    <p>
                        Gunakan pencarian atau pilih kategori
                        untuk menemukan peta yang dibutuhkan.
                    </p>
                </div>

                <label class="map-gallery-search">
                    <span class="sr-only">
                        Cari peta
                    </span>

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>

                        <path d="m20 20-4-4"></path>
                    </svg>

                    <input id="map-gallery-search" type="search" placeholder="Cari judul peta..." autocomplete="off">
                </label>
            </div>

            <div class="map-gallery-filters" aria-label="Filter kategori peta">
                <button type="button" class="map-gallery-filter is-active" data-map-filter="all">
                    Semua
                </button>

                @foreach ($maps->pluck('category')->unique()->values() as $category)
                    <button type="button" class="map-gallery-filter" data-map-filter="{{ str($category)->slug() }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            @php
                $galleryGroups = collect([
                    [
                        'name' => 'Peta Dasar',
                        'number' => '01',
                        'description' =>
                            'Kumpulan peta dasar yang menyajikan ' . 'informasi utama wilayah Dusun Wareng.',
                    ],
                    [
                        'name' => 'Peta Tematik',
                        'number' => '02',
                        'description' =>
                            'Kumpulan peta analisis sosial, ekonomi, ' .
                            'kependudukan, permukiman, administrasi, ' .
                            'infrastruktur, dan utilitas Dusun Wareng.',
                    ],
                ]);
            @endphp

            <div class="map-gallery-sections">
                @foreach ($galleryGroups as $galleryGroup)
                    @php
                        $groupMaps = $maps->where('group', $galleryGroup['name'])->values();
                    @endphp

                    @if ($groupMaps->isNotEmpty())
                        <section id="{{ \Illuminate\Support\Str::slug($galleryGroup['name']) }}"
                            class="map-gallery-category-section" data-map-section>
                            <header class="map-gallery-category-heading">
                                <div class="map-gallery-category-title">
                                    <span class="map-gallery-category-number" aria-hidden="true">
                                        {{ $galleryGroup['number'] }}
                                    </span>

                                    <div>
                                        <p>KATEGORI KOLEKSI</p>

                                        <h2>
                                            {{ $galleryGroup['name'] }}
                                        </h2>

                                        <span>
                                            {{ $galleryGroup['description'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="map-gallery-category-total">
                                    <strong>
                                        {{ $groupMaps->count() }}
                                    </strong>

                                    <span>
                                        koleksi peta
                                    </span>
                                </div>
                            </header>

                            <div class="map-gallery-grid">
                                @foreach ($groupMaps as $map)
                                    @include('maps.partials.map-card', ['map' => $map])
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>

            <div id="map-gallery-empty" class="map-gallery-empty" hidden>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>

                    <path d="m20 20-4-4"></path>
                </svg>

                <h3>
                    Peta tidak ditemukan
                </h3>

                <p>
                    Coba gunakan kata kunci atau kategori
                    yang berbeda.
                </p>
            </div>

            <div class="map-gallery-information">
                <span aria-hidden="true">
                    i
                </span>

                <p>
                    Beberapa peta masih dalam tahap penyusunan
                    dan verifikasi. Dokumen publikasi akan tersedia
                    untuk diunduh setelah proses tersebut selesai.
                </p>
            </div>
        </div>
    </section>

    {{-- MODAL PREVIEW PETA --}}
    <div id="map-preview-modal" class="map-preview-modal" role="dialog" aria-modal="true"
        aria-labelledby="map-preview-title" hidden>
        <div class="map-preview-backdrop" data-map-preview-close aria-hidden="true"></div>

        <div class="map-preview-dialog">
            <header class="map-preview-header">
                <div class="map-preview-heading">
                    <span class="map-preview-heading-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="m3 6 6-3 6 3
                                    6-3v15l-6 3-6-3
                                    -6 3V6Z"></path>

                            <path d="M9 3v15"></path>
                            <path d="M15 6v15"></path>
                        </svg>
                    </span>

                    <div>
                        <small>PRATINJAU DOKUMEN PETA</small>

                        <h2 id="map-preview-title">
                            Galeri Peta Dusun Wareng
                        </h2>
                    </div>
                </div>

                <button type="button" class="map-preview-close" data-map-preview-close aria-label="Tutup pratinjau peta"
                    title="Tutup">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 6l12 12"></path>
                        <path d="M18 6 6 18"></path>
                    </svg>
                </button>
            </header>

            <div class="map-preview-content">
                <div class="map-preview-loading">
                    <span aria-hidden="true"></span>

                    <p>Memuat dokumen peta...</p>
                </div>

                <iframe id="map-preview-frame" class="map-preview-frame" src="about:blank" title="Pratinjau peta"></iframe>
            </div>

            <footer class="map-preview-footer">
                <p>
                    Gunakan kontrol pada pembaca PDF untuk
                    memperbesar atau mengecilkan tampilan peta.
                </p>

                <div class="map-preview-actions">
                    <a id="map-preview-new-tab"
                        class="
                        map-preview-action
                        map-preview-action-secondary
                    "
                        href="#" target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M14 5h5v5"></path>
                            <path d="m13 11 6-6"></path>
                            <path d="M19 13v6H5V5h6"></path>
                        </svg>

                        Buka Tab Baru
                    </a>

                    <a id="map-preview-download"
                        class="
                        map-preview-action
                        map-preview-action-primary
                    "
                        href="#" download>
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3v12"></path>
                            <path d="m7 10 5 5 5-5"></path>
                            <path d="M5 21h14"></path>
                        </svg>

                        Unduh PDF
                    </a>
                </div>
            </footer>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {
                const searchInput =
                    document.getElementById(
                        'map-gallery-search'
                    );

                const filterButtons = Array.from(
                    document.querySelectorAll(
                        '[data-map-filter]'
                    )
                );

                const mapCards = Array.from(
                    document.querySelectorAll(
                        '[data-map-card]'
                    )
                );

                const emptyState =
                    document.getElementById(
                        'map-gallery-empty'
                    );

                let activeCategory = 'all';

                function updateGallery() {
                    const keyword =
                        searchInput.value
                        .trim()
                        .toLowerCase();

                    let visibleCount = 0;

                    mapCards.forEach(function(card) {
                        const searchableText =
                            card.dataset.mapSearch || '';

                        const category =
                            card.dataset.mapCategory || '';

                        const matchesCategory =
                            activeCategory === 'all' ||
                            category === activeCategory;

                        const matchesKeyword =
                            searchableText.includes(keyword);

                        const isVisible =
                            matchesCategory &&
                            matchesKeyword;

                        card.hidden = !isVisible;

                        if (isVisible) {
                            visibleCount += 1;
                        }
                    });

                    document
                        .querySelectorAll('[data-map-section]')
                        .forEach(function(section) {
                            const visibleCards =
                                section.querySelectorAll(
                                    '[data-map-card]:not([hidden])'
                                );

                            section.hidden =
                                visibleCards.length === 0;
                        });

                    emptyState.hidden =
                        visibleCount !== 0;
                }

                filterButtons.forEach(
                    function(button) {
                        button.addEventListener(
                            'click',
                            function() {
                                activeCategory =
                                    button.dataset.mapFilter;

                                filterButtons.forEach(
                                    function(item) {
                                        item.classList.remove(
                                            'is-active'
                                        );
                                    }
                                );

                                button.classList.add(
                                    'is-active'
                                );

                                updateGallery();
                            }
                        );
                    }
                );

                searchInput.addEventListener(
                    'input',
                    updateGallery
                );
            }
        );
    </script>

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {
                const modal =
                    document.getElementById(
                        'map-preview-modal'
                    );

                const modalTitle =
                    document.getElementById(
                        'map-preview-title'
                    );

                const modalFrame =
                    document.getElementById(
                        'map-preview-frame'
                    );

                const newTabButton =
                    document.getElementById(
                        'map-preview-new-tab'
                    );

                const downloadButton =
                    document.getElementById(
                        'map-preview-download'
                    );

                const previewButtons =
                    document.querySelectorAll(
                        '[data-map-preview]'
                    );

                const closeButtons =
                    modal.querySelectorAll(
                        '[data-map-preview-close]'
                    );

                let lastFocusedElement = null;

                function openMapPreview(button) {
                    const mapTitle =
                        button.dataset.mapTitle ||
                        'Galeri Peta Dusun Wareng';

                    const mapUrl =
                        button.dataset.mapUrl;

                    if (!mapUrl) {
                        return;
                    }

                    lastFocusedElement = button;

                    modalTitle.textContent = mapTitle;
                    modalFrame.title =
                        'Pratinjau ' + mapTitle;

                    newTabButton.href = mapUrl;
                    downloadButton.href = mapUrl;

                    modal.hidden = false;

                    document.body.classList.add(
                        'map-preview-open'
                    );

                    /*
                     * Memuat PDF setelah modal terlihat
                     * agar transisinya lebih halus.
                     */
                    window.requestAnimationFrame(
                        function() {
                            modal.classList.add(
                                'is-visible'
                            );

                            modalFrame.src = mapUrl;

                            const closeButton =
                                modal.querySelector(
                                    '.map-preview-close'
                                );

                            closeButton.focus();
                        }
                    );
                }

                function closeMapPreview() {
                    modal.classList.remove(
                        'is-visible'
                    );

                    document.body.classList.remove(
                        'map-preview-open'
                    );

                    window.setTimeout(
                        function() {
                            modal.hidden = true;

                            /*
                             * Menghapus PDF dari iframe
                             * setelah modal ditutup.
                             */
                            modalFrame.src =
                                'about:blank';

                            if (lastFocusedElement) {
                                lastFocusedElement.focus();
                            }
                        },
                        220
                    );
                }

                previewButtons.forEach(
                    function(button) {
                        button.addEventListener(
                            'click',
                            function() {
                                openMapPreview(button);
                            }
                        );
                    }
                );

                closeButtons.forEach(
                    function(button) {
                        button.addEventListener(
                            'click',
                            closeMapPreview
                        );
                    }
                );

                document.addEventListener(
                    'keydown',
                    function(event) {
                        if (
                            event.key === 'Escape' &&
                            !modal.hidden
                        ) {
                            closeMapPreview();
                        }
                    }
                );
            }
        );
    </script>
@endpush
