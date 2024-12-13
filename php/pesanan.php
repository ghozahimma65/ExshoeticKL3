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

// Fungsi untuk mapping ID treatment dari form ke format database
function mapTreatmentId($formId) {
    return 'TM' . str_pad($formId, 3, '0', STR_PAD_LEFT);
}

try {
    // Validasi input
    if (!isset($_POST['name'], $_POST['phone'], $_POST['treatment_id'], $_POST['total_bill'], $_POST['payment_method'], $_POST['address'], $_POST['brand'])) {
        throw new Exception("Data yang diperlukan tidak lengkap.");
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

    if ($total_tagihan <= 0) {
        throw new Exception("Total tagihan tidak valid.");
    }

    // Mulai transaksi
    $conn->begin_transaction();

    // 1. Insert ke tabel pesanan
    $queryPesanan = "INSERT INTO pesanan (Tanggal_Pesanan, Treatment_ID, Merk_Sepatu, Total_Tagihan, Status) 
                     VALUES (NOW(), ?, ?, ?, 'Belum Selesai')";
    $stmtPesanan = $conn->prepare($queryPesanan);
    $stmtPesanan->bind_param("ssi", $treatment_id, $merk_sepatu, $total_tagihan);
    if (!$stmtPesanan->execute()) {
        throw new Exception("Gagal menyimpan data pesanan: " . $stmtPesanan->error);
    }
    $id_pesanan = $conn->insert_id;

    // 2. Insert ke tabel customer
    $queryCustomer = "INSERT INTO customer (Nama, No_Hp, Alamat, Keterangan, ID_Pesanan) 
                      VALUES (?, ?, ?, ?, ?)";
    $stmtCustomer = $conn->prepare($queryCustomer);
    $stmtCustomer->bind_param("ssssi", $nama, $no_hp, $alamat, $notes, $id_pesanan);
    if (!$stmtCustomer->execute()) {
        throw new Exception("Gagal menyimpan data customer: " . $stmtCustomer->error);
    }

    // 3. Insert ke tabel pembayaran
    $pembayaran_id = 'PM' . str_pad($id_pesanan, 3, '0', STR_PAD_LEFT);
    $queryPembayaran = "INSERT INTO pembayaran (Pembayaran_ID, ID_Pesanan, Tanggal_Pembayaran, Metode_Pembayaran, Total_Tagihan) 
                        VALUES (?, ?, NOW(), ?, ?)";
    $stmtPembayaran = $conn->prepare($queryPembayaran);
    $stmtPembayaran->bind_param("sisi", $pembayaran_id, $id_pesanan, $metode_pembayaran, $total_tagihan);
    if (!$stmtPembayaran->execute()) {
        throw new Exception("Gagal menyimpan data pembayaran: " . $stmtPembayaran->error);
    }

    // 4. Insert ke tabel detail_pesanan
    $queryDetail = "INSERT INTO detail_pesanan (ID_Pesanan, Customer_ID, Pembayaran_ID, Treatment_ID) 
                   VALUES (?, LAST_INSERT_ID(), ?, ?)";
    $stmtDetail = $conn->prepare($queryDetail);
    $stmtDetail->bind_param("iss", $id_pesanan, $pembayaran_id, $treatment_id);
    if (!$stmtDetail->execute()) {
        throw new Exception("Gagal menyimpan detail pesanan: " . $stmtDetail->error);
    }

    // Commit transaksi
    $conn->commit();

    // Kirim respons dengan link ke halaman invoice
    echo json_encode([
        "status" => "success",
        "message" => "Pesanan berhasil dibuat.",
        "redirect" => "../invoice.php?id_pesanan=$id_pesanan"
    ]);
} catch (Exception $e) {
    // Rollback jika terjadi kesalahan
    $conn->rollback();
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
} finally {
    // Tutup statement dan koneksi
    if (isset($stmtPesanan)) $stmtPesanan->close();
    if (isset($stmtCustomer)) $stmtCustomer->close();
    if (isset($stmtPembayaran)) $stmtPembayaran->close();
    if (isset($stmtDetail)) $stmtDetail->close();
    $conn->close();
}
?>
