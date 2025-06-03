<?php
session_start();

// Cek apakah user sudah login, jika ya redirect ke dashboard
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'member') {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang Anggota - Sistem Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            text-align: center;
        }
        .jumbotron {
            background-color: #ffffff;
            padding: 4rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .jumbotron h1 {
            color: #4e73df;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .jumbotron p {
            color: #6e707e;
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            transition: background-color 0.15s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #224abe;
            border-color: #1e3c90;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1>Selamat Datang Anggota Perpustakaan</h1>
            <p>Silakan login untuk mengakses layanan perpustakaan.</p>
            <a href="login.php" class="btn btn-primary btn-lg">Login Anggota</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
