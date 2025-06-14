<?php
session_start();

// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_sig";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$feedback = "";
$feedback_type = ""; // Tambahkan untuk tipe feedback (success, danger)

// Ambil data rumah untuk dropdown dan pemetaan
$sql_rumah_dropdown = "SELECT id, nama_pemilik, alamat, latitude, longitude FROM data_rumah ORDER BY nama_pemilik ASC";
$result_rumah_dropdown = $conn->query($sql_rumah_dropdown);

$rumahDataDropdown = [];
if ($result_rumah_dropdown && $result_rumah_dropdown->num_rows > 0) {
    while ($row = $result_rumah_dropdown->fetch_assoc()) {
        $rumahDataDropdown[] = $row;
    }
}

// Proses Tambah Rumah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_lokasi'])) {
    $nama_pemilik = htmlspecialchars(trim($_POST['nama_pemilik']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);

    // Validasi sederhana
    if (empty($nama_pemilik) || empty($alamat) || !is_numeric($latitude) || !is_numeric($longitude)) {
        $feedback = "Mohon isi semua kolom data rumah dengan benar.";
        $feedback_type = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO data_rumah (nama_pemilik, alamat, latitude, longitude) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdd", $nama_pemilik, $alamat, $latitude, $longitude);

        if ($stmt->execute()) {
            $feedback = "Data rumah berhasil ditambahkan!";
            $feedback_type = "success";
        } else {
            $feedback = "Gagal menambahkan data rumah: " . $stmt->error;
            $feedback_type = "danger";
        }
        $stmt->close();
        // Refresh data setelah penambahan dan arahkan ke peta
        header("Location: " . $_SERVER['PHP_SELF'] . "#map-section");
        exit();
    }
}

// Proses Tambah Warga
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_warga'])) {
    $id_rumah = intval($_POST['id_rumah']);
    $nama_warga = htmlspecialchars(trim($_POST['nama_warga']));
    $umur = intval($_POST['umur']);

    // Validasi sederhana
    if (empty($nama_warga) || empty($umur) || empty($id_rumah) || $umur <= 0) {
        $feedback = "Mohon isi semua kolom data warga dengan benar.";
        $feedback_type = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO warga (rumah_id, nama_warga, umur) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $id_rumah, $nama_warga, $umur);

        if ($stmt->execute()) {
            $feedback = "Data warga berhasil ditambahkan!";
            $feedback_type = "success";
        } else {
            $feedback = "Gagal menambahkan data warga: " . $stmt->error;
            $feedback_type = "danger";
        }
        $stmt->close();
        // Refresh data setelah penambahan dan arahkan ke peta
        header("Location: " . $_SERVER['PHP_SELF'] . "#map-section");
        exit();
    }
}

// Ambil data rumah beserta warganya untuk tampilan peta
$rumahDataWithWarga = [];
$sql_full = "SELECT dr.id, dr.nama_pemilik, dr.alamat, dr.latitude, dr.longitude, w.nama_warga, w.umur
             FROM data_rumah dr LEFT JOIN warga w ON dr.id = w.rumah_id ORDER BY dr.id";
$result_full = $conn->query($sql_full);

