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

// Ambil data dari tabel departemen
$result = $conn->query("SELECT * FROM tbldepartemen");

// Dapatkan nomor urut terbaru untuk iddep baru
$stmt = $conn->query("SELECT iddep FROM tbldepartemen ORDER BY iddep DESC LIMIT 1");
$latestiddep = $stmt->fetch_assoc();
$urut = 1;
if ($latestiddep) {
    $latestNumber = (int) substr($latestiddep['iddep'], 1);
    $urut = $latestNumber + 1;
}
$newiddep = 'D' . str_pad($urut, 3, '0', STR_PAD_LEFT);

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
                    <h4>Departemen</h4>
                    <div>
                        <button type="button" class="btn btn-primary mb-3 mr-2" data-bs-toggle="modal" data-bs-target="#adddepartemenModal"><i class='fas fa-plus'></i> Add </button>
                        <button type="button" class="btn btn-secondary mb-3" id="printButton"><i class='fas fa-print'></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="departemenTable" class="table table-bordered table-striped table-hover">    
                            <thead class="text-center">
                                <tr style="background-color:#f3f6">
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Departemen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($tbldepartemen = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $no++ . "</td>";
                                        echo "<td class='text-center'>" . htmlspecialchars($tbldepartemen['iddep']) . "</td>";
                                        echo "<td>" . htmlspecialchars($tbldepartemen['departemen']) . "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<div class='d-flex justify-content-center'>";
                                        echo "<button class='btn btn-warning btn-sm edit-btn mr-1' data-bs-toggle='modal' data-bs-target='#editdepartemenModal' data-id='" . htmlspecialchars($tbldepartemen['iddep']) . "' data-name='" . htmlspecialchars($tbldepartemen['departemen']) .  "'><i class='fas fa-edit'></i> Edit</button>";
                                        echo "<button class='btn btn-danger btn-sm delete-btn' data-id='" . htmlspecialchars($tbldepartemen['iddep']) . "'><i class='fas fa-trash'></i> Delete</button>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No data found</td></tr>";
                                }
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

<!-- Modal Add departemen -->
<div class="modal fade" id="adddepartemenModal" tabindex="-1" aria-labelledby="adddepartemenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adddepartemenModalLabel">Add departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_departemen.php" method="post">
                    <div class="mb-3">
                        <label for="iddep" class="form-label">Kode departemen</label>
                        <input type="text" class="form-control" id="iddep" name="iddep" value="<?php echo htmlspecialchars($newiddep); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="departemen" class="form-label">Nama departemen</label>
                        <input type="text" class="form-control" id="departemen" name="departemen" required>
                    </div>
                                <!-- tinggal tambahin untuk add departemen -->
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit departemen -->
<div class="modal fade" id="editdepartemenModal" tabindex="-1" aria-labelledby="editdepartemenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editdepartemenModalLabel">Edit departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_departemen.php" method="post">
                    <div class="mb-3">
                        <label for="edit_iddep" class="form-label">Kode departemen</label>
                        <input type="text" class="form-control" id="edit_iddep" name="iddep" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_departemen" class="form-label">Nama departemen</label>
                        <input type="text" class="form-control" id="edit_departemen" name="departemen" required>
                    </div>
                    <!-- tinggal tambahin untuk edit departemen -->
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

            $('#departemenTable').DataTable().destroy();
            $('#departemenTable').DataTable({
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
        $('#editdepartemenModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var iddep = button.data('id');
            var departemen = button.data('name');
            
            var modal = $(this);
            modal.find('#edit_iddep').val(iddep);
            modal.find('#edit_departemen').val(departemen);
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
            var iddep = $(this).data('id');
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
                        url: 'delete_departemen.php',
                        type: 'POST',
                        data: { iddep: iddep },
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
                window.location.href = 'print_departemen.php';
            });
        });
    });
</script>
</body>
</html>
