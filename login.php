<?php
session_start();
include 'koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = $_POST['password'];

    // Ambil user dengan role staff
    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // Cek password (plain atau md5, sesuaikan dengan sistem Anda)
        if ($user['password'] === $password_input || $user['password'] === md5($password_input)) {
            // Set session staff
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            session_commit();

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan atau bukan staff!";
    }   
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Perpustakaan</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #fff;
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 320px;
            padding: 32px 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            background: #fff;
            text-align: center;
        }
        .logo {
            width: 100px;
            height: 100px;
            background: #e0e0e0;
            border-radius: 50%;
            margin: 0 auto 24px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #888;
            overflow: hidden;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px 0;
            background: #222;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #444;
        }
    </style>
</head>
<body>
    <form class="login-container" method="POST">
        <div class="logo"><img src="logobuku.png" alt="logobuku"></div>
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign in</button>
    </form>
</body>
</html>