$temp_rumah = [];
if ($result_full && $result_full->num_rows > 0) {
    while ($row = $result_full->fetch_assoc()) {
        $rumah_id = $row['id'];
        if (!isset($temp_rumah[$rumah_id])) {
            $temp_rumah[$rumah_id] = [
                'id' => $row['id'],
                'nama_pemilik' => $row['nama_pemilik'],
                'alamat' => $row['alamat'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'penghuni' => []
            ];
        }
        if (!empty($row['nama_warga'])) {
            $temp_rumah[$rumah_id]['penghuni'][] = [
                'nama_warga' => $row['nama_warga'],
                'umur' => $row['umur']
            ];
        }
    }
    $rumahDataWithWarga = array_values($temp_rumah);
}

// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistem Informasi Geografis Perumahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd; /* Biru Bootstrap */
            --secondary-color: #6c757d; /* Abu-abu gelap */
            --success-color: #28a745; /* Hijau */
            --danger-color: #dc3545; /* Merah */
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
            --info-color: #17a2b8;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-bg);
        }

        /* --- Navbar Styling --- */
        .navbar {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #ffffff !important; /* Override Bootstrap dark bg */
            padding: 0.8rem 1rem;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--primary-color) !important;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover {
            color: #0b5ed7 !important;
        }
        .navbar-nav .nav-link {
            color: var(--secondary-color) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
            border-bottom-color: var(--primary-color);
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }
        .navbar-toggler-icon {
            transition: transform 0.2s ease-in-out;
        }
        .navbar-toggler-icon:hover {
            transform: scale(1.2);
        }

        /* --- Hero Section / Jumbotron --- */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), #2575fc); /* Gradien biru */
            color: white;
            padding: 80px 0;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 1.5rem;
        }
        .hero-section p.lead {
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto 2rem;
        }
        .hero-section .btn-light {
            background-color: white;
            color: var(--primary-color);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .hero-section .btn-light:hover {
            background-color: #e2e6ea;
            color: #0a58ca;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* --- Section Titles --- */
        h2 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2.5rem;
            position: relative;
            padding-bottom: 10px;
            text-align: center;
        }
        h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        /* --- Map Section --- */
        #map-container {
            position: relative;
            margin-bottom: 30px;
        }
        #map {
            height: 550px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }
        .map-controls {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 90%;
            max-width: 500px;
            display: flex;
            gap: 10px;
        }
        .map-controls .form-control, .map-controls .btn {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .map-controls .btn {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .map-controls .btn:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }
        /* Leaflet Popups */
        .leaflet-popup-content-wrapper {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .leaflet-popup-content {
            font-family: 'Poppins', sans-serif;
            padding: 10px;
        }
        .leaflet-popup-content h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 5px;
        }
        .leaflet-popup-content small {
            color: var(--secondary-color);
            font-size: 0.85rem;
        }
        .leaflet-popup-content ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        .leaflet-popup-content ul li {
            font-size: 0.9rem;
            color: var(--dark-bg);
            line-height: 1.5;
        }
        .leaflet-popup-content .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 8px;
        }
        .leaflet-popup-content .btn-info:hover {
            background-color: #138496;
            border-color: #138496;
        }

        /* --- Modal Styling --- */
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            border: none;
        }
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 1.5rem;
            border-bottom: none;
        }
        .modal-header .modal-title {
            font-weight: 600;
        }
        .modal-header .btn-close {
            filter: invert(1); /* Agar ikon silang terlihat di latar belakang gelap */
        }
        .modal-body {
            padding: 2rem;
        }
        .modal-footer {
            border-top: none;
            padding: 1.5rem 2rem;
            background-color: var(--light-bg);
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }
        .modal-footer .btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        .modal-footer .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        .modal-footer .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* --- Alert Messages --- */
        .alert {
            border-radius: 10px;
            font-weight: 500;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        /* --- Utility & Animation --- */
        .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
        .fade-in-section {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            will-change: opacity, transform;
        }
        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Footer Styling (opsional, bisa ditambahkan jika ada footer) --- */
        .main-footer {
            background-color: var(--dark-bg);
            color: #f8f9fa;
            padding: 40px 0 20px;
            border-top: 5px solid var(--primary-color);
        }
        .main-footer a {
            color: #adb5bd;
            text-decoration: none;
        }
        .main-footer a:hover {
            color: var(--primary-color);
        }
        .main-footer .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
            font-size: 0.85rem;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#beranda">
            <i class="fas fa-map-marked-alt me-2"></i>SIG Perumahan
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="#beranda" class="nav-link active">
                        <i class="fas fa-home me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#map-section" class="nav-link">
                        <i class="fas fa-map-marker-alt me-1"></i> Peta Lokasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahRumah">
                        <i class="fas fa-house-chimney-medical me-1"></i> Tambah Rumah
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahWarga">
                        <i class="fas fa-users-line me-1"></i> Tambah Warga
                    </a>
                </li>
                <li class="nav-item">
                    <a href="data.php" class="nav-link">
                        <i class="fas fa-table me-1"></i> Tabel Data
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section id="beranda" class="hero-section fade-in-section">
    <div class="container">
        <h1>🏡 Sistem Informasi Geografis Perumahan</h1>
        <p class="lead">Kelola dan visualisasikan data perumahan Anda dengan mudah di peta interaktif.</p>
        <hr class="my-4">
        <p class="mb-4 text-white fw-semibold">
            Tambahkan rumah, data warga, dan pantau lokasi secara real-time.
        </p>
        <a class="btn btn-light btn-lg" href="#map-section" role="button">
            <i class="fas fa-compass me-2"></i> Jelajahi Peta Sekarang
        </a>
    </div>
</section>

<?php if (!empty($feedback)) : ?>
    <div class="container mt-4 fade-in-section is-visible">
        <div class="alert alert-<?= $feedback_type ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($feedback) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<section id="map-section" class="container py-5 fade-in-section">
    <h2>🗺️ Peta Lokasi Perumahan</h2>
    <div id="map-container">
        <div class="map-controls">
            <input type="text" id="cariInput" class="form-control" placeholder="Cari nama pemilik atau warga..." />
            <button id="lokasiBtn" class="btn" title="Temukan Lokasi Saya">
                <i class="fas fa-crosshairs"></i>
            </button>
        </div>
        <div id="map"></div>
    </div>
</section>

<div class="modal fade" id="modalTambahRumah" tabindex="-1" aria-labelledby="modalTambahRumahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="#map-section" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahRumahLabel">➕ Tambah Lokasi Rumah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        <div class="invalid-feedback">Mohon masukkan nama pemilik.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="alamat" class="form-label">Alamat Rumah</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Contoh: Blok A1 No. 5" required>
                        <div class="invalid-feedback">Mohon masukkan alamat rumah.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Contoh: -6.60905" required pattern="^-?\d+(\.\d+)?$">
                        <div class="invalid-feedback">Mohon masukkan nilai latitude yang valid (angka).</div>
                    </div>
                    <div class="col-md-6">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Contoh: 106.75529" required pattern="^-?\d+(\.\d+)?$">
                        <div class="invalid-feedback">Mohon masukkan nilai longitude yang valid (angka).</div>
                    </div>
                </div>
                <small class="text-muted mt-3 d-block">Tips: Klik di peta untuk mendapatkan koordinat Latitude dan Longitude!</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="tambah_lokasi" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Rumah
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalTambahWarga" tabindex="-1" aria-labelledby="modalTambahWargaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="#map-section" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahWargaLabel">👨‍👩‍👧 Tambah Warga ke Rumah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="id_rumah" class="form-label">Pilih Rumah</label>
                    <select name="id_rumah" id="id_rumah" class="form-select" required>
                        <option value="">-- Pilih Rumah --</option>
                        <?php foreach ($rumahDataDropdown as $r) : ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nama_pemilik']) ?> (<?= htmlspecialchars($r['alamat']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Mohon pilih rumah.</div>
                </div>
                <div class="mb-3">
                    <label for="nama_warga" class="form-label">Nama Warga</label>
                    <input type="text" name="nama_warga" id="nama_warga" class="form-control" placeholder="Contoh: Fitri" required>
                    <div class="invalid-feedback">Mohon masukkan nama warga.</div>
                </div>
                <div class="mb-3">
                    <label for="umur" class="form-label">Umur</label>
                    <input type="number" name="umur" id="umur" class="form-control" placeholder="Contoh: 30" required min="1">
                    <div class="invalid-feedback">Mohon masukkan umur yang valid (minimal 1).</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="tambah_warga" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Tambah Warga
                </button>
            </div>
        </form>
    </div>
</div>

<footer class="main-footer mt-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-12 text-center copyright">
                <p class="mb-0">© <?php echo date("Y"); ?> SIG Perumahan. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // --- Leaflet Map Initialization ---
    const map = L.map('map', {
        zoomControl: false // Nonaktifkan kontrol zoom default
    }).setView([-6.60905, 106.75529], 16); // Set view ke tengah perumahan

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan kontrol zoom kustom
    L.control.zoom({
        position: 'bottomright' // Posisikan di kanan bawah
    }).addTo(map);

    const rumahDataWithWarga = <?php echo json_encode($rumahDataWithWarga); ?>;
    const markerMap = {}; // Untuk menyimpan referensi marker

    // Custom Icon untuk Rumah
    const homeIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
        iconRetinaUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue-2x.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Custom Icon untuk Lokasi Pengguna
    const userLocationIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        iconRetinaUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red-2x.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Tambahkan marker untuk setiap rumah
    const markers = [];
    rumahDataWithWarga.forEach(r => {
        const marker = L.marker([r.latitude, r.longitude], {icon: homeIcon}).addTo(map);
        let popupContent = `<div class="card p-2 shadow-sm border-0">
                                <h6 class="card-title text-primary mb-1"><i class="fas fa-house-chimney me-1"></i>${r.nama_pemilik}</h6>
                                <p class="card-text mb-1"><small><i class="fas fa-map-pin me-1"></i>${r.alamat}</small></p>`;

        if (r.penghuni && r.penghuni.length > 0) {
            popupContent += `<hr class="my-2"><b><i class="fas fa-users-line me-1"></i>Warga:</b><ul>`;
            r.penghuni.forEach(w => {
                popupContent += `<li><small>${w.nama_warga} (${w.umur} th)</small></li>`;
            });
            popupContent += `</ul>`;
        } else {
            popupContent += `<p class="mb-1"><small class="text-muted"><i>Belum ada data warga</i></small></p>`;
        }

        // Encode URI Component untuk URL agar spasi dan karakter khusus tidak bermasalah
        const googleMapsLink = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(r.latitude)},${encodeURIComponent(r.longitude)}`;
        popupContent += `<a href="${googleMapsLink}" target="_blank" class="btn btn-sm btn-info mt-2">
                            <i class="fas fa-directions me-1"></i> Dapatkan Arah
                        </a></div>`;
        
        marker.bindPopup(popupContent);
        markerMap[r.nama_pemilik.toLowerCase()] = marker;
        // Tambahkan nama warga ke markerMap agar bisa dicari
        r.penghuni.forEach(w => markerMap[w.nama_warga.toLowerCase()] = marker);
        markers.push(marker); // Tambahkan marker ke array untuk fitBounds
    });

    // Fungsi untuk menyesuaikan zoom agar semua marker terlihat
    function fitAllMarkers() {
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2)); // Pad 0.2 agar ada sedikit ruang di tepi
        }
    }
    fitAllMarkers(); // Panggil saat peta pertama kali dimuat

    // Fitur "Lokasi Saya"
    let userMarker;
    document.getElementById('lokasiBtn').addEventListener('click', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;

                if (userMarker) {
                    map.removeLayer(userMarker); // Hapus marker lama jika ada
                }
                userMarker = L.marker([lat, lon], { icon: userLocationIcon })
                    .addTo(map)
                    .bindPopup("<strong>Lokasi Anda Saat Ini</strong><br>Latitude: " + lat.toFixed(6) + "<br>Longitude: " + lon.toFixed(6))
                    .openPopup();
                map.setView([lat, lon], 17); // Zoom in ke lokasi pengguna
            }, (error) => {
                let errorMessage;
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "Izin lokasi ditolak. Mohon aktifkan izin lokasi di browser Anda.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "Informasi lokasi tidak tersedia.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "Permintaan waktu habis saat mencoba mendapatkan lokasi pengguna.";
                        break;
                    case error.UNKNOWN_ERROR:
                        errorMessage = "Terjadi kesalahan tidak diketahui.";
                        break;
                }
                alert("Gagal mengambil lokasi: " + errorMessage);
            }, { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }); // Minta akurasi tinggi
        } else {
            alert("Browser Anda tidak mendukung Geolocation.");
        }
    });

    // Fitur Pencarian Interaktif
    document.getElementById('cariInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase().trim();
        let foundMarker = null;

        if (keyword === '') {
            fitAllMarkers(); // Kembali ke zoom awal jika input kosong
            return;
        }

        // Prioritaskan pencarian nama pemilik, lalu nama warga
        for (const r of rumahDataWithWarga) {
            if (r.nama_pemilik.toLowerCase().includes(keyword)) {
                foundMarker = markerMap[r.nama_pemilik.toLowerCase()];
                break;
            }
            for (const w of r.penghuni) {
                if (w.nama_warga.toLowerCase().includes(keyword)) {
                    foundMarker = markerMap[w.nama_warga.toLowerCase()];
                    break;
                }
            }
            if (foundMarker) break;
        }

        if (foundMarker) {
            map.setView(foundMarker.getLatLng(), 18); // Zoom ke lokasi marker yang ditemukan
            foundMarker.openPopup(); // Buka popup marker
        } else {
            // Opsional: bisa menambahkan visual feedback bahwa tidak ada hasil
            // console.log("Tidak ada hasil untuk: " + keyword);
        }
    });

    // Fitur Klik Peta untuk Mendapatkan Koordinat
    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        
        // Isi otomatis input latitude dan longitude di modal Tambah Rumah
        const modalTambahRumah = document.getElementById('modalTambahRumah');
        const bsModalTambahRumah = bootstrap.Modal.getInstance(modalTambahRumah); // Ambil instance modal
        if (bsModalTambahRumah && modalTambahRumah.classList.contains('show')) { // Cek apakah modal sedang terbuka
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        } else {
            // Jika modal tidak terbuka, tampilkan koordinat di popup
            L.popup()
                .setLatLng(e.latlng)
                .setContent(`<b>Koordinat yang Diklik:</b><br>Latitude: ${lat}<br>Longitude: ${lng}<br><small class="text-muted">Klik tombol 'Tambah Rumah' untuk menggunakannya.</small>`)
                .openOn(map);
        }
    });

    // Bootstrap Form Validation
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
    })();

    // Observer untuk animasi fade-in
    const fadeInSections = document.querySelectorAll('.fade-in-section');
    const observerOptions = {
        root: null, // viewport
        rootMargin: '0px',
        threshold: 0.1 // 10% of element visible to trigger
    };

    const sectionObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target); // Stop observing once it's visible
            }
        });
    }, observerOptions);

    fadeInSections.forEach(section => {
        sectionObserver.observe(section);
    });

    // Scroll to map section if feedback is present
    document.addEventListener('DOMContentLoaded', () => {
        <?php if (!empty($feedback)) : ?>
            const mapSection = document.getElementById('map-section');
            if (mapSection) {
                mapSection.scrollIntoView({ behavior: 'smooth' });
            }
        <?php endif; ?>
    });

</script>

</body>
</html>