<?php
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id           = $_POST['id'];
  $nama_pemilik = $_POST['nama_pemilik'];
  $alamat       = $_POST['alamat'];
  $latitude     = $_POST['latitude'];
  $longitude    = $_POST['longitude'];

  $query = "UPDATE data_rumah 
            SET nama_pemilik = '$nama_pemilik',
                alamat = '$alamat',
                latitude = '$latitude',
                longitude = '$longitude'
            WHERE id = $id";

  if (mysqli_query($conn, $query)) {
    header("Location: manage_rumah.php?update=success");
  } else {
    header("Location: manage_rumah.php?update=fail");
  }
  exit();
}
?>
