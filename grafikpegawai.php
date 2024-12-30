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
        .colored-div{
            background-color: #ff5900;
            border-radius:  50px;
            padding: 10px;
            margin: 80px 0 0 150px;
            display: flex;
            flex-direction: column;
            align-items:center;
            justify content: center;
            text-align: center;
            width:50%;
            margin-bottom: 90px;
            margin-left: 400px;


        }

        .container {
            display: flex;
            flex-direction: row;
            align-items:flex-start;
            justify-content: space-between;
            gap: 50px;
            padding: 80px;
            width: 50%;
            flex: 1;

        }

        .chart{
            width: 400px;
            padding: 2rem;
            border: 1px solid #f49131;
            border-radius: 1rem;
            background: #251c35;
            box-shadow: 0 0 16px rgba(0, 0, 0, 0);  
        
        }

    </style>

    <div class="colored-div">
        <h2><bold>Grafik Kinerja Karyawan Tahun 2024</bold></h2> <br>
    </div>
    <div class="container">
        
        <div class="chart1">
            <canvas id="barchart" width="300" height="300"></canvas>
        </div>
        <div class="chart2"> 
            <canvas id="doughnut" width="300" height="300"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <script>
        const ctx = document.getElementById('barchart').getContext('2d');
        const barchart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei'],
                datasets: [{
                    label: 'Transaksi',
                    data: [12, 19, 5, 15, 9, 11],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales:{
                    y:{
                        beginAtZero: true
                    }
                }
            }
        });
    </script> 
    <script>
        const ctx2 = document.getElementById('doughnut').getContext('2d');
        const doughnut = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Makanan', 'Minuman', 'Bahan Dapur', 'Lainnya'],
                datasets: [{
                    label: 'Produk Terjual',
                    data: [12, 19, 3, 8],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales:{
                    y:{
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <?php require 'footer.php'; ?>
</div>
</body>
</html>
