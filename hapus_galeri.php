<?php
include 'config/config.php'; // koneksi database
$id = $_GET['id'];

// Hapus file dari server
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM galeri WHERE id = $id"));
$path = "uploads/galeri/" . $data['gambar'];
if (file_exists($path)) {
    unlink($path);
}

// Hapus dari database
mysqli_query($conn, "DELETE FROM galeri WHERE id = $id");
header("Location: manage_galeri.php");
?>
