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


// Generate idpeg otomatis
$stmt = $conn->query("SELECT idpeg FROM tabelpegawai ORDER BY idpeg DESC LIMIT 1");
$latestidpeg = $stmt->fetch_assoc();
$urut = 1;
if ($latestidpeg) {
    $latestNumber = (int) substr($latestidpeg['idpeg'], 7);
    $urut = $latestNumber + 1;
}
$newidpeg = 'P' . date('Y') . date('m') . str_pad($urut, 3, '0', STR_PAD_LEFT);


// Query untuk mengambil data iddep
$iddep_query = "SELECT iddep FROM tbldepartemen";
$iddep_result = $conn->query($iddep_query);
// Query untuk mengambil data idjab
$idjab_query = "SELECT idjab FROM tbljabatan";
$idjab_result = $conn->query($idjab_query);


// Query untuk mendapatkan data departemen
$iddep_query_edit = "SELECT iddep, departemen FROM tbldepartemen";
$iddep_result_edit = $conn->query($iddep_query_edit);
// Query untuk mendapatkan data jabatan
$idjab_query_edit = "SELECT idjab, jabatan FROM tbljabatan";
$idjab_result_edit = $conn->query($idjab_query_edit);



// Simpan pesan ke variabel dan hapus dari session
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>


<?php require 'head.php'; ?>
<div class="wrapper">
    <header>
        <h4><?php echo htmlspecialchars($namaUsaha); ?></h4>
        <p><?php echo htmlspecialchars($alamatUsaha); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>
    <div class="content" id="content">
        
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h4>Data Pegawai</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#addpegawaiModal"><i class='fas fa-plus'></i> Add </button>
                        <button type="button" class="btn btn-secondary mb-3" id="printButton"><i class='fas fa-print'></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="pegawaiTable" class="table table-bordered table-striped table-hover">    
                            <thead class="text-center">
                                <tr style="background-color:#f3f6">
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>ID Pegawai</th>
                                    <th>Nama</th>
                                    <th>ID Dep</th>
                                    <th>Departemen</th>
                                    <th>ID Jab</th>
                                    <th>Jabatan</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Gaji</th>
                                    <th>Status</th>
                                    <th>J.Kelamin</th>
                                    <th>S.Kerja</th>
                                    <th>Cuti</th>
                                    <th>J.Pendidikan</th>
                                    <th>Tgl.Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                


                                <?php

                                // Koneksi ke database
                                $conn = new mysqli("localhost", "root", "", "db_lat_hrd");

                                // Periksa koneksi
                                if ($conn->connect_error) {
                                    die("Koneksi gagal: " . $conn->connect_error);
                                }
                                // Query SQL
                                $sql = "SELECT 
                                            tabelpegawai.idpeg, 
                                            tabelpegawai.nama, 
                                            tabelpegawai.iddep, 
                                            tbldepartemen.departemen, 
                                            tabelpegawai.idjab, 
                                            tbljabatan.jabatan, 
                                            tabelpegawai.alamat, 
                                            tabelpegawai.telepon, 
                                            tabelpegawai.email, 
                                            tabelpegawai.gaji, 
                                            tabelpegawai.status, 
                                            tabelpegawai.skerja, 
                                            tabelpegawai.cuti, 
                                            tabelpegawai.tglkerja, 
                                            tabelpegawai.jkelamin, 
                                            tabelpegawai.jenjangpendidikan
                                        FROM 
                                            tabelpegawai
                                        LEFT JOIN 
                                            tbljabatan ON tabelpegawai.idjab = tbljabatan.idjab
                                        LEFT JOIN 
                                            tbldepartemen ON tabelpegawai.iddep = tbldepartemen.iddep";

                                $result = $conn->query($sql);


                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($tabelpegawai = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no++ . "</td>"; //untuk no

                                        // Asumsikan $tabelpegawai adalah array yang berisi data pegawai dari database. UNTUK FOTO BISA
                                        echo "<td class='text-center'>";
                                        $idpeg = $tabelpegawai['idpeg']; //ambil idpegawai
                                        $fotoPath = 'foto/' . $idpeg . '.jpg'; // buat path sesuai dengan format penyimpanan
                                        if (file_exists($fotoPath)) {
                                            echo "<img src='" . htmlspecialchars($fotoPath) . "'alt='foto pegawai' style='width:50px;height:50px;'>";
                                        } else {
                                            echo "tidak ada ft";
                                        }


                                        echo "<td>" . htmlspecialchars($tabelpegawai['idpeg']) . "</td>"; //id pegawai
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['nama']) . "</td>"; //nama
                                        echo "<td>" . htmlspecialchars($tabelpegawai['iddep']) . "</td>"; //id departemen
                                        echo "<td>" . htmlspecialchars($tabelpegawai['departemen']) . "</td>"; //departemen
                                        echo "<td>" . htmlspecialchars($tabelpegawai['idjab']) . "</td>"; //id jabatan
                                        echo "<td>" . htmlspecialchars($tabelpegawai['jabatan']) . "</td>"; //jabatan
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['alamat']) . "</td>"; //alamat
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['telepon']) . "</td>"; //no telp
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['email']) . "</td>"; //email
                                        echo "<td>" . htmlspecialchars($tabelpegawai['gaji']) . "</td>"; //gaji
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['status']) . "</td>"; //status
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['jkelamin']) . "</td>"; //jenis kelamin
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['skerja']) . "</td>"; //status kerja
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['cuti']) . "</td>"; //cuti
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['jenjangpendidikan']) . "</td>"; //jenjang pendidikan
                                        echo "<td class='text-center'>" . htmlspecialchars($tabelpegawai['tglkerja']) . "</td>"; //tanggal kerja
                                        echo "<td class='text-center'>";
                                        echo "<div class='d-flex justify-content-center'>";
                                        echo "<button class='btn btn-warning btn-sm edit-btn mr-1' data-bs-toggle='modal' data-bs-target='#editpegawaiModal' data-id='" . htmlspecialchars($fotoPath)  . "' data-name='" . htmlspecialchars($tabelpegawai['tglkerja']) . "' data-name='" . htmlspecialchars($tabelpegawai['nama']) . "' data-id='" . htmlspecialchars($tabelpegawai['iddep']) . "' data-id='" . htmlspecialchars($tabelpegawai['iddep']) . "' data-id='" . htmlspecialchars($tabelpegawai['idjab']) . "' data-name='" . htmlspecialchars($tabelpegawai['idjab']) . "' data-name='" . htmlspecialchars($tabelpegawai['alamat']) . "' data-name='" . htmlspecialchars($tabelpegawai['telepon']) . "' data-name='" . htmlspecialchars($tabelpegawai['email']) . "' data-name='" . htmlspecialchars($tabelpegawai['gaji']) . "' data-name='" . htmlspecialchars($tabelpegawai['status']) . "' data-name='" . htmlspecialchars($tabelpegawai['jkelamin']) . "' data-name='" . htmlspecialchars($tabelpegawai['skerja']) . "' data-name='" . htmlspecialchars($tabelpegawai['cuti']) . "' data-name='" . htmlspecialchars($tabelpegawai['jenjangpendidikan']) . "' data-name='" . htmlspecialchars($tabelpegawai['tglkerja']) . "'><i class='fas fa-edit'></i> Edit</button>";
                                       
                                        echo "<button class='btn btn-danger btn-sm delete-btn' data-id='" . htmlspecialchars($tabelpegawai['idpeg']) . "'><i class='fas fa-trash'></i> Delete</button>";

                                                                
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No data found</td></tr>";
                                }

                                // Tutup koneksi
                                $conn->close();

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require 'footer.php'; ?>
</div>



