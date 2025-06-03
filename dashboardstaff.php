<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: loginstaff.php");
    exit();
}

// Proses aksi
if (isset($_GET['konfirmasi_akun'])) {
    $id = intval($_GET['konfirmasi_akun']);
    mysqli_query($conn, "UPDATE users SET status_akun='Aktif' WHERE id_user=$id");
    header("Location: dashboardstaff.php");
    exit();
}
if (isset($_GET['hapus_akun'])) {
    $id = intval($_GET['hapus_akun']);
    mysqli_query($conn, "DELETE FROM users WHERE id_user=$id");
    header("Location: dashboardstaff.php");
    exit();
}
if (isset($_GET['setujui_pinjam'])) {
    $id = intval($_GET['setujui_pinjam']);
    mysqli_query($conn, "UPDATE peminjaman SET status='Disetujui' WHERE id_peminjaman=$id");
    header("Location: dashboardstaff.php");
    exit();
}
if (isset($_GET['tolak_pinjam'])) {
    $id = intval($_GET['tolak_pinjam']);
    mysqli_query($conn, "UPDATE peminjaman SET status='Ditolak' WHERE id_peminjaman=$id");
    header("Location: dashboardstaff.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e3f2fd, #f1f8e9);
            font-family: 'Segoe UI', sans-serif;
        }
        .dashboard-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 25px 30px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2196f3;
            color: white;
            padding: 12px 20px;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }
        .table thead {
            background-color: #e3f2fd;
        }
        h4 {
            margin-top: 40px;
            font-weight: 600;
            color: #333;
        }
        .btn-success {
            background-color: #43a047;
            border-color: #43a047;
        }
        .btn-warning {
            background-color: #fb8c00;
            border-color: #fb8c00;
        }
        .btn-danger {
            background-color: #e53935;
            border-color: #e53935;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="header">
        <h3>Dashboard Staff Perpustakaan</h3>
    </div>

    <div class="text-end mt-2">
        <small>Login sebagai: <strong><?= $_SESSION['email']; ?></strong></small>
    </div>

    <!-- Konfirmasi Akun -->
    <h4>Konfirmasi Akun Anggota</h4>
    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Status Akun</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $akun = mysqli_query($conn, "SELECT * FROM users WHERE status_akun='Pending'");
        if (mysqli_num_rows($akun) == 0) {
            echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada akun yang menunggu konfirmasi.</td></tr>";
        }
        while ($row = mysqli_fetch_assoc($akun)) {
            echo "<tr>
                <td>{$row['nama']}</td>
                <td>{$row['email']}</td>
                <td>{$row['status_akun']}</td>
                <td>
                    <a href='?konfirmasi_akun={$row['id_user']}' class='btn btn-sm btn-success'>Setujui</a>
                    <a href='?hapus_akun={$row['id_user']}' class='btn btn-sm btn-danger'>Hapus</a>
                </td>
              </tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Konfirmasi Peminjaman -->
    <h4>Konfirmasi Peminjaman Buku</h4>
    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Status</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $pinjam = mysqli_query($conn, "
            SELECT p.id_peminjaman, u.nama, b.judul, p.status
            FROM peminjaman p
            JOIN users u ON p.id_user = u.id_user
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.status='Menunggu'
        ");
        if (mysqli_num_rows($pinjam) == 0) {
            echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada peminjaman yang menunggu konfirmasi.</td></tr>";
        }
        while ($row = mysqli_fetch_assoc($pinjam)) {
            echo "<tr>
                <td>{$row['nama']}</td>
                <td>{$row['judul']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?setujui_pinjam={$row['id_peminjaman']}' class='btn btn-sm btn-success'>Setujui</a>
                    <a href='?tolak_pinjam={$row['id_peminjaman']}' class='btn btn-sm btn-warning'>Tolak</a>
                </td>
              </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
