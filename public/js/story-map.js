document.addEventListener('DOMContentLoaded', () => {
    const mapElement = document.getElementById('story-map');

    const chapterElements = Array.from(
        document.querySelectorAll('[data-story-chapter]')
    );

    if (
        !mapElement ||
        chapterElements.length === 0 ||
        typeof L === 'undefined'
    ) {
        return;
    }

    /*
     * Titik sementara Dusun Wareng.
     * Belum merupakan koordinat batas resmi.
     */
    const defaultCenter = {
        latitude: -7.57167,
        longitude: 110.18639,
        zoom: 16,
    };

    const chapters = chapterElements.map((element, index) => {
        const latitude = Number.parseFloat(
            element.dataset.latitude
        );

        const longitude = Number.parseFloat(
            element.dataset.longitude
        );

        const zoom = Number.parseInt(
            element.dataset.zoom,
            10
        );

        return {
            index,
            element,
            latitude,
            longitude,

            zoom: Number.isFinite(zoom)
                ? zoom
                : defaultCenter.zoom,

            title:
                element.dataset.title ||
                `Bab ${index + 1}`,

            location:
                element.dataset.location ||
                'Dusun Wareng',

            hasLocation:
                Number.isFinite(latitude) &&
                Number.isFinite(longitude),
        };
    });

    const firstLocatedChapter = chapters.find(
        chapter => chapter.hasLocation
    );

    const initialLatitude =
        firstLocatedChapter?.latitude ??
        defaultCenter.latitude;

    const initialLongitude =
        firstLocatedChapter?.longitude ??
        defaultCenter.longitude;

    const initialZoom =
        firstLocatedChapter?.zoom ??
        defaultCenter.zoom;

    const map = L.map(mapElement, {
        scrollWheelZoom: false,
        zoomControl: true,
    }).setView(
        [
            initialLatitude,
            initialLongitude,
        ],
        initialZoom
    );

    /*
     * Basemap OpenStreetMap.
     */
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 20,

            attribution:
                '&copy; OpenStreetMap contributors',
        }
    ).addTo(map);

    /*
     * =================================================
     * BARU CHECKPOINT 25A
     *
     * Marker bab dan fasilitas dipisahkan menjadi
     * dua layer agar dapat dinyalakan atau dimatikan.
     * =================================================
     */
    const chapterLayer =
        L.featureGroup().addTo(map);

    const facilityLayer =
        L.featureGroup().addTo(map);

    /*
     * Kontrol layer pada kanan atas peta.
     */
    L.control.layers(
        {},
        {
            'Lokasi Bab': chapterLayer,
            'Sarana dan Prasarana': facilityLayer,
        },
        {
            collapsed: true,
            position: 'topright',
        }
    ).addTo(map);

    /*
     * Menyimpan marker setiap bab.
     */
    const markerRecords = new Map();

    /*
     * Membuat simbol marker nomor bab.
     */
    function createMarkerIcon(
        number,
        isActive = false
    ) {
        return L.divIcon({
            className:
                `story-map-marker${isActive
                    ? ' is-active'
                    : ''
                }`,

            html: `<span>${number}</span>`,

            iconSize: [40, 40],
            iconAnchor: [20, 20],
        });
    }

    /*
     * Membuat marker seluruh bab.
     */
    chapters.forEach(chapter => {
        if (!chapter.hasLocation) {
            return;
        }

        const number = chapter.index + 1;

        const marker = L.marker(
            [
                chapter.latitude,
                chapter.longitude,
            ],
            {
                icon: createMarkerIcon(
                    number,

                    chapter.index ===
                    firstLocatedChapter?.index
                ),

                title: chapter.title,
            }
        ).addTo(chapterLayer);

        marker.bindTooltip(
            chapter.title,
            {
                direction: 'top',
                offset: [0, -18],
            }
        );

        marker.on('click', () => {
            chapter.element.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            });

            setActiveChapter(
                chapter.index,
                true
            );
        });

        markerRecords.set(
            chapter.index,
            {
                marker,
                number,
            }
        );
    });

    const activeTitleElement =
        document.getElementById(
            'active-story-title'
        );

    const activeLocationElement =
        document.getElementById(
            'active-story-location'
        );

    /*
     * Indikator progres bab Jelajah Wareng.
     */
    const progressLabelElement =
        document.getElementById(
            'story-progress-label'
        );

    const progressPercentElement =
        document.getElementById(
            'story-progress-percent'
        );

    const progressTrackElement =
        document.getElementById(
            'story-progress-track'
        );

    const progressFillElement =
        document.getElementById(
            'story-progress-fill'
        );

    /*
     * Tombol navigasi bab.
     */
    const previousChapterButton =
        document.getElementById(
            'story-previous-button'
        );

    const nextChapterButton =
        document.getElementById(
            'story-next-button'
        );

    const totalChapters = chapters.length;

    let activeChapterIndex = null;

    /*
     * Mengaktifkan satu bab dan memindahkan peta.
     */
    function setActiveChapter(
        chapterIndex,
        moveMap = true
    ) {
        const chapter = chapters.find(
            item => item.index === chapterIndex
        );

        if (!chapter) {
            return;
        }

        activeChapterIndex = chapterIndex;

        /*
         * Mengubah tampilan kartu bab aktif.
         */
        chapters.forEach(item => {
            item.element.classList.toggle(
                'is-active',
                item.index === chapterIndex
            );
        });

        /*
         * Mengubah simbol marker bab aktif.
         */
        markerRecords.forEach(
            (record, markerIndex) => {
                record.marker.setIcon(
                    createMarkerIcon(
                        record.number,
                        markerIndex === chapterIndex
                    )
                );
            }
        );

        if (activeTitleElement) {
            activeTitleElement.textContent =
                chapter.title;
        }

        if (activeLocationElement) {
            activeLocationElement.textContent =
                chapter.location;
        }

        /*
 * Memperbarui progress berdasarkan
 * posisi bab yang sedang aktif.
 */
        const currentChapterNumber =
            chapter.index + 1;

        const progressPercentage =
            totalChapters > 0
                ? Math.round(
                    (
                        currentChapterNumber /
                        totalChapters
                    ) * 100
                )
                : 0;

        const formattedCurrentChapter =
            String(currentChapterNumber)
                .padStart(2, '0');

        const formattedTotalChapters =
            String(totalChapters)
                .padStart(2, '0');

        if (progressLabelElement) {
            progressLabelElement.textContent =
                `Bab ${formattedCurrentChapter} ` +
                `dari ${formattedTotalChapters}`;
        }

        if (progressPercentElement) {
            progressPercentElement.textContent =
                `${progressPercentage}%`;
        }

        if (progressFillElement) {
            progressFillElement.style.width =
                `${progressPercentage}%`;
        }

        if (progressTrackElement) {
            progressTrackElement.setAttribute(
                'aria-valuenow',
                String(currentChapterNumber)
            );

            progressTrackElement.setAttribute(
                'aria-valuemax',
                String(totalChapters)
            );
        }

        /*
 * Menonaktifkan tombol sesuai posisi bab.
 */
        if (previousChapterButton) {
            const isFirstChapter =
                chapterIndex === 0;

            previousChapterButton.disabled =
                isFirstChapter;

            previousChapterButton.setAttribute(
                'aria-disabled',
                String(isFirstChapter)
            );
        }

        if (nextChapterButton) {
            const isLastChapter =
                chapterIndex ===
                totalChapters - 1;

            nextChapterButton.disabled =
                isLastChapter;

            nextChapterButton.setAttribute(
                'aria-disabled',
                String(isLastChapter)
            );
        }

        if (
    moveMap &&
    chapter.hasLocation
) {
    /*
     * Menghentikan animasi sebelumnya agar peta
     * langsung mengikuti bab terbaru.
     */
    map.stop();

    map.flyTo(
        [
            chapter.latitude,
            chapter.longitude,
        ],
        chapter.zoom,
        {
            animate: true,
            duration: 0.8,
            easeLinearity: 0.25,
        }
    );
}
    }

    /*
 * Berpindah ke bab tertentu melalui
 * tombol sebelumnya atau berikutnya.
 */
    function navigateToChapter(chapterIndex) {
        const targetChapter = chapters.find(
            chapter =>
                chapter.index === chapterIndex
        );

        if (!targetChapter) {
            return;
        }

        targetChapter.element.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
        });

        setActiveChapter(
            chapterIndex,
            true
        );
    }

    /*
 * Tombol menuju bab sebelumnya.
 */
    previousChapterButton?.addEventListener(
        'click',
        () => {
            if (
                activeChapterIndex === null ||
                activeChapterIndex <= 0
            ) {
                return;
            }

            navigateToChapter(
                activeChapterIndex - 1
            );
        }
    );

    /*
     * Tombol menuju bab berikutnya.
     */
    nextChapterButton?.addEventListener(
        'click',
        () => {
            if (
                activeChapterIndex === null ||
                activeChapterIndex >=
                totalChapters - 1
            ) {
                return;
            }

            navigateToChapter(
                activeChapterIndex + 1
            );
        }
    );

    /*
     * =================================================
     * BARU CHECKPOINT 25A
     *
     * Membersihkan teks yang dimasukkan ke popup.
     * =================================================
     */
    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    /*
     * Memeriksa format warna dari database.
     */
    function isValidHexColor(value) {
        return /^#[0-9a-fA-F]{6}$/.test(
            String(value ?? '')
        );
    }

    /*
     * =================================================
     * BARU CHECKPOINT 25A
     *
     * Membuat marker sarana dan prasarana.
     * Warna mengikuti marker_color dari kategori.
     * =================================================
     */
    function createFacilityMarker(
        feature,
        latlng
    ) {
        const properties =
            feature.properties || {};

        const markerColor = isValidHexColor(
            properties.marker_color
        )
            ? properties.marker_color
            : '#66746b';

        return L.circleMarker(
            latlng,
            {
                radius: 7,
                color: '#ffffff',
                weight: 2,
                fillColor: markerColor,
                fillOpacity: 0.9,
            }
        );
    }

    /*
     * =================================================
     * BARU CHECKPOINT 25A
     *
     * Membuat popup fasilitas.
     * =================================================
     */
    function bindFacilityPopup(
        feature,
        layer
    ) {
        const properties =
            feature.properties || {};

        const id =
            properties.id ?? '';

        const name =
            properties.name ||
            'Sarana dan prasarana tanpa nama';

        const category =
            properties.category ||
            'Kategori belum tersedia';

        const description =
            properties.description ||
            'Deskripsi belum tersedia.';

        const address =
            properties.address ||
            'Alamat belum tersedia.';

        const photoUrl =
            properties.photo_url || '';

        /*
         * Foto hanya ditampilkan apabila tersedia.
         */
        const photoHtml = photoUrl
            ? `
                <img
                    class="map-popup-photo"
                    src="${escapeHtml(photoUrl)}"
                    alt="Foto ${escapeHtml(name)}"
                    loading="lazy"
                >
            `
            : '';

        /*
         * Parameter facility dipersiapkan agar nanti
         * halaman peta lengkap dapat langsung fokus
         * pada fasilitas yang dipilih.
         */
        const facilityQuery = id
            ? `?facility=${encodeURIComponent(id)}`
            : '';

        layer.bindPopup(`
            <div class="map-popup story-facility-popup">
                <strong>
                    ${escapeHtml(name)}
                </strong>

                ${photoHtml}

                <span class="map-popup-category">
                    ${escapeHtml(category)}
                </span>

                <p>
                    ${escapeHtml(description)}
                </p>

                <small>
                    ${escapeHtml(address)}
                </small>

                <a
                    class="story-popup-link"
                    href="/peta${facilityQuery}"
                >
                    Buka Peta Interaktif →
                </a>
            </div>
        `);

        layer.bindTooltip(
            escapeHtml(name),
            {
                direction: 'top',
                offset: [0, -6],
            }
        );
    }

    /*
     * =================================================
     * BARU CHECKPOINT 25A
     *
     * Mengambil sarana dan prasarana dari API Laravel.
     * =================================================
     */
    fetch('/api/map/facilities', {
        headers: {
            Accept: 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(
                    'Data sarana dan prasarana tidak dapat dimuat.'
                );
            }

            return response.json();
        })
        .then(geojsonData => {
            /*
             * Memastikan data API berbentuk
             * GeoJSON FeatureCollection.
             */
            if (
                geojsonData.type !==
                'FeatureCollection' ||
                !Array.isArray(
                    geojsonData.features
                )
            ) {
                throw new Error(
                    'Struktur GeoJSON fasilitas tidak valid.'
                );
            }

            /*
             * Mengubah GeoJSON menjadi marker Leaflet.
             */
            const renderedFacilities =
                L.geoJSON(
                    geojsonData,
                    {
                        pointToLayer:
                            createFacilityMarker,

                        onEachFeature:
                            bindFacilityPopup,
                    }
                );

            facilityLayer.addLayer(
                renderedFacilities
            );
        })
        .catch(error => {
            /*
             * Jika API fasilitas gagal, fungsi utama
             * Jelajah Wareng tetap dapat digunakan.
             */
            console.error(
                'Gagal memuat sarana dan prasarana:',
                error
            );
        });

    /*
     * Interaksi kartu bab.
     */
    chapterElements.forEach(element => {
        const chapterIndex =
            Number.parseInt(
                element.dataset.chapterIndex,
                10
            );

        /*
         * Klik kartu bab.
         */
        element.addEventListener(
            'click',
            event => {
                /*
                 * Jangan aktifkan kartu jika pengguna
                 * sedang menekan link atau tombol.
                 */
                if (
                    event.target.closest(
                        'a, button'
                    )
                ) {
                    return;
                }

                setActiveChapter(
                    chapterIndex,
                    true
                );
            }
        );

        /*
         * Dukungan keyboard.
         */
        element.addEventListener(
            'keydown',
            event => {
                if (
                    event.key !== 'Enter' &&
                    event.key !== ' '
                ) {
                    return;
                }

                event.preventDefault();

                setActiveChapter(
                    chapterIndex,
                    true
                );
            }
        );

        /*
         * Tombol lihat lokasi pada peta.
         */
        const focusButton =
            element.querySelector(
                '[data-focus-chapter]'
            );

        focusButton?.addEventListener(
            'click',
            event => {
                event.stopPropagation();

                setActiveChapter(
                    chapterIndex,
                    true
                );
            }
        );
    });

    /*
     * Mengaktifkan bab berdasarkan posisi scroll.
     */
    const observer = new IntersectionObserver(
        entries => {
            const visibleEntries = entries
                .filter(
                    entry =>
                        entry.isIntersecting
                )
                .sort(
                    (first, second) =>
                        second.intersectionRatio -
                        first.intersectionRatio
                );

            const mostVisibleEntry =
                visibleEntries[0];

            if (!mostVisibleEntry) {
                return;
            }

            const chapterIndex =
                Number.parseInt(
                    mostVisibleEntry.target
                        .dataset.chapterIndex,
                    10
                );

            if (
                chapterIndex !==
                activeChapterIndex
            ) {
                setActiveChapter(
                    chapterIndex,
                    true
                );
            }
        },
        {
            root: null,

            rootMargin:
                '-20% 0px -50% 0px',

            threshold: [
                0.15,
                0.35,
                0.55,
            ],
        }
    );

    chapterElements.forEach(element => {
        observer.observe(element);
    });

    /*
     * Mengaktifkan bab pertama saat halaman dibuka.
     */
    const initialChapterIndex =
        firstLocatedChapter?.index ??
        chapters[0].index;

    setActiveChapter(
        initialChapterIndex,
        false
    );

    /*
     * Memperbaiki ukuran peta ketika layar berubah.
     */
    window.addEventListener(
        'resize',
        () => {
            map.invalidateSize();
        }
    );
});
