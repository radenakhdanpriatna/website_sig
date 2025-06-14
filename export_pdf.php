<?php
require('fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Data Rumah dan Warga Perumahan',0,1,'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$koneksi = new mysqli("localhost", "root", "", "db_sig");
if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$queryRumah = $koneksi->query("SELECT * FROM data_rumah");

while ($rumah = $queryRumah->fetch_assoc()) {
    $id_rumah = $rumah['id'];
    $queryPenghuni = $koneksi->query("SELECT * FROM warga WHERE rumah_id = $id_rumah");

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,"Nama Pemilik: {$rumah['nama_pemilik']}",0,1);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,8,"Alamat: {$rumah['alamat']}",0,1);
    $pdf->Cell(0,8,"Koordinat: {$rumah['latitude']}, {$rumah['longitude']}",0,1);

    $pdf->Cell(0,8,"Penghuni:",0,1);

    if ($queryPenghuni->num_rows > 0) {
        while ($w = $queryPenghuni->fetch_assoc()) {
            $pdf->Cell(10); // indent
            $pdf->Cell(0,8,"- {$w['nama_warga']} ({$w['umur']} th)",0,1);
        }
    } else {
        $pdf->Cell(10);
        $pdf->Cell(0,8,"- Belum ada warga",0,1);
    }
    $pdf->Ln(5);
}

$pdf->Output('D', 'Data_Rumah_Warga.pdf');
exit;
