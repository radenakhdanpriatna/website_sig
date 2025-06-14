<?php
include 'config.php';
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$conn->query("INSERT INTO lokasi (nama, deskripsi, lat, lng) VALUES ('$nama', '$deskripsi', '$lat', '$lng')");
?>
