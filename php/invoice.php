<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'exshoetic_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]);
    exit;
}

// Validasi input
if (!isset($_GET['id_pesanan'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID pesanan tidak valid."]);
    exit;
}

$id_pesanan = (int)$_GET['id_pesanan'];

// Ambil data pesanan
$query = "SELECT p.Tanggal_Pesanan, p.Treatment_ID, p.Merk_Sepatu, p.Total_Tagihan, 
                 c.Nama, c.No_Hp, c.Alamat, c.Keterangan, 
                 pm.Pembayaran_ID, pm.Tanggal_Pembayaran, pm.Metode_Pembayaran 
          FROM pesanan p
          JOIN customer c ON p.ID_Pesanan = c.ID_Pesanan
          JOIN pembayaran pm ON p.ID_Pesanan = pm.ID_Pesanan
          WHERE p.ID_Pesanan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Pesanan tidak ditemukan."]);
    exit;
}

$data = $result->fetch_assoc();

// Tampilkan struk
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .receipt {
            border: 1px solid #000;
            padding: 20px;
            width: 300px;
            margin: 0 auto;
        }
        .receipt-item {
            margin-bottom: 10px;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="receipt">
    <h1>Struk Pembayaran</h1>
    <div class="receipt-item"><strong>Tanggal Pesanan:</strong> <?= $data['Tanggal_Pesanan'] ?></div>
    <div class="receipt-item"><strong>Nama:</strong> <?= $data['Nama'] ?></div>
    <div class="receipt-item"><strong>No HP:</strong> <?= $data['No_Hp'] ?></div>
    <div class="receipt-item"><strong>Alamat:</strong> <?= $data['Alamat'] ?></div>
    <div class="receipt-item"><strong>Treatment ID:</strong> <?= $data['Treatment_ID'] ?></div>
    <div class="receipt-item"><strong>Merk Sepatu:</strong> <?= $data['Merk_Sepatu'] ?></div>
    <div class="receipt-item"><strong>Total Tagihan:</strong> Rp <?= number_format($data['Total_Tagihan'], 0, ',', '.') ?></div>
    <div class="receipt-item"><strong>Metode Pembayaran:</strong> <?= $data['Metode_Pembayaran'] ?></div>
    <div class="receipt-item"><strong>Tanggal Pembayaran:</strong> <?= $data['Tanggal_Pembayaran'] ?></div>
    <div class="receipt-item"><strong>Keterangan:</strong> <?= $data['Keterangan'] ?></div>
</div>

</body>
</html>

<?php
// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>