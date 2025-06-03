<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id_peminjaman'])) {
    $id_peminjaman = intval($_GET['id_peminjaman']);
    // Update status peminjaman
    $query = "UPDATE peminjaman SET status='Dikembalikan' WHERE id_peminjaman=$id_peminjaman";
    mysqli_query($conn, $query);
}
header("Location: kelola_peminjaman.php");
exit(); 