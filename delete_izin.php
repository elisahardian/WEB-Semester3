<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_izin = $_POST['id_izin'];

    $stmt = $conn->prepare("DELETE FROM tblizin WHERE id_izin = ?");
    $stmt->bind_param("s", $id_izin);

    if ($stmt->execute()) {
        echo "Success: Data izin berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
