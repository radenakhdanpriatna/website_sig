<?php
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rumah_id = (int) $_POST['rumah_id'];
    $nama_pengguna = trim($_POST['nama_pengguna']);
    $komentar = trim($_POST['komentar']);
    $rating = (int) $_POST['rating'];

    $stmt = $conn->prepare("INSERT INTO ulasan (rumah_id, nama_pengguna, komentar, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $rumah_id, $nama_pengguna, $komentar, $rating);

    if ($stmt->execute()) {
        header("Location: index.php?pesan=berhasil#ulasan");
    } else {
        header("Location: index.php?pesan=gagal#ulasan");
    }
}
?>
