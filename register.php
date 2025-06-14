<?php
$feedback = "";
$conn = new mysqli("localhost", "root", "", "db_sig");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Password di-hash

    // Cek apakah username sudah ada
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $feedback = "Username sudah digunakan!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $feedback = "Registrasi berhasil! Silakan login.";
        } else {
            $feedback = "Registrasi gagal!";
        }
    }
}
?>

<!-- HTML Form Registrasi -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi | SIG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4>📝 Registrasi</h4>
                </div>
                <div class="card-body">
                    <?php if ($feedback): ?>
                        <div class="alert alert-info"><?= htmlspecialchars($feedback) ?></div>
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
                            <button type="submit" class="btn btn-success">Daftar</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="login.php">Sudah punya akun? Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
