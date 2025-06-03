<?php
header('Content-Type: application/json');

include '../koneksi.php'; // Sesuaikan path koneksi.php jika berbeda

$response = array();

// Cek koneksi database
if (!$conn) {
    $response['status'] = 'error';
    $response['message'] = 'Database connection failed: ' . mysqli_connect_error();
    echo json_encode($response);
    exit(); // Hentikan eksekusi jika koneksi gagal
}

// Hanya izinkan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari body request (misal: form-data atau raw JSON)
    // Jika menggunakan form-data dari Android
    $nama = isset($_POST['nama']) ? mysqli_real_escape_string($conn, $_POST['nama']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $nim = isset($_POST['nim']) ? mysqli_real_escape_string($conn, $_POST['nim']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : ''; // Password sebaiknya di-hash
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($conn, $_POST['jenis_kelamin']) : '';
    $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($conn, $_POST['alamat']) : '';

    // Validasi data dasar
    if (empty($nama) || empty($email) || empty($nim) || empty($password) || empty($jenis_kelamin) || empty($alamat)) {
        $response['status'] = 'error';
        $response['message'] = 'Semua field harus diisi!';
    } else {
        // Cek apakah email atau NIM sudah terdaftar
        $check_query = "SELECT id_user FROM users WHERE email='$email' OR nim='$nim' LIMIT 1";
        
        // Eksekusi query cek dan tambahkan penanganan error
        $check_result = mysqli_query($conn, $check_query);

        if ($check_result === false) {
             $response['status'] = 'error';
             $response['message'] = 'Database query failed: ' . mysqli_error($conn);
        } elseif (mysqli_num_rows($check_result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Email atau NIM sudah terdaftar.';
        } else {
            // Data valid, masukkan ke database
            // Password sebaiknya di-hash sebelum disimpan! Ini contoh sederhana.
            $hashed_password = md5($password); // Contoh sederhana, gunakan password_hash di produksi!

            $insert_query = "INSERT INTO users (nama, email, nim, password, role, jenis_kelamin, alamat, status_akun) 
                             VALUES ('$nama', '$email', '$nim', '$hashed_password', 'anggota', '$jenis_kelamin', '$alamat', 'Tidak Aktif')";

            // Eksekusi query insert dan tambahkan penanganan error
            if (mysqli_query($conn, $insert_query)) {
                $response['status'] = 'success';
                $response['message'] = 'Registrasi berhasil. Akun Anda akan diaktifkan oleh admin.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Registrasi gagal: ' . mysqli_error($conn);
            }
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode request tidak diizinkan.';
}

echo json_encode($response);

mysqli_close($conn);
?> 