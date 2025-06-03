<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan adalah admin
if(!isset($_SESSION['role']) || !($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff')) {
    header("Location: login.php");
    exit();
}

// Ambil data reservasi
$query = "SELECT r.*, u.nama, b.judul FROM reservasi r JOIN users u ON r.id_user = u.id_user JOIN buku b ON r.id_buku = b.id_buku ORDER BY r.tanggal_reservasi DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Reservasi - Admin Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Hapus atau sesuaikan style sidebar */
        .sidebar {
            display: none; /* Sembunyikan sidebar */
        }
        
        /* Style untuk bottom navbar */
        .bottom-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #28a745; /* Warna hijau Bootstrap */
            color: white;
            z-index: 1000;
            padding: 10px 0;
        }
        .bottom-navbar .nav-link {
            color: white; /* Teks putih */
            text-align: center;
            padding: 5px 0;
        }
        .bottom-navbar .nav-link:hover {
            background-color: #218838; /* Warna hijau sedikit lebih gelap saat hover */
        }
        .content-push-bottom {
            padding-bottom: 70px; /* Sesuaikan dengan tinggi navbar */
        }
         /* Atur nav-link active di bottom navbar */
        .bottom-navbar .nav-link.active {
             font-weight: bold; /* Atau style lain untuk menandai aktif */
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Hapus Sidebar -->
        <!--
        <div class="col-md-3 col-lg-2 sidebar p-0">
            <div class="p-3">
                <h4>Perpustakaan</h4>
                <p class="text-muted">Admin Dashboard</p>
            </div>
            <nav>
                <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="kelola_buku.php"><i class="fas fa-book"></i> Kelola Buku</a>
                <a href="kelola_anggota.php"><i class="fas fa-users"></i> Kelola Anggota</a>
                <a href="kelola_peminjaman.php"><i class="fas fa-hand-holding"></i> Peminjaman</a>
                <a href="kelola_reservasi.php" class="active"><i class="fas fa-calendar-check"></i> Reservasi</a>
                <a href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
                <a href="logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>
        -->
        <!-- Main Content -->
        <!-- Sesuaikan col-md dan col-lg menjadi 12 karena sidebar dihapus -->
        <div class="col-12 p-4 content-push-bottom">
            <h2 class="mb-4">Kelola Reservasi</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Anggota</th>
                                    <th>Buku</th>
                                    <th>Tanggal Reservasi</th>
                                    <th>Tanggal Pengambilan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['judul']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_reservasi'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_pengambilan'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            if($row['status'] == 'Menunggu') echo 'warning';
                                            elseif($row['status'] == 'Dikonfirmasi') echo 'success';
                                            else echo 'danger';
                                        ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($row['status'] == 'Menunggu'): ?>
                                            <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Konfirmasi</a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Tolak</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Navbar -->
<nav class="bottom-navbar">
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <a href="dashboard.php" class="nav-link"><i class="fas fa-home d-block"></i> Dashboard</a>
            </div>
            <div class="col text-center">
                <a href="kelolabuku.php" class="nav-link"><i class="fas fa-book d-block"></i> Buku</a>
            </div>
            <div class="col text-center">
                <a href="kelolaanggota.php" class="nav-link"><i class="fas fa-users d-block"></i> Anggota</a>
            </div>
            <div class="col text-center">
                <a href="kelolapeminjaman.php" class="nav-link"><i class="fas fa-hand-holding d-block"></i> Pinjam</a>
            </div>
            <div class="col text-center">
                <a href="kelolareservasi.php" class="nav-link active"><i class="fas fa-calendar-check d-block"></i> Reservasi</a>
            </div>
             <div class="col text-center">
                <a href="laporan.php" class="nav-link"><i class="fas fa-chart-bar d-block"></i> Laporan</a>
            </div>
             <div class="col text-center">
                <a href="logout.php" class="nav-link" id="logout-link-bottom"><i class="fas fa-sign-out-alt d-block"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sesuaikan event listener logout untuk link di bottom navbar
    document.getElementById('logout-link-bottom').addEventListener('click', function(e) {
        if(!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
    
    // Hapus event listener lama jika masih ada
    var oldLogoutLink = document.getElementById('logout-link');
    if (oldLogoutLink) {
        oldLogoutLink.removeEventListener('click', arguments.callee);
    }
</script>
</body>
</html> 