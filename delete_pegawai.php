<!-- TOMBOL DELETE MASIH GABISA DIPENCET -->

<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpeg = $_POST['idpeg'];

    $stmt = $conn->prepare("DELETE FROM tabelpegawai WHERE idpeg = ?");
    $stmt->bind_param("s", $idpeg);





    if ($stmt->execute()) {
        echo "Success: Data pegawai berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data." . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
