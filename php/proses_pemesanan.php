<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'exshoetic_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Mapping ID treatment dari form ke format database
function mapTreatmentId($formId) {
    return 'TM' . str_pad($formId, 3, '0', STR_PAD_LEFT);
}

try {
    // Validasi input
    if (!isset($_POST['name']) || !isset($_POST['phone']) || !isset($_POST['treatment_id'])) {
        throw new Exception("Data yang diperlukan tidak lengkap");
    }

    // Menerima dan membersihkan data dari form
    $nama = $conn->real_escape_string($_POST['name']);
    $no_hp = $conn->real_escape_string($_POST['phone']);
    $alamat = $conn->real_escape_string($_POST['address']);
    $merk_sepatu = $conn->real_escape_string($_POST['brand']);
    $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';
    $treatment_id = mapTreatmentId($_POST['treatment_id']);
    $total_tagihan = (int)$_POST['total_bill'];
    $metode_pembayaran = $conn->real_escape_string($_POST['payment_method']);

    // Mulai transaction
    $conn->begin_transaction();

    // 1. Insert ke tabel pesanan
    $queryPesanan = "INSERT INTO pesanan (Tanggal_Pesanan, Treatment_ID, Merk_Sepatu, Total_Tagihan, Status) 
                     VALUES (CURRENT_DATE(), ?, ?, ?, 'Diambil')";
    $stmtPesanan = $conn->prepare($queryPesanan);
    if (!$stmtPesanan) {
        throw new Exception("Prepare statement pesanan gagal: " . $conn->error);
    }
    $stmtPesanan->bind_param("ssi", $treatment_id, $merk_sepatu, $total_tagihan);
    if (!$stmtPesanan->execute()) {
        throw new Exception("Execute statement pesanan gagal: " . $stmtPesanan->error);
    }
    $id_pesanan = $conn->insert_id;

    // 2. Insert ke tabel customer
    $queryCustomer = "INSERT INTO customer (Nama, No_Hp, Alamat, ID_Pesanan) 
                     VALUES (?, ?, ?, ?)";
    $stmtCustomer = $conn->prepare($queryCustomer);
    if (!$stmtCustomer) {
        throw new Exception("Prepare statement customer gagal: " . $conn->error);
    }
    $stmtCustomer->bind_param("sssi", $nama, $no_hp, $alamat, $id_pesanan);
    if (!$stmtCustomer->execute()) {
        throw new Exception("Execute statement customer gagal: " . $stmtCustomer->error);
    }
    $customer_id = $conn->insert_id;

    // 3. Insert ke tabel pembayaran
    $pembayaran_id = 'PM' . str_pad(($id_pesanan), 3, '0', STR_PAD_LEFT);
    $queryPembayaran = "INSERT INTO pembayaran (Pembayaran_ID, ID_Pesanan, Tanggal_Pembayaran, Metode_Pembayaran, Total_Tagihan) 
                        VALUES (?, ?, CURRENT_DATE(), ?, ?)";
    $stmtPembayaran = $conn->prepare($queryPembayaran);
    if (!$stmtPembayaran) {
        throw new Exception("Prepare statement pembayaran gagal: " . $conn->error);
    }
    $stmtPembayaran->bind_param("sisi", $pembayaran_id, $id_pesanan, $metode_pembayaran, $total_tagihan);
    if (!$stmtPembayaran->execute()) {
        throw new Exception("Execute statement pembayaran gagal: " . $stmtPembayaran->error);
    }

    // 4. Insert ke tabel detail_pesanan
    $queryDetail = "INSERT INTO detail_pesanan (ID_Pesanan, Customer_ID, Pembayaran_ID) 
                   VALUES (?, ?, ?)";
    $stmtDetail = $conn->prepare($queryDetail);
    if (!$stmtDetail) {
        throw new Exception("Prepare statement detail_pesanan gagal: " . $conn->error);
    }
    $stmtDetail->bind_param("iis", $id_pesanan, $customer_id, $pembayaran_id);
    if (!$stmtDetail->execute()) {
        throw new Exception("Execute statement detail_pesanan gagal: " . $stmtDetail->error);
    }

    // Commit transaction
    $conn->commit();
    
    // Kirim response sukses
    echo json_encode([
        "status" => "success",
        "message" => "Pesanan berhasil dikirim! Kurir kami akan segera menghubungi Anda.",
        "order_id" => $id_pesanan
    ]);

} catch (Exception $e) {
    // Rollback jika terjadi error
    if ($conn->connect_error === null) {
        $conn->rollback();
    }
    echo json_encode([
        "status" => "error",
        "message" => "Error: " . $e->getMessage()
    ]);
} finally {
    // Tutup semua statement
    if (isset($stmtPesanan)) $stmtPesanan->close();
    if (isset($stmtCustomer)) $stmtCustomer->close();
    if (isset($stmtPembayaran)) $stmtPembayaran->close();
    if (isset($stmtDetail)) $stmtDetail->close();
    $conn->close();
}
?>
