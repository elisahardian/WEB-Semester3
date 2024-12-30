<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $iddep = $_POST['iddep'];

    $stmt = $conn->prepare("DELETE FROM tbldepartemen WHERE iddep = ?");
    $stmt->bind_param("s", $iddep);

    if ($stmt->execute()) {
        echo "Success: Data Departemen berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
