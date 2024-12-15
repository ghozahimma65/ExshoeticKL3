<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'mifmyho2_exshoetic';
$password = '@Mif2024';
$dbname = 'mifmyho2_exshoetic';

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error); // Menampilkan pesan error jika koneksi gagal
}

// Uncomment baris di bawah ini jika ingin menampilkan pesan untuk debug/testing
// echo "Koneksi berhasil!";
?>
