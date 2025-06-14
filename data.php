<?php
$koneksi = new mysqli("localhost", "root", "", "db_sig");
if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

$queryRumah = $koneksi->query("SELECT * FROM data_rumah");
$rumahData = [];
while ($rumah = $queryRumah->fetch_assoc()) {
    $id_rumah = $rumah['id'];
    $queryPenghuni = $koneksi->query("SELECT * FROM warga WHERE rumah_id = $id_rumah");
    $penghuni = [];
    while ($w = $queryPenghuni->fetch_assoc()) $penghuni[] = $w;
    $rumah['penghuni'] = $penghuni;
    $rumahData[] = $rumah;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Rumah dan Warga</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background: #f1f3f6; font-family: 'Segoe UI', sans-serif; }
    h2, h4 { font-weight: 600; }
    .table-responsive { background: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .table th, .table td { vertical-align: middle; }
    ul { padding-left: 1.2rem; margin: 0; }
    ul li { margin-bottom: 4px; }
    .badge { font-size: 0.9rem; }
  </style>
</head>
<body>

<div class="container py-5">
  <h2 class="text-center mb-4">🏘️ Data Rumah dan Warga Perumahan</h2>
  <hr class="my-4">

  <div class="d-flex justify-content-end mb-3 gap-2">
    <a href="export_pdf.php" class="btn btn-danger" target="_blank">📄 Export PDF</a>
    <a href="export_word.php" class="btn btn-primary" target="_blank">📝 Export Word</a>
  </div>

  <h4 class="mb-3">📋 Daftar Rumah & Penghuninya</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark text-center">
        <tr>
          <th>#</th>
          <th>🏠 Nama Pemilik</th>
          <th>📍 Alamat</th>
          <th>🌐 Koordinat</th>
          <th>👨‍👩‍👧‍👦 Penghuni</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; foreach ($rumahData as $r): ?>
          <tr>
            <td class="text-center fw-bold"><?= $no++ ?></td>
            <td><?= htmlspecialchars($r['nama_pemilik']) ?></td>
            <td><?= htmlspecialchars($r['alamat']) ?></td>
            <td><code><?= $r['latitude'] ?>, <?= $r['longitude'] ?></code></td>
            <td>
              <?php if (!empty($r['penghuni'])): ?>
                <ul>
                  <?php foreach ($r['penghuni'] as $w): ?>
                    <li><?= htmlspecialchars($w['nama_warga']) ?> <span class="badge bg-info text-dark"><?= $w['umur'] ?> th</span></li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <span class="text-muted fst-italic">Belum ada warga</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
