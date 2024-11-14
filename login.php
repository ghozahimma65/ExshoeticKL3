<?php
session_start();

// Konfigurasi koneksi ke database
$host = 'localhost';
$dbname = 'exshoetic_db';
$username = 'root';
$password = 'putritrisula14';

try {
    $conn = new PDO("mysql:host=localhost;dbname=exshoetic_db", "root", "putritrisula14");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM admin WHERE Username = :username AND Password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username, 'password' => $password]);

    if ($stmt->rowCount() > 0) {
        // login berhasil
        header("Location: admin.php");
        exit();
    } else {
        echo "Username atau Password salah!";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username && $password) {
    $sql = "SELECT * FROM admin WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // login berhasil
        header("Location: admin.php");
        exit();
    } else {
        echo "Username atau Password salah!";
    }
} else {
    echo "Masukkan username dan password!";
}

?>