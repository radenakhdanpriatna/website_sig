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

// Ambil data rumah untuk peta dan dropdown ulasan
$sql = "SELECT id, nama_pemilik, alamat, latitude, longitude FROM data_rumah";
$result = $conn->query($sql);

$rumahData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rumahData[] = [
            'id' => $row['id'],
            'nama' => $row['nama_pemilik'],
            'alamat' => $row['alamat'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude']
        ];
    }
}


// Proses submit ULASAN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating']) && isset($_POST['rumah_id'])) {
    $rumah_id = $_POST['rumah_id'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $komentar = $_POST['komentar'];
    $rating = $_POST['rating'];

    // Validasi sederhana
    if (empty($nama_pengguna) || empty($komentar) || empty($rating) || empty($rumah_id)) {
        $feedback = "Semua kolom ulasan harus diisi.";
        $feedback_type = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO ulasan (rumah_id, nama_pengguna, komentar, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $rumah_id, $nama_pengguna, $komentar, $rating);

        if ($stmt->execute()) {
            $feedback = "Ulasan berhasil dikirim, terima kasih atas masukan Anda!";
            $feedback_type = "success";
        } else {
            $feedback = "Gagal mengirim ulasan: " . $stmt->error;
            $feedback_type = "danger";
        }
        $stmt->close();
    }
}

