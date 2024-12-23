<?php
include 'database.php'; // Pastikan path ini benar

function generateIdPengeluaran() {
    return 'PNG-' . date('YmdHis') . rand(100, 999);
}

$success = false; // Status keberhasilan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengeluaran = generateIdPengeluaran();
    $nama_barang = htmlspecialchars($_POST['nama_barang']);
    $harga_satuan = (int) htmlspecialchars($_POST['harga_satuan']);
    $total = (int) htmlspecialchars($_POST['total']);
    $harga_jumlah = $harga_satuan * $total;
    $tanggal = htmlspecialchars($_POST['tanggal']); // Tangkap tanggal dari input

    if (empty($nama_barang) || empty($harga_satuan) || empty($total) || empty($tanggal)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {
        if (!$conn) {
            die('Koneksi database gagal: ' . mysqli_connect_error());
        }

        $query = "INSERT INTO pengeluaran (id_pengeluaran, nama_barang, harga_satuan, total, jumlah_harga, tanggal_pembelian) 
                  VALUES ('$id_pengeluaran', '$nama_barang', '$harga_satuan', '$total', '$harga_jumlah', '$tanggal')";

        if (mysqli_query($conn, $query)) {
            $success = true; // Menandai proses berhasil
        } else {
            echo "<script>alert('Terjadi kesalahan: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Input Pengeluaran</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../admin-css/add.css" />
    <style>
.success-message {
    background-color: #4CAF50;
    color: white;
    padding: 15px;
    text-align: center;
    border-radius: 5px;
    margin-bottom: 20px;
    font-size: 16px;
}

.alert-error {
    background-color: #f44336;
    color: white;
    padding: 20px;
    text-align: center;
    font-size: 18px;
    border-radius: 5px;
    display: none;
}
    </style>
  </head>
  <body>
    <a href="pengeluaran.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
    <div class="container">
      <div class="form-container">
        <div class="form-header">
          <h1 class="form-title">Form Input Pengeluaran</h1>
        </div>

        <div class="success-message" id="successMessage" style="display: <?php echo $success ? 'block' : 'none'; ?>;">
            Data pengeluaran berhasil ditambahkan
        </div>

        <div id="errorMessage" class="alert-error">
          Terjadi kesalahan saat menyimpan data
        </div>

        <form id="expenseForm" action="" method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label for="nama_barang">Nama Barang</label>
              <input type="text" id="nama_barang" name="nama_barang" required />
            </div>
            <div class="form-group">
              <label for="harga_satuan">Harga Satuan</label>
              <input type="number" id="harga_satuan" name="harga_satuan" required />
            </div>
            <div class="form-group">
              <label for="total">Total</label>
              <input type="number" id="total" name="total" required />
              <div id="formatted-price" class="price-display"></div>
            </div>
            <div class="form-group">
              <label for="harga_jumlah">Harga Jumlah</label>
              <input type="number" id="harga_jumlah" name="harga_jumlah" readonly required />
            </div>
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" id="tanggal" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required />
            </div>
          </div>
          <button type="submit" class="btn-submit">Kirim Data</button>
        </form>
      </div>
    </div>

    <script>
      function formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID", {
          style: "currency",
          currency: "IDR",
          minimumFractionDigits: 0,
          maximumFractionDigits: 0,
        }).format(angka);
      }

      function updateTotal() {
        const hargaSatuan = parseInt(document.getElementById("harga_satuan").value) || 0;
        const total = parseInt(document.getElementById("total").value) || 0;
        const hargaJumlah = hargaSatuan * total;

        // Menampilkan harga jumlah
        document.getElementById("harga_jumlah").value = hargaJumlah;

        const formattedPriceDiv = document.getElementById("formatted-price");
        formattedPriceDiv.textContent = formatRupiah(hargaJumlah);
      }

      document.getElementById("harga_satuan").addEventListener("input", updateTotal);
      document.getElementById("total").addEventListener("input", updateTotal);

      // Show success message after form submission
      document.addEventListener("DOMContentLoaded", function () {
          const successMessage = document.getElementById("successMessage");
          if (successMessage.style.display === "block") {
              setTimeout(() => {
                  successMessage.style.display = "none"; // Sembunyikan pesan setelah 3 detik
              }, 3000);
          }
      });
    </script>
  </body>
</html>
