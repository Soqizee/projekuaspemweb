<?php
session_start();
include 'koneksi.php';

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Cek apakah user sudah login dan adalah admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Proses tambah buku
if(isset($_POST['submit'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $pengarang = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stok = intval($_POST['stok']);
    $cover = '';

    // Upload cover jika ada
    if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        $filename = 'cover/' . uniqid() . '.' . $ext;
        if(move_uploaded_file($_FILES['cover']['tmp_name'], $filename)) {
            $cover = $filename;
        }
    }

    $query = "INSERT INTO buku (judul, pengarang, deskripsi, stok, cover) VALUES ('$judul', '$pengarang', '$deskripsi', $stok, '$cover')";
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Buku berhasil ditambahkan!');window.location='kelola_buku.php';</script>";
        exit();
    } else {
        $error = 'Gagal menambah buku!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - Admin Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #495057;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar p-0">
            <div class="p-3">
                <h4>Perpustakaan</h4>
                <p class="text-muted">Admin Dashboard</p>
            </div>
            <nav>
                <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="kelola_buku.php" class="active"><i class="fas fa-book"></i> Kelola Buku</a>
                <a href="kelola_anggota.php"><i class="fas fa-users"></i> Kelola Anggota</a>
                <a href="kelola_peminjaman.php"><i class="fas fa-hand-holding"></i> Peminjaman</a>
                <a href="kelola_reservasi.php"><i class="fas fa-calendar-check"></i> Reservasi</a>
                <a href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>
                <a href="logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4">Tambah Buku</h2>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="pengarang" class="form-label">Pengarang</label>
                            <input type="text" class="form-control" id="pengarang" name="pengarang" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="cover" class="form-label">Cover Buku (opsional)</label>
                            <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="kelola_buku.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('logout-link').addEventListener('click', function(e) {
        if(!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
</script>
</body>
</html> 