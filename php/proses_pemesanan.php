<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['name'];
    $telepon = $_POST['phone'];
    $jenis_layanan = $_POST['service'];
    $jenis_sepatu = $_POST['shoe-type'];
    $ukuran_sepatu = $_POST['size'];
    $alamat = $_POST['address'];
    $kota = $_POST['city'];
    $kecamatan = $_POST['district'];
    $kode_pos = $_POST['postal-code'];
    $catatan = $_POST['notes'];
    
    // Tentukan harga berdasarkan jenis layanan
    $harga = 0;
    switch ($jenis_layanan) {
        case 'deep-clean': $harga = 35000; break;
        case 'fast-clean': $harga = 15000; break;
        case 'repaint': $harga = 150000; break;
        case 'unyellowing': $harga = 45000; break;
        case 'repair-sol': $harga = 70000; break;
        case 'whitening': $harga = 50000; break;
        case 'leather-care': $harga = 40000; break;
        case 'reglue': $harga = 70000; break;
    }

    // Query untuk menyimpan data ke database
    $query = "INSERT INTO pesanan_sepatu (nama_pelanggan, no_telepon, jenis_layanan, jenis_sepatu, ukuran_sepatu, alamat_lengkap, kota, kecamatan, kode_pos, catatan, harga) 
              VALUES ('$nama', '$telepon', '$jenis_layanan', '$jenis_sepatu', '$ukuran_sepatu', '$alamat', '$kota', '$kecamatan', '$kode_pos', '$catatan', '$harga')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Pesanan berhasil dikirim! Kurir kami akan segera menghubungi Anda.'); window.location.href = '../cuci.html';</script>";
    } else {
        echo "<script>alert('Gagal mengirim pesanan: " . $conn->error . "'); window.history.back();</script>";
    }

    $conn->close();
}
?>
