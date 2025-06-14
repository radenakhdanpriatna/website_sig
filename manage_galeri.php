<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --poppins-font: 'Poppins', sans-serif;
        --primary-color: #007bff; /* Bootstrap primary blue */
        --success-color: #28a745;
        --danger-color: #dc3545;
        --secondary-color: #6c757d;
        --light-bg: #f8f9fa; /* Latar belakang body yang lebih terang */
        --dark-text: #343a40;
        --light-text: #6c757d;
        --card-bg: #ffffff;
    }

    body {
        font-family: var(--poppins-font);
        background-color: var(--light-bg);
        color: var(--dark-text);
    }

    /* Adjust content-wrapper padding for better spacing */
    .content-wrapper {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    /* Content Header Styling */
    .content-header {
        padding: 30px 0; /* Padding atas bawah yang lebih besar */
        background-color: var(--card-bg);
        border-bottom: 1px solid #dee2e6; /* Garis bawah pemisah */
        margin-bottom: 30px; /* Jarak dengan konten utama */
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); /* Bayangan lembut */
    }

    .content-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0; /* Hilangkan margin bawah default */
        display: flex; /* Untuk menyelaraskan ikon */
        align-items: center;
    }
    .content-header h1 i {
        margin-right: 15px; /* Spasi ikon dengan teks */
        color: var(--primary-color);
    }


    /* Button Styling */
    .btn {
        font-weight: 500;
        border-radius: 8px; /* Sudut membulat pada tombol */
        padding: 10px 20px;
        transition: all 0.3s ease;
        display: inline-flex; /* Agar ikon dan teks sejajar */
        align-items: center;
        gap: 8px; /* Jarak antara ikon dan teks */
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
        color: white;
    }
    .btn-danger:hover {
        background-color: #bd2130;
        border-color: #bd2130;
    }

    /* Card Styling for Gallery Items */
    .card {
        border-radius: 12px; /* Sudut membulat yang lebih besar */
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); /* Bayangan yang lebih menonjol */
        border: none; /* Hilangkan border default Bootstrap */
        overflow: hidden; /* Pastikan konten di dalam card tidak meluber saat border-radius */
        transition: transform 0.2s ease-in-out; /* Animasi saat hover */
    }
    .card:hover {
        transform: translateY(-5px); /* Sedikit naik saat di-hover */
    }

    .card-img-top {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        height: 200px; /* Tinggi gambar yang sedikit lebih besar */
        object-fit: cover;
    }

    .card-body {
        padding: 1.25rem; /* Padding konten card */
        background-color: var(--card-bg);
    }

    /* Form Upload Styling */
    .form-group label {
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--dark-text);
    }

    .form-control {
        border-radius: 8px; /* Sudut membulat pada input form */
        padding: 10px 15px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* General Layout adjustments for main content */
    main.col-md-9 {
        padding-left: 30px !important;
        padding-right: 30px !important;
        padding-top: 30px !important; /* Adjust overall padding */
    }

    /* Responsive adjustments for smaller screens */
    @media (max-width: 767.98px) {
        .col-md-9.ml-sm-auto.col-lg-10.px-md-4.pt-4 {
            margin-left: 0 !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
    }
</style>

<?php
// koneksi ke database
include 'config/config.php'; // pastikan kamu punya file koneksi
?>

<?php include 'partials/header.php'; ?> <?php include 'partials/sidebar.php'; ?> <div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1 class="animate__animated animate__fadeInLeft"><i class="fas fa-images"></i> Kelola Galeri Gambar</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-primary animate__animated animate__fadeInDown mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-upload"></i> Upload Gambar Baru</h3>
                </div>
                <div class="card-body">
                    <form action="proses_upload_galeri.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="gambar">Pilih Gambar</label>
                            <input type="file" name="gambar" class="form-control-file" id="gambar" required accept="image/*">
                            <small class="form-text text-muted">Format yang didukung: JPG, PNG, GIF. Ukuran maksimum: 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary animate__animated animate__pulse animate__infinite" style="--animate-duration: 2s;">
                            <i class="fas fa-cloud-upload-alt"></i> Unggah Gambar
                        </button>
                    </form>
                </div>
            </div>

            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title"><i class="fas fa-grip-horizontal"></i> Daftar Gambar Galeri</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        // Memastikan koneksi database tersedia
                        if (isset($conn)) {
                            $result = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <div class="col-6 col-md-4 col-lg-3 mb-4 animate__animated animate__zoomIn">
                                        <div class="card h-100"> <img src="uploads/galeri/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="Gambar Galeri" style="height:200px;object-fit:cover;">
                                            <div class="card-body d-flex align-items-end justify-content-center"> <a href="hapus_galeri.php?id=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')" class="btn btn-sm btn-danger" title="Hapus Gambar">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                echo '<div class="col-12 text-center text-muted py-5">';
                                echo '<i class="fas fa-box-open fa-3x mb-3"></i>';
                                echo '<h4>Belum ada gambar dalam galeri.</h4>';
                                echo '<p>Mulai unggah gambar untuk ditampilkan di sini.</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="col-12 text-center text-danger py-5">';
                            echo '<i class="fas fa-exclamation-triangle fa-3x mb-3"></i>';
                            echo '<h4>Koneksi database gagal.</h4>';
                            echo '<p>Periksa file config/config.php Anda.</p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div> <?php include 'partials/footer.php'; ?> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>