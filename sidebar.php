<div class="sidebar">
    <div class="user-info">
        <img src="foto/<?php echo htmlspecialchars($username); ?>.jpg" alt="User Photo" class="user-photo">
        <p class="user-name"><?php echo htmlspecialchars($username); ?></p>
    </div>
    <ul>
        <li><a href="profiladmin.php"><span><i class="fas fa-user"></i> Profil</span></a></li>
        <li><a href="index.php"><span><i class="fas fa-home"></i> Home</span></a></li>
        <li><a href="namausaha.php"><span><i class="fas fa-building"></i> Identitas Usaha</span></a></li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-users"></i> Master</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="departemen.php"><span>Departemen</span></a></li>
                <li><a href="jabatan.php"><span>Jabatan</span></a></li>
                <li><a href="pegawai.php"><span>Kepegawaian</span></a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-exchange-alt"></i> Transaksi</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="peringatan.php"><span>Peringatan</span></a></li>
                <li><a href="penghargaan.php"><span>Penghargaan</span></a></li>
                <li><a href="izin.php"><span>Izin</span></a></li>
                <li><a href="cuti.php"><span>Cuti</span></a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="menu-toggle"><span><i class="fas fa-chart-line"></i> Report</span><i class="fas fa-chevron-right arrow"></i></a>
            <ul class="sub-menu">
                <li><a href="grafikpegawai.php"><span>Pegawai</span></a></li>
                <li><a href="grafikperingatan.php"><span>Peringatan</span></a></li>
                <li><a href="grafikpenghargaan.php"><span>Penghargaan</span></a></li>
                <li><a href="grafikizin.php"><span>Izin</span></a></li>
                <li><a href="grafikcuti.php"><span>Cuti</span></a></li>
            </ul>
        </li>
        <li><a href="logout.php"><span><i class="fas fa-sign-out-alt"></i> Logout</span></a></li>
    </ul>
    <div class="toggle-sidebar">
        <i class="fas fa-bars"></i>
    </div>
</div>
