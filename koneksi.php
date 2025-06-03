<?php
$host = 'btwrtgmmj2ldjwjweeth-mysql.services.clever-cloud.com';
$user = 'unaeydi5hto00naw'; // sesuaikan
$pass = 'ZoqQXniiUMXu1byh0FZV';     // sesuaikan
$db   = 'btwrtgmmj2ldjwjweeth'; // ganti nama database

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
