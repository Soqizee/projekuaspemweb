<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "perpus_android"; // Ganti jika nama database berbeda

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Koneksi database gagal: ' . $conn->connect_error
    ]));
}

// Terima user_id dari parameter GET
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Tambahkan logging: Log User ID yang diterima
file_put_contents('borrowed_books_log.txt', "User ID received: " . $user_id . "\n", FILE_APPEND);

// Validasi user_id
if ($user_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User ID tidak valid.'
    ]);
    $conn->close();
    exit();
}

// Query untuk mengambil buku yang sedang dipinjam oleh user tertentu
$sql = "SELECT 
            b.id_buku, 
            b.judul, 
            b.pengarang, 
            b.cover, 
            p.tanggal_pinjam, 
            p.batas_pengembalian,
            p.status,
            p.id_peminjaman
        FROM peminjaman p
        JOIN buku b ON p.id_buku = b.id_buku
        WHERE p.id_user = ? AND p.status = 'Dipinjam'";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error preparing statement: ' . $conn->error
    ]);
    $conn->close();
    exit();
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error executing query: ' . $stmt->error
    ]);
    $stmt->close();
    $conn->close();
    exit();
}

$result = $stmt->get_result();

// Tambahkan logging: Log jumlah hasil
file_put_contents('borrowed_books_log.txt', "Number of rows found: " . $result->num_rows . "\n", FILE_APPEND);

$borrowed_books = array();
while ($row = $result->fetch_assoc()) {
    // Tambahkan URL lengkap untuk cover
    $row['cover'] = 'http://10.0.2.2/uaspbbsem2/uploads/' . $row['cover']; // Sesuaikan IP jika perlu
    $borrowed_books[] = $row;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Data buku pinjaman berhasil diambil',
    'data' => $borrowed_books
]);

$stmt->close();
$conn->close();
?> 