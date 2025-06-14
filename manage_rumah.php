<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --poppins-font: 'Poppins', sans-serif;
        --primary-color: #007bff; /* Warna utama Bootstrap blue */
        --success-color: #28a745;
        --warning-color: #ffc107;
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
        margin-bottom: 15px; /* Spasi antara judul dan tombol */
    }

    /* Button Styling */
    .btn {
        font-weight: 500;
        border-radius: 8px; /* Sudut membulat pada tombol */
        padding: 10px 20px;
        transition: all 0.3s ease;
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

    /* Card Styling */
    .card {
        border-radius: 12px; /* Sudut membulat yang lebih besar */
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1); /* Bayangan yang lebih menonjol */
        border: none; /* Hilangkan border default Bootstrap */
        overflow: hidden; /* Pastikan konten di dalam card tidak meluber saat border-radius */
    }

    .card-header {
        background-color: var(--primary-color); /* Latar belakang header card utama */
        color: white;
        padding: 1.25rem 1.75rem; /* Padding lebih besar */
        font-weight: 600;
        font-size: 1.3rem;
        border-bottom: none; /* Hilangkan border bawah header */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header .card-title {
        font-weight: 600; /* Tebalkan judul card */
        color: white;
        margin-bottom: 0;
        display: flex; /* Untuk menyelaraskan ikon */
        align-items: center;
    }
    .card-header .card-title i {
        margin-right: 10px; /* Spasi ikon dengan teks */
    }

    .card-body {
        padding: 2rem; /* Padding konten card yang lebih besar */
        background-color: var(--card-bg);
    }

    /* Table Styling */
    .table {
        margin-bottom: 0; /* Hilangkan margin bawah default tabel */
    }

    .table thead th {
        background-color: var(--light-bg); /* Latar belakang header tabel yang lebih terang */
        color: var(--dark-text);
        font-weight: 600;
        border-bottom: 2px solid #e9ecef; /* Garis bawah yang lebih tebal */
        padding: 15px; /* Padding sel header lebih besar */
        vertical-align: middle;
    }

    .table tbody td {
        padding: 12px 15px; /* Padding sel body lebih besar */
        vertical-align: middle;
        color: var(--dark-text);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05); /* Efek hover biru muda */
        transition: background-color 0.2s ease-in-out;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e3e6f0; /* Border tabel yang lebih lembut */
    }

    /* Action Buttons in Table */
    .table .btn-sm {
        padding: 6px 10px;
        font-size: 0.85rem;
        border-radius: 6px;
    }

    .table .btn-warning {
        background-color: var(--warning-color);
        border-color: var(--warning-color);
        color: var(--dark-text); /* Warna teks untuk tombol warning */
    }
    .table .btn-warning:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        color: var(--dark-text);
    }

    .table .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
        color: white;
    }
    .table .btn-danger:hover {
        background-color: #bd2130;
        border-color: #bd2130;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        border: none;
        animation: fadeInDown 0.5s; /* Animasi modal saat muncul */
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .modal-header .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
        font-size: 1.8rem;
    }
    .modal-header .close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 2rem 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
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

    /* Optional: Scrollbar styling for table-responsive */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
        background-color: #f5f5f5;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #ced4da;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background-color: #a7b0b8;
    }

    /* Adjust content-wrapper padding for better spacing */
    .content-wrapper {
        padding-top: 20px; /* Tambahkan padding atas untuk jarak dari navbar jika ada */
        padding-bottom: 20px; /* Tambahkan padding bawah */
    }

    /* Specific for this page, container-fluid usually handled by AdminLTE */
    .content-wrapper .container-fluid {
        padding-left: 20px;
        padding-right: 20px;
    }

</style>

<?php
// koneksi ke database
include 'config/config.php'; // pastikan kamu punya file koneksi
?>

<?php include 'partials/header.php'; ?> <?php include 'partials/sidebar.php'; ?> <div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="mb-2 animate__animated animate__fadeInLeft">Manajemen Data Rumah</h1>
            <button class="btn btn-primary animate__animated animate__fadeInRight" data-toggle="modal" data-target="#modalTambahRumah">
                <i class="fas fa-plus"></i> Tambah Rumah
            </button>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-list-alt"></i> Daftar Rumah</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="tableRumah">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pemilik</th>
                                <th>Alamat</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th class="text-center">Aksi</th> </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Pastikan $conn terdefinisi dari config/config.php
                            if (isset($conn)) {
                                $query = mysqli_query($conn, "SELECT * FROM data_rumah ORDER BY id DESC"); // Order by ID terbaru
                                if (mysqli_num_rows($query) > 0) {
                                    while ($row = mysqli_fetch_assoc($query)) {
                                    ?>
                                        <tr class="animate__animated animate__fadeIn">
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['nama_pemilik']); ?></td>
                                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                                            <td><?= htmlspecialchars($row['latitude']); ?></td>
                                            <td><?= htmlspecialchars($row['longitude']); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?= $row['id']; ?>" title="Edit Data">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="hapus_rumah.php?id=<?= $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm" title="Hapus Data">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered"> <div class="modal-content">
                                                    <form method="POST" action="update_rumah.php">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Rumah</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                                                            <div class="form-group">
                                                                <label for="nama_pemilik_<?= $row['id']; ?>">Nama Pemilik</label>
                                                                <input type="text" name="nama_pemilik" id="nama_pemilik_<?= $row['id']; ?>" class="form-control" value="<?= htmlspecialchars($row['nama_pemilik']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="alamat_<?= $row['id']; ?>">Alamat</label>
                                                                <textarea name="alamat" id="alamat_<?= $row['id']; ?>" class="form-control" required><?= htmlspecialchars($row['alamat']); ?></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="latitude_<?= $row['id']; ?>">Latitude</label>
                                                                <input type="text" name="latitude" id="latitude_<?= $row['id']; ?>" class="form-control" value="<?= htmlspecialchars($row['latitude']); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="longitude_<?= $row['id']; ?>">Longitude</label>
                                                                <input type="text" name="longitude" id="longitude_<?= $row['id']; ?>" class="form-control" value="<?= htmlspecialchars($row['longitude']); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Perubahan</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center">Tidak ada data rumah ditemukan.</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center text-danger">Koneksi database gagal. Periksa file config/config.php Anda.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTambahRumah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"> <div class="modal-content">
            <form method="POST" action="tambah_rumah.php">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Rumah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>