<?php
session_start(); 
// Koneksi ke database
include 'database.php';

// Periksa apakah ID_Pesanan ada di POST
if (isset($_POST['id_pesanan'])) {
    $idPesanan = $_POST['id_pesanan'];

    // Query untuk menghapus data berdasarkan ID_Pesanan
    $sql = "DELETE FROM pesanan WHERE ID_Pesanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $idPesanan); // "s" untuk string

    if ($stmt->execute()) {
        // Jika berhasil, arahkan kembali ke halaman pesanan.php dengan status success
        header("Location: pesanan.php?status=success");
    } else {
        // Jika gagal, arahkan kembali dengan pesan error
        header("Location: pesanan.php?status=error");
    }

    $stmt->close();
} else {
    // Jika tidak ada ID_Pesanan, arahkan kembali ke halaman pesanan.php dengan status invalid
    header("Location: pesanan.php?status=invalid");
}

// Tutup koneksi
$conn->close();
exit;
?>
