// Inisialisasi peta Leaflet
var map = L.map('map').setView([-6.592157,106.806978], 13); // Ganti koordinat sesuai lokasi

// Tambahkan tile layer dari OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: 'Peta oleh <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
}).addTo(map);

// Contoh marker rumah
var rumah1 = L.marker([-6.914, 107.609]).addTo(map)
  .bindPopup("<b>Rumah A1</b><br>Jl. Tirta Raya No. 1").openPopup();

var rumah2 = L.marker([-6.916, 107.611]).addTo(map)
  .bindPopup("<b>Rumah B2</b><br>Jl. Tirta Lestari No. 8");

// Tambahkan event untuk klik di peta
map.on('click', function(e) {
  var latlng = e.latlng;
  L.popup()
    .setLatLng(latlng)
    .setContent("Koordinat: " + latlng.lat.toFixed(6) + ", " + latlng.lng.toFixed(6))
    .openOn(map);
});
