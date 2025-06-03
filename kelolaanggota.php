<?php
include 'koneksi.php';

// Ambil semua data anggota
$query = "SELECT id_user, nama, alamat FROM users ORDER BY id_user ASC"; // Urutkan berdasarkan id_user
$result = mysqli_query($conn, $query);

// Periksa apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// Proses hapus anggota
if (isset($_POST['hapus'])) {
    $id_user = intval($_POST['id_user']);
    $query_hapus = "DELETE FROM users WHERE id_user = $id_user";
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('Anggota berhasil dihapus!');window.location='kelolabuku.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus anggota: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Anggota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f2f2f2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: rgb(255, 196, 33);
            color: white;
        }
        .form-container {
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(255, 196, 33);
        }
        .form-container input, .form-container button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .action-buttons form {
            display: inline-block;
        }
        .action-buttons button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>

<h1>Kelola Anggota</h1>

<!-- Daftar Anggota -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($anggota = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($anggota['id_user']) ?></td>
                <td><?= htmlspecialchars($anggota['nama']) ?></td>
                <td><?= htmlspecialchars($anggota['alamat']) ?></td>
                <td class="action-buttons">
                    <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                        <input type="hidden" name="id_user" value="<?= $anggota['id_user'] ?>">
                        <button type="submit" name="hapus" class="delete-btn">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Tambahkan tombol untuk kembali ke halaman Kelola Buku -->
<div style="margin-top: 20px;">
    <a href="kelolabuku.php" style="text-decoration: none;">
        <button style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Kembali ke Kelola Buku
        </button>
    </a>
</div>

</body>
</html>