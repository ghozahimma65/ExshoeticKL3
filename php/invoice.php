<?php
// Konfigurasi koneksi database
// cors
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Konfigurasi koneksi database
$host = 'localhost';
$user = 'mifmyho2_exshoetic';
$password = '@Mif2024';
$dbname = 'mifmyho2_exshoetic';

$conn = new mysqli($host, $user, $password, $dbname);

// if ($conn->connect_error) {
//     die("Koneksi gagal: " . $conn->connect_error);
// } else {
//     echo "Koneksi berhasil!";
// }

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
    <link rel="stylesheet" href="../css/faktur.css">
</head>
<body>
    <div class="invoice">
        <button class="close-btn" onclick="closeInvoice()">×</button>
        <h1>Invoice</h1>
        <p><strong>Tanggal Pesanan:</strong> <?php echo date("d-m-Y", strtotime($order['Tanggal_Pesanan'])); ?></p>
        <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['Nama']); ?></p>
        <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($order['No_Hp']); ?></p>
        <p><strong>Alamat:</strong> <?php echo htmlspecialchars($order['Alamat']); ?></p>
        <p><strong>Keterangan:</strong> <?php echo htmlspecialchars($order['Keterangan']); ?></p>
        <p><strong>Treatment:</strong> <?php echo htmlspecialchars($order['Nama_Treatment']); ?></p>
        <p><strong>Total Tagihan:</strong> <?php echo number_format($order['Total_Tagihan'], 0, ',', '.'); ?> IDR</p>
        
        <a href="#" id="download-btn" onclick="downloadPDF()">Download Invoice</a>
        <a href="#" id="wa-btn" onclick="sendWhatsApp()">Konfirmasi ke WhatsApp</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const invoice = document.querySelector('.invoice');
            const opt = {
                margin: 10,
                filename: 'invoice_<?php echo $id_pesanan; ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(invoice).save();
        }

        function closeInvoice() {
            document.querySelector('.invoice').style.display = 'none';
        }

        function sendWhatsApp() {
                const nomorHP = "<?php echo htmlspecialchars($order['No_Hp']); ?>"; // Nomor pelanggan
                const pesan = `Halo, <?php echo htmlspecialchars($order['Nama']); ?>!
                
            Pesanan Anda telah dibuat dengan detail berikut:
            - Tanggal Pesanan: <?php echo date("d-m-Y", strtotime($order['Tanggal_Pesanan'])); ?>
            - Treatment: <?php echo htmlspecialchars($order['Nama_Treatment']); ?>
            - Total Tagihan: <?php echo number_format($order['Total_Tagihan'], 0, ',', '.'); ?> IDR.

            Silakan hubungi kami untuk detail lebih lanjut. Terima kasih!`;

                const url = `https://wa.me/${08155290506}?text=${encodeURIComponent(pesan)}`;
                window.open(url, '_blank');
            }

    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>