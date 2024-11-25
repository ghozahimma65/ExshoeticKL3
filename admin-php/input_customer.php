<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangkap data dari form
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $brand = htmlspecialchars($_POST['brand']);
    $notes = htmlspecialchars($_POST['notes']);
    $address = htmlspecialchars($_POST['address']);
    $service = htmlspecialchars($_POST['treatment_id']);
    $total_bill = htmlspecialchars($_POST['total_bill']);
    $payment_method = htmlspecialchars($_POST['payment_method']);

    // Validasi input sederhana
    if (empty($name) || empty($phone) || empty($address) || empty($service) || empty($total_bill)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {
        // Contoh proses selanjutnya (tampilkan data)
        echo "<script>alert('Pesanan atas nama $name berhasil ditambahkan!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Exshoetic Shoes & Care</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../admin-css/addcus.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />

    
    
  </head>
  <body>
    <a href="customer.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
    <div class="container">
      <div class="form-container">
        <div class="form-header">
          <h1 class="form-title">Form Pemesanan Layanan Cuci Sepatu</h1>
          <div class="service-highlights">
            <div class="highlight-item">
              <i class="fas fa-box"></i><span>Packing Aman</span>
            </div>
            <div class="highlight-item">
              <i class="fas fa-truck"></i><span>Pickup & Delivery</span>
            </div>
            <div class="highlight-item">
              <i class="fas fa-map-marker-alt"></i
              ><span>Tracking Real-time</span>
            </div>
          </div>
        </div>

        <div class="success-message" id="successMessage" style="display: none;">
          Pesanan berhasil ditambahkan
      </div>

        <form
          id="shoeServiceForm"
          action="../php/proses_pemesanan.php"
          method="POST"
        >
          <div class="form-grid">
            <div class="form-group">
              <label for="name">Nama Lengkap</label>
              <input type="text" id="name" name="name" required />
            </div>
            <div class="form-group">
              <label for="phone">Nomor Telepon</label>
              <input type="tel" id="phone" name="phone" required />
            </div>

            <div class="form-group">
              <label for="brand">Merk Sepatu</label>
              <input
                type="text"
                id="brand"
                name="brand"
                placeholder="Contoh: Nike, Adidas"
                required
              />
            </div>

            <div class="form-group full-width">
              <label for="notes">Keterangan Tambahan</label>
              <textarea
                id="notes"
                name="notes"
                placeholder="Catatan khusus untuk layanan (opsional)"
              ></textarea>
            </div>

            <div class="form-group full-width">
              <label for="address">Alamat Lengkap</label>
              <input
                type="text"
                id="address"
                name="address"
                placeholder="Nama Jalan, Nomor Rumah"
                required
              />
            </div>

            <div class="form-group">
              <label for="service">Jenis Layanan</label>
              <select
                id="service"
                name="treatment_id"
                onchange="updateHarga()"
                required
              >
                <option value="">Pilih Layanan</option>
                <option value="1">Deep Clean</option>
                <option value="2">Fast Cleaning</option>
                <option value="3">Unyellowing</option>
                <option value="4">Repaint</option>
                <option value="5">Repair Sol</option>
                <option value="6">Whitening</option>
                <option value="7">Leather Care</option>
                <option value="8">Reglue</option>
              </select>
            </div>

            <div class="form-group">
              <label for="total">Total Tagihan</label>
              <input
                type="number"
                id="total"
                name="total_bill"
                readonly
                required
              />
              <div id="formatted-price" class="price-display"></div>
            </div>

            <div class="form-group">
              <label for="payment_method">Metode Pembayaran</label>
              <select id="payment_method" name="payment_method" required>
                <option value="BRI">BRI</option>
                <option value="Mandiri">Mandiri</option>
                <option value="BCA">BCA</option>
                <option value="DANA">DANA</option>
                <option value="ShopeePay">ShopeePay</option>
                <option value="SeaBank">SeaBank</option>
                <option value="COD">COD</option>
                <option value="QRIS">QRIS</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn-submit">Kirim Pesanan</button>
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

      function updateHarga() {
        const serviceSelect = document.getElementById("service");
        const totalInput = document.getElementById("total");
        const formattedPriceDiv = document.getElementById("formatted-price");

        const priceMapping = {
          1: 35000,
          2: 15000,
          3: 45000,
          4: 150000,
          5: 70000,
          6: 50000,
          7: 40000,
          8: 70000,
        };

        const harga = priceMapping[serviceSelect.value] || 0;
        totalInput.value = harga;
        formattedPriceDiv.textContent = formatRupiah(harga);
      }

      document
        .getElementById("shoeServiceForm")
        .addEventListener("submit", function (event) {
          event.preventDefault();

          const form = this;
          const formData = new FormData(form);
          const submitButton = form.querySelector('button[type="submit"]');
          const successMessage = document.getElementById("successMessage");

          submitButton.disabled = true;
          submitButton.textContent = "Mengirim...";

          fetch("../php/proses_pemesanan.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => {
              if (!response.ok) {
                throw new Error("Network response was not ok");
              }
              return response.json();
            })
            .then((data) => {
              if (data.status === "success") {
                successMessage.style.display = "block";
                successMessage.textContent = data.message;
                form.reset();
                updateHarga();

                successMessage.scrollIntoView({
                  behavior: "smooth",
                  block: "center",
                });

                setTimeout(() => {
                  successMessage.style.display = "none";
                }, 5000);
              } else {
                throw new Error(
                  data.message || "Terjadi kesalahan saat memproses pesanan"
                );
              }
            })
            .catch((error) => {
              alert("Terjadi kesalahan: " + error.message);
              console.error("Error:", error);
            })
            .finally(() => {
              submitButton.disabled = false;
              submitButton.textContent = "Kirim Pesanan";
            });
        });

      document.addEventListener("DOMContentLoaded", function () {
        const serviceSelect = document.getElementById("service");
        if (serviceSelect.value) {
          updateHarga();
        }
      });
    </script>
  </body>
</html>
