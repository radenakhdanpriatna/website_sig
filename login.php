<?php
session_start();
$feedback = "";

// Koneksi
$conn = new mysqli("localhost", "root", "", "db_sig");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Cek password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: admin_dashboard.php"); // Kembali ke halaman utama
            exit;
        } else {
            $feedback = "Password salah!";
        }
    } else {
        $feedback = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login | SIG Perumahan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
          <h4>🔐 Login Admin</h4>
        </div>
        <div class="card-body">
          <?php if ($feedback): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($feedback) ?></div>
          <?php endif; ?>
          <form method="POST">
            <div class="mb-3">
              <label>Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <a href="register.php">Belum punya akun? Daftar di sini</a>

          </form>
        </div>
        <div class="card-footer text-center">
          <a href="admin_dashboard">← Kembali ke Beranda</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
