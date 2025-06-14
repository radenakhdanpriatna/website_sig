<?php
session_start();
include 'config.php'; // Menghubungkan dengan config.php untuk koneksi database

// Mengambil data dari form login
$username = $_POST['username'];
$password = md5($_POST['password']);  // Menggunakan MD5 untuk enkripsi password (gunakan password_hash() di aplikasi nyata)

// Mengecek apakah username dan password ada di database
$query = mysqli_query($koneksi, "SELECT * FROM tb_users WHERE username='$username' AND password='$password'");

// Jika ditemukan 1 baris data
if (mysqli_num_rows($query) == 1) {
    // Menyimpan data pengguna di session
    $user = mysqli_fetch_array($query);
    $_SESSION['username'] = $user['username'];  // Menyimpan username di session
    $_SESSION['level'] = $user['level'];  // Menyimpan level pengguna jika diperlukan

    // Redirect ke halaman dashboard admin
    header('Location: admin_dashboard.php');
    exit();
} else {
    // Jika login gagal, redirect kembali ke halaman login dengan pesan error
    header('Location: login.php?error=1');
    exit();
}
?>
