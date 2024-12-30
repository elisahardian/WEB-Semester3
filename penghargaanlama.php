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
<div class="wrapper">
    <header>
        <h4><?php echo htmlspecialchars($namaUsaha); ?></h4>
        <p><?php echo htmlspecialchars($alamatUsaha); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>

    <style>
        .container {
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            height: 90vh;
            width: 100vh;
            margin-top: 8%;
            margin-left: 28%;
            border-radius: 10px;
  
        }

        .colored-div{
            background-color: rgb(217, 7, 245);
            padding: 40px;
        }
        .container {
            display: flex;
        }
        .card {
            border: 5px solid #a41fe7;
            border-radius: 10px;
            padding: 10px;
            margin: 10px;
            align-items: center;
            width: 250px;
        }
        .card img {
            border-radius: 50%;
            width: 140px;
            height: 140px;
            margin-right: 10px;
            align-items: center;
        }
        .card div {
            display: flex;
            flex-direction: column;
            align-items: center;    
        }
    </style>


<div class="container">
        <div class="img">
            <div class="card">
                <h3>Tas</h3>
                <img src="static/foto/tas.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Baju</h3>
                <img src="static/foto/baju.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Cincin</h3>
                <img src="static/foto/cincin.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Kalung</h3>
                <img src="static/foto/kalung.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Celana</h3>
                <img src="static/foto/celana.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="img">
            <div class="card">
                <h3>Gaun</h3>
                <img src="static/foto/gaun.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Anting</h3>
                <img src="static/foto/anting.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Jaket</h3>
                <img src="static/foto/jaket.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Gelang</h3>
                <img src="static/foto/gelang.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
        <div class="img">
            <div class="card">
                <h3>Jam Tangan</h3>
                <img src="static/foto/jam.jpg">
                <a href="#" class="btn btn-link" role="button">Selengkapnya</a>
            </div>
        </div>
    </div> 
        
    <?php require 'footer.php'; ?>
</div>
</body>
</html>
