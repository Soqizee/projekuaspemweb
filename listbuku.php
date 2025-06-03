<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../koneksi.php';

$query = "SELECT * FROM buku ORDER BY judul ASC";
$result = mysqli_query($koneksi, $query);

$buku = array();
while ($row = mysqli_fetch_assoc($result)) {
    $buku[] = array(
        'id' => $row['id'],
        'judul' => $row['judul'],
        'pengarang' => $row['pengarang'],
        'cover' => $row['cover'],
        'status' => $row['status']
    );
}

echo json_encode($buku);
?> 