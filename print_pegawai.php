<!-- TOMBOL PRINT MASIH GABISA DIPENCET -->

<?php

session_start();
require 'config.php';
require 'fpdf/fpdf.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

//ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

//ambil data dari tabel pegawai
$result = $conn->query("SELECT * FROM tabelpegawai");

//buat pdf
$pdf = new FPDF();
$pdf->AddPage();

//tambahkan kop dokumen
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, $namaUsaha, 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $alamatUsaha, 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Daftar Pegawai', 0, 1, 'C');
$pdf->Ln(2);

//tambahkan header table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, 'No', 1, 0, 'C');
$pdf->Cell(40, 10, 'ID Pegawai', 1, 0, 'L');
$pdf->Cell(140, 10, 'ID Departemen', 1, 1, 'L');
$pdf->Cell(150, 10, 'ID Jabatan', 1, 2, 'L');
$pdf->Cell(160, 10, 'Nama Pegawai', 1, 3, 'L');
$pdf->Cell(170, 10, 'Alamat', 1, 4, 'L');
$pdf->Cell(180, 10, 'Telepon', 1, 5, 'L');
$pdf->Cell(190, 10, 'Email', 1, 6, 'L');
$pdf->Cell(200, 10, 'Gaji', 1, 7, 'L');
$pdf->Cell(210, 10, 'Status', 1, 8, 'L');
$pdf->Cell(220, 10, 'Jenis Kelamin', 1, 9, 'L');
$pdf->Cell(230, 10, 'Status Kerja', 1, 10, 'L');
$pdf->Cell(240, 10, 'Cuti', 1, 11, 'L');
$pdf->Cell(250, 10, 'Jenjang Pendidikan', 1, 12, 'L');
$pdf->Cell(260, 10, 'Tanggal Kerja', 1, 13, 'L');

//tambahkan data tabel
$pdf->SetFont('Arial', '', 12);
$no = 1;
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(10, 10, $no++, 1, 0, 'C');
    $pdf->Cell(40, 10, $row['idpeg'], 1, 0, 'L');
    $pdf->Cell(140, 10, $row['iddep'], 1, 1, 'L');
    $pdf->Cell(150, 10, $row['idjab'], 1, 2, 'L');
    $pdf->Cell(160, 10, $row['nama'], 1, 3, 'L');
    $pdf->Cell(170, 10, $row['alamat'], 1, 4, 'L');
    $pdf->Cell(180, 10, $row['telepom'], 1, 5, 'L');
    $pdf->Cell(190, 10, $row['email'], 1, 6, 'L');
    $pdf->Cell(200, 10, $row['gaji'], 1, 7, 'L');
    $pdf->Cell(210, 10, $row['status'], 1, 8, 'L');
    $pdf->Cell(220, 10, $row['jkelamin'], 1, 9, 'L');
    $pdf->Cell(230, 10, $row['skerja'], 1, 10, 'L');
    $pdf->Cell(240, 10, $row['cuti'], 1, 11, 'L');
    $pdf->Cell(250, 10, $row['jenjangpendidikan'], 1, 12, 'L');
    $pdf->Cell(260, 10, $row['tglkerja'], 1, 13, 'L');
}

//output pdf
$pdf->Output('I', 'Daftar_pegawai.pdf');
?>