<!-- Modal Add pegawai -->
<div class="modal fade" id="addpegawaiModal" tabindex="-1" aria-labelledby="addpegawaiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addpegawaiModalLabel">Add pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_pegawai.php" method="post" enctype="multipart/form-data">
                    
                    <!-- input foto -->
                    <div class="mb-3">
                        <label for="foto" class="form-label">Upload Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>

                    
                    <div class="mb-3">
                        <label for="idpeg" class="form-label">Id Pegawai</label>
                        <input type="text" class="form-control" id="idpeg" name="idpeg" value="<?php echo htmlspecialchars($newidpeg); ?>" readonly>
                    </div>

                    <div class="mb-3">        
                        <label for="iddep" class="form-label">Id Departemen</label>
                        <select class="form-select" id="iddep" name="iddep" required>
                            <?php
                            if ($iddep_result->num_rows > 0) {
                                while($row = $iddep_result->fetch_assoc()) {
                                    echo "<option value='" . $row['iddep'] . "'>" . $row['iddep'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        
                    <label for="idjab" class="form-label">Id Jabatan</label>
                    <select class="form-select" id="idjab" name="idjab" required>
                        <?php
                        if ($idjab_result->num_rows > 0) {
                            while($row = $idjab_result->fetch_assoc()) {
                                echo "<option value='" . $row['idjab'] . "'>" . $row['idjab'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Pegawai</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">No Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="gaji" class="form-label">Gaji</label>
                        <input type="text" class="form-control" id="gaji" name="gaji" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Belum">Belum</option>
                            <option value="Menikah">Menikah</option>
                            <option value="Pisah">Pisah</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="jkelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jkelamin" name="jkelamin" required>
                            <option value="LakiLaki">Laki Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="skerja" class="form-label">Status Kerja</label>
                        <select class="form-select" id="skerja" name="skerja" required>
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Pensiun">Pensiun</option>
                            <option value="PHK">PHK</option>
                            <option value="Keluar">Keluar</option>
                            <option value="MengundurkanDiri">Mengundurkan Diri</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cuti" class="form-label">Cuti</label>
                        <input type="text" class="form-control" id="cuti" name="cuti" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="jenjangpendidikan" class="form-label">Jenjang Pendidikan</label>
                        <select class="form-select" id="jenjangpendidikan" name="jenjangpendidikan" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tglkerja" class="form-label"Tanggal Kerja</label>
                        <input type="date" class="form-control" id="tglkerja" name="tglkerja" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit pegawai -->
<div class="modal fade" id="editpegawaiModal" tabindex="-1" aria-labelledby="editpegawaiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editpegawaiModalLabel">Edit Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_pegawai.php" method="post" enctype="multipart/form-data">

                    <!-- input foto -->
                    <div class="mb-3">
                        <label for="edit_foto" class="form-label">Foto Pegawai</label>
                        
                            
                        <input type="file" class="form-control" id="foto" name="foto">
                        <small class="text-muted">kosongkan jika tidak ingin mengganti foto</small>
                    </div>

                        
                    <div class="mb-3">
                        <label for="edit_idpeg" class="form-label">Id Pegawai</label>
                        <input type="text" class="form-control" id="idpeg" name="idpeg" value="<?php echo htmlspecialchars($newidpeg); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="edit_iddep" class="form-label">Departemen</label>
                        <select class="form-select" id="iddep" name="iddep" required>
                            <?php
                            if ($iddep_result->num_rows > 0) {
                                while($rowedit = $iddep_result_edit->fetch_assoc()) {
                                    echo "<option value='" . $rowedit['iddep'] . "'>" . $rowedit['departemen'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_idjab" class="form-label">Jabatan</label>
                        <select class="form-select" id="idjab" name="idjab" required>
                            <?php
                            if ($idjab_result->num_rows > 0) {
                                while($rowedit = $idjab_result_edit->fetch_assoc()) {
                                    echo "<option value='" . $rowedit['idjab'] . "'>" . $rowedit['jabatan'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label">Alamat Pegawai</label>
                        <input type="text" class="form-control" id="edit_alamat" name="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_telepon" class="form-label">No Telepon</label>
                        <input type="text" class="form-control" id="edit_telepon" name="telepon" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gaji" class="form-label">Gaji</label>
                        <input type="text" class="form-control" id="edit_gaji" name="gaji" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="Belum">Belum</option>
                            <option value="Menikah">Menikah</option>
                            <option value="Pisah">Pisah</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_jkelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="edit_jkelamin" name="jkelamin" required>
                            <option value="LakiLaki">Laki Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_skerja" class="form-label">Status Kerja</label>
                        <select class="form-select" id="edit_skerja" name="skerja" required>
                            <option value="Tetap">Tetap</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Pensiun">Pensiun</option>
                            <option value="PHK">PHK</option>
                            <option value="Keluar">Keluar</option>
                            <option value="MengundurkanDiri">Mengundurkan Diri</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cuti" class="form-label">Cuti</label>
                        <input type="text" class="form-control" id="edit_cuti" name="cuti" required>
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jenjangpendidikan" class="form-label">Jenjang Pendidikan</label>
                        <select class="form-select" id="edit_jenjangpendidikan" name="jenjangpendidikan" required>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tglkerja" class="form-label"Tanggal Kerja</label>
                        <input type="date" class="form-control" id="edit_tglkerja" name="tglkerja" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Adjust DataTables' scrolling to avoid overlapping with the footer
        function adjustTableHeight() {
            var footerHeight = $('footer').outerHeight();
            var tableHeight = 'calc(100vh - 290px - ' + footerHeight + 'px)';

            $('#pegawaiTable').DataTable().destroy();
            $('#pegawaiTable').DataTable({
                "pagingType": "simple_numbers",
                "scrollY": tableHeight,
                "scrollCollapse": true,
                "paging": true
            });
        }

        // Call the function to adjust table height initially
        adjustTableHeight();

        // Adjust table height on window resize
        $(window).resize(function() {
            adjustTableHeight();
        });

        // Populate edit modal with data
        $('#editpegawaiModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var idpeg = button.data('idpeg');
            var idpeg = button.data('idpeg');
            var idjab = button.data('idjab');
            var nama = button.data('nama');            
            var alamat = button.data('alamat');
            var telepon = button.data('telepon');            
            var email = button.data('email');
            var gaji = button.data('gaji');            
            var status = button.data('status');
            var jkelamin = button.data('jkelamin');            
            var skerja = button.data('skerja');
            var cuti = button.data('cuti');            
            var jenjangpendidikan = button.data('jenjangpendidikan');
            var tglkerja = button.data('tglkerja');
        
            var modal = $(this);
            modal.find('#edit_idpeg').val(idpeg);
            modal.find('#edit_idpeg').val(idpeg);
            modal.find('#edit_idjab').val(idjab);
            modal.find('#edit_nama').val(nama);
            modal.find('#edit_alamat').val(alamat);
            modal.find('#edit_telepon').val(telepon);
            modal.find('#edit_email').val(email);
            modal.find('#edit_gaji').val(gaji);
            modal.find('#edit_status').val(status);
            modal.find('#edit_jkelamin').val(jkelamin);
            modal.find('#edit_skerja').val(skerja);
            modal.find('#edit_cuti').val(cuti);
            modal.find('#edit_jenjangpendidikan').val(jenjangpendidikan);
            modal.find('#edit_tglkerja').val(tglkerja);
        });

        // Show message if it exists in the session
        <?php if ($message): ?>
            Swal.fire({
                title: '<?php echo $message['type'] === 'success' ? 'Success!' : 'Error!'; ?>',
                text: '<?php echo $message['text']; ?>',
                icon: '<?php echo $message['type'] === 'success' ? 'success' : 'error'; ?>'
            });
        <?php endif; ?>

        // Handle delete button click
        $(document).on('click', '.delete-btn', function() {
            var idpeg = $(this).data('id');
            console.log(idpeg); //debugging untuk melihat apakah idpeg sudah benar
            Swal.fire({
                title: 'Are you sure?',
                text: 'Apa benar data tersebut dihapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_pegawai.php',
                        type: 'POST',
                        data: { idpeg: idpeg },
                        success: function(response) {
                            console.log(response); // Debugging
                            if (response.includes('Success')) {
                                Swal.fire(
                                    'Deleted!',
                                    'Data berhasil dihapus.',
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText); // Debugging
                            Swal.fire(
                                'Error!',
                                'An error occurred: ' + error,
                                'error'
                            );
                        }
                    });
                }
            });   
        });        
        //Print ke PDF        
        $(document).ready(function() {
            // Other existing scripts...

            // Handle print button click
            $('#printButton').click(function() {
                window.location.href = 'print_pegawai.php';
            });
        });
    });
</script>
</body>
</html>
