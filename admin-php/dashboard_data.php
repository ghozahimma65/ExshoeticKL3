<?php
// Koneksi ke database
include('database.php');

// Query untuk menghitung total pesanan
$query_total_pesanan = "SELECT COUNT(*) AS total_pesanan FROM pesanan";
$result_pesanan = mysqli_query($conn, $query_total_pesanan);
$data_pesanan = mysqli_fetch_assoc($result_pesanan);
$total_pesanan = $data_pesanan['total_pesanan'];

// Query untuk menghitung total pendapatan
$query_total_pendapatan = "SELECT SUM(Total_Tagihan) AS total_pendapatan FROM pesanan";
$result_pendapatan = mysqli_query($conn, $query_total_pendapatan);
$data_pendapatan = mysqli_fetch_assoc($result_pendapatan);
$total_pendapatan = $data_pendapatan['total_pendapatan'] ?? 0;

// Format total pendapatan sebagai Rupiah
$total_pendapatan_formatted = "Rp " . number_format($total_pendapatan, 0, ',', '.');


// Query untuk menghitung total customer
$query_total_customer = "SELECT COUNT(DISTINCT Customer_ID) AS total_customer FROM customer";
$result_customer = mysqli_query($conn, $query_total_customer);
$data_customer = mysqli_fetch_assoc($result_customer);
$total_customer = $data_customer['total_customer'];

// Query untuk menghitung total treatment selesai
$query_total_treatment_selesai = "SELECT COUNT(*) AS total_treatment_selesai FROM pesanan WHERE Status = 'Selesai'";
$result_treatment_selesai = mysqli_query($conn, $query_total_treatment_selesai);
$data_treatment_selesai = mysqli_fetch_assoc($result_treatment_selesai);
$total_treatment_selesai = $data_treatment_selesai['total_treatment_selesai'];

// Query untuk menghitung total pengeluaran
$query_total_pengeluaran = "SELECT SUM(Jumlah_Harga) AS total_pengeluaran FROM pengeluaran";
$result_pengeluaran = mysqli_query($conn, $query_total_pengeluaran);
$data_pengeluaran = mysqli_fetch_assoc($result_pengeluaran);
$total_pengeluaran = $data_pengeluaran['total_pengeluaran'] ?? 0; // Pastikan jika NULL maka jadi 0

// Query data untuk grafik pesanan bulanan
$query_bulanan = "
    SELECT MONTH(tanggal_pesanan) AS bulan, COUNT(*) AS total 
    FROM pesanan 
    GROUP BY MONTH(tanggal_pesanan)";
$result_bulanan = $conn->query($query_bulanan);

$pesanan_bulanan = array_fill(1, 12, 0); // Isi awal dengan 0 untuk 12 bulan
while ($row = $result_bulanan->fetch_assoc()) {
    $pesanan_bulanan[(int)$row['bulan']] = (int)$row['total'];
}

// Return data sebagai array
$data = [
    'total_pesanan' => $total_pesanan,
    'total_pendapatan' => (int)$total_pendapatan, // Pastikan angka, tidak diformat di sini
    'total_customer' => $total_customer,
    'total_treatment_selesai' => $total_treatment_selesai,
    'pesanan_bulanan' => $pesanan_bulanan,
    'total_pengeluaran' => (int)$total_pengeluaran // Tambahkan pengeluaran
];

echo json_encode($data); // Mengirim data dalam format JSON
?>
