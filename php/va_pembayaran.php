<?php
// Konfigurasi database
$host = 'localhost';
$user = 'root'; // Ganti jika username MySQL Anda berbeda
$password = ''; // Ganti jika ada password MySQL
$database = 'exshoetic_db';

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses data dari form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = htmlspecialchars($_POST['nama']);
    $telepon = htmlspecialchars($_POST['telepon']);
    $merk_sepatu = htmlspecialchars($_POST['merk']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    $jenis_layanan = htmlspecialchars($_POST['jenis']);
    $tagihan = htmlspecialchars($_POST['tagihan']);
    $metode_pembayaran = htmlspecialchars($_POST['metode']);

        // Nomor VA (dapat menggunakan API atau algoritma lain)
        $va_number = "BRI" . rand(1000000000, 9999999999);

        // Simulasikan proses penyimpanan data ke database (pastikan proses pemesanan berhasil)
        $status = "success";  // Misal jika berhasil
    
        // Kirimkan respons JSON
        echo json_encode([
            'status' => $status,
            'message' => 'Pesanan berhasil diproses.',
            'va_number' => $va_number,
            'total_bill' => $total_bill
        ]);
    }
    ?>