// Proses submit KONTAK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan']) && isset($_POST['email']) && !isset($_POST['rating'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];

    // Validasi sederhana
    if (empty($nama) || empty($email) || empty($pesan) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Mohon isi semua kolom kontak dengan benar, termasuk format email.";
        $feedback_type = "danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO kontak (nama, email, pesan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $email, $pesan);

        if ($stmt->execute()) {
            $feedback = "Pesan berhasil dikirim, kami akan segera merespon!";
            $feedback_type = "success";
        } else {
            $feedback = "Gagal mengirim pesan: " . $stmt->error;
            $feedback_type = "danger";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SIG Pemetaan Perumahan - Alam Tirta Lestari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
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
        /* --- Jumbotron / Hero Section --- */
        .jumbotron {
            background: linear-gradient(135deg, var(--primary-color), #2575fc); /* Gradien biru */
            color: white;
            padding: 80px 0;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }
        .jumbotron h2 {
            color: white;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
        }
        .jumbotron p.lead {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }
        .jumbotron .btn-primary {
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
        .jumbotron .btn-primary:hover {
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
        #map {
            height: 550px; /* Tinggi peta lebih besar */
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            position: relative; /* Untuk posisi elemen dalam peta */
        }
        .map-controls {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000; /* Pastikan di atas peta */
            width: 90%;
            max-width: 400px;
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


        /* --- Galeri Carousel --- */
        #galeriCarousel {
            max-width: 700px; /* Batasi lebar carousel */
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden; /* Penting untuk radius */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        #galeriCarousel .carousel-item img {
            height: 450px; /* Atur tinggi gambar */
            object-fit: cover; /* Crop gambar supaya tetap rapi */
            border-radius: 15px; /* Radius gambar di dalam */
            transition: transform 0.5s ease;
        }
        #galeriCarousel .carousel-item.active img {
            transform: scale(1.02); /* Sedikit zoom pada gambar aktif */
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6) !important; /* Background caption lebih gelap */
            border-radius: 10px;
            padding: 10px 15px;
            bottom: 20px;
            font-size: 0.95rem;
            color: white;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5) !important;
            border-radius: 50%;
            padding: 15px;
            width: 50px;
            height: 50px;
            transition: background-color 0.3s ease;
        }
        .carousel-control-prev-icon:hover, .carousel-control-next-icon:hover {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }

        /* --- Ulasan Section --- */
        #ulasan form, #kontak form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2.5rem;
        }
        #ulasan .form-control, #kontak .form-control, #ulasan select {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #dee2e6;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        #ulasan .form-control:focus, #kontak .form-control:focus, #ulasan select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        #ulasan .btn-primary, #kontak .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        #ulasan .btn-primary:hover, #kontak .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }

        /* Ulasan Cards */
        .ulasan-card {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }
        .ulasan-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .ulasan-card strong {
            color: var(--primary-color);
        }
        .ulasan-card .text-muted {
            font-size: 0.85rem;
        }
        .ulasan-card p {
            margin-top: 10px;
            line-height: 1.6;
        }
        .star-rating {
            color: #ffc107; /* Warna bintang kuning */
            font-size: 1.1rem;
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

        /* --- Fade-in Animation --- */
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

        /* --- Footer Styling --- */
        .main-footer {
            background-color: var(--dark-bg);
            color: #f8f9fa;
            padding: 40px 0 20px;
            border-top: 5px solid var(--primary-color); /* Garis atas biru */
        }
        .main-footer h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .main-footer p, .main-footer a {
            color: #adb5bd;
            font-size: 0.95rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .main-footer a:hover {
            color: var(--primary-color);
        }
        .main-footer .social-icons a {
            font-size: 1.5rem;
            margin-right: 15px;
            color: #adb5bd;
        }
        .main-footer .social-icons a:hover {
            color: white;
        }
        .main-footer .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
            font-size: 0.85rem;
        }

        /* Utility classes */
        .bg-gradient-primary {
            background: linear-gradient(45deg, #0d6efd, #0b5ed7);
            color: white;
        }
        .bg-gradient-secondary {
            background: linear-gradient(45deg, #6c757d, #5a6268);
            color: white;
        }
        .shadow-lg-custom {
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
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
                    <a href="#galeri" class="nav-link">
                        <i class="fas fa-images me-1"></i> Galeri
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#ulasan" class="nav-link">
                        <i class="fas fa-star me-1"></i> Ulasan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#kontak" class="nav-link">
                        <i class="fas fa-envelope me-1"></i> Kontak
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="fas fa-user-circle me-1"></i> Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a href="login.php" class="nav-link">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<section id="beranda" class="jumbotron text-center fade-in-section">
    <div class="container">
        <h2 class="display-5 animate__animated animate__fadeInDown">🏡 Sistem Informasi Geografis Perumahan Alam Tirta Lestari</h2>
        <p class="lead animate__animated animate__fadeInUp">Nikmati kemudahan dalam menelusuri, memantau, dan mengevaluasi data perumahan secara interaktif dan akurat.</p>
        <hr class="my-4 animate__animated animate__zoomIn">
        <p class="mb-4 text-white fw-semibold animate__animated animate__fadeInUp animate__delay-1s">
            Lihat lokasi rumah, 📝 baca ulasan warga, dan ⭐ berikan penilaian langsung dari peta digital kami.
        </p>
        <p class="text-white fw-semibold animate__animated animate__fadeInUp animate__delay-1-5s">🎯 Transparan • Interaktif • Terkini</p>
        <a class="btn btn-primary btn-lg animate__animated animate__bounceIn" href="maps.php" role="button">
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
    <h2 class="text-center mb-5">🗺️ Peta Lokasi Rumah</h2>
    <div class="map-controls">
        <input type="text" id="cariInput" class="form-control" placeholder="Cari nama rumah..." />
        <button id="lokasiBtn" class="btn">
            <i class="fas fa-location-crosshairs"></i>
        </button>
    </div>
    <div id="map"></div>
</section>

<section id="galeri" class="container py-5 fade-in-section">
    <h2 class="text-center mb-4">Acara Yang diadakan oleh Warga Perumahan Alam Tirta Lestari</h2>
    <div id="galeriCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/pengajian.jpeg" class="d-block w-100" alt="Pengajian Rutin">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Pengajian Rutin Bulanan</h5>
                    <p>Bersama: Ust. Badrani Abbas AL-Fajri. Momen kebersamaan dan peningkatan spiritual.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/kerjabakti.jpeg" class="d-block w-100" alt="Kerja Bakti Warga">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Kerja Bakti Warga Perumahan</h5>
                    <p>Wujud gotong royong menjaga kebersihan dan kenyamanan lingkungan.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/kepedulian.jpg" class="d-block w-100" alt="Kepedulian Bencana Alam">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Kepedulian Warga Terhadap Korban Bencana Alam</h5>
                    <p>Aksi simpatik dan penggalangan dana untuk membantu sesama.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#galeriCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#galeriCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Selanjutnya</span>
        </button>
    </div>
</section>

<section id="ulasan" class="container py-5 fade-in-section">
    <h2 class="text-center mb-5">⭐ Ulasan Pengguna</h2>

    <form method="POST" action="#ulasan" class="mb-5 needs-validation" novalidate>
        <p class="text-center text-muted mb-4">Berikan ulasan Anda tentang pengalaman di perumahan ini!</p>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nama_pengguna" class="form-label">Nama Anda</label>
                <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" placeholder="Nama Lengkap" required>
                <div class="invalid-feedback">Mohon masukkan nama Anda.</div>
            </div>
            <div class="col-md-6">
                <label for="rumah_id" class="form-label">Pilih Rumah</label>
                <select name="rumah_id" id="rumah_id" class="form-select" required>
                    <option value="">-- Pilih Rumah untuk Ulasan --</option>
                    <?php foreach ($rumahData as $rumah): ?>
                        <option value="<?= $rumah['id'] ?>"><?= htmlspecialchars($rumah['nama']) ?> (<?= htmlspecialchars($rumah['alamat']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Mohon pilih rumah yang akan diulas.</div>
            </div>
            <div class="col-12">
                <label for="rating" class="form-label">Rating</label>
                <select name="rating" id="rating" class="form-select" required>
                    <option value="">-- Beri Rating --</option>
                    <option value="5">⭐⭐⭐⭐⭐ Sangat Baik</option>
                    <option value="4">⭐⭐⭐⭐ Baik</option>
                    <option value="3">⭐⭐⭐ Cukup</option>
                    <option value="2">⭐⭐ Kurang</option>
                    <option value="1">⭐ Buruk</option>
                </select>
                <div class="invalid-feedback">Mohon berikan rating.</div>
            </div>
            <div class="col-12">
                <label for="komentar" class="form-label">Komentar Anda</label>
                <textarea name="komentar" id="komentar" class="form-control" rows="4" placeholder="Tulis komentar atau pengalaman Anda di sini..." required></textarea>
                <div class="invalid-feedback">Mohon tulis komentar Anda.</div>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Ulasan
                </button>
            </div>
        </div>
    </form>

    <div class="mt-5">
        <h4 class="mb-4 text-center text-primary">💬 Ulasan Terbaru dari Warga:</h4>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php
            $ulasanQuery = "SELECT u.*, dr.nama_pemilik AS nama_rumah_pemilik, dr.alamat FROM ulasan u JOIN data_rumah dr ON u.rumah_id = dr.id ORDER BY u.tanggal DESC LIMIT 6";
            $ulasanResult = $conn->query($ulasanQuery);

            if ($ulasanResult && $ulasanResult->num_rows > 0) {
                while ($row = $ulasanResult->fetch_assoc()) {
                    echo "<div class='col fade-in-section'>";
                    echo "<div class='ulasan-card'>";
                    echo "<strong>" . htmlspecialchars($row['nama_pengguna']) . "</strong> untuk <em>" . htmlspecialchars($row['nama_rumah_pemilik']) . "</em><br>";
                    echo "<small class='text-muted'>Rating: <span class='star-rating'>" . str_repeat("⭐", (int)$row['rating']) . "</span> - " . date('d M Y, H:i', strtotime($row['tanggal'])) . "</small>";
                    echo "<p>" . nl2br(htmlspecialchars($row['komentar'])) . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='col-12'><p class='text-muted text-center'>Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p></div>";
            }
            ?>
        </div>
    </div>
</section>

<section id="kontak" class="container py-5 fade-in-section">
    <h2 class="text-center mb-5">📞 Hubungi Kami</h2>
    <p class="text-center text-muted mb-4">Punya pertanyaan atau masukan? Jangan ragu untuk menghubungi kami!</p>

    <form method="POST" action="#kontak" class="needs-validation" novalidate>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Anda" required>
                <div class="invalid-feedback">Mohon masukkan nama lengkap Anda.</div>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email Anda" required>
                <div class="invalid-feedback">Mohon masukkan alamat email yang valid.</div>
            </div>
            <div class="col-12">
                <label for="pesan" class="form-label">Pesan Anda</label>
                <textarea name="pesan" id="pesan" class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                <div class="invalid-feedback">Mohon tulis pesan Anda.</div>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                </button>
            </div>
        </div>
    </form>
</section>

<footer class="main-footer">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="fas fa-map-marked-alt me-2"></i>SIG Perumahan</h5>
                <p>Sistem Informasi Geografis untuk Pemetaan Perumahan Alam Tirta Lestari. Memberikan informasi lokasi, ulasan, dan data terkait perumahan.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Tautan Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="#beranda"><i class="fas fa-angle-right me-2"></i>Beranda</a></li>
                    <li><a href="#map-section"><i class="fas fa-angle-right me-2"></i>Peta Lokasi</a></li>
                    <li><a href="#galeri"><i class="fas fa-angle-right me-2"></i>Galeri</a></li>
                    <li><a href="#ulasan"><i class="fas fa-angle-right me-2"></i>Ulasan</a></li>
                    <li><a href="#kontak"><i class="fas fa-angle-right me-2"></i>Kontak</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Hubungi Kami</h5>
                <p><i class="fas fa-map-pin me-2"></i>Jl. Alam Tirta Lestari No. 1, Ciomas, Bogor</p>
                <p><i class="fas fa-phone-alt me-2"></i>(0251) 123456</p>
                <p><i class="fas fa-envelope me-2"></i>info@sigperumahan.com</p>
                <div class="social-icons mt-3">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center copyright">
                <p class="mb-0">&copy; <?php echo date("Y"); ?> SIG Perumahan Alam Tirta Lestari. All rights reserved.</p>
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
    }).setView([-6.60905, 106.75529], 15); // Set view ke tengah perumahan

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan kontrol zoom kustom
    L.control.zoom({
        position: 'bottomright' // Posisikan di kanan bawah
    }).addTo(map);

    // Ambil data rumah dari PHP
    const rumahData = <?php echo json_encode($rumahData); ?>;
    const markerMap = {}; // Untuk menyimpan referensi marker

    // Icon kustom untuk rumah
    const homeIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/25/25694.png', // Contoh icon rumah dari Flaticon
        iconSize: [32, 32],    // Ukuran icon
        iconAnchor: [16, 32],  // Titik tengah bawah icon
        popupAnchor: [0, -32]  // Titik popup relatif terhadap iconAnchor
    });

    // Tambahkan marker untuk setiap rumah
    rumahData.forEach(rumah => {
        const marker = L.marker([rumah.latitude, rumah.longitude], {icon: homeIcon})
            .addTo(map)
            .bindPopup(`
                <div class="card p-2 shadow-sm border-0">
                    <h6 class="card-title text-primary mb-1">${rumah.nama}</h6>
                    <p class="card-text mb-1"><small>${rumah.alamat}</small></p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${rumah.latitude},${rumah.longitude}" target="_blank" class="btn btn-sm btn-info mt-2">
                        <i class="fas fa-directions me-1"></i> Dapatkan Arah
                    </a>
                </div>
            `);
        markerMap[rumah.nama.toLowerCase()] = marker;
    });

    // Fungsi untuk menyesuaikan zoom agar semua marker terlihat
    function fitAllMarkers() {
        if (rumahData.length > 0) {
            const group = new L.featureGroup(
                rumahData.map(r => L.marker([r.latitude, r.longitude]))
            );
            map.fitBounds(group.getBounds().pad(0.2)); // Pad 0.2 agar ada sedikit ruang di tepi
        }
    }
    fitAllMarkers(); // Panggil saat peta pertama kali dimuat

    // Fungsi lokasi saya
    document.getElementById('lokasiBtn').addEventListener('click', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;
                
                // Icon kustom untuk lokasi pengguna
                const userIcon = L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                    iconSize: [35, 35],
                    iconAnchor: [17, 35],
                    popupAnchor: [0, -35]
                });

                L.marker([lat, lon], { icon: userIcon })
                    .addTo(map)
                    .bindPopup("<strong>Lokasi Anda Saat Ini</strong>")
                    .openPopup();
                map.setView([lat, lon], 17); // Zoom in ke lokasi pengguna
            }, () => {
                alert("Gagal mengambil lokasi. Pastikan GPS aktif dan izin lokasi diberikan.");
            }, { enableHighAccuracy: true }); // Minta akurasi tinggi
        } else {
            alert("Browser Anda tidak mendukung Geolocation.");
        }
    });

    // Fungsi pencarian rumah
    document.getElementById('cariInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase().trim();
        let found = false;
        for (const namaRumah in markerMap) {
            if (namaRumah.includes(keyword)) { // Cari yang mengandung keyword
                const marker = markerMap[namaRumah];
                map.setView(marker.getLatLng(), 18);
                marker.openPopup();
                found = true;
                break; // Hentikan setelah yang pertama ditemukan
            } else {
                 markerMap[namaRumah].closePopup(); // Tutup popup jika tidak cocok
            }
        }
        if (!found && keyword.length > 0) {
            // Bisa tambahkan feedback jika tidak ditemukan
            // console.log("Rumah tidak ditemukan.");
        } else if (keyword.length === 0) {
            fitAllMarkers(); // Kembali ke zoom awal jika search box kosong
            for (const namaRumah in markerMap) {
                markerMap[namaRumah].closePopup();
            }
        }
    });

    // --- Observer untuk Fade-in Animation ---
    document.addEventListener("DOMContentLoaded", function() {
        const sections = document.querySelectorAll(".fade-in-section");

        const observerOptions = {
            root: null, // viewport
            rootMargin: "0px",
            threshold: 0.1 // Ketika 10% dari elemen terlihat
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target); // Stop observing once it's visible
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });

        // --- Bootstrap Form Validation (Client-side) ---
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // --- Navbar Active State on Scroll ---
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        const sectionsForNav = document.querySelectorAll('section[id]');

        window.addEventListener('scroll', () => {
            let current = '';
            sectionsForNav.forEach(section => {
                const sectionTop = section.offsetTop - 100; // Adjust for sticky header
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });

        // Ensure Home is active on load
        if (window.location.hash === '' || window.location.hash === '#beranda') {
            document.querySelector('.nav-link[href="#beranda"]').classList.add('active');
        } else {
            // If page loaded with a hash, activate that link
            const targetId = window.location.hash.substring(1);
            if (targetId) {
                const targetLink = document.querySelector(`.nav-link[href="#${targetId}"]`);
                if (targetLink) {
                    targetLink.classList.add('active');
                    // Scroll to section if loaded with hash
                    document.getElementById(targetId)?.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }
    });
</script>

</body>
</html>