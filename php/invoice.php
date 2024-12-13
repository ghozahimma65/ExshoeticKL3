<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'exshoetic_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID pesanan dari parameter URL
$id_pesanan = isset($_GET['id_pesanan']) ? intval($_GET['id_pesanan']) : 0;

if ($id_pesanan <= 0) {
    die("ID pesanan tidak valid.");
}

// Query untuk mendapatkan detail pesanan
$query = "SELECT p.Tanggal_Pesanan, p.Total_Tagihan, c.Nama, c.No_Hp, c.Alamat, c.Keterangan, t.Treatment_ID, t.Nama_Treatment
          FROM pesanan p
          JOIN customer c ON p.ID_Pesanan = c.ID_Pesanan
          JOIN treatmen t ON p.Treatment_ID = t.Treatment_ID
          WHERE p.ID_Pesanan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Pesanan tidak ditemukan.");
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Exshoetic</title>
    <link rel="stylesheet" href="css/invoice.css"> <!-- Optional: Add your own CSS for styling -->
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

.invoice {
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 5px;
}

h1 {
    text-align: center;
}

footer {
    text-align: center;
    margin-top: 20px;
}
</style>
<body>
    <div class="invoice">
        <h1>Invoice</h1>
        <p><strong>Tanggal Pesanan:</strong> <?php echo date("d-m-Y", strtotime($order['Tanggal_Pesanan'])); ?></p>
        <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['Nama']); ?></p>
        <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($order['No_Hp']); ?></p>
        <p><strong>Alamat:</strong> <?php echo htmlspecialchars($order['Alamat']); ?></p>
        <p><strong>Keterangan:</strong> <?php echo htmlspecialchars($order['Keterangan']); ?></p>
        <p><strong>Merk Sepatu:</strong> <?php echo htmlspecialchars($order['Nama_Treatment']); ?></p>
        <p><strong>Total Tagihan:</strong> <?php echo number_format($order['Total_Tagihan'], 0, ',', '.'); ?> IDR</p>
    </div>
    <footer>
        <p>Terima kasih telah menggunakan layanan kami!</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>