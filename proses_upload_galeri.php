<?php
include 'config/config.php'; // koneksi database

$targetDir = "uploads/galeri/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_FILES['gambar']['name']) {
    $fileName = basename($_FILES["gambar"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowed)) {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
            $fileNameOnly = basename($targetFile);
            mysqli_query($conn, "INSERT INTO galeri (gambar) VALUES ('$fileNameOnly')");
            header("Location: manage_galeri.php");
        } else {
            echo "Upload gagal.";
        }
    } else {
        echo "Format file tidak didukung.";
    }
}
?>
