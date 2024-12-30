<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cuti = $_POST['id_cuti'];

    $stmt = $conn->prepare("DELETE FROM tabelcuti WHERE id_cuti = ?");
    $stmt->bind_param("s", $id_cuti);

    if ($stmt->execute()) {
        echo "Success: Data cuti berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
