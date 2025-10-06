document.addEventListener('DOMContentLoaded', function () {
    // Create a map centered on a specific position (latitude, longitude)
    const map = L.map('map').setView([51.505, -0.09], 13);

    // Add TileLayer to the map (background of the map)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // Fix for the missing marker icon issue
    L.Icon.Default.mergeOptions({
        iconUrl:
            'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        iconRetinaUrl:
            'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
        shadowUrl:
            'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    });

    // Create a marker at the center of the map
    const marker = L.marker([51.505, -0.09]).addTo(map);

    // Add a popup to the marker
    marker.bindPopup('A pretty CSS3 popup.<br /> Easily customizable.').openPopup();
});