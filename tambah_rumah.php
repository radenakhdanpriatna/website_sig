<?php
include 'config/config.php'; // koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_pemilik = $_POST['nama_pemilik'];
  $alamat       = $_POST['alamat'];
  $latitude     = $_POST['latitude'];
  $longitude    = $_POST['longitude'];

  $query = "INSERT INTO data_rumah (nama_pemilik, alamat, latitude, longitude) 
            VALUES ('$nama_pemilik', '$alamat', '$latitude', '$longitude')";
  $result = mysqli_query($conn, $query);

  if ($result) {
    echo "<script>alert('Data berhasil ditambahkan'); window.location.href='manage_rumah.php';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan data'); window.location.href='manage_rumah.php';</script>";
  }
} else {
  // Jika akses langsung, redirect
  header('Location: manage_rumah.php');
}
?>
