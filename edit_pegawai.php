<!-- EDIT MASIH GABISA DATANYA GAMASUK -->

<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //dri sini. coba2 edit foto, blm bisa
    //ambil idpeg.
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

    $sql ="UPDATE tabelpegawai SET nama='$nama', iddep='$iddep' WHERE idpeg='$idpeg'";
    if ($conn->query($sql) === TRUE) {
        echo "data pegawai berhasil diupdte";
    } else {
        echo "errorr" . $conn->error;
    }
    //smpe sini
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

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("UPDATE tabelpegawai SET iddep = ?, idjab = ?, nama = ?, alamat = ?, telepon = ?, email = ?, gaji = ?, status = ?, jkelamin = ?, skerja = ?, cuti = ?, jenjangpendidikan = ?, tglkerja = ? WHERE idpeg = ?");
    
    // Bind the parameters to the SQL query
    $stmt->bind_param("ssssssssssssss", $iddep, $idjab, $nama, $alamat, $telepon, $email, $gaji, $status, $jkelamin, $skerja, $cuti, $jenjangpendidikan, $tglkerja, $idpeg);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pegawai berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }

    // Close the statement
    $stmt->close();

    // Redirect to the pegawai.php page
    header("Location: pegawai.php");
    exit();
} else {
    // Redirect to the pegawai.php page if the request method is not POST
    header("Location: pegawai.php");
    exit();
}
?>
