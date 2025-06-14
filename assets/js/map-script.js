let map;
let marker;

function initMap() {
  // Titik pusat peta, contoh pusat Indonesia
  const center = { lat: -2.5489, lng: 118.0149 };

  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 5,
    center: center,
  });

  // Jika ada data lokasi dari pencarian, tampilkan marker-nya
  if (locationData && locationData.length > 0) {
    locationData.forEach(loc => {
      const pos = { lat: parseFloat(loc[0]), lng: parseFloat(loc[1]) };
      const markerLoc = new google.maps.Marker({
        position: pos,
        map: map,
        title: `${loc[2]}, ${loc[3]}`,
      });

      const infoContent = `
        <div>
          <strong>Desa:</strong> ${loc[2]}<br>
          <strong>Kecamatan:</strong> ${loc[3]}<br>
          <strong>Laki-laki:</strong> ${loc[4]}<br>
          <strong>Perempuan:</strong> ${loc[5]}<br>
          <strong>Jumlah:</strong> ${loc[6]}
        </div>
      `;

      const infoWindow = new google.maps.InfoWindow({
        content: infoContent,
      });

      markerLoc.addListener('click', () => {
        infoWindow.open(map, markerLoc);
      });
    });

    // Zoom ke lokasi pertama agar fokus peta pas data muncul
    map.setCenter({ lat: parseFloat(locationData[0][0]), lng: parseFloat(locationData[0][1]) });
    map.setZoom(12);
  }

  // Klik peta untuk pilih koordinat lokasi baru
  map.addListener('click', (e) => {
    const lat = e.latLng.lat();
    const lng = e.latLng.lng();

    // Jika marker sudah ada, hapus dulu supaya gak numpuk
    if (marker) {
      marker.setMap(null);
    }

    // Buat marker baru di titik yang diklik
    marker = new google.maps.Marker({
      position: { lat, lng },
      map: map,
      title: "Lokasi baru",
      draggable: true,
    });

    // Isi nilai koordinat ke input hidden form
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;

    // Kalau marker digeser, update koordinat di form juga
    marker.addListener('dragend', () => {
      const newPos = marker.getPosition();
      document.getElementById('lat').value = newPos.lat();
      document.getElementById('lng').value = newPos.lng();
    });
  });
}
