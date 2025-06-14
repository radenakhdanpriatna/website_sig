<?php
include 'config.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];
    $desk = $_POST['deskripsi'];

    $conn->query("UPDATE lokasi SET nama='$nama', latitude='$lat', longitude='$lng', deskripsi='$desk' WHERE id=$id");
    header("Location: index.php");
    exit;
}

$data = $conn->query("SELECT * FROM lokasi WHERE id=$id")->fetch_assoc();
?>

<form method="POST">
    <input type="text" name="nama" value="<?= $data['nama'] ?>" required><br>
    <input type="text" name="latitude" value="<?= $data['latitude'] ?>" required><br>
    <input type="text" name="longitude" value="<?= $data['longitude'] ?>" required><br>
    <textarea name="deskripsi" required><?= $data['deskripsi'] ?></textarea><br>
    <button type="submit">Update</button>
</form>
