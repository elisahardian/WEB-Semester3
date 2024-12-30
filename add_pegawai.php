<?php
session_start();
require 'config.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {

    //ambil idpeg. UNTUK FOTO BISA
    $idpeg = $_POST['idpeg'];
    //cek apakah foto sudah di upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileType = $_FILES['foto']['type'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        // tentukan folder tempat penyimpanann foto
        $uploadFolder = 'foto/';
        //tentukan nama filr baru sesuai idpeg
        $newFileName = $idpeg . '.jpg';
        //tentukan path lengkap untuk penyimpanan file
        $dest_path = $uploadFolder . $newFileName;
        //pindah file ke folder tujuan
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            echo "file berhasil diupload";
        } else {
            echo "terjadi kesalahan saat mengupload file";
        } 
    } else {
        echo "tidak ada file yang di upload atau terjadi kesalahan";
    }

    $idpeg = $_POST['idpeg'];
    $iddep = $_POST['iddep'];
    $idjab = $_POST['idjab'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $gaji = $_POST['gaji'];
    $status = $_POST['status'];
    $jkelamin = $_POST['jkelamin'];
    $skerja = $_POST['skerja'];
    $cuti = $_POST['cuti'];
    $jenjangpendidikan = $_POST['jenjangpendidikan'];
    $tglkerja = $_POST['tglkerja'];

    $stmt = $conn->prepare("INSERT INTO tabelpegawai ( idpeg, iddep, idjab, nama, alamat, telepon, email, gaji, status, jkelamin, skerja, cuti, jenjangpendidikan, tglkerja) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssss", $idpeg, $iddep, $idjab, $nama, $alamat, $telepon, $email, $gaji, $status, $jkelamin, $skerja, $cuti, $jenjangpendidikan, $tglkerja);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pegawai berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }
    $stmt->close();
    header("Location: pegawai.php");
    exit();
} else {
    header("Location: pegawai.php");
    exit();
}
?>