<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if($user['role'] == 'admin') {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>
                alert('Hanya admin yang bisa login ke dashboard!');
                window.location='login.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Username atau password salah!');
            window.location='login.php';
        </script>";
    }
} else {
    header("Location: login.php");
    exit();
}
?> 