<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id_user'])) {
    $id_user = intval($_GET['id_user']);
    $query = "DELETE FROM users WHERE id_user=$id_user AND role='anggota'";
    mysqli_query($conn, $query);
}
header("Location: kelolaanggota.php");
exit(); 