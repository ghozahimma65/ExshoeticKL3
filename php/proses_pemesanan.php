<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'esac';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menerima data dari form
$nama = $_POST['name'];
$no_hp = $_POST['phone'];
$alamat = $_POST['address'];
$treatmen_id = $_POST['treatment_id'];
$total_tagihan = $_POST['total_bill'];
$metode_pembayaran = $_POST['payment_method'];

// Query untuk menyimpan data ke tabel Customer
$queryCustomer = "INSERT INTO Customer (Nama, No_HP, Alamat) VALUES ('$nama', '$no_hp', '$alamat')";
if ($conn->query($queryCustomer) === TRUE) {
    $customer_id = $conn->insert_id;

    // Query untuk menyimpan data ke tabel Pesanan
    $queryPesanan = "INSERT INTO Pesanan (Tanggal_Pesanan, Treatmen_ID, Total_Tagihan, Status) VALUES (NOW(), '$treatmen_id', '$total_tagihan', 'Menunggu')";
    if ($conn->query($queryPesanan) === TRUE) {
        $pesanan_id = $conn->insert_id;

        // Query untuk menyimpan data ke tabel Pembayaran
        $queryPembayaran = "INSERT INTO Pembayaran (Pesanan_ID, Tanggal_Pembayaran, Metode_Pembayaran, Total_Tagihan) VALUES ('$pesanan_id', NOW(), '$metode_pembayaran', '$total_tagihan')";
        if ($conn->query($queryPembayaran) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Pesanan berhasil dikirim! Kurir kami akan segera menghubungi Anda."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
}
$conn->close();
?>
