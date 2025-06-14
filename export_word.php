<?php
header("Content-Type: application/msword");
header("Content-Disposition: attachment; filename=Data_Rumah_Warga.doc");
header("Pragma: no-cache");
header("Expires: 0");

$koneksi = new mysqli("localhost", "root", "", "db_sig");
if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

echo "<html><head><meta charset='UTF-8'><title>Data Rumah dan Warga</title>";
echo "<style>
body { font-family: Calibri, sans-serif; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #000; padding: 8px; }
th { background-color:rgb(9, 11, 165); color: white; }
</style>";
echo "</head><body>";
echo "<h2>Data Rumah dan Warga Perumahan</h2>";
echo "<table>";
echo "<tr><th>#</th><th>Nama Pemilik</th><th>Alamat</th><th>Koordinat</th><th>Penghuni</th></tr>";

$queryRumah = $koneksi->query("SELECT * FROM data_rumah");
$no = 1;

while ($rumah = $queryRumah->fetch_assoc()) {
    $id_rumah = $rumah['id'];
    $queryPenghuni = $koneksi->query("SELECT * FROM warga WHERE rumah_id = $id_rumah");

    $penghuniText = "";
    if ($queryPenghuni->num_rows > 0) {
        while ($w = $queryPenghuni->fetch_assoc()) {
            $penghuniText .= htmlspecialchars($w['nama_warga'])." (".$w['umur']." th), ";
        }
        $penghuniText = rtrim($penghuniText, ", ");
    } else {
        $penghuniText = "Belum ada warga";
    }

    echo "<tr>";
    echo "<td align='center'>{$no}</td>";
    echo "<td>".htmlspecialchars($rumah['nama_pemilik'])."</td>";
    echo "<td>".htmlspecialchars($rumah['alamat'])."</td>";
    echo "<td>{$rumah['latitude']}, {$rumah['longitude']}</td>";
    echo "<td>{$penghuniText}</td>";
    echo "</tr>";

    $no++;
}

echo "</table></body></html>";
exit;
