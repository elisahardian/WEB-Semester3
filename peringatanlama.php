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
            height: 18vh;
            width: 100vh;
            margin-top: 7%;
            margin-left: 30%;
            border-radius: 10px;
            background-color: #ffb84d;    
        }
        
        table {
            width: 80%;
            border: 1px collapse;
            margin: 3% 0 0 15%;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f3f6;
        }
    </style>


    <div class="container">

        <h1>Transaksi Peringatan Gaji Karyawan</h1>
        <form method="post" action="">
            <label for="departemen">Pilih Departemen:</label>
            <select name="departemen" id="departemen">
                <option value="">-- Pilih Departemen --</option>
                <?php
                // Array departemen
                $listdepartemen = [
                    'IT' => 'IT',
                    'HRD' => 'HRD',
                    'Finance' => 'Finance',
                    'Marketing' => 'Marketing',
                ];
                // Menampilkan opsi departemen
                foreach ($listdepartemen as $key => $departemen) {
                    echo "<option value='$key'>$departemen</option>";
                }
                ?>
            </select>
            <input type="submit" value="Tampilkan">
        </form>
    </div>
    

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['departemen'])) {
        $selected_departemen = $_POST['departemen'];

        // Contoh data transaksi
        $transactions = [
            ['departemen' => 'IT', 'jabatan' => 'Software Engineer', 'tanggal' => '5', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen IT gaji dicairkan setiap bulan pada tanggal 5'],
            ['departemen' => 'IT', 'jabatan' => 'System Analyst', 'tanggal' => '5', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen IT gaji dicairkan setiap bulan pada tanggal 5'],
            ['departemen' => 'IT', 'jabatan' => 'Network Administrator', 'tanggal' => '5', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen IT gaji dicairkan setiap bulan pada tanggal 5'],
            ['departemen' => 'IT', 'jabatan' => 'Database Administrator', 'tanggal' => '5', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen IT gaji dicairkan setiap bulan pada tanggal 5'],
            ['departemen' => 'HRD', 'jabatan' => 'HRD Manager', 'tanggal' => '4', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen HRD gaji dicairkan setiap bulan pada tanggal 4'],
            ['departemen' => 'HRD', 'jabatan' => 'RecruHRDer', 'tanggal' => '4', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen HRD gaji dicairkan setiap bulan pada tanggal 4'],
            ['departemen' => 'HRD', 'jabatan' => 'HRD Generalist', 'tanggal' => '4', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen HRD gaji dicairkan setiap bulan pada tanggal 4'],
            ['departemen' => 'Finance', 'jabatan' => 'Finance Manager', 'tanggal' => '3', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen Finance gaji dicairkan setiap bulan pada tanggal 3'],
            ['departemen' => 'Finance', 'jabatan' => 'Accountant', 'tanggal' => '3', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen Finance gaji dicairkan setiap bulan pada tanggal 3'],
            ['departemen' => 'Finance', 'jabatan' => 'Financial Analyst', 'tanggal' => '3', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen Finance gaji dicairkan setiap bulan pada tanggal 3'],
            ['departemen' => 'Marketing', 'jabatan' => 'Marketing Manager', 'tanggal' => '2', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen Marketing gaji dicairkan setiap bulan pada tanggal 2'],
            ['departemen' => 'Marketing', 'jabatan' => 'Sales Executive', 'tanggal' => '2', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen Marketing gaji dicairkan setiap bulan pada tanggal 2'],
            ['departemen' => 'Marketing', 'jabatan' => 'DigMarketingal Marketing Specialist', 'tanggal' => '2', 'keterangan' => 'Menginfokan kepada seluruh pegawai departemen IT gaji dicairkan setiap bulan pada tanggal 2'],
            // Tambahkan data lain sesuai kebutuhan
        ];

        // Filter transaksi berdasarkan departemen yang dipilih
        $filtered_transactions = array_filter($transactions, function($transaction) use ($selected_departemen) {
            return $transaction['departemen'] === $selected_departemen;
        });

        // Tampilkan tabel
        if (!empty($filtered_transactions)) {
            echo "<table>";
            echo "<tr>
            <th>No</th><th>Departemen</th><th>Jabatan</th><th>Tanggal</th><th>Keterangan</th>
            </tr>";
            $no = 1;
            foreach ($filtered_transactions as $transaction) {
                echo "<tr>";
                echo "<td class='text-center'>" . $no++ . "</td>";
                echo "<td>{$transaction['departemen']}</td>";
                echo "<td>{$transaction['jabatan']}</td>";
                echo "<td>{$transaction['tanggal']}</td>";
                echo "<td>{$transaction['keterangan']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada transaksi untuk departemen yang dipilih.</p>";
        }
    }
    ?>

    
    <?php require 'footer.php'; ?>
</div>
</body>
</html>
