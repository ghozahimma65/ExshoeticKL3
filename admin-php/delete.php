<?php
// Koneksi ke database
include 'database.php';

// Pastikan parameter ID diterima melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data dari tabel customer berdasarkan ID_Pesanan
    $sql = "DELETE FROM customer WHERE ID_Pesanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika berhasil, arahkan kembali ke halaman customer dengan pesan sukses
        header("Location: customer.php?message=deleted");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $stmt->error;
    }

    // Tutup statement
    $stmt->close();
} else {
    echo "ID tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>
