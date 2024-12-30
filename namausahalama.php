<?php
session_start();
require 'config.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, email FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();
?>

<?php require 'head.php'; ?>
<d class="wrapper">
    <header>
        <h4><?php echo htmlspecialchars($namaUsaha); ?></h4>
        <p><?php echo htmlspecialchars($alamatUsaha); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>

    <style>

        .identitas-usaha {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 50px;
            border-radius: 8px;
        }

        .logo img {
            width: 450px;
            height: 500px;
            padding-left: 85px;
        }

        .info {
            max-width: 600px;
        }

        .info h1 {
            font-size: 2em;
            margin: 0;
        }

        .info p {
            margin: 10px 0;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .box {
            padding: 10px;
            border: 1px;
            border-radius: 10px;
            background-color: #fffebb;

        }

        table {
            font-size: 20px;
            border: 1px solid black;
            border-radius: 10px;
            margin: 20px;
        }
    </style>

    <div class="identitas-usaha">
        <div class="logo">
            <img src="foto/logo.jpg" alt="Logo Usaha">
        </div>
        <div class="info">
            <h1 class="box">Identitas Perusahaan</h1>
            <table>
                <tr>
                    <th><strong>Nama</strong></th>
                    <th>PT Angin Puting Beliung</th>
                </tr>
                <tr>
                    <th><strong>Nama</strong></th>
                    <th>PT Angin Puting Beliung</th>
                </tr>
                <tr>
                    <th><strong>Nama</strong></th>
                    <th>PT Angin Puting Beliung</th>
                </tr>



                <p>Deskripsi singkat tentang usaha kamu. Misalnya, "Kami menyediakan produk berkualitas untuk kebutuhan sehari-hari."</p>
                <tr>
                    <td><b>Nama</b></td>
                    <td>: PT Angin Puting Beliung</td>
                </tr>
                <tr>
                    <td><b>No. Telepon</b></td>
                    <td>: 0812-179-185</td>
                </tr>
                <tr>
                    <td><b>Fax</b></td>
                    <td>: 12345</td>
                </tr>
                <tr>
                    <td><b>Email</b></td>
                    <td>: anginputingbeliung@gmail.com</td>
                </tr>
                <tr>
                    <td><b>NPWP</b></td>
                    <td>: 123-179-185</td>
                </tr>
                <tr>
                    <td><b>Bank</b></td>
                    <td>: BCA</td>
                </tr>
                <tr>
                    <td><b>No. Rekening</b></td>
                    <td>: 0246-185-179</td>
                </tr>
                <tr>
                    <td><b>Atas Nama</b></td>
                    <td>: ELGLO</td>
                </tr>
                <tr>
                    <td><b>Pimpinan</b></td>
                    <td>: ELGLO</td>
                </tr>
            </table>
            <a href="kontak.php" class="btn">Hubungi Kami</a>
        </div>
    </div>

    <!-- Full-width card for Aplikasi Kepegawaian -->
    <div class="full-width-card">
        <div class="card w-100">
            <div class="card-header"><strong>Aplikasi Kepegawaian</strong></div>
                <img src="logo/bg4.jpg" class="img-fluid" style="display:block; margin:auto;">
            </div>
        </div>
    </div>

    <?php require 'footer.php'; ?>
</div>
</body>
</html>
