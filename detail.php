<?php
require_once 'config/config.php';

$id = $_GET['id'];
$query = $conn->query("SELECT * FROM rumah2 WHERE id = $id");
$data = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Rumah</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
  <h2>Detail Rumah</h2>
  <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']) ?></p>
  <p><strong>Alamat:</strong> <?= htmlspecialchars($data['alamat']) ?></p>
  <p><strong>Luas Tanah:</strong> <?= htmlspecialchars($data['luas']) ?> m²</p>
  <p><strong>Latitude:</strong> <?= htmlspecialchars($data['lat']) ?></p>
  <p><strong>Longitude:</strong> <?= htmlspecialchars($data['lon']) ?></p>
  <a href="index.php" class="btn btn-secondary mt-3">Kembali ke Peta</a>
</body>
</html>
