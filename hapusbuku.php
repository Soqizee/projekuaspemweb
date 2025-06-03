<?php
session_start();
include 'koneksi.php';

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id_buku'])) {
    $id_buku = intval($_GET['id_buku']);
    // Hapus cover jika ada
    $q = mysqli_query($conn, "SELECT cover FROM buku WHERE id_buku=$id_buku");
    $d = mysqli_fetch_assoc($q);
    if($d && $d['cover'] && file_exists($d['cover'])) {
        unlink($d['cover']);
    }
    // Hapus data buku
    $query = "DELETE FROM buku WHERE id_buku=$id_buku";
    mysqli_query($conn, $query);
}
header("Location: kelolabuku.php");
exit(); 