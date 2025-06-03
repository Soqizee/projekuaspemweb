<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "perpus_android";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Koneksi database gagal: ' . $conn->connect_error
    ]));
}

// Query untuk mengambil semua buku, termasuk stok dan status
$sql = "SELECT id_buku, judul, pengarang, stok, cover, 
        CASE WHEN stok > 0 THEN 'Tersedia' ELSE 'Tidak Tersedia' END as status 
        FROM buku";
$result = $conn->query($sql);

if ($result) {
    $books = array();
    while ($row = $result->fetch_assoc()) {
        // Tambahkan URL lengkap untuk cover
        $row['cover'] = 'http://10.0.2.2/uaspbbsem2/uploads/' . $row['cover'];
        $books[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Data buku berhasil diambil',
        'data' => $books
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil data buku: ' . $conn->error
    ]);
}

$conn->close();
?> 