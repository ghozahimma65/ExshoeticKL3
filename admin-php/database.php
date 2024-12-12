<?php
$host = 'localhost';
$user = 'root'; // Sesuaikan dengan username database Anda
$password = 'putritrisula14'; // Sesuaikan dengan password database Anda
$dbname = 'exshoetic_db'; // Nama database Anda

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
