document.addEventListener('DOMContentLoaded', function () {
    const mapElement =
        document.getElementById('admin-facility-map');

    const latitudeInput =
        document.getElementById('latitude');

    const longitudeInput =
        document.getElementById('longitude');

    const statusElement =
        document.getElementById(
            'admin-coordinate-map-status'
        );

    const syncButton =
        document.getElementById(
            'sync-coordinate-map-button'
        );

    const resetButton =
        document.getElementById(
            'reset-coordinate-map-button'
        );

    if (
        !mapElement ||
        !latitudeInput ||
        !longitudeInput
    ) {
        return;
    }

    if (typeof L === 'undefined') {
        if (statusElement) {
            statusElement.textContent =
                'Peta tidak dapat dimuat karena Leaflet belum tersedia.';

            statusElement.classList.add(
                'is-error'
            );
        }

        return;
    }

    const defaultLatitude =
        Number.parseFloat(
            mapElement.dataset.defaultLatitude
        );

    const defaultLongitude =
        Number.parseFloat(
            mapElement.dataset.defaultLongitude
        );

    const defaultLocation = [
        Number.isFinite(defaultLatitude)
            ? defaultLatitude
            : -7.57167,

        Number.isFinite(defaultLongitude)
            ? defaultLongitude
            : 110.18639
    ];

    const map = L.map('admin-facility-map', {
        zoomControl: true,
        scrollWheelZoom: true
    }).setView(defaultLocation, 16);

    L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution:
                '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);

    let selectedMarker = null;

    function coordinatesAreValid(
        latitude,
        longitude
    ) {
        return (
            Number.isFinite(latitude) &&
            Number.isFinite(longitude) &&
            latitude >= -90 &&
            latitude <= 90 &&
            longitude >= -180 &&
            longitude <= 180
        );
    }

    function setStatus(
        message,
        statusType = ''
    ) {
        if (!statusElement) {
            return;
        }

        statusElement.textContent = message;

        statusElement.classList.remove(
            'is-success',
            'is-error'
        );

        if (statusType) {
            statusElement.classList.add(
                statusType
            );
        }
    }

    function updateCoordinateInputs(latlng) {
        latitudeInput.value =
            latlng.lat.toFixed(6);

        longitudeInput.value =
            latlng.lng.toFixed(6);
    }

    function bindMarkerEvents(marker) {
        marker.on('dragend', function (event) {
            const newPosition =
                event.target.getLatLng();

            updateCoordinateInputs(
                newPosition
            );

            setStatus(
                `Koordinat diperbarui menjadi ` +
                `${newPosition.lat.toFixed(6)}, ` +
                `${newPosition.lng.toFixed(6)}.`,
                'is-success'
            );
        });
    }

    function placeMarker(
        latitude,
        longitude,
        options = {}
    ) {
        if (
            !coordinatesAreValid(
                latitude,
                longitude
            )
        ) {
            setStatus(
                'Koordinat tidak valid. Periksa nilai latitude dan longitude.',
                'is-error'
            );

            return;
        }

        const latlng =
            L.latLng(latitude, longitude);

        if (selectedMarker) {
            selectedMarker.setLatLng(latlng);
        } else {
            selectedMarker = L.marker(
                latlng,
                {
                    draggable: true,
                    title:
                        'Geser marker untuk memperbaiki lokasi'
                }
            ).addTo(map);

            bindMarkerEvents(
                selectedMarker
            );
        }

        if (options.updateInputs !== false) {
            updateCoordinateInputs(latlng);
        }

        if (options.focus !== false) {
            map.flyTo(
                latlng,
                18,
                {
                    duration: 0.8
                }
            );
        }

        selectedMarker
            .bindPopup(`
                <div class="map-popup">
                    <strong>Lokasi Fasilitas</strong>

                    <p>
                        Latitude:
                        ${latlng.lat.toFixed(6)}
                        <br>
                        Longitude:
                        ${latlng.lng.toFixed(6)}
                    </p>

                    <small>
                        Marker dapat digeser.
                    </small>
                </div>
            `);

        setStatus(
            `Lokasi dipilih pada ` +
            `${latlng.lat.toFixed(6)}, ` +
            `${latlng.lng.toFixed(6)}.`,
            'is-success'
        );
    }

    /*
     * Memilih koordinat melalui klik peta.
     */
    map.on('click', function (event) {
        placeMarker(
            event.latlng.lat,
            event.latlng.lng,
            {
                focus: false,
                updateInputs: true
            }
        );
    });

    /*
     * Menampilkan koordinat yang sudah
     * ditulis secara manual pada input.
     */
    if (syncButton) {
        syncButton.addEventListener(
            'click',
            function () {
                const latitude =
                    Number.parseFloat(
                        latitudeInput.value
                    );

                const longitude =
                    Number.parseFloat(
                        longitudeInput.value
                    );

                if (
                    !coordinatesAreValid(
                        latitude,
                        longitude
                    )
                ) {
                    setStatus(
                        'Isi latitude dan longitude yang valid terlebih dahulu.',
                        'is-error'
                    );

                    return;
                }

                placeMarker(
                    latitude,
                    longitude,
                    {
                        updateInputs: false,
                        focus: true
                    }
                );
            }
        );
    }

    /*
     * Mengembalikan tampilan peta ke
     * pusat sementara Dusun Wareng.
     * Koordinat input tidak dihapus.
     */
    if (resetButton) {
        resetButton.addEventListener(
            'click',
            function () {
                map.flyTo(
                    defaultLocation,
                    16,
                    {
                        duration: 0.8
                    }
                );

                setStatus(
                    'Peta dipusatkan kembali ke wilayah Wareng.'
                );
            }
        );
    }

    /*
     * Saat halaman edit dibuka atau validasi
     * gagal, koordinat lama langsung ditampilkan.
     */
    const initialLatitude =
        Number.parseFloat(
            latitudeInput.value
        );

    const initialLongitude =
        Number.parseFloat(
            longitudeInput.value
        );

    if (
        coordinatesAreValid(
            initialLatitude,
            initialLongitude
        )
    ) {
        placeMarker(
            initialLatitude,
            initialLongitude,
            {
                updateInputs: false,
                focus: false
            }
        );

        map.setView(
            [
                initialLatitude,
                initialLongitude
            ],
            18
        );

        setStatus(
            'Koordinat fasilitas saat ini ditampilkan pada peta.',
            'is-success'
        );
    }

    /*
     * Memastikan ukuran peta dihitung ulang
     * setelah seluruh tampilan selesai dimuat.
     */
    window.setTimeout(function () {
        map.invalidateSize();
    }, 150);
});
