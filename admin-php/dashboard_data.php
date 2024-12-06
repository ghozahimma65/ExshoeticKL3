<?php
// Koneksi ke database
include('database.php');

// Query untuk menghitung total treatment selesai
$query_total_treatment_selesai = "SELECT COUNT(*) AS total_treatment_selesai FROM pesanan WHERE Status = 'Selesai'";
$result_treatment_selesai = mysqli_query($conn, $query_total_treatment_selesai);
$data_treatment_selesai = mysqli_fetch_assoc($result_treatment_selesai);
$total_treatment_selesai = $data_treatment_selesai['total_treatment_selesai'];

// Return data sebagai array
$data = [
    'total_treatment_selesai' => $total_treatment_selesai,
];

echo json_encode($data); // Mengirim data dalam format JSON
?>
