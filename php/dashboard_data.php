<?php
header('Content-Type: application/json');
include 'database.php'; // Pastikan file ini sudah berisi koneksi database

// Array untuk menyimpan hasil data
$response = [
    "daily_orders" => [],
    "service_distribution" => []
];

// 1. Mengambil Data Pesanan Harian
$queryDailyOrders = "
    SELECT DATE(tanggal_pesan) AS tanggal, 
           COUNT(*) AS jumlah_pesanan, 
           SUM(harga) AS total_pendapatan
    FROM pesanan_sepatu
    GROUP BY DATE(tanggal_pesan)
    ORDER BY DATE(tanggal_pesan) ASC
";

$resultDailyOrders = $conn->query($queryDailyOrders);

if ($resultDailyOrders->num_rows > 0) {
    while ($row = $resultDailyOrders->fetch_assoc()) {
        $response['daily_orders'][] = [
            "tanggal" => $row['tanggal'],
            "jumlah_pesanan" => $row['jumlah_pesanan'],
            "total_pendapatan" => $row['total_pendapatan']
        ];
    }
}

// 2. Mengambil Data Distribusi Layanan (Pie Chart)
$queryServiceDistribution = "
    SELECT jenis_layanan, 
           COUNT(*) AS jumlah
    FROM pesanan_sepatu
    GROUP BY jenis_layanan
    ORDER BY jumlah DESC
";

$resultServiceDistribution = $conn->query($queryServiceDistribution);

if ($resultServiceDistribution->num_rows > 0) {
    while ($row = $resultServiceDistribution->fetch_assoc()) {
        $response['service_distribution'][] = [
            "jenis_layanan" => $row['jenis_layanan'],
            "jumlah" => $row['jumlah']
        ];
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($response);

$conn->close();
?>
