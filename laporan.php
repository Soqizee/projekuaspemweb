<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['role']) || !($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff')) {
    header("Location: login.php");
    exit();
}

// Statistik
$total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
$total_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='anggota'"))['total'];
$total_peminjaman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman"))['total'];
$total_pengembalian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status='Dikembalikan'"))['total'];
$total_reservasi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM reservasi"))['total'];

// Laporan peminjaman
$query = "SELECT p.*, u.nama, b.judul FROM peminjaman p JOIN users u ON p.id_user = u.id_user JOIN buku b ON p.id_buku = b.id_buku ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Admin Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
            padding-bottom: 70px; /* Meningkatkan padding bawah untuk memastikan konten tidak tertutup navbar */    
        }
         /* Atur nav-link active di bottom navbar */
        .bottom-navbar .nav-link.active {
             font-weight: bold; /* Atau style lain untuk menandai aktif */
        }.stat-card {
            padding:15px;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
         .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-12 p-4 content-push-bottom">
            <h2 class="mb-4">Laporan Perpustakaan</h2>
            <!-- Statistik -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="stat-card bg-primary text-white text-center">
                        <i class="fas fa-book"></i>
                        <h4><?php echo $total_buku; ?></h4>
                        <p>Buku</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card bg-info text-white text-center">
                        <i class="fas fa-users"></i>
                        <h4><?php echo $total_anggota; ?></h4>
                        <p>Anggota</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card bg-success text-white text-center">
                        <i class="fas fa-hand-holding"></i>
                        <h4><?php echo $total_peminjaman; ?></h4>
                        <p>Peminjaman</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card bg-warning text-white text-center">
                        <i class="fas fa-undo"></i>
                        <h4><?php echo $total_pengembalian; ?></h4>
                        <p>Pengembalian</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card bg-secondary text-white text-center">
                        <i class="fas fa-calendar-check"></i>
                        <h4><?php echo $total_reservasi; ?></h4>
                        <p>Reservasi</p>
                    </div>
                </div>
            </div>
            <!-- Tabel Laporan Peminjaman -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Laporan Peminjaman</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Anggota</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['judul']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['batas_pengembalian'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'Dipinjam' ? 'primary' : ($row['status'] == 'Dikembalikan' ? 'success' : 'warning'); ?>">
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
                <a href="laporan.php" class="nav-link active"><i class="fas fa-chart-bar d-block"></i> Laporan</a>
            </div>
             <div class="col text-center">
                <a href="logout.php" class="nav-link" id="logout-link-bottom"><i class="fas fa-sign-out-alt d-block"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('logout-link-bottom').addEventListener('click', function(e) {
        if(!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
</script>
</body>
</html> 