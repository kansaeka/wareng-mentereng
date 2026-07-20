document.addEventListener('DOMContentLoaded', function () {
    const mapElement =
        document.getElementById('main-wareng-map');

    const filterContainer =
        document.getElementById('map-page-filters');

    const showAllButton =
        document.getElementById('map-page-show-all');

    const visibleCountElement =
        document.getElementById('map-page-visible-count');

    const dataStatusElement =
        document.getElementById('map-page-data-status');

    /*
 * Elemen panel navigasi rute.
 */
    const routePanelElement =
        document.getElementById(
            'map-route-panel'
        );

    const routeStatusBadgeElement =
        document.getElementById(
            'map-route-status-badge'
        );

    const routeMessageElement =
        document.getElementById(
            'map-route-message'
        );

    const routeDestinationElement =
        document.getElementById(
            'map-route-destination'
        );

    const routeDestinationNameElement =
        document.getElementById(
            'map-route-destination-name'
        );

    const routeSummaryElement =
        document.getElementById(
            'map-route-summary'
        );

    const routeDistanceElement =
        document.getElementById(
            'map-route-distance'
        );

    const routeDurationElement =
        document.getElementById(
            'map-route-duration'
        );

    const routeRemainingDistanceElement =
        document.getElementById(
            'map-route-remaining-distance'
        );

    /*
 * Elemen pilihan alternatif rute.
 */
    const routeAlternativesElement =
        document.getElementById(
            'map-route-alternatives'
        );

    const routeAlternativeListElement =
        document.getElementById(
            'map-route-alternative-list'
        );

    const routeAlternativeCountElement =
        document.getElementById(
            'map-route-alternative-count'
        );

    /*
 * Elemen petunjuk langkah perjalanan.
 */
    const routeDirectionsElement =
        document.getElementById(
            'map-route-directions'
        );

    const routeStepCountElement =
        document.getElementById(
            'map-route-step-count'
        );

    const routeStepListElement =
        document.getElementById(
            'map-route-step-list'
        );

    const routeStartButton =
        document.getElementById(
            'map-route-start-button'
        );

    const routeClearButton =
        document.getElementById(
            'map-route-clear-button'
        );

    const routeNavigationStartButton =
        document.getElementById(
            'map-route-navigation-start-button'
        );

    const routeNavigationStopButton =
        document.getElementById(
            'map-route-navigation-stop-button'
        );

    const searchInput =
        document.getElementById('map-location-search');

    const clearSearchButton =
        document.getElementById('clear-map-search');

    const locationListElement =
        document.getElementById('map-location-list');

    const landUseLegendElement =
        document.getElementById('land-use-legend');

    const selectedTitle =
        document.getElementById('selected-feature-title');

    const selectedCategory =
        document.getElementById('selected-feature-category');

    const selectedDescription =
        document.getElementById(
            'selected-feature-description'
        );

    const selectedAddress =
        document.getElementById(
            'selected-feature-address'
        );

    const measureDistanceButton =
        document.getElementById(
            'measure-distance-button'
        );

    const measureAreaButton =
        document.getElementById(
            'measure-area-button'
        );

    const clearMeasurementsButton =
        document.getElementById(
            'clear-measurements-button'
        );

    const findUserLocationButton =
        document.getElementById(
            'find-user-location-button'
        );

    const measurementResultElement =
        document.getElementById(
            'measurement-result'
        );

    const geolocationStatusElement =
        document.getElementById(
            'geolocation-status'
        );

    const cursorCoordinateElement =
        document.getElementById(
            'cursor-coordinate'
        );

    const selectedCoordinateElement =
        document.getElementById(
            'selected-coordinate'
        );

    const selectCoordinateButton =
        document.getElementById(
            'select-coordinate-button'
        );

    const copyCoordinateButton =
        document.getElementById(
            'copy-coordinate-button'
        );

    const clearCoordinateButton =
        document.getElementById(
            'clear-coordinate-button'
        );

    const coordinateStatusElement =
        document.getElementById(
            'coordinate-status'
        );

    if (!mapElement) {
        return;
    }

    /*
     * Titik pusat masih bersifat sementara.
     */
    const warengCenter = [-7.57167, 110.18639];

    /*
     * Warna simbol berdasarkan kategori.
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
 * Warna kategori penggunaan lahan.
 *
 * Seluruh warna dan label disimpan di satu tempat
 * agar style peta dan legenda selalu sama.
 */
    const landUseStyles = {
        Permukiman: {
            label: 'Permukiman',
            color: '#b85c47',
            fillColor: '#e88a73'
        },

        Pertanian: {
            label: 'Pertanian',
            color: '#5f8b3d',
            fillColor: '#9ac66f'
        },

        Kebun: {
            label: 'Kebun',
            color: '#277354',
            fillColor: '#59a982'
        },

        'Ruang Terbuka': {
            label: 'Ruang Terbuka',
            color: '#92763c',
            fillColor: '#d1b66f'
        },

        Lainnya: {
            label: 'Lainnya',
            color: '#68756d',
            fillColor: '#aeb8b1'
        }
    };

    let facilityGeoJsonData = null;
    let initialBounds = null;
    let boundaryBounds = null;
    let searchTimer = null;
    let activeMeasurementMode = null;
    let coordinateSelectionMode = false;
    let selectedCoordinate = null;
    let selectedCoordinateMarker = null;
    let selectedRouteDestination = null;
    let currentUserLocation = null;
    let userLocationMarker = null;
    let userAccuracyCircle = null;
    let availableRoutes = [];
    let routeLayers = [];
    let activeRouteIndex = 0;
    let navigationWatchId = null;
    let isNavigationActive = false;
    let lastRerouteLocation = null;
    let lastRerouteTime = 0;
    let routeRequestInProgress = false;

    /*
     * Rute diperbarui setelah pengguna bergerak
     * minimal 30 meter dan jeda minimal 12 detik.
     */
    const rerouteDistanceThreshold = 30;
    const rerouteTimeThreshold = 12000;

    /*
 * Pengguna dianggap tiba ketika berada
 * dalam radius 25 meter dari tujuan.
 */
    const arrivalDistanceThreshold = 25;

    /*
     * Status mendekati tujuan ditampilkan
     * ketika jarak tersisa kurang dari 100 meter.
     */
    const approachingDistanceThreshold = 100;

    /*
     * Penanda agar notifikasi tiba hanya
     * dijalankan satu kali.
     */
    let hasArrivedAtDestination = false;

    /*
 * Membaca ID fasilitas dari URL.
 *
 * Contoh:
 * /peta?facility=15
 */
    const urlParameters =
        new URLSearchParams(
            window.location.search
        );

    const requestedFacilityId =
        Number.parseInt(
            urlParameters.get('facility'),
            10
        );

    const hasRequestedFacility =
        Number.isFinite(
            requestedFacilityId
        );


    /*
     * Menyimpan hubungan antara ID data
     * dengan simbol Leaflet.
     */
    const renderedLayerById = new Map();

    const map = L.map('main-wareng-map', {
        zoomControl: true,
        scrollWheelZoom: true
    }).setView(warengCenter, 16);

    /*
 * Kontrol kecil untuk menampilkan
 * posisi koordinat kursor.
 */
    const coordinateReadoutControl = L.control({
        position: 'bottomleft'
    });

    coordinateReadoutControl.onAdd = function () {
        const container = L.DomUtil.create(
            'div',
            'map-coordinate-readout'
        );

        container.textContent =
            'Lat: — | Lng: —';

        L.DomEvent.disableClickPropagation(
            container
        );

        return container;
    };

    coordinateReadoutControl.addTo(map);

    const coordinateReadoutContainer =
        coordinateReadoutControl.getContainer();

    /*
 * Menampilkan posisi kursor.
 * Pada perangkat sentuh, posisi akan
 * diperbarui ketika peta disentuh.
 */
    map.on('mousemove', function (event) {
        const coordinate =
            formatCoordinate(event.latlng);

        if (cursorCoordinateElement) {
            cursorCoordinateElement.textContent =
                coordinate;
        }

        if (coordinateReadoutContainer) {
            coordinateReadoutContainer.textContent =
                `Lat: ${event.latlng.lat.toFixed(6)} | ` +
                `Lng: ${event.latlng.lng.toFixed(6)}`;
        }
    });

    /*
 * Salinan cadangan untuk browser yang
 * tidak mendukung Clipboard API.
 */
    function fallbackCopyText(text) {
        const temporaryTextArea =
            document.createElement('textarea');

        temporaryTextArea.value = text;
        temporaryTextArea.setAttribute(
            'readonly',
            ''
        );

        temporaryTextArea.style.position =
            'fixed';

        temporaryTextArea.style.opacity =
            '0';

        document.body.appendChild(
            temporaryTextArea
        );

        temporaryTextArea.select();
        temporaryTextArea.setSelectionRange(
            0,
            text.length
        );

        const copied =
            document.execCommand('copy');

        document.body.removeChild(
            temporaryTextArea
        );

        return copied;
    }

    /*
     * Menyalin koordinat dengan Clipboard API
     * atau metode cadangan.
     */
    async function copySelectedCoordinate() {
        if (!selectedCoordinate) {
            setCoordinateStatus(
                'Pilih titik terlebih dahulu.',
                'is-error'
            );

            return;
        }

        const coordinateText =
            formatCoordinate(selectedCoordinate);

        try {
            if (
                navigator.clipboard &&
                window.isSecureContext
            ) {
                await navigator.clipboard.writeText(
                    coordinateText
                );
            } else {
                const copied =
                    fallbackCopyText(coordinateText);

                if (!copied) {
                    throw new Error(
                        'Penyalinan tidak didukung.'
                    );
                }
            }

            setCoordinateStatus(
                `Koordinat ${coordinateText} berhasil disalin.`,
                'is-success'
            );
        } catch (error) {
            console.error(
                'Koordinat gagal disalin:',
                error
            );

            setCoordinateStatus(
                'Koordinat gagal disalin. Salin secara manual dari bagian titik terpilih.',
                'is-error'
            );
        }
    }

    if (copyCoordinateButton) {
        copyCoordinateButton.addEventListener(
            'click',
            copySelectedCoordinate
        );
    }

    function clearSelectedCoordinate() {
        selectedCoordinateLayer.clearLayers();

        selectedCoordinate = null;
        selectedCoordinateMarker = null;

        setCoordinateSelectionMode(false);

        if (selectedCoordinateElement) {
            selectedCoordinateElement.textContent =
                'Belum ada titik dipilih';
        }

        updateCoordinateButtons();

        setCoordinateStatus(
            'Titik koordinat telah dihapus.'
        );
    }

    if (clearCoordinateButton) {
        clearCoordinateButton.addEventListener(
            'click',
            clearSelectedCoordinate
        );
    }

    updateCoordinateButtons();

    /*
 * Peta dasar jalan dari OpenStreetMap.
 */
    const streetBasemap = L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution:
                '&copy; OpenStreetMap contributors'
        }
    );

    /*
     * Peta topografi.
     * Cocok untuk melihat bentuk medan dan kontur.
     */
    const topographyBasemap = L.tileLayer(
        'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 17,
            attribution:
                'Map data: &copy; OpenStreetMap contributors, ' +
                'Map style: &copy; OpenTopoMap'
        }
    );

    /*
     * Peta Rupabumi Indonesia dari
     * Badan Informasi Geospasial.
     *
     * maxNativeZoom berarti tile asli tersedia
     * hingga zoom 16. Leaflet tetap dapat memperbesar
     * tampilannya setelah zoom tersebut.
     */
    const indonesiaBasemap = L.tileLayer(
        'https://geoservices.big.go.id/rbi/rest/services/' +
        'BASEMAP/Rupabumi_Indonesia/MapServer/' +
        'tile/{z}/{y}/{x}',
        {
            maxNativeZoom: 16,
            maxZoom: 19,
            attribution:
                '&copy; Badan Informasi Geospasial'
        }
    );

    /*
     * Hanya satu peta dasar yang dimasukkan
     * sebagai tampilan awal.
     */
    streetBasemap.addTo(map);

    /*
 * Pane digunakan untuk mengatur urutan tampilan layer.
 * Batas berada paling bawah, jalan di atas batas,
 * dan fasilitas berada di atas keduanya.
 */
    map.createPane('boundaryPane');
    map.getPane('boundaryPane').style.zIndex = 350;

    /*
     * Penggunaan lahan ditempatkan di atas batas,
     * tetapi masih berada di bawah jaringan jalan.
     */
    map.createPane('landUsePane');
    map.getPane('landUsePane').style.zIndex = 355;

    map.createPane('roadPane');
    map.getPane('roadPane').style.zIndex = 360;

    /*
     * Layer poligon batas wilayah.
     */
    const boundaryLayer = L.geoJSON(null, {
        pane: 'boundaryPane',

        style: function () {
            return {
                color: '#26734d',
                weight: 3,
                opacity: 0.95,
                dashArray: '9 7',
                fillColor: '#72b58b',
                fillOpacity: 0.14
            };
        },

        onEachFeature: function (feature, layer) {
            const properties = feature.properties || {};

            const name =
                properties.name || 'Batas wilayah';

            const description =
                properties.description ||
                'Belum ada deskripsi.';

            const source =
                properties.source ||
                'Sumber belum tersedia.';

            layer.bindPopup(`
            <div class="map-popup">
                <strong>${escapeHtml(name)}</strong>

                <span class="map-popup-category">
                    Batas Wilayah
                </span>

                <p>
                    ${escapeHtml(description)}
                </p>

                <small>
                    Sumber: ${escapeHtml(source)}
                </small>
            </div>
        `);
        }
    }).addTo(map);

    /*
 * Layer poligon penggunaan lahan.
 */
    const landUseLayer = L.geoJSON(null, {
        pane: 'landUsePane',

        style: getLandUseStyle,

        onEachFeature: bindLandUseInteraction
    }).addTo(map);

    /*
     * Layer jaringan jalan.
     */
    const roadLayer = L.geoJSON(null, {
        pane: 'roadPane',

        style: function (feature) {
            const properties = feature.properties || {};

            if (properties.road_type === 'Jalan Utama') {
                return {
                    color: '#d17728',
                    weight: 6,
                    opacity: 0.95
                };
            }

            return {
                color: '#dba45e',
                weight: 4,
                opacity: 0.9,
                dashArray: '6 4'
            };
        },

        onEachFeature: function (feature, layer) {
            const properties = feature.properties || {};

            const name =
                properties.name || 'Jalan tanpa nama';

            const roadType =
                properties.road_type ||
                'Jenis jalan belum tersedia';

            const surface =
                properties.surface ||
                'Belum tersedia';

            const condition =
                properties.condition ||
                'Belum tersedia';

            layer.bindPopup(`
            <div class="map-popup">
                <strong>${escapeHtml(name)}</strong>

                <span class="map-popup-category">
                    ${escapeHtml(roadType)}
                </span>

                <p>
                    Permukaan: ${escapeHtml(surface)}
                    <br>
                    Kondisi: ${escapeHtml(condition)}
                </p>
            </div>
        `);

            layer.bindTooltip(escapeHtml(name), {
                sticky: true
            });
        }
    }).addTo(map);

    /*
     * Wadah titik fasilitas.
     */
    const facilityLayer = L.featureGroup().addTo(map);

    /*
    * Menyimpan garis dan poligon hasil pengukuran.
    */
    const measurementLayer =
        L.featureGroup().addTo(map);

    /*
     * Menyimpan marker dan lingkaran akurasi
     * lokasi pengguna.
     */
    const userLocationLayer =
        L.featureGroup().addTo(map);

    /*
    * Menyimpan marker titik koordinat
    * yang dipilih pengguna.
    */
    const selectedCoordinateLayer =
        L.featureGroup().addTo(map);

    /*
     * Pilihan peta dasar.
     */
    const baseMaps = {
        'Peta Jalan': streetBasemap,
        'Peta Topografi': topographyBasemap,
        'Rupabumi Indonesia': indonesiaBasemap
    };

    /*
     * Layer tambahan yang dapat dinyalakan
     * dan dimatikan pengguna.
     */
    const overlayMaps = {
        'Batas Dusun': boundaryLayer,
        'Penggunaan Lahan': landUseLayer,
        'Jaringan Jalan': roadLayer,
        'Objek Fasilitas': facilityLayer,
        'Hasil Pengukuran': measurementLayer,
        'Lokasi Saya': userLocationLayer,
        'Titik Koordinat': selectedCoordinateLayer
    };

    const layerControl = L.control.layers(
        baseMaps,
        overlayMaps,
        {
            position: 'topright',

            /*
             * true membuat kontrol tampil sebagai
             * tombol ikon dan dapat dibuka-tutup.
             */
            collapsed: true
        }
    );

    layerControl.addTo(map);

    /*
     * Menambahkan keterangan aksesibilitas
     * pada tombol kontrol layer.
     */
    const layerControlContainer =
        layerControl.getContainer();

    const layerControlToggle =
        layerControlContainer.querySelector(
            '.leaflet-control-layers-toggle'
        );

    if (layerControlToggle) {
        layerControlToggle.title =
            'Pilih peta dasar dan layer';

        layerControlToggle.setAttribute(
            'aria-label',
            'Buka pilihan peta dasar dan layer'
        );
    }

    /*
 * Semua geometri yang digambar menggunakan
 * alat ukur dimasukkan ke measurementLayer.
 */
    map.pm.setGlobalOptions({
        layerGroup: measurementLayer,
        snappable: true,
        allowSelfIntersection: false
    });

    /*
     * Mencegah data dimasukkan ke popup
     * sebagai HTML yang tidak aman.
     */
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
 * Mengambil konfigurasi warna berdasarkan
 * kategori penggunaan lahan.
 */
    function getLandUseConfiguration(landUse) {
        return (
            landUseStyles[landUse] ||
            landUseStyles.Lainnya
        );
    }

    /*
     * Menentukan style awal setiap poligon.
     */
    function getLandUseStyle(feature) {
        const properties = feature.properties || {};

        const landUse =
            properties.land_use || 'Lainnya';

        const configuration =
            getLandUseConfiguration(landUse);

        return {
            color: configuration.color,
            weight: 2,
            opacity: 0.9,
            fillColor: configuration.fillColor,
            fillOpacity: 0.38
        };
    }

    /*
     * Menghasilkan keterangan luas.
     */
    function getLandUseAreaText(area) {
        if (
            area === null ||
            area === undefined ||
            area === ''
        ) {
            return 'Belum dihitung';
        }

        return `${area} hektare`;
    }

    /*
     * Membuat interaksi popup dan hover
     * pada setiap poligon penggunaan lahan.
     */
    function bindLandUseInteraction(feature, layer) {
        const properties = feature.properties || {};

        const name =
            properties.name || 'Area tanpa nama';

        const landUse =
            properties.land_use || 'Lainnya';

        const description =
            properties.description ||
            'Belum ada deskripsi.';

        const area =
            getLandUseAreaText(properties.area_ha);

        const source =
            properties.source ||
            'Sumber belum tersedia.';

        layer.bindPopup(`
        <div class="map-popup">
            <strong>${escapeHtml(name)}</strong>

            <span class="map-popup-category">
                ${escapeHtml(landUse)}
            </span>

            <p>
                ${escapeHtml(description)}
            </p>

            <small>
                Luas: ${escapeHtml(area)}
                <br>
                Sumber: ${escapeHtml(source)}
            </small>
        </div>
    `);

        layer.bindTooltip(
            escapeHtml(name),
            {
                sticky: true
            }
        );

        /*
         * Menonjolkan poligon ketika kursor
         * diarahkan ke area tersebut.
         */
        layer.on('mouseover', function () {
            layer.setStyle({
                weight: 4,
                fillOpacity: 0.58
            });
        });

        /*
         * Mengembalikan style awal setelah
         * kursor meninggalkan poligon.
         */
        layer.on('mouseout', function () {
            landUseLayer.resetStyle(layer);
        });
    }

    /*
     * Membuat legenda penggunaan lahan
     * berdasarkan objek landUseStyles.
     */
    function renderLandUseLegend() {
        if (!landUseLegendElement) {
            return;
        }

        landUseLegendElement.innerHTML = '';

        Object.entries(landUseStyles)
            .filter(function ([category]) {
                return category !== 'Lainnya';
            })
            .forEach(function ([category, configuration]) {
                const item =
                    document.createElement('div');

                item.className =
                    'land-use-legend-item';

                const symbol =
                    document.createElement('span');

                symbol.className =
                    'land-use-legend-symbol';

                symbol.style.backgroundColor =
                    configuration.fillColor;

                symbol.style.borderColor =
                    configuration.color;

                const label =
                    document.createElement('span');

                label.textContent =
                    configuration.label || category;

                item.appendChild(symbol);
                item.appendChild(label);

                landUseLegendElement.appendChild(item);
            });
    }

    /*
 * Mengambil file GeoJSON dan memasukkannya
 * ke layer Leaflet yang sudah tersedia.
 */
    function loadGeoJsonLayer(url, targetLayer) {
        return fetch(url)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error(
                        `Data tidak dapat dimuat dari ${url}`
                    );
                }

                return response.json();
            })
            .then(function (geojsonData) {
                const validTypes = [
                    'FeatureCollection',
                    'Feature'
                ];

                if (!validTypes.includes(geojsonData.type)) {
                    throw new Error(
                        `Struktur GeoJSON tidak valid pada ${url}`
                    );
                }

                targetLayer.addData(geojsonData);

                return targetLayer;
            });
    }

    /*
 * Mengubah nilai jarak kilometer menjadi
 * meter atau kilometer yang mudah dibaca.
 */
    function formatDistance(kilometers) {
        if (kilometers < 1) {
            const meters = kilometers * 1000;

            return `${meters.toFixed(1)} meter`;
        }

        return `${kilometers.toFixed(2)} kilometer`;
    }

    /*
     * Mengubah nilai luas meter persegi menjadi
     * meter persegi atau hektare.
     */
    function formatArea(squareMeters) {
        if (squareMeters < 10000) {
            return `${squareMeters.toFixed(1)} m²`;
        }

        const hectares = squareMeters / 10000;

        return `${hectares.toFixed(2)} hektare`;
    }

    /*
     * Mengubah isi panel hasil pengukuran.
     */
    function showMeasurementResult(title, value) {
        if (!measurementResultElement) {
            return;
        }

        measurementResultElement.innerHTML = '';

        const titleElement =
            document.createElement('strong');

        titleElement.textContent = title;

        const valueElement =
            document.createElement('span');

        valueElement.textContent = value;

        measurementResultElement.appendChild(
            titleElement
        );

        measurementResultElement.appendChild(
            valueElement
        );

        measurementResultElement.classList.add(
            'has-result'
        );
    }

    /*
 * Menandai tombol alat ukur yang sedang aktif.
 */
    function setActiveMeasurementButton(mode) {
        activeMeasurementMode = mode;

        if (measureDistanceButton) {
            measureDistanceButton.classList.toggle(
                'is-active',
                mode === 'distance'
            );
        }

        if (measureAreaButton) {
            measureAreaButton.classList.toggle(
                'is-active',
                mode === 'area'
            );
        }
    }

    /*
     * Menghentikan mode gambar yang sedang aktif.
     */
    function stopMeasurementDrawing() {
        if (map.pm) {
            map.pm.disableDraw();
        }

        setActiveMeasurementButton(null);
    }

    /*
 * Mengaktifkan mode gambar garis.
 */
    function startDistanceMeasurement() {
        stopMeasurementDrawing();

        setActiveMeasurementButton('distance');

        showMeasurementResult(
            'Ukur jarak aktif',
            'Klik beberapa titik pada peta. Klik dua kali pada titik terakhir untuk selesai.'
        );

        map.pm.enableDraw('Line', {
            snappable: true,

            pathOptions: {
                color: '#2667a8',
                weight: 4,
                opacity: 0.95
            },

            templineStyle: {
                color: '#2667a8',
                weight: 3
            },

            hintlineStyle: {
                color: '#6d9dcc',
                dashArray: [5, 5]
            }
        });
    }

    /*
 * Mengaktifkan mode gambar poligon.
 */
    function startAreaMeasurement() {
        stopMeasurementDrawing();

        setActiveMeasurementButton('area');

        showMeasurementResult(
            'Ukur luas aktif',
            'Klik titik-titik batas area. Klik titik awal atau klik dua kali untuk selesai.'
        );

        map.pm.enableDraw('Polygon', {
            snappable: true,
            allowSelfIntersection: false,

            pathOptions: {
                color: '#a85f24',
                weight: 3,
                fillColor: '#e2a363',
                fillOpacity: 0.3
            },

            templineStyle: {
                color: '#a85f24',
                weight: 3
            },

            hintlineStyle: {
                color: '#d19a6a',
                dashArray: [5, 5]
            }
        });
    }

    /*
 * Event ini dijalankan setiap kali pengguna selesai
 * menggambar garis atau poligon.
 */
    map.on('pm:create', function (event) {
        const layer = event.layer;
        const shape = event.shape;

        if (!measurementLayer.hasLayer(layer)) {
            measurementLayer.addLayer(layer);
        }

        const geoJsonFeature = layer.toGeoJSON();

        if (shape === 'Line') {
            const lengthKilometers = turf.length(
                geoJsonFeature,
                {
                    units: 'kilometers'
                }
            );

            const formattedDistance =
                formatDistance(lengthKilometers);

            layer.bindTooltip(
                `Jarak: ${formattedDistance}`,
                {
                    permanent: true,
                    direction: 'top',
                    className: 'measurement-label'
                }
            );

            const linePoints = layer.getLatLngs();

            if (linePoints.length > 0) {
                layer.openTooltip(
                    linePoints[linePoints.length - 1]
                );
            }

            showMeasurementResult(
                'Hasil pengukuran jarak',
                formattedDistance
            );
        }

        if (shape === 'Polygon') {
            const squareMeters =
                turf.area(geoJsonFeature);

            const formattedArea =
                formatArea(squareMeters);

            layer.bindTooltip(
                `Luas: ${formattedArea}`,
                {
                    permanent: true,
                    direction: 'center',
                    className: 'measurement-label'
                }
            );

            layer.openTooltip(
                layer.getBounds().getCenter()
            );

            showMeasurementResult(
                'Hasil pengukuran luas',
                formattedArea
            );
        }

        stopMeasurementDrawing();
    });

    if (measureDistanceButton) {
        measureDistanceButton.addEventListener(
            'click',
            function () {
                if (
                    activeMeasurementMode ===
                    'distance'
                ) {
                    stopMeasurementDrawing();

                    showMeasurementResult(
                        'Pengukuran dibatalkan',
                        'Pilih kembali alat ukur untuk memulai.'
                    );

                    return;
                }

                startDistanceMeasurement();
            }
        );
    }

    if (measureAreaButton) {
        measureAreaButton.addEventListener(
            'click',
            function () {
                if (
                    activeMeasurementMode ===
                    'area'
                ) {
                    stopMeasurementDrawing();

                    showMeasurementResult(
                        'Pengukuran dibatalkan',
                        'Pilih kembali alat ukur untuk memulai.'
                    );

                    return;
                }

                startAreaMeasurement();
            }
        );
    }

    if (clearMeasurementsButton) {
        clearMeasurementsButton.addEventListener(
            'click',
            function () {
                stopMeasurementDrawing();

                measurementLayer.clearLayers();

                if (measurementResultElement) {
                    measurementResultElement.innerHTML = `
                    <strong>Belum ada pengukuran</strong>
                    <span>
                        Pilih alat ukur, lalu gambar pada peta.
                    </span>
                `;

                    measurementResultElement.classList.remove(
                        'has-result'
                    );
                }
            }
        );
    }

    /*
 * Mengubah pesan status pencarian lokasi.
 */
    function setGeolocationStatus(
        message,
        statusType = ''
    ) {
        if (!geolocationStatusElement) {
            return;
        }

        geolocationStatusElement.textContent =
            message;

        geolocationStatusElement.classList.remove(
            'is-loading',
            'is-success',
            'is-error'
        );

        if (statusType) {
            geolocationStatusElement.classList.add(
                statusType
            );
        }
    }

    /*
     * Mengatur kondisi tombol Lokasi Saya.
     */
    function setLocationButtonLoading(isLoading) {
        if (!findUserLocationButton) {
            return;
        }

        findUserLocationButton.disabled =
            isLoading;

        findUserLocationButton.textContent =
            isLoading
                ? 'Mencari...'
                : 'Lokasi Saya';
    }

    /*
 * Memeriksa dan meminta lokasi perangkat.
 */
    async function findUserLocation() {
        const localHostnames = [
            'localhost',
            '127.0.0.1',
            '::1'
        ];

        const isAllowedContext =
            window.isSecureContext ||
            localHostnames.includes(
                window.location.hostname
            );

        if (!isAllowedContext) {
            setGeolocationStatus(
                'Lokasi tidak dapat digunakan melalui HTTP. Gunakan HTTPS atau buka website melalui localhost.',
                'is-error'
            );

            return;
        }

        if (!('geolocation' in navigator)) {
            setGeolocationStatus(
                'Browser atau perangkat ini tidak mendukung pencarian lokasi.',
                'is-error'
            );

            return;
        }

        /*
         * Memeriksa apakah izin lokasi sudah
         * ditolak secara permanen.
         */
        if (
            navigator.permissions &&
            navigator.permissions.query
        ) {
            try {
                const permissionStatus =
                    await navigator.permissions.query({
                        name: 'geolocation'
                    });

                if (
                    permissionStatus.state ===
                    'denied'
                ) {
                    setGeolocationStatus(
                        'Izin lokasi telah ditolak. Aktifkan kembali izin lokasi melalui pengaturan browser.',
                        'is-error'
                    );

                    return;
                }
            } catch (error) {
                /*
                 * Beberapa browser tidak mendukung
                 * pemeriksaan izin. Proses tetap dilanjutkan.
                 */
                console.warn(
                    'Status izin lokasi tidak dapat diperiksa:',
                    error
                );
            }
        }

        /*
         * Pastikan layer lokasi sedang aktif.
         */
        if (!map.hasLayer(userLocationLayer)) {
            userLocationLayer.addTo(map);
        }

        userLocationLayer.clearLayers();

        /*
         * Menghentikan permintaan lokasi sebelumnya.
         */
        map.stopLocate();

        setLocationButtonLoading(true);

        setGeolocationStatus(
            'Sedang mencari lokasi perangkat...',
            'is-loading'
        );

        map.locate({
            watch: false,
            setView: false,
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0,
            maxZoom: 18
        });
    }

    map.on('locationfound', function (event) {
        map.stopLocate();

        userLocationLayer.clearLayers();

        const accuracy = Number.isFinite(
            event.accuracy
        )
            ? Math.round(event.accuracy)
            : 0;

        /*
         * Zoom disesuaikan berdasarkan tingkat
         * akurasi perangkat.
         */
        let locationZoom = 18;

        if (accuracy > 100) {
            locationZoom = 17;
        }

        if (accuracy > 500) {
            locationZoom = 15;
        }

        if (accuracy > 2000) {
            locationZoom = 13;
        }

        const accuracyCircle = L.circle(
            event.latlng,
            {
                radius: Math.max(accuracy, 10),
                color: '#1667b1',
                weight: 2,
                fillColor: '#67a8df',
                fillOpacity: 0.14
            }
        );

        const locationMarker = L.circleMarker(
            event.latlng,
            {
                radius: 10,
                color: '#ffffff',
                weight: 3,
                fillColor: '#1667b1',
                fillOpacity: 1
            }
        );

        locationMarker.bindPopup(`
        <div class="map-popup">
            <strong>Lokasi Perangkat</strong>

            <span class="map-popup-category">
                Lokasi Saya
            </span>

            <p>
                Latitude:
                ${event.latlng.lat.toFixed(6)}
                <br>
                Longitude:
                ${event.latlng.lng.toFixed(6)}
            </p>

            <small>
                Perkiraan akurasi:
                ${accuracy > 0
                ? `${accuracy} meter`
                : 'tidak tersedia'}
            </small>
        </div>
    `);

        userLocationLayer.addLayer(
            accuracyCircle
        );

        userLocationLayer.addLayer(
            locationMarker
        );

        map.flyTo(
            event.latlng,
            locationZoom,
            {
                duration: 1.2
            }
        );

        window.setTimeout(function () {
            locationMarker.openPopup();
        }, 700);

        setGeolocationStatus(
            accuracy > 0
                ? `Lokasi ditemukan dengan perkiraan akurasi ${accuracy} meter.`
                : 'Lokasi berhasil ditemukan.',
            'is-success'
        );

        setLocationButtonLoading(false);
    });

    map.on('locationerror', function (event) {
        map.stopLocate();

        const errorMessages = {
            1:
                'Izin lokasi ditolak. Izinkan akses lokasi melalui pengaturan browser.',
            2:
                'Posisi perangkat belum dapat ditemukan. Pastikan layanan lokasi perangkat aktif.',
            3:
                'Pencarian lokasi melewati batas waktu. Coba kembali di area dengan sinyal yang lebih baik.'
        };

        const message =
            errorMessages[event.code] ||
            event.message ||
            'Lokasi perangkat tidak dapat ditemukan.';

        console.error(
            'Pencarian lokasi gagal:',
            event
        );

        setGeolocationStatus(
            message,
            'is-error'
        );

        setLocationButtonLoading(false);
    });

    if (findUserLocationButton) {
        findUserLocationButton.addEventListener(
            'click',
            function () {
                findUserLocation();
            }
        );
    }

    /*
 * Menampilkan koordinat dengan enam
 * angka di belakang koma.
 */
    function formatCoordinate(latlng) {
        if (!latlng) {
            return '';
        }

        return (
            `${latlng.lat.toFixed(6)}, ` +
            `${latlng.lng.toFixed(6)}`
        );
    }

    /*
     * Format yang sesuai untuk GeoJSON.
     * Perhatikan bahwa GeoJSON menggunakan
     * urutan longitude, latitude.
     */
    function formatGeoJsonCoordinate(latlng) {
        if (!latlng) {
            return '';
        }

        return (
            `[${latlng.lng.toFixed(6)}, ` +
            `${latlng.lat.toFixed(6)}]`
        );
    }

    /*
 * Mengubah pesan status panel koordinat.
 */
    function setCoordinateStatus(
        message,
        statusType = ''
    ) {
        if (!coordinateStatusElement) {
            return;
        }

        coordinateStatusElement.textContent =
            message;

        coordinateStatusElement.classList.remove(
            'is-active',
            'is-success',
            'is-error'
        );

        if (statusType) {
            coordinateStatusElement.classList.add(
                statusType
            );
        }
    }

    /*
     * Mengaktifkan atau menonaktifkan
     * tombol terkait koordinat.
     */
    function updateCoordinateButtons() {
        const hasSelectedCoordinate =
            selectedCoordinate !== null;

        if (copyCoordinateButton) {
            copyCoordinateButton.disabled =
                !hasSelectedCoordinate;
        }

        if (clearCoordinateButton) {
            clearCoordinateButton.disabled =
                !hasSelectedCoordinate;
        }
    }

    /*
 * Mengaktifkan atau menonaktifkan
 * mode pemilihan titik koordinat.
 */
    function setCoordinateSelectionMode(isActive) {
        coordinateSelectionMode = isActive;

        if (selectCoordinateButton) {
            selectCoordinateButton.classList.toggle(
                'is-active',
                isActive
            );

            selectCoordinateButton.textContent =
                isActive
                    ? 'Batalkan Pilih'
                    : 'Pilih Titik';
        }

        mapElement.classList.toggle(
            'coordinate-selection-active',
            isActive
        );

        if (isActive) {
            /*
             * Mode ukur dihentikan agar tidak bertabrakan
             * dengan mode pemilihan koordinat.
             */
            stopMeasurementDrawing();

            setCoordinateStatus(
                'Klik satu lokasi pada peta untuk memilih koordinat.',
                'is-active'
            );

            return;
        }

        if (!selectedCoordinate) {
            setCoordinateStatus(
                'Belum ada titik koordinat yang dipilih.'
            );
        }
    }

    /*
 * Memilih koordinat dan menampilkan
 * marker yang dapat digeser.
 */
    function setSelectedCoordinate(latlng) {
        selectedCoordinate = L.latLng(
            latlng.lat,
            latlng.lng
        );

        if (!map.hasLayer(selectedCoordinateLayer)) {
            selectedCoordinateLayer.addTo(map);
        }

        selectedCoordinateLayer.clearLayers();

        selectedCoordinateMarker = L.marker(
            selectedCoordinate,
            {
                draggable: true,
                title: 'Titik koordinat terpilih'
            }
        );

        selectedCoordinateLayer.addLayer(
            selectedCoordinateMarker
        );

        selectedCoordinateMarker.bindPopup(`
        <div class="map-popup">
            <strong>Titik Koordinat</strong>

            <span class="map-popup-category">
                Lokasi Terpilih
            </span>

            <p>
                Latitude:
                ${selectedCoordinate.lat.toFixed(6)}
                <br>
                Longitude:
                ${selectedCoordinate.lng.toFixed(6)}
            </p>

            <small>
                Marker dapat digeser untuk
                memperbaiki posisi.
            </small>
        </div>
    `);

        selectedCoordinateMarker.on(
            'dragend',
            function (event) {
                const newPosition =
                    event.target.getLatLng();

                setSelectedCoordinate(newPosition);
            }
        );

        selectedCoordinateLayer.addLayer(
            selectedCoordinateMarker
        );

        if (selectedCoordinateElement) {
            selectedCoordinateElement.textContent =
                formatCoordinate(selectedCoordinate);
        }

        updateCoordinateButtons();

        setCoordinateStatus(
            'Titik berhasil dipilih. Marker dapat digeser untuk memperbaiki posisinya.',
            'is-success'
        );

        selectedCoordinateMarker.openPopup();
    }

    /*
 * Memilih koordinat ketika pengguna mengeklik peta.
 */
    map.on('click', function (event) {
        /*
         * Selalu memperbarui informasi posisi terakhir
         * yang diklik pada peta.
         */
        if (cursorCoordinateElement) {
            cursorCoordinateElement.textContent =
                formatCoordinate(event.latlng);
        }

        if (coordinateReadoutContainer) {
            coordinateReadoutContainer.textContent =
                `Lat: ${event.latlng.lat.toFixed(6)} | ` +
                `Lng: ${event.latlng.lng.toFixed(6)}`;
        }

        /*
         * Jika mode pilih titik belum aktif,
         * klik tidak membuat marker.
         */
        if (!coordinateSelectionMode) {
            return;
        }

        /*
         * Pastikan alat gambar Geoman tidak sedang aktif.
         */
        if (
            map.pm &&
            typeof map.pm.globalDrawModeEnabled ===
            'function' &&
            map.pm.globalDrawModeEnabled()
        ) {
            return;
        }

        /*
         * Membuat marker koordinat terpilih.
         */
        setSelectedCoordinate(event.latlng);

        /*
         * Mode pilih titik berhenti setelah
         * satu titik berhasil dipilih.
         */
        setCoordinateSelectionMode(false);
    });

    if (selectCoordinateButton) {
        selectCoordinateButton.addEventListener(
            'click',
            function () {
                setCoordinateSelectionMode(
                    !coordinateSelectionMode
                );
            }
        );
    }

    /*
     * Menyeragamkan teks untuk pencarian.
     */
    function normalizeText(value) {
        return String(value ?? '')
            .trim()
            .toLowerCase();
    }

    /*
 * Membuat checkbox kategori fasilitas
 * berdasarkan data dari database.
 */
    function renderCategoryFilters(categories) {
        if (!filterContainer) {
            return;
        }

        filterContainer.replaceChildren();

        if (categories.length === 0) {
            const emptyMessage =
                document.createElement('p');

            emptyMessage.className =
                'facility-filter-loading';

            emptyMessage.textContent =
                'Belum ada kategori aktif.';

            filterContainer.appendChild(
                emptyMessage
            );

            return;
        }

        categories.forEach(function (category) {
            const label =
                document.createElement('label');

            label.className = 'filter-item';

            const input =
                document.createElement('input');

            input.type = 'checkbox';
            input.name = 'map-page-category';
            input.value = category.slug;
            input.checked = true;

            const symbol =
                document.createElement('span');

            symbol.className = 'legend-symbol';

            symbol.style.backgroundColor =
                isValidHexColor(category.marker_color)
                    ? category.marker_color
                    : categoryStyles.Lainnya.color;

            const text =
                document.createElement('span');

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
     * Mengambil kategori yang sedang aktif.
     */
    function getActiveCategories() {
        if (!filterContainer) {
            return [];
        }

        const checkedInputs =
            filterContainer.querySelectorAll(
                'input[name="map-page-category"]:checked'
            );

        return Array.from(checkedInputs).map(
            function (input) {
                return input.value;
            }
        );
    }

    /*
     * Mengambil warna simbol berdasarkan kategori.
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
     * Menghasilkan fitur berdasarkan filter
     * kategori dan kata pencarian.
     */
    function getFilteredFeatures() {
        if (!facilityGeoJsonData) {
            return [];
        }

        const activeCategories =
            getActiveCategories();

        const searchTerm = normalizeText(
            searchInput ? searchInput.value : ''
        );

        return facilityGeoJsonData.features.filter(
            function (feature) {
                const properties =
                    feature.properties || {};

                const categorySlug =
                    properties.category_slug || '';

                const categoryMatches =
                    activeCategories.includes(
                        categorySlug
                    );

                if (!categoryMatches) {
                    return false;
                }

                if (searchTerm === '') {
                    return true;
                }

                const searchableText = normalizeText([
                    properties.name,
                    properties.category,
                    properties.description,
                    properties.address
                ].join(' '));

                return searchableText.includes(searchTerm);
            }
        );
    }

    /*
     * Membuat simbol lingkaran.
     */
    function createFacilityMarker(feature, latlng) {
        const properties =
            feature.properties || {};

        const category =
            properties.category || 'Lainnya';

        const markerColor =
            properties.marker_color || null;

        return L.circleMarker(latlng, {
            radius: 10,
            color: '#ffffff',
            weight: 2,

            fillColor: getCategoryColor(
                category,
                markerColor
            ),

            fillOpacity: 0.95
        });
    }

    /*
     * Mengubah isi panel Informasi Objek.
     */
    function showFeatureInformation(properties) {
        const name =
            properties.name || 'Objek tanpa nama';

        const category =
            properties.category || 'Lainnya';

        const description =
            properties.description ||
            'Belum ada deskripsi untuk objek ini.';

        const address =
            properties.address ||
            'Alamat belum tersedia.';

        if (selectedTitle) {
            selectedTitle.textContent = name;
        }

        if (selectedCategory) {
            selectedCategory.textContent = category;

            selectedCategory.style.backgroundColor =
                getCategoryColor(
                    category,
                    properties.marker_color
                );
        }

        if (selectedDescription) {
            selectedDescription.textContent = description;
        }

        if (selectedAddress) {
            selectedAddress.textContent = address;
        }
    }

    /*
     * Memberi tanda pada lokasi aktif di daftar.
     */
    function setActiveLocationItem(featureId) {
        const locationButtons =
            document.querySelectorAll(
                '.location-list-item'
            );

        locationButtons.forEach(function (button) {
            const buttonId = Number(
                button.dataset.featureId
            );

            button.classList.toggle(
                'is-active',
                buttonId === featureId
            );
        });
    }

    /*
     * Membuat popup, tooltip, dan klik marker.
     */
    function bindFacilityInteraction(feature, layer) {
        const properties = feature.properties || {};

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
            loading="lazy"
        >
    `
            : '';

        const featureId =
            Number(properties._internal_id);

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

        layer.bindTooltip(escapeHtml(name), {
            direction: 'top',
            offset: [0, -7]
        });

        layer.on('click', function () {
            showFeatureInformation(properties);
            setActiveLocationItem(featureId);
            setRouteDestination(
                feature,
                layer
            );
        });

        renderedLayerById.set(featureId, layer);
    }

    /*
 * Menetapkan fasilitas sebagai tujuan rute.
 */
    function setRouteDestination(
        feature,
        layer
    ) {
        if (
            !feature ||
            !layer ||
            typeof layer.getLatLng !== 'function'
        ) {
            return false;
        }

        const properties =
            feature.properties || {};

        const latlng = layer.getLatLng();

        if (
            !Number.isFinite(latlng.lat) ||
            !Number.isFinite(latlng.lng)
        ) {
            return false;
        }

        stopLiveNavigation(false);
        clearCalculatedRoute();

        hasArrivedAtDestination = false;

        if (routeRemainingDistanceElement) {
            routeRemainingDistanceElement.textContent =
                '—';
        }

        selectedRouteDestination = {
            id: Number(properties.id),
            name:
                properties.name ||
                'Fasilitas tanpa nama',
            category:
                properties.category ||
                'Kategori belum tersedia',
            latitude: latlng.lat,
            longitude: latlng.lng,
        };

        /*
         * Menampilkan nama tujuan.
         */
        if (routeDestinationNameElement) {
            routeDestinationNameElement.textContent =
                selectedRouteDestination.name;
        }

        if (routeDestinationElement) {
            routeDestinationElement.hidden = false;
        }

        /*
         * Ringkasan jarak belum tersedia karena
         * rute belum dihitung.
         */
        if (routeSummaryElement) {
            routeSummaryElement.hidden = true;
        }

        if (routeDistanceElement) {
            routeDistanceElement.textContent = '—';
        }

        if (routeDurationElement) {
            routeDurationElement.textContent = '—';
        }

        /*
         * Mengaktifkan tombol routing.
         */
        if (routeStartButton) {
            routeStartButton.disabled = false;
        }

        if (routeClearButton) {
            routeClearButton.hidden = true;
        }

        if (routeStatusBadgeElement) {
            routeStatusBadgeElement.textContent =
                'Tujuan dipilih';

            routeStatusBadgeElement.classList.remove(
                'is-loading',
                'is-error'
            );

            routeStatusBadgeElement.classList.add(
                'is-active'
            );
        }

        if (routeMessageElement) {
            routeMessageElement.textContent =
                'Tujuan sudah dipilih. Tekan “Rute ke Lokasi” untuk menggunakan posisi GPS Anda.';
        }

        return true;
    }

    /*
     * Mengarahkan peta menuju satu objek.
     */
    function focusFeature(featureId) {
        const normalizedFeatureId =
            Number(featureId);

        const layer =
            renderedLayerById.get(
                normalizedFeatureId
            );

        if (!layer || !layer.getLatLng) {
            return false;
        }

        const properties =
            layer.feature?.properties || {};

        /*
         * Pastikan layer fasilitas aktif.
         */
        if (!map.hasLayer(facilityLayer)) {
            facilityLayer.addTo(map);
        }

        setRouteDestination(
            layer.feature,
            layer
        );

        showFeatureInformation(properties);

        setActiveLocationItem(
            normalizedFeatureId
        );

        /*
         * Menampilkan marker di atas objek lain.
         */
        if (typeof layer.bringToFront === 'function') {
            layer.bringToFront();
        }

        map.flyTo(
            layer.getLatLng(),
            18,
            {
                animate: true,
                duration: 1.2
            }
        );

        map.once('moveend', function () {
            layer.openPopup();
        });

        return true;
    }

    /*
 * Memfokuskan fasilitas yang dikirim
 * melalui parameter URL.
 */
    function focusRequestedFacility() {
        if (!hasRequestedFacility) {
            return;
        }

        const focused = focusFeature(
            requestedFacilityId
        );

        if (focused) {
            /*
             * Menggulir halaman agar area peta
             * terlihat jelas ketika dibuka.
             */
            mapElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            return;
        }

        /*
         * ID mungkin tidak tersedia, tidak aktif,
         * tidak dipublikasikan, atau tersaring.
         */
        if (dataStatusElement) {
            dataStatusElement.textContent =
                'Fasilitas yang diminta tidak ditemukan atau belum dipublikasikan.';

            dataStatusElement.classList.add(
                'map-data-status-warning'
            );
        }
    }

    /*
     * Membuat daftar lokasi secara aman
     * menggunakan elemen DOM.
     */
    function renderLocationList(features) {
        if (!locationListElement) {
            return;
        }

        locationListElement.innerHTML = '';

        if (features.length === 0) {
            const emptyMessage =
                document.createElement('p');

            emptyMessage.className =
                'location-list-empty';

            emptyMessage.textContent =
                'Tidak ada lokasi yang sesuai dengan pencarian dan filter.';

            locationListElement.appendChild(
                emptyMessage
            );

            return;
        }

        features.forEach(function (feature) {
            const properties =
                feature.properties || {};

            const featureId =
                Number(properties._internal_id);

            const name =
                properties.name || 'Objek tanpa nama';

            const category =
                properties.category || 'Lainnya';

            const address =
                properties.address ||
                'Alamat belum tersedia.';

            const button =
                document.createElement('button');

            button.type = 'button';
            button.className =
                'location-list-item';

            button.dataset.featureId =
                String(featureId);

            const header =
                document.createElement('span');

            header.className =
                'location-list-item-header';

            const symbol =
                document.createElement('span');

            symbol.className =
                'location-list-symbol';

            symbol.style.backgroundColor =
                getCategoryColor(
                    category,
                    properties.marker_color
                );

            const nameElement =
                document.createElement('strong');

            nameElement.className =
                'location-list-name';

            nameElement.textContent = name;

            header.appendChild(symbol);
            header.appendChild(nameElement);

            const categoryElement =
                document.createElement('span');

            categoryElement.className =
                'location-list-category';

            categoryElement.textContent = category;

            const addressElement =
                document.createElement('small');

            addressElement.className =
                'location-list-address';

            addressElement.textContent = address;

            button.appendChild(header);
            button.appendChild(categoryElement);
            button.appendChild(addressElement);

            button.addEventListener(
                'click',
                function () {
                    focusFeature(featureId);
                }
            );

            locationListElement.appendChild(button);
        });
    }

    /*
     * Menampilkan ulang marker dan daftar lokasi.
     */
    function renderFacilities() {
        if (!facilityGeoJsonData) {
            return;
        }

        const filteredFeatures =
            getFilteredFeatures();

        facilityLayer.clearLayers();
        renderedLayerById.clear();

        const filteredGeoJson = {
            type: 'FeatureCollection',
            features: filteredFeatures
        };

        const renderedLayer = L.geoJSON(
            filteredGeoJson,
            {
                pointToLayer: createFacilityMarker,

                onEachFeature:
                    bindFacilityInteraction
            }
        );

        facilityLayer.addLayer(renderedLayer);

        renderLocationList(filteredFeatures);

        if (visibleCountElement) {
            visibleCountElement.textContent =
                String(filteredFeatures.length);
        }

        if (!dataStatusElement) {
            return;
        }

        if (filteredFeatures.length === 0) {
            dataStatusElement.textContent =
                'Tidak ada objek yang sesuai.';

            dataStatusElement.classList.add(
                'map-data-status-warning'
            );

            return;
        }

        dataStatusElement.textContent =
            'Data peta berhasil dimuat.';

        dataStatusElement.classList.remove(
            'map-data-status-warning',
            'map-data-status-error'
        );
    }

    /*
 * Memuat poligon batas sementara.
 */
    loadGeoJsonLayer(
        '/data/wareng-boundary.geojson',
        boundaryLayer
    )
        .then(function (loadedBoundaryLayer) {
            if (
                loadedBoundaryLayer
                    .getBounds()
                    .isValid()
            ) {
                boundaryBounds =
                    loadedBoundaryLayer.getBounds();

                /*
                 * Jangan pusatkan ke batas jika pengguna
                 * membuka fasilitas tertentu dari URL.
                 */
                if (!hasRequestedFacility) {
                    map.fitBounds(
                        boundaryBounds,
                        {
                            padding: [35, 35],
                            maxZoom: 17
                        }
                    );
                }
            }
        })
        .catch(function (error) {
            console.error(
                'Batas wilayah gagal dimuat:',
                error
            );
        });

    /*
 * Memuat data penggunaan lahan.
 */
    loadGeoJsonLayer(
        '/data/land-use.geojson',
        landUseLayer
    )
        .catch(function (error) {
            console.error(
                'Penggunaan lahan gagal dimuat:',
                error
            );
        });

    /*
     * Memuat jaringan jalan.
     */
    loadGeoJsonLayer(
        '/data/roads.geojson',
        roadLayer
    )
        .catch(function (error) {
            console.error(
                'Jaringan jalan gagal dimuat:',
                error
            );
        });

    /*
    * Menampilkan legenda meskipun data GeoJSON
    * masih dalam proses pemuatan.
    * */
    renderLandUseLegend();

    /*
 * Mengambil kategori dan fasilitas
 * dari API Laravel secara bersamaan.
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

            /*
             * Memastikan respons kategori benar.
             */
            if (
                !categoryResponse ||
                !Array.isArray(categoryResponse.data)
            ) {
                throw new Error(
                    'Struktur data kategori tidak valid.'
                );
            }

            /*
             * Memastikan respons fasilitas berupa
             * GeoJSON FeatureCollection.
             */
            if (
                geojsonData.type !== 'FeatureCollection' ||
                !Array.isArray(geojsonData.features)
            ) {
                throw new Error(
                    'Struktur GeoJSON fasilitas tidak valid.'
                );
            }

            /*
             * Checkbox kategori harus dibuat dahulu
             * sebelum fasilitas dirender.
             */
            renderCategoryFilters(
                categoryResponse.data
            );

            /*
             * Menambahkan ID internal berdasarkan
             * ID fasilitas dari database.
             */
            facilityGeoJsonData = {
                type: 'FeatureCollection',

                features: geojsonData.features.map(
                    function (feature, index) {
                        const properties =
                            feature.properties || {};

                        const databaseId =
                            Number(properties.id);

                        return {
                            ...feature,

                            properties: {
                                ...properties,

                                _internal_id:
                                    Number.isFinite(databaseId)
                                        ? databaseId
                                        : index
                            }
                        };
                    }
                )
            };

            /*
             * Menghitung cakupan seluruh fasilitas.
             */
            const allFacilitiesLayer =
                L.geoJSON(facilityGeoJsonData);

            if (
                allFacilitiesLayer
                    .getBounds()
                    .isValid()
            ) {
                initialBounds =
                    allFacilitiesLayer.getBounds();

                /*
                 * Gunakan cakupan fasilitas hanya
                 * ketika batas wilayah belum tersedia.
                 */
                if (
                    !boundaryBounds &&
                    !hasRequestedFacility
                ) {
                    map.fitBounds(initialBounds, {
                        padding: [50, 50],
                        maxZoom: 17
                    });
                }
            }

            renderFacilities();

            /*
             * Marker harus selesai dirender dahulu
             * sebelum fasilitas dari URL difokuskan.
             */
            window.requestAnimationFrame(
                function () {
                    focusRequestedFacility();
                }
            );
        })
        .catch(function (error) {
            console.error(
                'Data fasilitas atau kategori gagal dimuat:',
                error
            );

            if (filterContainer) {
                filterContainer.replaceChildren();

                const errorFilterMessage =
                    document.createElement('p');

                errorFilterMessage.className =
                    'facility-filter-loading';

                errorFilterMessage.textContent =
                    'Kategori gagal dimuat.';

                filterContainer.appendChild(
                    errorFilterMessage
                );
            }

            if (dataStatusElement) {
                dataStatusElement.textContent =
                    'Data fasilitas atau kategori gagal dimuat. Periksa API dan koneksi database.';

                dataStatusElement.classList.add(
                    'map-data-status-error'
                );
            }

            if (visibleCountElement) {
                visibleCountElement.textContent = '0';
            }

            if (locationListElement) {
                locationListElement.replaceChildren();

                const errorMessage =
                    document.createElement('p');

                errorMessage.className =
                    'location-list-empty';

                errorMessage.textContent =
                    'Daftar lokasi gagal dimuat.';

                locationListElement.appendChild(
                    errorMessage
                );
            }
        });

    /*
     * Filter kategori.
     */
    if (filterContainer) {
        filterContainer.addEventListener(
            'change',
            function (event) {
                if (
                    event.target.matches(
                        'input[name="map-page-category"]'
                    )
                ) {
                    renderFacilities();
                }
            }
        );
    }

    /*
     * Pencarian dengan jeda pendek.
     */
    if (searchInput) {
        searchInput.addEventListener(
            'input',
            function () {
                window.clearTimeout(searchTimer);

                searchTimer = window.setTimeout(
                    function () {
                        renderFacilities();
                    },
                    150
                );
            }
        );
    }

    /*
     * Menghapus kata pencarian.
     */
    if (clearSearchButton) {
        clearSearchButton.addEventListener(
            'click',
            function () {
                if (!searchInput) {
                    return;
                }

                searchInput.value = '';
                searchInput.focus();

                renderFacilities();
            }
        );
    }

    /*
     * Mengaktifkan semua kategori dan
     * menghapus pencarian.
     */
    if (showAllButton) {
        showAllButton.addEventListener(
            'click',
            function () {
                const categoryInputs =
                    filterContainer
                        ? filterContainer.querySelectorAll(
                            'input[name="map-page-category"]'
                        )
                        : [];

                categoryInputs.forEach(function (input) {
                    input.checked = true;
                });

                if (searchInput) {
                    searchInput.value = '';
                }

                renderFacilities();

                const targetBounds =
                    boundaryBounds || initialBounds;

                if (targetBounds) {
                    map.fitBounds(targetBounds, {
                        padding: [35, 35],
                        maxZoom: 17
                    });

                    return;
                }
            }
        );
    }

    /*
     * Tombol pusatkan peta.
     */
    const resetControl = L.control({
        position: 'bottomright'
    });

    resetControl.onAdd = function () {
        const container = L.DomUtil.create(
            'div',
            'leaflet-bar map-reset-control'
        );

        const button = L.DomUtil.create(
            'button',
            'map-reset-button',
            container
        );

        button.type = 'button';
        button.innerHTML = '&#9678;';
        button.title = 'Pusatkan peta';
        button.setAttribute(
            'aria-label',
            'Pusatkan kembali tampilan peta'
        );

        /*
         * Mencegah klik tombol ikut dianggap
         * sebagai klik pada peta.
         */
        L.DomEvent.disableClickPropagation(container);
        L.DomEvent.disableScrollPropagation(container);

        L.DomEvent.on(
            button,
            'click',
            function (event) {
                L.DomEvent.stop(event);

                /*
                 * Prioritas cakupan:
                 * 1. batas dusun;
                 * 2. seluruh fasilitas;
                 * 3. titik pusat sementara.
                 */
                const targetBounds =
                    boundaryBounds || initialBounds;

                if (
                    targetBounds &&
                    typeof targetBounds.isValid ===
                    'function' &&
                    targetBounds.isValid()
                ) {
                    map.fitBounds(targetBounds, {
                        padding: [35, 35],
                        maxZoom: 17
                    });

                    return;
                }

                map.flyTo(
                    warengCenter,
                    16,
                    {
                        duration: 1.2
                    }
                );
            }
        );

        return container;
    };

    resetControl.addTo(map);

    /*
 * Mengubah jarak meter menjadi teks
 * yang lebih mudah dibaca.
 */
    function formatRouteDistance(distanceInMeters) {
        if (!Number.isFinite(distanceInMeters)) {
            return '—';
        }

        if (distanceInMeters < 1000) {
            return `${Math.round(distanceInMeters)} m`;
        }

        return `${(distanceInMeters / 1000).toFixed(1)
            } km`;
    }

    /*
     * Mengubah durasi detik menjadi menit
     * atau jam dan menit.
     */
    function formatRouteDuration(durationInSeconds) {
        if (!Number.isFinite(durationInSeconds)) {
            return '—';
        }

        const totalMinutes = Math.max(
            1,
            Math.round(durationInSeconds / 60)
        );

        if (totalMinutes < 60) {
            return `${totalMinutes} menit`;
        }

        const hours = Math.floor(
            totalMinutes / 60
        );

        const minutes =
            totalMinutes % 60;

        if (minutes === 0) {
            return `${hours} jam`;
        }

        return `${hours} jam ${minutes} menit`;
    }

    /*
 * Menghapus hasil perhitungan rute lama
 * tanpa menghapus tujuan yang dipilih.
 */
    function clearCalculatedRoute() {
        /*
 * Menghapus semua garis rute dari peta.
 */
        routeLayers.forEach(function (layer) {
            if (map.hasLayer(layer)) {
                map.removeLayer(layer);
            }
        });

        routeLayers = [];
        availableRoutes = [];
        activeRouteIndex = 0;

        if (routeSummaryElement) {
            routeSummaryElement.hidden = true;
        }

        if (routeDistanceElement) {
            routeDistanceElement.textContent = '—';
        }

        if (routeDurationElement) {
            routeDurationElement.textContent = '—';
        }

        if (routeClearButton) {
            routeClearButton.hidden = true;
        }

        if (routeStepListElement) {
            routeStepListElement.replaceChildren();
        }

        if (routeStepCountElement) {
            routeStepCountElement.textContent =
                '0 langkah';
        }

        if (routeDirectionsElement) {
            routeDirectionsElement.hidden = true;
        }

        if (routeAlternativeListElement) {
            routeAlternativeListElement
                .replaceChildren();
        }

        if (routeAlternativeCountElement) {
            routeAlternativeCountElement
                .textContent = '0 rute';
        }

        if (routeAlternativesElement) {
            routeAlternativesElement.hidden = true;
        }
    }

    /*
 * Menampilkan atau memperbarui posisi
 * pengguna pada peta.
 */
    function showUserLocation(
        latitude,
        longitude,
        accuracy
    ) {
        const userLatLng = [
            latitude,
            longitude
        ];

        /*
         * Buat marker apabila belum tersedia.
         */
        if (!userLocationMarker) {
            userLocationMarker = L.circleMarker(
                userLatLng,
                {
                    radius: 9,
                    color: '#ffffff',
                    weight: 3,
                    fillColor: '#2374d8',
                    fillOpacity: 1
                }
            ).addTo(map);

            userLocationMarker.bindTooltip(
                'Posisi Anda',
                {
                    permanent: false,
                    direction: 'top',
                    offset: [0, -8]
                }
            );
        } else {
            userLocationMarker.setLatLng(
                userLatLng
            );
        }

        /*
         * Lingkaran menunjukkan perkiraan
         * tingkat akurasi GPS.
         */
        if (!userAccuracyCircle) {
            userAccuracyCircle = L.circle(
                userLatLng,
                {
                    radius: accuracy,
                    color: '#2374d8',
                    weight: 1,
                    opacity: 0.55,
                    fillColor: '#2374d8',
                    fillOpacity: 0.12
                }
            ).addTo(map);
        } else {
            userAccuracyCircle
                .setLatLng(userLatLng)
                .setRadius(accuracy);
        }

        /*
         * Marker ditampilkan di atas layer lain.
         */
        if (
            typeof userLocationMarker
                .bringToFront === 'function'
        ) {
            userLocationMarker.bringToFront();
        }
    }

    /*
 * Menampilkan pesan berdasarkan jenis
 * kegagalan Geolocation API.
 */
    function handleLocationError(error) {
        let message =
            'Posisi perangkat tidak dapat diperoleh.';

        if (error.code === 1) {
            message =
                'Izin lokasi ditolak. Aktifkan izin lokasi pada browser, lalu coba kembali.';
        }

        if (error.code === 2) {
            message =
                'Posisi perangkat tidak tersedia. Pastikan GPS atau layanan lokasi aktif.';
        }

        if (error.code === 3) {
            message =
                'Pengambilan lokasi terlalu lama. Silakan coba kembali.';
        }

        if (routeStatusBadgeElement) {
            routeStatusBadgeElement.textContent =
                'GPS gagal';

            routeStatusBadgeElement.classList.remove(
                'is-loading',
                'is-active'
            );

            routeStatusBadgeElement.classList.add(
                'is-error'
            );
        }

        if (routeMessageElement) {
            routeMessageElement.textContent =
                message;
        }

        if (routeStartButton) {
            routeStartButton.disabled = false;
        }

        console.error(
            'Gagal mengambil lokasi pengguna:',
            error
        );
    }

    /*
 * Memilih simbol berdasarkan arah manuver.
 */
    function getRouteStepIcon(
        maneuverType,
        modifier
    ) {
        const normalizedType =
            String(maneuverType || '')
                .replaceAll('_', ' ')
                .toLowerCase();

        const normalizedModifier =
            String(modifier || '')
                .replaceAll('_', ' ')
                .toLowerCase();

        if (normalizedType === 'depart') {
            return '●';
        }

        if (normalizedType === 'arrive') {
            return '◆';
        }

        if (
            normalizedType === 'roundabout' ||
            normalizedType === 'rotary'
        ) {
            return '↻';
        }

        const icons = {
            uturn: '↶',
            'sharp left': '↰',
            left: '←',
            'slight left': '↖',
            straight: '↑',
            'slight right': '↗',
            right: '→',
            'sharp right': '↱',
        };

        return icons[normalizedModifier] || '↑';
    }

    /*
 * Mengubah modifier OSRM menjadi
 * keterangan arah berbahasa Indonesia.
 */
    function translateRouteModifier(modifier) {
        const normalizedModifier =
            String(modifier || '')
                .replaceAll('_', ' ')
                .toLowerCase();

        const translations = {
            uturn: 'putar balik',
            'sharp left': 'belok tajam ke kiri',
            left: 'belok kiri',
            'slight left': 'ambil sedikit ke kiri',
            straight: 'lurus',
            'slight right': 'ambil sedikit ke kanan',
            right: 'belok kanan',
            'sharp right': 'belok tajam ke kanan',
        };

        return translations[normalizedModifier] ||
            'lanjutkan perjalanan';
    }

    /*
 * Membentuk petunjuk perjalanan berdasarkan
 * jenis dan arah manuver dari OSRM.
 */
    function createRouteInstruction(step) {
        const maneuver = step.maneuver || {};

        const maneuverType =
            String(maneuver.type || 'turn')
                .replaceAll('_', ' ')
                .toLowerCase();

        const modifier =
            maneuver.modifier || '';

        const direction =
            translateRouteModifier(modifier);

        const roadName =
            String(step.name || '').trim();

        const roadText = roadName
            ? ` ke ${roadName}`
            : '';

        if (maneuverType === 'depart') {
            if (roadName) {
                return `Mulai perjalanan melalui ${roadName}`;
            }

            return 'Mulai perjalanan dari posisi Anda';
        }

        if (maneuverType === 'arrive') {
            const arrivalSide =
                translateArrivalSide(modifier);

            return arrivalSide
                ? `Tujuan berada di sebelah ${arrivalSide}`
                : 'Anda telah sampai di tujuan';
        }

        if (
            maneuverType === 'roundabout' ||
            maneuverType === 'rotary'
        ) {
            const exitNumber =
                maneuver.exit;

            if (Number.isFinite(exitNumber)) {
                return (
                    `Masuk bundaran dan ambil jalan keluar ` +
                    `ke-${exitNumber}${roadText}`
                );
            }

            return `Masuk bundaran${roadText}`;
        }

        if (
            maneuverType === 'fork'
        ) {
            return `Ambil percabangan ${direction}${roadText}`;
        }

        if (
            maneuverType === 'merge'
        ) {
            return `Bergabung ${direction}${roadText}`;
        }

        if (
            maneuverType === 'on ramp'
        ) {
            return `Masuk ke jalur penghubung ${direction}${roadText}`;
        }

        if (
            maneuverType === 'off ramp'
        ) {
            return `Keluar dari jalur utama ${direction}${roadText}`;
        }

        if (
            maneuverType === 'end of road'
        ) {
            return `Di ujung jalan, ${direction}${roadText}`;
        }

        if (
            maneuverType === 'new name'
        ) {
            return roadName
                ? `Lanjutkan perjalanan melalui ${roadName}`
                : 'Lanjutkan mengikuti jalan';
        }

        if (
            maneuverType === 'continue'
        ) {
            return `Tetap ${direction}${roadText}`;
        }

        return `${capitalizeFirstLetter(direction)}${roadText}`;
    }

    /*
 * Menerjemahkan posisi tujuan.
 */
    function translateArrivalSide(modifier) {
        const normalizedModifier =
            String(modifier || '')
                .toLowerCase();

        if (normalizedModifier.includes('left')) {
            return 'kiri';
        }

        if (normalizedModifier.includes('right')) {
            return 'kanan';
        }

        return '';
    }

    /*
     * Membuat huruf pertama menjadi kapital.
     */
    function capitalizeFirstLetter(value) {
        const text = String(value || '');

        if (!text) {
            return text;
        }

        return (
            text.charAt(0).toUpperCase() +
            text.slice(1)
        );
    }

    /*
 * Menampilkan seluruh langkah perjalanan
 * pada panel navigasi.
 */
    function renderRouteDirections(steps) {
        if (
            !routeDirectionsElement ||
            !routeStepListElement
        ) {
            return;
        }

        routeStepListElement.replaceChildren();

        if (
            !Array.isArray(steps) ||
            steps.length === 0
        ) {
            routeDirectionsElement.hidden = true;

            if (routeStepCountElement) {
                routeStepCountElement.textContent =
                    '0 langkah';
            }

            return;
        }

        steps.forEach(function (step, index) {
            const maneuver =
                step.maneuver || {};

            const listItem =
                document.createElement('li');

            listItem.className =
                'map-route-step-item';

            const icon =
                document.createElement('span');

            icon.className =
                'map-route-step-icon';

            icon.textContent =
                getRouteStepIcon(
                    maneuver.type,
                    maneuver.modifier
                );

            const content =
                document.createElement('div');

            content.className =
                'map-route-step-content';

            const instruction =
                document.createElement('strong');

            instruction.textContent =
                createRouteInstruction(step);

            const meta =
                document.createElement('span');

            meta.textContent =
                formatRouteDistance(
                    Number(step.distance)
                );

            content.append(
                instruction,
                meta
            );

            const number =
                document.createElement('span');

            number.className =
                'map-route-step-number';

            number.textContent =
                String(index + 1)
                    .padStart(2, '0');

            listItem.append(
                icon,
                content,
                number
            );

            routeStepListElement.appendChild(
                listItem
            );
        });

        if (routeStepCountElement) {
            routeStepCountElement.textContent =
                `${steps.length} langkah`;
        }

        routeDirectionsElement.hidden = false;
    }

    /*
 * Menggabungkan langkah perjalanan dari
 * seluruh leg pada satu rute.
 */
    function getRouteSteps(route) {
        if (!Array.isArray(route?.legs)) {
            return [];
        }

        return route.legs.flatMap(
            function (leg) {
                return Array.isArray(leg.steps)
                    ? leg.steps
                    : [];
            }
        );
    }

    /*
 * Menentukan tampilan garis berdasarkan
 * status aktif atau alternatif.
 */
    function getRouteLineStyle(isActive) {
        if (isActive) {
            return {
                color: '#2673c9',
                weight: 7,
                opacity: 0.95,
                lineCap: 'round',
                lineJoin: 'round'
            };
        }

        return {
            color: '#7d8a82',
            weight: 4,
            opacity: 0.5,
            dashArray: '8 8',
            lineCap: 'round',
            lineJoin: 'round'
        };
    }

    /*
 * Mengaktifkan salah satu rute berdasarkan
 * indeks dari respons OSRM.
 */
    function selectRoute(
        routeIndex,
        shouldFitBounds = true
    ) {
        const selectedRoute =
            availableRoutes[routeIndex];

        const selectedLayer =
            routeLayers[routeIndex];

        if (!selectedRoute || !selectedLayer) {
            return false;
        }

        activeRouteIndex = routeIndex;

        /*
         * Memperbarui tampilan semua garis.
         */
        routeLayers.forEach(
            function (layer, index) {
                layer.setStyle(
                    getRouteLineStyle(
                        index === routeIndex
                    )
                );
            }
        );

        /*
         * Garis aktif diletakkan paling depan.
         */
        if (
            typeof selectedLayer
                .bringToFront === 'function'
        ) {
            selectedLayer.bringToFront();
        }

        /*
         * Memperbarui ringkasan perjalanan.
         */
        if (routeDistanceElement) {
            routeDistanceElement.textContent =
                formatRouteDistance(
                    Number(selectedRoute.distance)
                );
        }

        if (routeDurationElement) {
            routeDurationElement.textContent =
                formatRouteDuration(
                    Number(selectedRoute.duration)
                );
        }

        if (routeSummaryElement) {
            routeSummaryElement.hidden = false;
        }

        /*
         * Petunjuk belokan mengikuti rute aktif.
         */
        renderRouteDirections(
            getRouteSteps(selectedRoute)
        );

        /*
         * Memperbarui status tombol alternatif.
         */
        if (routeAlternativeListElement) {
            routeAlternativeListElement
                .querySelectorAll(
                    '[data-route-index]'
                )
                .forEach(function (button) {
                    const buttonIndex =
                        Number(
                            button.dataset.routeIndex
                        );

                    const isActive =
                        buttonIndex === routeIndex;

                    button.classList.toggle(
                        'is-active',
                        isActive
                    );

                    button.setAttribute(
                        'aria-pressed',
                        isActive
                            ? 'true'
                            : 'false'
                    );
                });
        }

        if (
            shouldFitBounds &&
            selectedLayer.getBounds
        ) {
            const selectedBounds =
                selectedLayer.getBounds();

            if (selectedBounds.isValid()) {
                map.fitBounds(
                    selectedBounds,
                    {
                        padding: [60, 60],
                        maxZoom: 18
                    }
                );
            }
        }

        if (
            userLocationMarker &&
            typeof userLocationMarker
                .bringToFront === 'function'
        ) {
            userLocationMarker.bringToFront();
        }

        if (routeStatusBadgeElement) {
            routeStatusBadgeElement.textContent =
                routeIndex === 0
                    ? 'Rute utama'
                    : `Alternatif ${routeIndex}`;

            routeStatusBadgeElement
                .classList.remove(
                    'is-loading',
                    'is-error'
                );

            routeStatusBadgeElement
                .classList.add(
                    'is-active'
                );
        }

        if (routeMessageElement) {
            const routeLabel =
                routeIndex === 0
                    ? 'Rute utama'
                    : `Rute alternatif ${routeIndex}`;

            routeMessageElement.textContent =
                `${routeLabel} menuju ${selectedRouteDestination?.name ||
                'tujuan'
                } sedang ditampilkan.`;
        }

        return true;
    }

    /*
 * Membuat tombol pilihan rute berdasarkan
 * hasil yang dikembalikan OSRM.
 */
    function renderRouteAlternatives(routes) {
        if (
            !routeAlternativesElement ||
            !routeAlternativeListElement
        ) {
            return;
        }

        routeAlternativeListElement
            .replaceChildren();

        if (
            !Array.isArray(routes) ||
            routes.length <= 1
        ) {
            routeAlternativesElement.hidden = true;

            if (routeAlternativeCountElement) {
                routeAlternativeCountElement
                    .textContent = routes?.length
                        ? '1 rute'
                        : '0 rute';
            }

            return;
        }

        routes.forEach(function (route, index) {
            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'map-route-alternative-button';

            button.dataset.routeIndex =
                String(index);

            button.setAttribute(
                'aria-pressed',
                index === 0
                    ? 'true'
                    : 'false'
            );

            if (index === 0) {
                button.classList.add(
                    'is-active'
                );
            }

            const title =
                document.createElement('strong');

            title.textContent =
                index === 0
                    ? 'Rute Utama'
                    : `Alternatif ${index}`;

            const summary =
                document.createElement('span');

            summary.textContent =
                `${formatRouteDistance(
                    Number(route.distance)
                )
                } · ${formatRouteDuration(
                    Number(route.duration)
                )
                }`;

            button.append(
                title,
                summary
            );

            button.addEventListener(
                'click',
                function () {
                    selectRoute(index);
                }
            );

            routeAlternativeListElement
                .appendChild(button);
        });

        if (routeAlternativeCountElement) {
            routeAlternativeCountElement
                .textContent =
                `${routes.length} rute`;
        }

        routeAlternativesElement.hidden = false;
    }

    /*
 * Menggambar semua rute. Alternatif digambar
 * lebih dahulu agar rute utama berada di atas.
 */
    function drawRouteLayers(routes) {
        routeLayers = new Array(
            routes.length
        );

        for (
            let index = routes.length - 1;
            index >= 0;
            index -= 1
        ) {
            const route = routes[index];

            if (!route.geometry) {
                continue;
            }

            const layer = L.geoJSON(
                route.geometry,
                {
                    style: getRouteLineStyle(
                        index === 0
                    )
                }
            ).addTo(map);

            /*
             * Garis alternatif juga bisa dipilih
             * langsung dari peta.
             */
            layer.on('click', function () {
                selectRoute(index, false);
            });

            routeLayers[index] = layer;
        }
    }

    /*
 * Menghitung jarak lurus antara dua posisi
 * menggunakan rumus Haversine.
 */
    function calculateCoordinateDistance(
        firstLocation,
        secondLocation
    ) {
        if (!firstLocation || !secondLocation) {
            return Infinity;
        }

        const earthRadius = 6371000;

        const firstLatitude =
            firstLocation.latitude *
            Math.PI / 180;

        const secondLatitude =
            secondLocation.latitude *
            Math.PI / 180;

        const latitudeDifference =
            (
                secondLocation.latitude -
                firstLocation.latitude
            ) *
            Math.PI / 180;

        const longitudeDifference =
            (
                secondLocation.longitude -
                firstLocation.longitude
            ) *
            Math.PI / 180;

        const haversineValue =
            Math.sin(
                latitudeDifference / 2
            ) ** 2 +
            Math.cos(firstLatitude) *
            Math.cos(secondLatitude) *
            Math.sin(
                longitudeDifference / 2
            ) ** 2;

        const angularDistance =
            2 *
            Math.atan2(
                Math.sqrt(haversineValue),
                Math.sqrt(
                    1 - haversineValue
                )
            );

        return earthRadius * angularDistance;
    }

    /*
 * Menghitung jarak lurus posisi pengguna
 * menuju fasilitas tujuan.
 */
    function updateRemainingDistance(
        userLocation
    ) {
        if (
            !userLocation ||
            !selectedRouteDestination
        ) {
            return Infinity;
        }

        const remainingDistance =
            calculateCoordinateDistance(
                userLocation,
                selectedRouteDestination
            );

        if (routeRemainingDistanceElement) {
            routeRemainingDistanceElement.textContent =
                formatRouteDistance(
                    remainingDistance
                );
        }

        return remainingDistance;
    }

    /*
 * Menangani kondisi ketika pengguna telah
 * berada di sekitar fasilitas tujuan.
 */
    function handleDestinationArrival() {
        if (hasArrivedAtDestination) {
            return;
        }

        hasArrivedAtDestination = true;

        /*
         * Hentikan pelacakan GPS, tetapi tetap
         * pertahankan garis rute terakhir.
         */
        stopLiveNavigation(false);

        if (routeStatusBadgeElement) {
            routeStatusBadgeElement.textContent =
                'Telah sampai';

            routeStatusBadgeElement.classList.remove(
                'is-loading',
                'is-error'
            );

            routeStatusBadgeElement.classList.add(
                'is-active'
            );
        }

        if (routeMessageElement) {
            routeMessageElement.textContent =
                `Anda telah sampai di ${selectedRouteDestination?.name ||
                'lokasi tujuan'
                }.`;
        }

        if (routeRemainingDistanceElement) {
            routeRemainingDistanceElement.textContent =
                'Tiba';
        }

        if (routeNavigationStartButton) {
            routeNavigationStartButton.hidden = true;
        }

        if (routeNavigationStopButton) {
            routeNavigationStopButton.hidden = true;
        }

        /*
         * Buka popup fasilitas tujuan.
         */
        const destinationLayer =
            renderedLayerById.get(
                Number(
                    selectedRouteDestination?.id
                )
            );

        if (destinationLayer) {
            destinationLayer.openPopup();

            if (
                typeof destinationLayer
                    .bringToFront === 'function'
            ) {
                destinationLayer.bringToFront();
            }
        }
    }

    /*
 * Memperbarui status navigasi berdasarkan
 * jarak pengguna terhadap tujuan.
 */
    function checkNavigationProgress(
        userLocation
    ) {
        const remainingDistance =
            updateRemainingDistance(
                userLocation
            );

        if (!Number.isFinite(remainingDistance)) {
            return;
        }

        if (
            remainingDistance <=
            arrivalDistanceThreshold
        ) {
            handleDestinationArrival();
            return;
        }

        if (
            isNavigationActive &&
            remainingDistance <=
            approachingDistanceThreshold
        ) {
            if (routeStatusBadgeElement) {
                routeStatusBadgeElement.textContent =
                    'Mendekati tujuan';

                routeStatusBadgeElement.classList.remove(
                    'is-loading',
                    'is-error'
                );

                routeStatusBadgeElement.classList.add(
                    'is-active'
                );
            }

            if (routeMessageElement) {
                routeMessageElement.textContent =
                    `Anda hampir sampai di ${selectedRouteDestination?.name ||
                    'tujuan'
                    }.`;
            }
        }
    }

    async function requestRoute(
        origin,
        destination,
        options = {}
    ) {
        if (!origin || !destination) {
            return false;
        }

        /*
         * Hindari dua permintaan routing berjalan
         * pada waktu yang sama.
         */
        if (routeRequestInProgress) {
            return false;
        }

        routeRequestInProgress = true;

        const shouldFitBounds =
            options.shouldFitBounds !== false;
        clearCalculatedRoute();

        if (routeStatusBadgeElement) {
            routeStatusBadgeElement.textContent =
                'Menghitung rute';

            routeStatusBadgeElement.classList.remove(
                'is-active',
                'is-error'
            );

            routeStatusBadgeElement.classList.add(
                'is-loading'
            );
        }

        if (routeMessageElement) {
            routeMessageElement.textContent =
                'Sedang mencari rute melalui jaringan jalan...';
        }

        if (routeStartButton) {
            routeStartButton.disabled = true;
        }

        /*
         * Urutan koordinat OSRM:
         * longitude, latitude.
         */
        const originCoordinate =
            `${origin.longitude},${origin.latitude}`;

        const destinationCoordinate =
            `${destination.longitude},${destination.latitude}`;

        const routeUrl =
            'https://router.project-osrm.org' +
            '/route/v1/driving/' +
            `${originCoordinate};${destinationCoordinate}` +
            '?overview=full' +
            '&geometries=geojson' +
            '&steps=true' +
            '&alternatives=3';

        try {
            const response = await fetch(
                routeUrl,
                {
                    headers: {
                        Accept: 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error(
                    `Routing server merespons ${response.status}.`
                );
            }

            const routeData =
                await response.json();

            if (
                routeData.code !== 'Ok' ||
                !Array.isArray(routeData.routes) ||
                routeData.routes.length === 0
            ) {
                throw new Error(
                    'Rute menuju tujuan tidak ditemukan.'
                );
            }

            /*
 * Hanya gunakan rute yang memiliki
 * geometri untuk digambar.
 */
            availableRoutes =
                routeData.routes.filter(
                    function (route) {
                        return Boolean(route.geometry);
                    }
                );

            if (availableRoutes.length === 0) {
                throw new Error(
                    'Geometri rute tidak tersedia.'
                );
            }

            /*
             * Menggambar semua alternatif.
             */
            drawRouteLayers(
                availableRoutes
            );

            /*
             * Membuat tombol pilihan rute.
             */
            renderRouteAlternatives(
                availableRoutes
            );

            /*
             * Rute pertama merupakan rekomendasi utama.
             */
            selectRoute(
                0,
                shouldFitBounds
            );

            if (routeClearButton) {
                routeClearButton.hidden = false;
            }

            if (
                routeNavigationStartButton &&
                !isNavigationActive
            ) {
                routeNavigationStartButton.hidden = false;
            }

            if (routeNavigationStopButton) {
                routeNavigationStopButton.hidden =
                    !isNavigationActive;
            }

        } catch (error) {
            clearCalculatedRoute();

            if (routeStatusBadgeElement) {
                routeStatusBadgeElement.textContent =
                    'Rute gagal';

                routeStatusBadgeElement.classList.remove(
                    'is-loading',
                    'is-active'
                );

                routeStatusBadgeElement.classList.add(
                    'is-error'
                );
            }

            if (routeMessageElement) {
                routeMessageElement.textContent =
                    'Rute tidak dapat dihitung. Periksa koneksi internet atau pilih tujuan lain.';
            }

            console.error(
                'Gagal menghitung rute:',
                error
            );
        } finally {
            routeRequestInProgress = false;

            if (routeStartButton) {
                routeStartButton.disabled =
                    !selectedRouteDestination;
            }
        }
    }

    /*
 * Menghentikan pelacakan posisi pengguna.
 */
    function stopLiveNavigation(
        updateInterface = true
    ) {
        if (navigationWatchId !== null) {
            navigator.geolocation.clearWatch(
                navigationWatchId
            );

            navigationWatchId = null;
        }

        isNavigationActive = false;
        lastRerouteLocation = null;
        lastRerouteTime = 0;

        if (routeNavigationStartButton) {
            routeNavigationStartButton.hidden =
                availableRoutes.length === 0;
        }

        if (routeNavigationStopButton) {
            routeNavigationStopButton.hidden = true;
        }

        if (!updateInterface) {
            return;
        }

        if (routeStatusBadgeElement) {
            routeStatusBadgeElement.textContent =
                availableRoutes.length > 0
                    ? 'Navigasi dihentikan'
                    : 'Tujuan dipilih';

            routeStatusBadgeElement.classList.remove(
                'is-loading',
                'is-error'
            );

            routeStatusBadgeElement.classList.add(
                'is-active'
            );
        }

        if (routeMessageElement) {
            routeMessageElement.textContent =
                'Pelacakan posisi dihentikan. Rute terakhir masih ditampilkan.';
        }
    }

    /*
 * Memulai pelacakan posisi secara langsung.
 */
    function startLiveNavigation() {
        if (
            !selectedRouteDestination ||
            !navigator.geolocation
        ) {
            return;
        }

        if (navigationWatchId !== null) {
            return;
        }

        isNavigationActive = true;

        lastRerouteLocation =
            currentUserLocation
                ? {
                    latitude:
                        currentUserLocation.latitude,
                    longitude:
                        currentUserLocation.longitude
                }
                : null;

        lastRerouteTime = Date.now();

        if (routeNavigationStartButton) {
            routeNavigationStartButton.hidden = true;
        }

        if (routeNavigationStopButton) {
            routeNavigationStopButton.hidden = false;
        }

        if (routeStatusBadgeElement) {
            routeStatusBadgeElement.textContent =
                'Navigasi aktif';

            routeStatusBadgeElement.classList.remove(
                'is-loading',
                'is-error'
            );

            routeStatusBadgeElement.classList.add(
                'is-active'
            );
        }

        if (routeMessageElement) {
            routeMessageElement.textContent =
                'Posisi Anda sedang dipantau. Rute akan diperbarui ketika Anda bergerak.';
        }

        navigationWatchId =
            navigator.geolocation.watchPosition(
                function (position) {
                    const nextLocation = {
                        latitude:
                            position.coords.latitude,
                        longitude:
                            position.coords.longitude,
                        accuracy:
                            position.coords.accuracy
                    };

                    currentUserLocation =
                        nextLocation;

                    showUserLocation(
                        nextLocation.latitude,
                        nextLocation.longitude,
                        nextLocation.accuracy
                    );

                    checkNavigationProgress(
                        nextLocation
                    );

                    if (hasArrivedAtDestination) {
                        return;
                    }

                    const movedDistance =
                        calculateCoordinateDistance(
                            lastRerouteLocation,
                            nextLocation
                        );

                    const elapsedTime =
                        Date.now() -
                        lastRerouteTime;

                    const shouldReroute =
                        !lastRerouteLocation ||
                        (
                            movedDistance >=
                            rerouteDistanceThreshold &&
                            elapsedTime >=
                            rerouteTimeThreshold
                        );

                    if (!shouldReroute) {
                        return;
                    }

                    lastRerouteLocation = {
                        latitude:
                            nextLocation.latitude,
                        longitude:
                            nextLocation.longitude
                    };

                    lastRerouteTime = Date.now();

                    requestRoute(
                        nextLocation,
                        selectedRouteDestination,
                        {
                            /*
                             * Jangan menggeser seluruh
                             * peta setiap pembaruan GPS.
                             */
                            shouldFitBounds: false
                        }
                    );
                },
                function (error) {
                    stopLiveNavigation(false);
                    handleLocationError(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 3000
                }
            );
    }

    routeNavigationStartButton
        ?.addEventListener(
            'click',
            startLiveNavigation
        );

    routeNavigationStopButton
        ?.addEventListener(
            'click',
            function () {
                stopLiveNavigation(true);
            }
        );

    /*
 * Tahap sementara sebelum GPS diaktifkan.
 */
    /*
 * Mengambil posisi GPS pengguna.
 */
    routeStartButton?.addEventListener(
        'click',
        function () {
            if (!selectedRouteDestination) {
                return;
            }

            /*
             * Periksa apakah browser mendukung
             * Geolocation API.
             */
            if (!navigator.geolocation) {
                if (routeStatusBadgeElement) {
                    routeStatusBadgeElement.textContent =
                        'Tidak didukung';

                    routeStatusBadgeElement
                        .classList.remove(
                            'is-loading',
                            'is-active'
                        );

                    routeStatusBadgeElement
                        .classList.add(
                            'is-error'
                        );
                }

                if (routeMessageElement) {
                    routeMessageElement.textContent =
                        'Browser ini tidak mendukung pengambilan lokasi perangkat.';
                }

                return;
            }

            /*
             * Tampilkan status proses.
             */
            routeStartButton.disabled = true;

            if (routeStatusBadgeElement) {
                routeStatusBadgeElement.textContent =
                    'Mencari GPS';

                routeStatusBadgeElement.classList.remove(
                    'is-active',
                    'is-error'
                );

                routeStatusBadgeElement.classList.add(
                    'is-loading'
                );
            }

            if (routeMessageElement) {
                routeMessageElement.textContent =
                    'Sedang mengambil posisi perangkat Anda...';
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const latitude =
                        position.coords.latitude;

                    const longitude =
                        position.coords.longitude;

                    const accuracy =
                        position.coords.accuracy;

                    currentUserLocation = {
                        latitude,
                        longitude,
                        accuracy
                    };

                    showUserLocation(
                        latitude,
                        longitude,
                        accuracy
                    );

                    hasArrivedAtDestination = false;

                    checkNavigationProgress(
                        currentUserLocation
                    );

                    /*
                     * Tampilkan posisi pengguna dan
                     * tujuan dalam satu cakupan peta.
                     */
                    const routePreviewBounds =
                        L.latLngBounds([
                            [
                                latitude,
                                longitude
                            ],
                            [
                                selectedRouteDestination
                                    .latitude,
                                selectedRouteDestination
                                    .longitude
                            ]
                        ]);

                    map.fitBounds(
                        routePreviewBounds,
                        {
                            padding: [70, 70],
                            maxZoom: 18
                        }
                    );

                    if (routeStatusBadgeElement) {
                        routeStatusBadgeElement
                            .textContent =
                            'GPS ditemukan';

                        routeStatusBadgeElement
                            .classList.remove(
                                'is-loading',
                                'is-error'
                            );

                        routeStatusBadgeElement
                            .classList.add(
                                'is-active'
                            );
                    }

                    if (routeMessageElement) {
                        const roundedAccuracy =
                            Math.round(accuracy);

                        routeMessageElement.textContent =
                            `Posisi ditemukan dengan perkiraan akurasi ${roundedAccuracy} meter. Rute sedang dihitung...`;
                    }

                    /*
                     * Setelah GPS ditemukan, langsung
                     * hitung rute menuju fasilitas.
                     */
                    requestRoute(
                        currentUserLocation,
                        selectedRouteDestination
                    );
                },
                handleLocationError,
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }
    );

    /*
 * Membersihkan posisi pengguna dan
 * informasi perjalanan sementara.
 */
    routeClearButton?.addEventListener(
        'click',
        function () {
            stopLiveNavigation(false);
            clearCalculatedRoute();
            hasArrivedAtDestination = false;

            if (routeRemainingDistanceElement) {
                routeRemainingDistanceElement.textContent =
                    '—';
            }
            currentUserLocation = null;

            if (userLocationMarker) {
                map.removeLayer(
                    userLocationMarker
                );

                userLocationMarker = null;
            }

            if (userAccuracyCircle) {
                map.removeLayer(
                    userAccuracyCircle
                );

                userAccuracyCircle = null;
            }

            if (routeSummaryElement) {
                routeSummaryElement.hidden = true;
            }

            if (routeDistanceElement) {
                routeDistanceElement.textContent =
                    '—';
            }

            if (routeDurationElement) {
                routeDurationElement.textContent =
                    '—';
            }

            if (routeClearButton) {
                routeClearButton.hidden = true;
            }

            if (routeStatusBadgeElement) {
                routeStatusBadgeElement.textContent =
                    selectedRouteDestination
                        ? 'Tujuan dipilih'
                        : 'Belum aktif';

                routeStatusBadgeElement.classList.remove(
                    'is-loading',
                    'is-error'
                );

                routeStatusBadgeElement.classList.toggle(
                    'is-active',
                    Boolean(
                        selectedRouteDestination
                    )
                );
            }

            if (routeMessageElement) {
                routeMessageElement.textContent =
                    selectedRouteDestination
                        ? 'Tujuan masih dipilih. Tekan “Rute ke Lokasi” untuk mengambil kembali posisi GPS.'
                        : 'Pilih salah satu fasilitas pada peta untuk melihat pilihan rute dari posisi Anda.';
            }

            if (routeNavigationStartButton) {
                routeNavigationStartButton.hidden = true;
            }

            if (routeNavigationStopButton) {
                routeNavigationStopButton.hidden = true;
            }
        }
    );
});
