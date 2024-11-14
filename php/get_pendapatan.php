<?php
include 'database.php'; // Menghubungkan ke database

header('Content-Type: application/json'); // Set header untuk JSON

// Mengambil total pendapatan
$query_pendapatan = "SELECT SUM(Total_Tagihan) AS total_pendapatan FROM pesanan";
$result_pendapatan = mysqli_query($conn, $query_pendapatan);

$response = array('status' => 'error', 'data' => 0); // Inisialisasi respon default
if ($result_pendapatan) {
    $data_pendapatan = mysqli_fetch_assoc($result_pendapatan);
    $total_pendapatan = $data_pendapatan['total_pendapatan'] ?? 0;
    $response = array('status' => 'success', 'data' => $total_pendapatan);
}

// Tutup koneksi
mysqli_close($conn);

// Mengembalikan response dalam format JSON
echo json_encode($response);
