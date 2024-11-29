<?php
require_once 'database.php';

// Tangkap ID treatment dari URL
$treatment_id = $_GET['id'] ?? ''; 
$treatment = null;
$success = null; // Status berhasil/gagal akan disimpan di sini

// Ambil data treatment berdasarkan ID
if ($treatment_id) {
    $stmt = $conn->prepare("SELECT * FROM treatmen WHERE Treatment_ID = ?");
    $stmt->bind_param("s", $treatment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $treatment = $result->fetch_assoc();
    $stmt->close();
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap data dari form
    $nama_treatment = htmlspecialchars($_POST['nama_treatment']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $harga = htmlspecialchars($_POST['harga']);
    $estimasi = htmlspecialchars($_POST['estimasi']);

    // Validasi input
    if (!empty($nama_treatment) && !empty($deskripsi) && !empty($harga) && !empty($estimasi)) {
        // Update data di database
        $stmt = $conn->prepare("UPDATE treatmen SET Nama_Treatment = ?, Deskripsi = ?, Harga = ?, Estimasi = ? WHERE Treatment_ID = ?");
        $stmt->bind_param("ssdss", $nama_treatment, $deskripsi, $harga, $estimasi, $treatment_id);

        // Set status sukses/gagal berdasarkan hasil query
        if ($stmt->execute()) {
            $success = true;
        } else {
            $success = false;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Treatment - Exshoetic Admin</title>
    <link rel="stylesheet" href="../admin-css/add.css">
</head>
<body>
    <a href="treatment.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
    <div class="container">
        <div class="form-container">
            <h1>Edit Treatment</h1>

            <!-- Tampilkan pesan sukses/gagal -->
            <?php if ($success === true): ?>
                <div class="alert alert-success">
                    Data berhasil diperbarui! Mengarahkan kembali...
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'treatment.php';
                    }, 3000); // Redirect setelah 3 detik
                </script>
            <?php elseif ($success === false): ?>
                <div class="alert alert-danger">
                    Gagal memperbarui data. Silakan coba lagi.
                </div>
            <?php endif; ?>

            <!-- Form edit -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nama_treatment">Nama Treatment</label>
                    <input type="text" id="nama_treatment" name="nama_treatment" value="<?= htmlspecialchars($treatment['Nama_Treatment'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($treatment['Deskripsi'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" value="<?= htmlspecialchars($treatment['Harga'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="estimasi">Estimasi Waktu</label>
                    <input type="text" id="estimasi" name="estimasi" value="<?= htmlspecialchars($treatment['Estimasi'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
