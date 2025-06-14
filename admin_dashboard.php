<?php
include 'config/config.php'; // pastikan file koneksi.php sesuai

// Ambil data statistik rumah
$queryRumah = $conn->query("
    SELECT  
        COUNT(*) AS totalRumah,
        SUM(nama_pemilik = 'tersedia') AS rumahTersedia,
        SUM(nama_pemilik = 'terjual') AS rumahTerjual
    FROM data_rumah
");
$dataRumah = $queryRumah->fetch_assoc();

// Ambil data ulasan
$queryUlasan = $conn->query("SELECT COUNT(*) AS totalUlasan FROM ulasan");
$dataUlasan = $queryUlasan->fetch_assoc();

// Simpan ke variabel
$totalRumah     = $dataRumah['totalRumah'] ?? 0;
$rumahTersedia  = $dataRumah['rumahTersedia'] ?? 0;
$rumahTerjual   = $dataRumah['rumahTerjual'] ?? 0;
$totalUlasan    = $dataUlasan['totalUlasan'] ?? 0;
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin - SIG Pemetaan Perumahan</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        :root {
            --poppins-font: 'Poppins', sans-serif;
            --primary-color: #0d6efd; /* Info/Blue */
            --success-color: #28a745; /* Success/Green */
            --warning-color: #ffc107; /* Warning/Yellow */
            --danger-color: #dc3545; /* Danger/Red */
            --secondary-color: #6c757d; /* Abu-abu default */
            --light-bg: #f4f6f9; /* AdminLTE default body bg */
            --dark-text: #343a40;
            --light-text: #6c757d;
        }

        body {
            font-family: var(--poppins-font);
            background-color: var(--light-bg);
            color: var(--dark-text);
        }

        /* Styling yang sudah ada */
        .small-box .inner h3 {
            font-size: 2.5rem;
            font-weight: 700;
        }
        table.table thead th {
            background-color: #f4f6f9;
            color: #333;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .product-list-in-card .item {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .product-img i {
            color: #6c757d; /* warna icon user */
        }

        /* Penambahan dan Perbaikan Styling */
        .small-box {
            border-radius: 15px; /* Sudut membulat */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); /* Bayangan lebih menonjol */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .small-box:hover {
            transform: translateY(-5px); /* Efek mengangkat saat hover */
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        .small-box .inner h3 {
            font-size: 2.8rem; /* Ukuran font lebih besar dari sebelumnya */
            font-weight: 700;
            margin-bottom: 5px;
        }
        .small-box .inner p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .small-box .icon {
            top: 15px; /* Sesuaikan posisi ikon */
            font-size: 80px; /* Ukuran ikon lebih besar */
            opacity: 0.3; /* Sedikit transparan */
        }
        .small-box-footer {
            border-bottom-left-radius: 15px; /* Sesuaikan radius footer */
            border-bottom-right-radius: 15px;
            background: rgba(0,0,0,0.1);
            color: rgba(255,255,255,0.8);
            font-weight: 600;
            padding: 8px 10px;
        }
        .small-box-footer:hover {
            color: white;
            background: rgba(0,0,0,0.2);
        }

        /* Card Styling */
        .card {
            border-radius: 15px; /* Sudut membulat */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); /* Bayangan lebih menonjol */
            border: none; /* Hilangkan border default */
        }
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.08); /* Garis bawah header */
            padding: 1.25rem 1.5rem;
            background-color: #ffffff; /* Latar belakang putih */
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .card-title {
            font-weight: 600;
            color: var(--dark-text);
            display: flex; /* Untuk menyelaraskan ikon */
            align-items: center;
        }
        .card-title i.fas {
            margin-right: 8px; /* Spasi antara ikon dan teks */
        }
        /* Top border color for card outlines */
        .card-primary.card-outline .card-header { border-top: 3px solid var(--primary-color); }
        .card-secondary.card-outline .card-header { border-top: 3px solid var(--secondary-color); }
        .card-info.card-outline .card-header { border-top: 3px solid var(--primary-color); } /* Menggunakan primary untuk info */
        .card-success.card-outline .card-header { border-top: 3px solid var(--success-color); }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background-color: var(--light-bg); /* Header tabel abu-abu muda */
            color: var(--dark-text);
            font-weight: 600;
            border-bottom: 2px solid rgba(0,0,0,0.1);
            padding: 12px 15px;
        }
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(0,0,0,0.03); /* Efek hover pada baris tabel */
        }
        .table tbody td {
            padding: 10px 15px;
            vertical-align: middle;
            color: var(--dark-text);
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,0.01); /* Warna striping sangat tipis */
        }
        .table-responsive {
            border-radius: 10px; /* Sudut membulat untuk responsif tabel */
            overflow: hidden; /* Penting untuk radius */
            border: 1px solid rgba(0,0,0,0.05); /* Border tipis */
        }
        .table .badge {
            font-size: 0.75rem;
            padding: 0.3em 0.6em;
            border-radius: 5px;
            font-weight: 600;
        }
        .table .badge-success { background-color: var(--success-color); }
        .table .badge-secondary { background-color: var(--secondary-color); } /* Untuk status 'Terjual' */


        /* Leaflet Mini Map */
        #map-mini {
            height: 350px;
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .leaflet-popup-content {
            font-family: var(--poppins-font);
            font-size: 0.9rem;
            color: var(--dark-text);
        }
        .leaflet-popup-content strong {
            color: var(--primary-color);
        }

        /* Ulasan Terbaru Styling */
        .products-list .item {
            padding: 15px 10px; /* Padding lebih besar */
            border-bottom: 1px solid rgba(0,0,0,0.05); /* Garis lebih tipis */
            display: flex;
            align-items: center;
        }
        .products-list .item:last-child {
            border-bottom: none; /* Hilangkan border di item terakhir */
        }
        .products-list .product-img {
            margin-right: 15px;
        }
        .products-list .product-img i {
            color: var(--light-text); /* Warna icon user */
            font-size: 2.5rem; /* Ukuran icon lebih besar */
        }
        .products-list .product-info {
            flex-grow: 1;
        }
        .products-list .product-title {
            font-weight: 600;
            color: var(--dark-text);
            text-decoration: none;
            display: block; /* Agar span badge bisa float */
        }
        .products-list .product-title:hover {
            color: var(--primary-color);
        }
        .products-list .product-description {
            font-size: 0.85rem;
            color: var(--light-text);
            display: block;
            margin-top: 3px;
        }
        .products-list .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.6em;
            border-radius: 5px;
            font-weight: 600;
        }
        .products-list .badge-info {
            background-color: var(--warning-color); /* Ubah badge rating jadi kuning */
            color: white;
        }

        /* Chart Styling */
        #chartRumah {
            max-height: 250px; /* Batasi tinggi chart */
        }
        
        /* Breadcrumb Styling */
        .content-header ol.breadcrumb .breadcrumb-item {
            font-size: 0.95rem;
        }
        .content-header ol.breadcrumb .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .content-header ol.breadcrumb .breadcrumb-item.active {
            color: var(--secondary-color);
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php include 'partials/navbar.php'; ?>
    <?php include 'partials/sidebar.php'; ?>

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div></section>

        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= $totalRumah ?></h3>
                                <p>Total Rumah</p>
                            </div>
                            <div class="icon"><i class="fas fa-home"></i></div>
                            <a href="manage_rumah.php" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= $rumahTersedia ?></h3>
                                <p>Rumah Tersedia</p>
                            </div>
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                            <a href="manage_rumah.php?status=tersedia" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= $rumahTerjual ?></h3>
                                <p>Rumah Terjual</p>
                            </div>
                            <div class="icon"><i class="fas fa-tags"></i></div>
                            <a href="manage_rumah.php?status=terjual" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= $totalUlasan ?></h3>
                                <p>Total Ulasan</p>
                            </div>
                            <div class="icon"><i class="fas fa-comments"></i></div>
                            <a href="manage_ulasan.php" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-map-marked-alt"></i>Peta Lokasi Rumah Terbaru</h3>
                    </div>
                    <div class="card-body">
                        <div id="map-mini" style="height: 350px;"></div>
                    </div>
                </div>

                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-house-chimney"></i>Data Rumah Terbaru</h3>
                    </div>
                    <div class="card-body table-responsive p-0" style="max-height: 300px;">
                        <table class="table table-head-fixed text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Rumah</th>
                                    <th>Status</th>
                                    <th>Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Contoh statik, ganti dengan query data asli
                                for ($i = 1; $i <= 7; $i++) {
                                    $status = ($i % 2 == 0) ? "Terjual" : "Tersedia";
                                    $badgeClass = ($status == "Tersedia") ? "badge-success" : "badge-secondary";
                                    echo "<tr>";
                                    echo "<td>$i</td>";
                                    echo "<td>Rumah Contoh $i</td>";
                                    echo "<td><span class=\"badge $badgeClass\">$status</span></td>";
                                    echo "<td>" . date('Y-m-d', strtotime("-$i days")) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-comments"></i>Ulasan Pengguna Terbaru</h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    <?php
                                    for ($j = 1; $j <= 5; $j++) {
                                        $rating = rand(1, 5);
                                        echo '<li class="item">';
                                        echo '<div class="product-img"><i class="fas fa-user-circle fa-2x text-muted"></i></div>';
                                        echo '<div class="product-info">';
                                        echo '<a href="#" class="product-title">User ' . $j . '<span class="badge badge-info float-right">' . $rating . ' ⭐</span></a>';
                                        echo '<span class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. (' . date('Y-m-d') . ')</span>';
                                        echo '</div></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-chart-line"></i>Grafik Jumlah Rumah Per Bulan</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="chartRumah" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div></section>

    </div>

    <?php include 'partials/footer.php'; ?>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Inisialisasi Leaflet Mini Map
    var mapMini = L.map('map-mini').setView([-6.2, 106.8], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapMini);
    L.marker([-6.2, 106.8]).addTo(mapMini).bindPopup('Lokasi').openPopup();

    // Chart.js Line Chart Rumah Per Bulan
    const ctx = document.getElementById('chartRumah').getContext('2d');
    const chartRumah = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            datasets: [{
                label: 'Jumlah Rumah',
                data: [12, 19, 15, 25, 22, 30, 35],
                borderColor: 'var(--success-color)', /* Menggunakan CSS Variable */
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true,
                tension: 0.3, /* Membuat garis sedikit melengkung */
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: 'var(--success-color)',
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
                pointHoverBackgroundColor: 'var(--success-color)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, /* Penting untuk responsifitas tinggi */
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            family: 'Poppins', /* Menggunakan font Poppins */
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' unit';
                        }
                    },
                    bodyFont: {
                        family: 'Poppins'
                    },
                    titleFont: {
                        family: 'Poppins',
                        weight: 'bold'
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false /* Hilangkan grid vertikal */
                    },
                    ticks: {
                        font: {
                            family: 'Poppins'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5,
                        font: {
                            family: 'Poppins'
                        }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)' /* Grid horizontal lebih tipis */
                    }
                }
            }
        }
    });
</script>
</body>
</html>