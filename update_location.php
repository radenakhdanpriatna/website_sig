<?php
include 'config/config.php';
$id = $_POST['id'];
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$conn->query("UPDATE lokasi SET nama='$nama', deskripsi='$deskripsi', lat='$lat', lng='$lng' WHERE id=$id");
?>
