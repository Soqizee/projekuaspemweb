<?php
include 'koneksi.php';

// Periksa apakah parameter id_anggota ada
if (!isset($_GET['id_anggota']) || empty($_GET['id_anggota'])) {
    header("Location: kelolaanggota.php");
    exit();
}

$id_anggota = intval($_GET['id_anggota']);

// Ambil data anggota berdasarkan id_anggota
$query = "SELECT id_anggota, nama, alamat FROM anggota WHERE id_anggota = $id_anggota";
$result = mysqli_query($conn, $query);

// Periksa apakah data anggota ditemukan
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: kelolaanggota.php");
    exit();
}

$anggota = mysqli_fetch_assoc($result);

// Proses hapus anggota
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query_delete = "DELETE FROM anggota WHERE id_anggota = $id_anggota";
    if (mysqli_query($conn, $query_delete)) {
        echo "<script>alert('Anggota berhasil dihapus!');window.location='kelolaanggota.php';</script>";
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
    <title>Hapus Anggota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f2f2f2;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #f44336;
            color: white;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<h1>Hapus Anggota</h1>

<div class="form-container">
    <p><strong>ID Anggota:</strong> <?= htmlspecialchars($anggota['id_anggota']) ?></p>
    <p><strong>Nama:</strong> <?= htmlspecialchars($anggota['nama']) ?></p>
    <p><strong>Alamat:</strong> <?= htmlspecialchars($anggota['alamat']) ?></p>
    <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
        <button type="submit">Hapus Anggota</button>
    </form>
</div>

</body>
</html>