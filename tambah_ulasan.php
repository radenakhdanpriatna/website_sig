<?php
// Tampilkan error jika ada
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Koneksi ke database
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $komentar      = mysqli_real_escape_string($conn, $_POST['komentar']);
    $rating        = intval($_POST['rating']);
    $tanggal       = date('Y-m-d');

    // Query untuk menyimpan data
    $query = "INSERT INTO ulasan (nama_pengguna, komentar, rating, tanggal)
              VALUES ('$nama_pengguna', '$komentar', '$rating', '$tanggal')";

    if (mysqli_query($conn, $query)) {
        // Redirect ke halaman utama setelah berhasil
        header("Location: manage_ulasan.php"); // ganti sesuai nama file utama jika perlu
        exit;
    } else {
        echo "Gagal menyimpan ulasan: " . mysqli_error($conn);
    }
} else {
    echo "Metode tidak diperbolehkan.";
}
?>
