@php
    $title = $map['title'] ?? 'Peta Dusun Wareng';

    $category = $map['category'] ?? 'Lainnya';

    $group = $map['group'] ?? 'Galeri Peta';

    $description = $map['description'] ?? 'Informasi peta Dusun Wareng.';

    $year = $map['year'] ?? now()->year;

    $format = $map['format'] ?? 'PDF';

    $status = $map['status'] ?? 'Dalam penyusunan';

    $categorySlug = $map['category_slug'] ?? \Illuminate\Support\Str::slug($category);

    $groupSlug = $map['group_slug'] ?? \Illuminate\Support\Str::slug($group);

    $searchText =
        $map['search_text'] ??
        \Illuminate\Support\Str::of(implode(' ', [$title, $group, $category, $description, $map['keywords'] ?? '']))
            ->lower()
            ->squish()
            ->toString();

    $thumbnailExists = $map['thumbnail_exists'] ?? false;

    $downloadExists = $map['download_exists'] ?? false;
@endphp

<article class="map-gallery-card" data-map-card data-map-category="{{ $categorySlug }}"
    data-map-group="{{ $groupSlug }}" data-map-search="{{ $searchText }}">
    <div class="map-gallery-card-media">
        @if ($thumbnailExists)
            <img src="{{ asset($map['thumbnail']) }}" alt="{{ $title }}" loading="lazy">
        @else
            <div class="map-gallery-placeholder" aria-label="Pratinjau peta belum tersedia">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m3 6 6-3 6 3 6-3
                        v15l-6 3-6-3-6 3V6Z"></path>

                    <path d="M9 3v15"></path>
                    <path d="M15 6v15"></path>
                </svg>

                <span>
                    Pratinjau belum tersedia
                </span>
            </div>
        @endif

        <span class="map-gallery-category">
            {{ $category }}
        </span>

        <span
            class="
                map-gallery-status
                {{ $downloadExists ? 'is-available' : '' }}
            ">
            {{ $downloadExists ? 'Tersedia' : $status }}
        </span>
    </div>

    <div class="map-gallery-card-body">
        <div class="map-gallery-meta">
            <span>{{ $group }}</span>

            <span aria-hidden="true">•</span>

            <span>{{ $year }}</span>

            <span aria-hidden="true">•</span>

            <span>{{ $format }}</span>
        </div>

        <h3>
            {{ $title }}
        </h3>

        <p>
            {{ $description }}
        </p>

        <div class="map-gallery-actions">
            @if ($downloadExists)
                <button type="button"
                    class="
                        map-gallery-button
                        map-gallery-button-secondary
                    "
                    data-map-preview data-map-title="{{ $title }}"
                    data-map-url="{{ asset($map['download']) }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 12s3.5-6 9-6
                            9 6 9 6-3.5 6-9 6
                            -9-6-9-6Z"></path>

                        <circle cx="12" cy="12" r="2.5"></circle>
                    </svg>

                    Lihat Peta
                </button>

                <a href="{{ asset($map['download']) }}"
                    class="
                        map-gallery-button
                        map-gallery-button-primary
                    "
                    download>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 3v12"></path>
                        <path d="m7 10 5 5 5-5"></path>
                        <path d="M5 21h14"></path>
                    </svg>

                    Unduh PDF
                </a>
            @else
                <span
                    class="
                        map-gallery-button
                        map-gallery-button-disabled
                    "
                    aria-disabled="true">
                    Dokumen belum tersedia
                </span>
            @endif
        </div>
    </div>
</article>
