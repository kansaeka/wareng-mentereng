document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('wareng-map');
    const filterContainer =
        document.getElementById('facility-filters');
    const showAllButton =
        document.getElementById('show-all-facilities');
    const visibleCountElement =
        document.getElementById('visible-facility-count');
    const dataStatusElement =
        document.getElementById('map-data-status');

    if (!mapElement) {
        return;
    }

    /*
     * Titik pusat sementara di wilayah Sumberarum.
     * Nantinya akan diganti dengan koordinat Dusun
     * Wareng yang telah diverifikasi.
     */
    const warengCenter = [-7.57167, 110.18639];

    /*
     * Pengaturan warna setiap kategori.
     */
    const categoryStyles = {
        Pemerintahan: {
            color: '#2f5f98'
        },
        Keagamaan: {
            color: '#7857a8'
        },
        Pertanian: {
            color: '#4f7f42'
        },
        Ekonomi: {
            color: '#b3752d'
        },
        Lainnya: {
            color: '#66746b'
        }
    };

    /*
     * Menyimpan seluruh data GeoJSON setelah berhasil
     * diambil dari file.
     */
    let facilityGeoJsonData = null;

    /*
     * Menyimpan batas awal seluruh objek agar tombol
     * pusatkan peta dapat mengembalikan tampilan.
     */
    let initialBounds = null;

    const map = L.map('wareng-map', {
        zoomControl: true,
        scrollWheelZoom: false
    }).setView(warengCenter, 16);

    /*
     * Peta dasar OpenStreetMap.
     */
    L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);

    /*
     * FeatureGroup digunakan sebagai wadah seluruh
     * marker fasilitas yang sedang aktif.
     */
    const facilityLayer = L.featureGroup().addTo(map);

    /*
 * Membuat checkbox kategori berdasarkan data
 * dari tabel facility_categories.
 */
    function renderCategoryFilters(categories) {
        if (!filterContainer) {
            return;
        }

        filterContainer.replaceChildren();

        categories.forEach(function (category) {
            const label = document.createElement('label');
            label.className = 'facility-filter-option';

            const input = document.createElement('input');
            input.type = 'checkbox';
            input.name = 'facility-category';
            input.value = category.slug;
            input.checked = true;

            const symbol = document.createElement('span');
            symbol.className = 'facility-filter-symbol';

            symbol.style.backgroundColor =
                isValidHexColor(category.marker_color)
                    ? category.marker_color
                    : categoryStyles.Lainnya.color;

            const text = document.createElement('span');
            text.textContent = category.name;

            label.append(
                input,
                symbol,
                text
            );

            filterContainer.appendChild(label);
        });
    }

    /*
     * Mengambil daftar kategori yang dicentang.
     */
    function getActiveCategories() {
        if (!filterContainer) {
            return [];
        }

        const checkedInputs =
            filterContainer.querySelectorAll(
                'input[name="facility-category"]:checked'
            );

        return Array.from(checkedInputs).map(
            function (input) {
                return input.value;
            }
        );
    }

    /*
     * Mendapatkan warna berdasarkan kategori objek.
     */
    function isValidHexColor(value) {
        return /^#[0-9a-fA-F]{6}$/.test(
            String(value ?? '')
        );
    }

    function getCategoryColor(
        category,
        markerColor = null
    ) {
        if (isValidHexColor(markerColor)) {
            return markerColor;
        }

        const style =
            categoryStyles[category] ||
            categoryStyles.Lainnya;

        return style.color;
    }

    /*
     * Membuat simbol untuk setiap titik GeoJSON.
     */
    function createFacilityMarker(feature, latlng) {
        const properties =
            feature.properties || {};

        const category =
            properties.category || 'Lainnya';

        const markerColor =
            properties.marker_color || null;

        return L.circleMarker(latlng, {
            radius: 9,
            color: '#ffffff',
            weight: 2,
            fillColor: getCategoryColor(
                category,
                markerColor
            ),
            fillOpacity: 0.95
        });
    }

    function escapeHtml(value) {
        const text = String(value ?? '');

        return text
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    /*
     * Membuat popup informasi objek.
     */
    function bindFacilityPopup(feature, layer) {
        const properties =
            feature.properties || {};

        const name =
            properties.name || 'Objek tanpa nama';

        const category =
            properties.category || 'Lainnya';

        const description =
            properties.description ||
            'Belum ada deskripsi.';

        const address =
            properties.address ||
            'Alamat belum tersedia.';

        const photoUrl =
            properties.photo_url || '';

        const photoHtml = photoUrl
            ? `
            <img
                class="map-popup-photo"
                src="${escapeHtml(photoUrl)}"
                alt="Foto ${escapeHtml(name)}"
            >
        `
            : '';

        layer.bindPopup(`
        <div class="map-popup">
            <strong>${escapeHtml(name)}</strong>

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
        </div>
    `);

        layer.bindTooltip(
            escapeHtml(name),
            {
                direction: 'top',
                offset: [0, -6]
            }
        );
    }

    /*
     * Menampilkan ulang fasilitas berdasarkan kategori
     * yang sedang dipilih pengguna.
     */
    function renderFacilities() {
        if (!facilityGeoJsonData) {
            return;
        }

        const activeCategories = getActiveCategories();

        const filteredFeatures =
            facilityGeoJsonData.features.filter(
                function (feature) {
                    const properties =
                        feature.properties || {};

                    const categorySlug =
                        properties.category_slug || '';

                    return activeCategories.includes(
                        categorySlug
                    );
                }
            );

        /*
         * Menghapus marker yang sedang tampil sebelum
         * menambahkan hasil filter baru.
         */
        facilityLayer.clearLayers();

        const filteredGeoJson = {
            type: 'FeatureCollection',
            features: filteredFeatures
        };

        const renderedLayer = L.geoJSON(
            filteredGeoJson,
            {
                pointToLayer: createFacilityMarker,
                onEachFeature: bindFacilityPopup
            }
        );

        facilityLayer.addLayer(renderedLayer);

        if (visibleCountElement) {
            visibleCountElement.textContent =
                filteredFeatures.length;
        }

        if (dataStatusElement) {
            if (filteredFeatures.length === 0) {
                dataStatusElement.textContent =
                    'Tidak ada kategori yang sedang ditampilkan.';

                dataStatusElement.classList.add(
                    'map-data-status-warning'
                );
            } else {
                dataStatusElement.textContent =
                    'Data peta berhasil dimuat.';

                dataStatusElement.classList.remove(
                    'map-data-status-warning'
                );
            }
        }
    }

    /*
      * Mengambil data fasilitas dari API Laravel
      * yang bersumber dari PostgreSQL/PostGIS.
     */
    /*
 * Mengambil kategori dan fasilitas secara bersamaan
 * dari API Laravel.
 */
    Promise.all([
        fetch('/api/map/facility-categories')
            .then(function (response) {
                if (!response.ok) {
                    throw new Error(
                        'Kategori fasilitas tidak dapat dimuat.'
                    );
                }

                return response.json();
            }),

        fetch('/api/map/facilities')
            .then(function (response) {
                if (!response.ok) {
                    throw new Error(
                        'Data fasilitas tidak dapat dimuat.'
                    );
                }

                return response.json();
            })
    ])
        .then(function (results) {
            const categoryResponse = results[0];
            const geojsonData = results[1];

            if (
                !categoryResponse ||
                !Array.isArray(categoryResponse.data)
            ) {
                throw new Error(
                    'Struktur data kategori tidak valid.'
                );
            }

            if (
                geojsonData.type !== 'FeatureCollection' ||
                !Array.isArray(geojsonData.features)
            ) {
                throw new Error(
                    'Struktur GeoJSON fasilitas tidak valid.'
                );
            }

            /*
             * Checkbox harus dibuat terlebih dahulu
             * sebelum renderFacilities membaca kategori aktif.
             */
            renderCategoryFilters(
                categoryResponse.data
            );

            facilityGeoJsonData = geojsonData;

            const allFacilitiesLayer =
                L.geoJSON(geojsonData);

            if (allFacilitiesLayer.getBounds().isValid()) {
                initialBounds =
                    allFacilitiesLayer.getBounds();

                map.fitBounds(initialBounds, {
                    padding: [40, 40],
                    maxZoom: 17
                });
            }

            renderFacilities();
        })
        .catch(function (error) {
            console.error(
                'Gagal memuat data peta:',
                error
            );

            if (filterContainer) {
                filterContainer.textContent =
                    'Kategori gagal dimuat.';
            }

            if (dataStatusElement) {
                dataStatusElement.textContent =
                    'Data peta gagal dimuat. Periksa API dan koneksi database.';

                dataStatusElement.classList.add(
                    'map-data-status-error'
                );
            }

            if (visibleCountElement) {
                visibleCountElement.textContent = '0';
            }
        });

    /*
     * Menjalankan filter ketika checkbox berubah.
     */
    if (filterContainer) {
        filterContainer.addEventListener(
            'change',
            function (event) {
                if (
                    event.target.matches(
                        'input[name="facility-category"]'
                    )
                ) {
                    renderFacilities();
                }
            }
        );
    }

    /*
     * Mengaktifkan semua kategori.
     */
    if (showAllButton) {
        showAllButton.addEventListener(
            'click',
            function () {
                const categoryInputs =
                    filterContainer
                        ? filterContainer.querySelectorAll(
                            'input[name="facility-category"]'
                        )
                        : [];

                categoryInputs.forEach(function (input) {
                    input.checked = true;
                });

                renderFacilities();

                if (initialBounds) {
                    map.fitBounds(initialBounds, {
                        padding: [40, 40],
                        maxZoom: 17
                    });
                }
            }
        );
    }

    /*
     * Membuat tombol pusatkan peta di dalam Leaflet.
     */
    const resetControl = L.control({
        position: 'bottomright'
    });

    resetControl.onAdd = function () {
        const button = L.DomUtil.create(
            'button',
            'map-reset-button'
        );

        button.type = 'button';
        button.textContent = 'Pusatkan Peta';
        button.title = 'Kembali ke tampilan awal peta';

        L.DomEvent.disableClickPropagation(button);
        L.DomEvent.disableScrollPropagation(button);

        L.DomEvent.on(button, 'click', function () {
            if (initialBounds) {
                map.fitBounds(initialBounds, {
                    padding: [40, 40],
                    maxZoom: 17
                });

                return;
            }

            map.flyTo(warengCenter, 16, {
                duration: 1.2
            });
        });

        return button;
    };

    resetControl.addTo(map);
});
