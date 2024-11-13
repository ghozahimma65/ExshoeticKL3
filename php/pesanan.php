<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'exshoetic_db'; // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Menerima data dari form
$nama = $conn->real_escape_string($_POST['name']);
$no_hp = $conn->real_escape_string($_POST['phone']);
$alamat = $conn->real_escape_string($_POST['address']);
$treatment_id = 'TM00' . $conn->real_escape_string($_POST['treatment_id']); // Format sesuai database
$total_tagihan = (int)$_POST['total_bill'];
$metode_pembayaran = $conn->real_escape_string($_POST['payment_method']);

// Mulai transaction
$conn->begin_transaction();

try {
    // 1. Insert ke tabel pesanan
    $queryPesanan = "INSERT INTO pesanan (Tanggal_Pesanan, Treatment_ID, Total_Tagihan, Status) 
                     VALUES (NOW(), ?, ?, 'Diambil')";
    $stmtPesanan = $conn->prepare($queryPesanan);
    $stmtPesanan->bind_param("si", $treatment_id, $total_tagihan);
    $stmtPesanan->execute();
    $id_pesanan = $conn->insert_id;

    // 2. Insert ke tabel customer
    $queryCustomer = "INSERT INTO customer (Nama, No_Hp, Alamat, ID_Pesanan) 
                     VALUES (?, ?, ?, ?)";
    $stmtCustomer = $conn->prepare($queryCustomer);
    $stmtCustomer->bind_param("sssi", $nama, $no_hp, $alamat, $id_pesanan);
    $stmtCustomer->execute();
    $customer_id = $conn->insert_id;

    // 3. Insert ke tabel pembayaran
    $pembayaran_id = 'PM' . str_pad($id_pesanan, 3, '0', STR_PAD_LEFT);
    $queryPembayaran = "INSERT INTO pembayaran (Pembayaran_ID, ID_Pesanan, Tanggal_Pembayaran, Metode_Pembayaran, Total_Tagihan) 
                        VALUES (?, ?, NOW(), ?, ?)";
    $stmtPembayaran = $conn->prepare($queryPembayaran);
    $stmtPembayaran->bind_param("sisi", $pembayaran_id, $id_pesanan, $metode_pembayaran, $total_tagihan);
    $stmtPembayaran->execute();

    // 4. Insert ke detail_pesanan
    $detail_id = 'DP' . str_pad($id_pesanan, 3, '0', STR_PAD_LEFT);
    $queryDetail = "INSERT INTO detail_pesanan (DetailPesanan_ID, ID_Pesanan, Customer_ID, Pembayaran_ID) 
                   VALUES (?, ?, ?, ?)";
    $stmtDetail = $conn->prepare($queryDetail);
    $stmtDetail->bind_param("siis", $detail_id, $id_pesanan, $customer_id, $pembayaran_id);
    $stmtDetail->execute();

    // Commit transaction
    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Pesanan berhasil dikirim! Kurir kami akan segera menghubungi Anda."]);

} catch (Exception $e) {
    // Rollback jika terjadi error
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

$conn->close();
?>