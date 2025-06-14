<?php
require_once 'classes/database.php';

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desa = $_POST['desa'] ?? '';
    $kecamatan = $_POST['kecamatan'] ?? '';
    $laki = intval($_POST['laki'] ?? 0);
    $perempuan = intval($_POST['perempuan'] ?? 0);
    $lat = floatval($_POST['lat'] ?? 0);
    $lng = floatval($_POST['lng'] ?? 0);
    $jumlah = $laki + $perempuan;

    // Validasi sederhana
    if (!$desa || !$kecamatan || !$lat || !$lng) {
        die('Data tidak lengkap. Mohon isi semua field dengan benar.');
    }

    $db = new Database();

    // Query simpan data ke tabel lokasi (sesuaikan nama tabel dan kolom)
    $query = "INSERT INTO lokasi (Desa, Kecamatan, L, P, Jumlah, Lat, Long) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->mysqli->prepare($query);
    if (!$stmt) {
        die('Prepare statement gagal: ' . $db->mysqli->error);
    }
    $stmt->bind_param('ssiiidd', $desa, $kecamatan, $laki, $perempuan, $jumlah, $lat, $lng);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman peta atau halaman lain
        header('Location: index.php?status=success');
        exit;
    } else {
        die('Gagal menyimpan data: ' . $stmt->error);
    }
} else {
    die('Akses tidak diperbolehkan.');
}
