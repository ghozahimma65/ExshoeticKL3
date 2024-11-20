<?php
// Koneksi ke database
include 'database.php';

// Cek apakah data sudah ada
if (isset($_POST['id_pesanan']) && isset($_POST['status'])) {
    $idPesanan = $_POST['id_pesanan'];
    $status = $_POST['status'];

    // Perbarui status di database
    $sql = "UPDATE pesanan SET Status = ? WHERE ID_Pesanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $idPesanan);

    if ($stmt->execute()) {
        echo "Status berhasil diperbarui!";
    } else {
        echo "Gagal memperbarui status.";
    }
    $stmt->close();
}
$conn->close();
?>
