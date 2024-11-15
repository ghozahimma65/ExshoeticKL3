<?php
session_start();

// Konfigurasi koneksi ke database
$host = 'localhost';
$dbname = 'exshoetic_db';
$username = 'root';
$password = '';

try {
    // Koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa apakah pengguna ada di tabel `admin`
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE Username = :username AND Password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Jika login berhasil, simpan session dan arahkan ke dashboard
        $_SESSION['username'] = $user['Username'];
        header("Location: admin.php"); // Ganti dengan file dashboard admin Anda
        exit();
    } else {
        // Jika login gagal, kirim pesan kesalahan
        $error_message = "Username atau password salah!";
    }
}
?>