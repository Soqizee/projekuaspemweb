<?php
ini_set('display_errors', 0); // Matikan display error ke output
ini_set('log_errors', 1); // Aktifkan logging error PHP
ini_set('error_log', __DIR__ . '/borrow_book_php_error.log'); // Arahkan error log ke file spesifik

ob_start(); // Start output buffering
require_once __DIR__ . '/Konfigurasi.php';

header('Content-Type: application/json');

// Menerima data JSON dari body request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$logFile = __DIR__ . '/borrow_book_activity.log'; // File log aktivitas PHP

// Fungsi untuk menulis ke log aktivitas
function writeToActivityLog($message, $logFile) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $message . "\n", FILE_APPEND);
}

writeToActivityLog('Received request. Raw JSON: ' . $json, $logFile); // Log data yang diterima

$response = array();

if ($data === null) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid JSON input.';
    writeToActivityLog('Error: Invalid JSON input.', $logFile); // Log error
    echo json_encode($response);
    ob_end_flush(); // Flush output buffer
    exit;
}

$userId = $data['user_id'] ?? null;
$idBuku = $data['id_buku'] ?? null;

writeToActivityLog('Parsed data - User ID: ' . ($userId ?? 'null') . ', Buku ID: ' . ($idBuku ?? 'null'), $logFile); // Log data yang diparsing

// Validasi input
if ($userId === null || $idBuku === null) {
    $response['status'] = 'error';
    $response['message'] = 'User ID and Book ID are required.';
    writeToActivityLog('Error: User ID or Book ID missing.', $logFile); // Log error
    echo json_encode($response);
    ob_end_flush(); // Flush output buffer
    exit;
}

// Buat koneksi database
$koneksi = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if ($koneksi->connect_error) {
    $response['status'] = 'error';
    $response['message'] = 'Database connection failed: ' . $koneksi->connect_error;
    writeToActivityLog('Error: Database connection failed: ' . $koneksi->connect_error, $logFile); // Log error
    echo json_encode($response);
    ob_end_flush(); // Flush output buffer
    exit;
}

// Cek apakah buku tersedia dan stok > 0
$stmt = $koneksi->prepare("SELECT stok FROM buku WHERE id_buku = ?");
$stmt->bind_param("i", $idBuku);
$stmt->execute();
$result = $stmt->get_result();

writeToActivityLog('Executing query: SELECT stok FROM buku WHERE id_buku = ' . $idBuku, $logFile); // Log query

if ($result->num_rows === 0) {
    $response['status'] = 'error';
    $response['message'] = 'Book not found.';
    writeToActivityLog('Error: Book not found with ID ' . $idBuku, $logFile); // Log error
    echo json_encode($response);
    $stmt->close();
    $koneksi->close();
    ob_end_flush(); // Flush output buffer
    exit;
}

$row = $result->fetch_assoc();
$stok = $row['stok'];
$stmt->close();

writeToActivityLog('Book ID ' . $idBuku . ' found. Current stock: ' . $stok, $logFile); // Log stok

if ($stok <= 0) {
    $response['status'] = 'error';
    $response['message'] = 'Book is out of stock.';
    writeToActivityLog('Error: Book ID ' . $idBuku . ' is out of stock.', $logFile); // Log error
    echo json_encode($response);
    $koneksi->close();
    ob_end_flush(); // Flush output buffer
    exit;
}

// Mulai transaksi
$koneksi->begin_transaction();
writeToActivityLog('Starting transaction.', $logFile); // Log transaksi

try {
    // Tambahkan data peminjaman ke tabel peminjaman
    $tanggalPinjam = date('Y-m-d');
    $batasPengembalian = date('Y-m-d', strtotime($tanggalPinjam . ' + 7 days')); // Contoh: 7 hari peminjaman
    $statusPeminjaman = 'Dipinjam';

    $stmt = $koneksi->prepare("INSERT INTO peminjaman (id_user, id_buku, tanggal_pinjam, batas_pengembalian, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $userId, $idBuku, $tanggalPinjam, $batasPengembalian, $statusPeminjaman);
    $stmt->execute();
    $stmt->close();

    writeToActivityLog('Inserted into peminjaman: User ID ' . $userId . ', Buku ID ' . $idBuku, $logFile); // Log insert

    // Kurangi stok buku di tabel buku
    $stmt = $koneksi->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?");
    $stmt->bind_param("i", $idBuku);
    $stmt->execute();
    $stmt->close();

    writeToActivityLog('Updated stok for Buku ID ' . $idBuku, $logFile); // Log update stok

    // Commit transaksi
    $koneksi->commit();
    writeToActivityLog('Transaction committed.', $logFile); // Log commit

    $response['status'] = 'success';
    $response['message'] = 'Book borrowed successfully!';
    writeToActivityLog('Success: Book borrowed successfully!', $logFile); // Log success
    
} catch (mysqli_sql_exception $exception) {
    // Rollback transaksi jika terjadi error
    $koneksi->rollback();
    $response['status'] = 'error';
    $response['message'] = 'Failed to borrow book: ' . $exception->getMessage();
    writeToActivityLog('Error during transaction: ' . $exception->getMessage(), $logFile); // Log error
}

$koneksi->close();

echo json_encode($response);
ob_end_flush(); // Flush output buffer
?> 