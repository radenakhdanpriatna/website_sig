<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --poppins-font: 'Poppins', sans-serif;
        --primary-color: #007bff; /* Bootstrap primary blue */
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
    .btn-warning { /* Untuk tombol edit */
        background-color: var(--warning-color);
        border-color: var(--warning-color);
        color: #343a40; /* Teks gelap agar terlihat di latar kuning */
    }
    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }
    .btn-success { /* Jika ada tombol success */
        background-color: var(--success-color);
        border-color: var(--success-color);
        color: white;
    }
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
    .btn-secondary { /* Untuk tombol batal modal */
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    /* Card Styling */
    .card {
        border-radius: 12px; /* Sudut membulat yang lebih besar */
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1); /* Bayangan yang lebih menonjol */
        border: none; /* Hilangkan border default Bootstrap */
        overflow: hidden; /* Pastikan konten di dalam card tidak meluber saat border-radius */
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
        text-align: left; /* Default rata kiri */
    }

    .table tbody td {
        padding: 12px 15px; /* Padding sel body lebih besar */
        vertical-align: middle;
        color: var(--dark-text);
        text-align: left; /* Default rata kiri */
    }
    .table tbody td.text-center { /* Untuk rating atau kolom yang perlu di tengah */
        text-align: center;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05); /* Efek hover biru muda */
        transition: background-color 0.2s ease-in-out;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e3e6f0; /* Border tabel yang lebih lembut */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02); /* Sedikit striping untuk row ganjil */
    }

    /* Action Buttons in Table */
    .table .btn-sm {
        padding: 6px 12px; /* Padding lebih proporsional */
        font-size: 0.85rem;
        border-radius: 6px;
    }
    .table .btn-sm + .btn-sm { /* Jarak antar tombol aksi */
        margin-left: 5px;
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
        display: flex;
        align-items: center;
        gap: 10px;
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
        display: flex; /* Untuk menata tombol */
        justify-content: flex-end; /* Menata tombol ke kanan */
        gap: 10px; /* Jarak antar tombol */
    }

    .form-control, .form-control-file, .form-control-select {
        border-radius: 8px; /* Sudut membulat pada input form */
        padding: 10px 15px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus, .form-control-file:focus, .form-control-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .form-group label {
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--dark-text);
    }

    /* Placeholder text style */
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #a0a0a0;
        opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #a0a0a0;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
        color: #a0a0a0;
    }

    /* Specific for rating stars */
    .rating-stars {
        color: #ffc107; /* Warna bintang kuning */
        font-size: 1.1em; /* Ukuran bintang sedikit lebih besar */
    }

    /* Adjust main content padding */
    .content-wrapper.p-4 {
        padding: 30px !important; /* Override default p-4 to be more spacious */
    }

    /* Override any conflicting AdminLTE defaults if necessary */
    .wrapper {
        background-color: var(--light-bg); /* Ensure wrapper matches body background */
    }
</style>

<?php
// koneksi ke database
include 'config/config.php'; // pastikan kamu punya file koneksi
$query = mysqli_query($conn, "SELECT * FROM ulasan ORDER BY tanggal DESC");
?>

<?php include 'partials/header.php'; ?> <?php include 'partials/sidebar.php'; ?> <div class="content-wrapper"> <section class="content-header">
        <div class="container-fluid">
            <h1 class="animate__animated animate__fadeInLeft"><i class="fas fa-comments"></i> Kelola Ulasan Pengguna</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <button class="btn btn-primary mb-4 animate__animated animate__fadeInRight" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus-circle"></i> Tambah Ulasan
            </button>

            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-list-alt"></i> Daftar Ulasan</h3>
                </div>
                <div class="card-body table-responsive"> <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Komentar</th>
                                <th class="text-center">Rating</th> <th>Tanggal</th>
                                <th class="text-center">Aksi</th> </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Pastikan $conn terdefinisi dari config/config.php
                            if (isset($conn)) {
                                $query = mysqli_query($conn, "SELECT * FROM ulasan ORDER BY tanggal DESC");
                                if (mysqli_num_rows($query) > 0): 
                                    while ($row = mysqli_fetch_assoc($query)) : ?>
                                    <tr class="animate__animated animate__fadeIn">
                                        <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                                        <td><?= htmlspecialchars($row['komentar']) ?></td>
                                        <td class="text-center"><span class="rating-stars"><?= htmlspecialchars($row['rating']) ?> ⭐</span></td>
                                        <td><?= htmlspecialchars(date('d F Y, H:i', strtotime($row['tanggal']))) ?></td> <td class="text-center">
                                            <a href="edit_ulasan.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-warning btn-sm" title="Edit Ulasan">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="hapus_ulasan.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')" title="Hapus Ulasan">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <h4>Belum ada ulasan pengguna.</h4>
                                            <p>Tambahkan ulasan baru untuk ditampilkan di sini.</p>
                                        </td>
                                    </tr>
                                <?php endif; 
                            } else {
                                echo '<tr><td colspan="5" class="text-center text-danger py-5">';
                                echo '<i class="fas fa-exclamation-triangle fa-3x mb-3"></i>';
                                echo '<h4>Koneksi database gagal.</h4>';
                                echo '<p>Periksa file config/config.php Anda.</p>';
                                echo '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div> <div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"> <form action="tambah_ulasan.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Ulasan Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_pengguna">Nama Pengguna</label>
                    <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" placeholder="Masukkan nama pengguna" required>
                </div>
                <div class="form-group">
                    <label for="komentar">Komentar</label>
                    <textarea name="komentar" id="komentar" class="form-control" placeholder="Tulis komentar ulasan" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select name="rating" id="rating" class="form-control" required>
                        <option value="">Pilih Rating</option>
                        <?php for ($i = 1; $i <= 5; $i++) echo "<option value='$i'>$i ⭐</option>"; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Batal</button>
            </div>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>