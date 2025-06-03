<?php
session_start();
include 'koneksi.php';

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal:" . mysqli_connect_error());
}
  
// Cek apakah user sudah login dan adalah admin
// if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     header("Location: loginpage.php");
//     exit();
// }

// Mengambil statistik
$query_buku = "SELECT COUNT(*) as total_buku FROM buku";
$query_peminjaman = "SELECT COUNT(*) as total_pinjam FROM peminjaman WHERE status='Dipinjam'";
$query_anggota = "SELECT COUNT(*) as total_anggota FROM users WHERE role='anggota'";
$query_reservasi = "SELECT COUNT(*) as total_reservasi FROM reservasi WHERE status='Menunggu'";

$result_buku = mysqli_query($conn, $query_buku);
$result_peminjaman = mysqli_query($conn, $query_peminjaman);
$result_anggota = mysqli_query($conn, $query_anggota);
$result_reservasi = mysqli_query($conn, $query_reservasi);

$total_buku = mysqli_fetch_assoc($result_buku)['total_buku'];
$total_pinjam = mysqli_fetch_assoc($result_peminjaman)['total_pinjam'];
$total_anggota = mysqli_fetch_assoc($result_anggota)['total_anggota'];
$total_reservasi = mysqli_fetch_assoc($result_reservasi)['total_reservasi'];

// Mengambil data peminjaman terbaru
$query_pinjaman_terbaru = "SELECT p.*, u.nama, b.judul 
                          FROM peminjaman p 
                          JOIN users u ON p.id_user = u.id_user 
                          JOIN buku b ON p.id_buku = b.id_buku 
                          ORDER BY p.tanggal_pinjam DESC LIMIT 5";
$result_pinjaman_terbaru = mysqli_query($conn, $query_pinjaman_terbaru);

// Mengambil data reservasi yang menunggu
$query_reservasi_menunggu = "SELECT r.*, u.nama, b.judul 
                            FROM reservasi r 
                            JOIN users u ON r.id_user = u.id_user 
                            JOIN buku b ON r.id_buku = b.id_buku 
                            WHERE r.status='Menunggu' 
                            ORDER BY r.tanggal_reservasi ASC";
$result_reservasi_menunggu = mysqli_query($conn, $query_reservasi_menunggu);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan</title>
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
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .container-fluid{
            margin-bottom: 70px;
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
                    <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                    <a href="kelolabuku.php"><i class="fas fa-book"></i> Kelola Buku</a>
                    <a href="kelola_anggota.php"><i class="fas fa-users"></i> Kelola Anggota</a>
                    <a href="kelola_peminjaman.php"><i class="fas fa-hand-holding"></i> Peminjaman</a>
                    <a href="kelola_reservasi.php"><i class="fas fa-calendar-check"></i> Reservasi</a>
                    <a href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
                    <a href="logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>
            -->

            <!-- Main Content -->
            <!-- Sesuaikan col-md dan col-lg menjadi 12 karena sidebar dihapus -->
            <div class="col-12 p-4 content-push-bottom">
                <h2 class="mb-4">Dashboard</h2>
                
                <!-- Statistik -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary text-white">
                            <i class="fas fa-book"></i>
                            <h3><?php echo $total_buku; ?></h3>
                            <p>Total Buku</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success text-white">
                            <i class="fas fa-hand-holding"></i>
                            <h3><?php echo $total_pinjam; ?></h3>
                            <p>Peminjaman Aktif</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info text-white">
                            <i class="fas fa-users"></i>
                            <h3><?php echo $total_anggota; ?></h3>
                            <p>Total Anggota</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning text-white">
                            <i class="fas fa-clock"></i>
                            <h3><?php echo $total_reservasi; ?></h3>
                            <p>Reservasi Menunggu</p>
                        </div>
                    </div>
                </div>

                <!-- Peminjaman Terbaru -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Peminjaman Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Anggota</th>
                                        <th>Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Batas Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($result_pinjaman_terbaru)): ?>
                                    <tr>
                                        <td><?php echo $row['nama']; ?></td>
                                        <td><?php echo $row['judul']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['batas_pengembalian'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $row['status'] == 'Dipinjam' ? 'primary' : 'success'; ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Reservasi Menunggu -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Reservasi Menunggu Konfirmasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Anggota</th>
                                        <th>Buku</th>
                                        <th>Tanggal Reservasi</th>
                                        <th>Tanggal Pengambilan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($result_reservasi_menunggu)): ?>
                                    <tr>
                                        <td><?php echo $row['nama']; ?></td>
                                        <td><?php echo $row['judul']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_reservasi'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_pengambilan'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-success">Konfirmasi</button>
                                            <button class="btn btn-sm btn-danger">Tolak</button>
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
                    <a href="dashboard.php" class="nav-link active"><i class="fas fa-home d-block"></i> Dashboard</a>
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
                    <a href="kelolareservasi.php" class="nav-link"><i class="fas fa-calendar-check d-block"></i> Reservasi</a>
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