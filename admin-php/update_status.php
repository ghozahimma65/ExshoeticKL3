<?php
include('database.php');

// Validate and sanitize input
$id_pesanan = isset($_POST['id_pesanan']) ? intval($_POST['id_pesanan']) : null;
$status = isset($_POST['status']) ? trim($_POST['status']) : null;

// Validasi input dasar
if (!$id_pesanan || !$status) {
    die("Parameter tidak lengkap.");
}

// Status yang valid sesuai dengan form awal
$validStatuses = ['Belum Selesai', 'Sudah Selesai'];

if (!in_array($status, $validStatuses)) {
    die("Status tidak valid.");
}

// Cek apakah ID Pesanan ada di database
$checkQuery = "SELECT * FROM pesanan WHERE ID_Pesanan = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("i", $id_pesanan);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows == 0) {
    die("ID Pesanan tidak ditemukan.");
}

// Update status
$updateQuery = "UPDATE pesanan SET Status = ? WHERE ID_Pesanan = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("si", $status, $id_pesanan);

if ($updateStmt->execute()) {
    if ($updateStmt->affected_rows > 0) {
        echo "Status berhasil diperbarui";
    } else {
        echo "Status tidak berubah";
    }
} else {
    echo "Gagal memperbarui status: " . $updateStmt->error;
}

$checkStmt->close();
$updateStmt->close();
$conn->close();
?>
