<?php
// Start session dan sambungkan ke database
session_start();
require 'config.php';

// Cek apakah data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_cuti = $_POST['id_cuti'];
    $idpeg = $_POST['idpeg'];
    $tanggal = $_POST['tanggal'];
    $alasan = $_POST['alasan'];
    $ditetapkan = $_POST['ditetapkan'];
    $pembuat_surat = $_POST['pembuat_surat'];
    
    // Validasi data input
    if (empty($idpeg) || empty($tanggal) || empty($alasan)) {
        $_SESSION['message'] = 'Semua field harus diisi!';
        header('Location: cuti.php');
        exit();
    }

    // Cek apakah idpeg ada di tabel pegawai
    $cek_pegawai = $conn->prepare("SELECT idpeg FROM tabelpegawai WHERE idpeg = ?");
    $cek_pegawai->bind_param('s', $idpeg);
    $cek_pegawai->execute();
    $cek_pegawai->store_result();

    if ($cek_pegawai->num_rows == 0) {
        $_SESSION['message'] = 'ID Pegawai tidak ditemukan!';
        header('Location: cuti.php');
        exit();
    }

    // Insert data ke database jika idpeg valid
    $stmt = $conn->prepare("INSERT INTO tabelcuti (id_cuti, idpeg, tanggal, alasan, ditetapkan, pembuat_surat) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $id_cuti, $idpeg, $tanggal, $alasan, $ditetapkan, $pembuat_surat);

    // Eksekusi query dan cek apakah berhasil
    if ($stmt->execute()) {
        $_SESSION['message'] = 'cuti berhasil ditambahkan!';
    } else {
        $_SESSION['message'] = 'Gagal menambahkan cuti: ' . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect kembali ke halaman cuti
    header('Location: cuti.php');
    exit();
} else {
    // Jika bukan melalui POST, kembalikan ke halaman utama
    header('Location: cuti.php');
    exit();
}
