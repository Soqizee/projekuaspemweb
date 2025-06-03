<?php
include 'koneksi.php';

// Periksa apakah parameter id_buku ada
if (!isset($_GET['id_buku'])) {
    echo "ID Buku tidak ditemukan!";
    exit;
}

// Ambil ID Buku dari parameter
$id_buku = intval($_GET['id_buku']);

// Ambil data buku berdasarkan ID
$query = $conn->prepare("SELECT * FROM buku WHERE id_buku = ?");
$query->bind_param("i", $id_buku);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "Buku tidak ditemukan!";
    exit;
}

$buku = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
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
        .form-container input, .form-container textarea, .form-container button {
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
    </style>
</head>
<body>

<h1>Edit Buku</h1>

<div class="form-container">
    <form action="editbuku.php" method="POST">
        <input type="hidden" name="id_buku" value="<?= htmlspecialchars($buku['id_buku']) ?>">
        <input type="text" name="judul" placeholder="Judul Buku" value="<?= htmlspecialchars($buku['judul']) ?>" required>
        <input type="text" name="pengarang" placeholder="Pengarang" value="<?= htmlspecialchars($buku['pengarang']) ?>" required>
        <textarea name="deskripsi" placeholder="Deskripsi Buku" rows="4" required><?= htmlspecialchars($buku['deskripsi']) ?></textarea>
        <input type="number" name="stok" placeholder="Stok Buku" value="<?= htmlspecialchars($buku['stok']) ?>" required>
        <button type="submit">Simpan Perubahan</button> 
    </form>
</div>

</body>
</html>