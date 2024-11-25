<?php
// Koneksi ke database
include 'database.php';

// Periksa apakah Treatment_ID ada di query string
if (isset($_GET['Treatment_ID'])) {
    $treatmentID = $_GET['Treatment_ID'];

    // Query untuk menghapus data berdasarkan Treatment_ID
    $sql = "DELETE FROM treatmen WHERE Treatment_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $treatmentID);

    if ($stmt->execute()) {
        // Jika berhasil, arahkan kembali ke halaman treatment.php
        header("Location: treatment.php?status=success");
    } else {
        // Jika gagal, arahkan kembali dengan pesan error
        header("Location: treatment.php?status=error");
    }

    $stmt->close();
} else {
    // Jika tidak ada Treatment_ID, kembali ke halaman treatment.php
    header("Location: treatment.php?status=invalid");
}

// Tutup koneksi
$conn->close();
exit;
?>
