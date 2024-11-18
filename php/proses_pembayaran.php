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

    // Simulasi nomor VA (dapat diganti dengan API bank)
    $va_number = "BRI" . rand(1000000000, 9999999999);

    // Simpan data ke database
    $sql = "INSERT INTO pesanan 
            (nama, telepon, merk_sepatu, alamat, keterangan, jenis_layanan, tagihan, metode_pembayaran, va_number, status_pembayaran)
            VALUES 
            ('$nama', '$telepon', '$merk_sepatu', '$alamat', '$keterangan', '$jenis_layanan', '$tagihan', '$metode_pembayaran', '$va_number', 'pending')";

    if ($conn->query($sql) === TRUE) {
        // Redirect ke halaman VA pembayaran
        header("Location: va_pembayaran.php?va=$va_number&tagihan=$tagihan");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
