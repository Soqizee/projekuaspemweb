<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
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

// Terima data dari POST request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_peminjaman'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID peminjaman tidak ditemukan'
    ]);
    $conn->close();
    exit();
}

$id_peminjaman = intval($data['id_peminjaman']);

// Update status peminjaman
$sql = "UPDATE peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error preparing statement: ' . $conn->error
    ]);
    $conn->close();
    exit();
}

$stmt->bind_param("i", $id_peminjaman);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error executing query: ' . $stmt->error
    ]);
    $stmt->close();
    $conn->close();
    exit();
}

if ($stmt->affected_rows > 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Buku berhasil dikembalikan'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tidak ada peminjaman yang ditemukan'
    ]);
}

$stmt->close();
$conn->close();
?> 