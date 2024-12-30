<?php
require 'config.php';

// Ambil data pegawai dari database
$pegawai = $conn->query("SELECT idpeg, nama FROM tabelpegawai");

$options = '';
while ($row = $tabelpegawai->fetch_assoc()) {
    $options .= "<option value='" . htmlspecialchars($row['idpeg']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
}

echo $options;
?>
