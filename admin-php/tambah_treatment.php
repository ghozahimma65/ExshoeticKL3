<?php
require_once 'database.php';
require_once '../php/add_treatment.php';

$success = false; // Status keberhasilan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $treatment_id = htmlspecialchars($_POST['treatment_id']);
    $nama_treatment = htmlspecialchars($_POST['nama_treatment']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $harga = htmlspecialchars($_POST['harga']);
    $estimasi = htmlspecialchars($_POST['estimasi']);

    if (empty($treatment_id) || empty($nama_treatment) || empty($deskripsi) || empty($harga) || empty($estimasi)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {
        // Panggil fungsi untuk menyimpan data ke database
        $success = insertTreatment($conn, $treatment_id, $nama_treatment, $deskripsi, $harga, $estimasi);
        if (!$success) {
            echo "<script>alert('Gagal menambahkan treatment.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Treatment - Exshoetic Shoes & Care</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../admin-css/add.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
  </head>
  <body>
    <a href="treatment.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
    <div class="container">
      <div class="form-container">
        <div class="form-header">
          <h1 class="form-title">Form Tambah Treatment</h1>
        </div>
        
        <!-- Pesan sukses -->
        <div class="success-message" id="successMessage" style="display: <?php echo $success ? 'block' : 'none'; ?>;">
          Treatment berhasil ditambahkan
        </div>

        <!-- Form -->
        <form id="treatmentForm" action="" method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label for="treatment_id">ID Treatment</label>
              <input type="text" id="treatment_id" name="treatment_id" required />
            </div>
            <div class="form-group">
              <label for="nama_treatment">Nama Treatment</label>
              <input type="text" id="nama_treatment" name="nama_treatment" required />
            </div>
            <div class="form-group">
              <label for="deskripsi">Deskripsi</label>
              <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi singkat treatment" required></textarea>
            </div>
            <div class="form-group">
              <label for="harga">Harga</label>
              <input type="number" id="harga" name="harga" required />
            </div>
            <div class="form-group">
              <label for="estimasi">Estimasi Waktu</label>
              <input type="text" id="estimasi" name="estimasi" placeholder="Contoh: 2 hari, 3 jam" required />
            </div>
          </div>
          <button type="submit" class="btn-submit">Tambah Treatment</button>
        </form>
      </div>
    </div>

    <script>
      // Menampilkan pesan sukses dengan efek (opsional)
      document.addEventListener("DOMContentLoaded", function () {
        const successMessage = document.getElementById("successMessage");
        if (successMessage.style.display === "block") {
          setTimeout(() => {
            successMessage.style.display = "none"; // Sembunyikan setelah 3 detik
          }, 3000);
        }
      });
    </script>
  </body>
</html>
