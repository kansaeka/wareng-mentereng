document.addEventListener('DOMContentLoaded', () => {
    const mapElement =
        document.getElementById(
            'story-chapter-map'
        );

    const latitudeInput =
        document.getElementById('latitude');

    const longitudeInput =
        document.getElementById('longitude');

    const zoomInput =
        document.getElementById('map_zoom');

    const clearButton =
        document.getElementById(
            'clear-story-chapter-location'
        );

    if (
        !mapElement ||
        !latitudeInput ||
        !longitudeInput ||
        typeof L === 'undefined'
    ) {
        return;
    }

    /*
     * Titik pusat sementara.
     * Akan diganti setelah batas Wareng
     * terverifikasi tersedia.
     */
    const defaultCenter = [
        -7.57167,
        110.18639,
    ];

    const latitude = Number.parseFloat(
        latitudeInput.value
    );

    const longitude = Number.parseFloat(
        longitudeInput.value
    );

    const initialZoom = Number.parseInt(
        zoomInput?.value,
        10
    );

    const hasInitialLocation =
        Number.isFinite(latitude) &&
        Number.isFinite(longitude);

    const map = L.map(mapElement).setView(
        hasInitialLocation
            ? [latitude, longitude]
            : defaultCenter,
        Number.isFinite(initialZoom)
            ? initialZoom
            : 16
    );

    L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 20,
            attribution:
                '&copy; OpenStreetMap contributors',
        }
    ).addTo(map);

    let marker = null;

    function setMarker(
        selectedLatitude,
        selectedLongitude,
        moveMap = false
    ) {
        if (!marker) {
            marker = L.marker(
                [
                    selectedLatitude,
                    selectedLongitude,
                ],
                {
                    draggable: true,
                }
            ).addTo(map);

            marker.on('dragend', event => {
                const position =
                    event.target.getLatLng();

                updateInputs(
                    position.lat,
                    position.lng
                );
            });
        } else {
            marker.setLatLng([
                selectedLatitude,
                selectedLongitude,
            ]);
        }

        updateInputs(
            selectedLatitude,
            selectedLongitude
        );

        if (moveMap) {
            map.panTo([
                selectedLatitude,
                selectedLongitude,
            ]);
        }
    }

    function updateInputs(
        selectedLatitude,
        selectedLongitude
    ) {
        latitudeInput.value =
            selectedLatitude.toFixed(6);

        longitudeInput.value =
            selectedLongitude.toFixed(6);
    }

    function updateMarkerFromInputs() {
        const newLatitude =
            Number.parseFloat(
                latitudeInput.value
            );

        const newLongitude =
            Number.parseFloat(
                longitudeInput.value
            );

        if (
            !Number.isFinite(newLatitude) ||
            !Number.isFinite(newLongitude)
        ) {
            return;
        }

        setMarker(
            newLatitude,
            newLongitude,
            true
        );
    }

    if (hasInitialLocation) {
        setMarker(
            latitude,
            longitude
        );
    }

    map.on('click', event => {
        setMarker(
            event.latlng.lat,
            event.latlng.lng
        );
    });

    latitudeInput.addEventListener(
        'change',
        updateMarkerFromInputs
    );

    longitudeInput.addEventListener(
        'change',
        updateMarkerFromInputs
    );

    zoomInput?.addEventListener(
        'change',
        () => {
            const newZoom =
                Number.parseInt(
                    zoomInput.value,
                    10
                );

            if (Number.isFinite(newZoom)) {
                map.setZoom(newZoom);
            }
        }
    );

    clearButton?.addEventListener(
        'click',
        () => {
            latitudeInput.value = '';
            longitudeInput.value = '';

            if (marker) {
                map.removeLayer(marker);
                marker = null;
            }
        }
    );

    window.addEventListener('resize', () => {
        map.invalidateSize();
    });
});
