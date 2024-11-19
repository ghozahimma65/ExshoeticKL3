<?php
// Koneksi ke database
include 'database.php';

// Query untuk mengambil data pembayaran
$sql = "SELECT Pembayaran_ID, ID_Pesanan, Tanggal_Pembayaran, Metode_Pembayaran, Total_Tagihan FROM pembayaran";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pembayaran - Exshoetic Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/exshoetic/admin-css/sidebar.css"> 
  <link rel="stylesheet" href="/exshoetic/admin-css/cont-customer.css"> 
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <button class="toggle-sidebar" onclick="toggleSidebar()">
    <i class="fas fa-arrow-left"></i>
  </button>
  <ul>
    <li style="--i:1">
      <a onclick="toggleSubmenu('data-master')">
        <i class="fas fa-database"></i> Data Master
      </a>
      <ul id="data-master">
        <li><a href="customer.php" class="active"><i class="fas fa-users"></i>Customer</a></li>
        <li><a href="treatment.php"><i class="fas fa-shoe-prints"></i>Treatment</a></li>
      </ul>
    </li>
    <li style="--i:2">
      <a onclick="toggleSubmenu('keuangan')">
        <i class="fas fa-chart-line"></i> Keuangan
      </a>
      <ul id="keuangan" class="show">
        <li><a href="pemasukan.php"><i class="fas fa-arrow-up"></i>Pemasukan</a></li>
        <li><a href="#"><i class="fas fa-arrow-down"></i>Pengeluaran</a></li>
      </ul>
    </li>
    <li style="--i:3">
      <a onclick="toggleSubmenu('transaksi')">
        <i class="fas fa-exchange-alt"></i> Transaksi
      </a>
      <ul id="transaksi">
        <li><a href="pesanan.php"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="#"><i class="fas fa-truck"></i>Pengiriman</a></li>
      </ul>
    </li>
  </ul>
</div>

<!-- Content -->
<div class="content" id="content">
  <h1 class="page-title">Data Pembayaran</h1>
  <a href="../admin-php/admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>

  <input type="text" id="searchInput" placeholder="Cari berdasarkan ID Pesanan atau Metode Pembayaran" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
    <table>
    <thead>
  <tr>
    <th>No</th>
    <th>Pembayaran ID</th>
    <th>ID Pesanan</th>
    <th>Tanggal Pembayaran</th>
    <th>Metode Pembayaran</th>
    <th>Total Tagihan</th>
  </tr>
</thead>
<tbody id="pembayaranTable">
  <?php
  if ($result->num_rows > 0) {
      $no = 1;
      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$no}</td>
                  <td>{$row['Pembayaran_ID']}</td>
                  <td>{$row['ID_Pesanan']}</td>
                  <td>{$row['Tanggal_Pembayaran']}</td>
                  <td>{$row['Metode_Pembayaran']}</td>
                  <td>Rp " . number_format($row['Total_Tagihan'], 0, ',', '.') . "</td>
                </tr>";
          $no++;
      }
  } else {
      echo "<tr><td colspan='6' style='text-align: center;'>Data pembayaran tidak ditemukan</td></tr>";
  }
  ?>
</tbody>

    </table>
  </div>
</div>

<script>
  // Fungsi untuk toggle sidebar
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleButton = document.querySelector('.toggle-sidebar i');
    sidebar.classList.toggle('hidden');
    content.classList.toggle('full-width');
    if (sidebar.classList.contains('hidden')) {
      toggleButton.classList.remove('fa-arrow-left');
      toggleButton.classList.add('fa-arrow-right');
    } else {
      toggleButton.classList.remove('fa-arrow-right');
      toggleButton.classList.add('fa-arrow-left');
    }
  }

  // Fungsi untuk toggle submenu
  function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    const allSubmenus = document.querySelectorAll('.sidebar ul ul');
    allSubmenus.forEach(menu => {
      if (menu.id !== id) {
        menu.classList.remove('show');
      }
    });
    submenu.classList.toggle('show');
  }

  // Fungsi pencarian
  function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.getElementById("keuangan").getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
      const idPesanan = rows[i].getElementsByTagName("td")[2]?.innerText.toLowerCase() || "";
      const metode = rows[i].getElementsByTagName("td")[4]?.innerText.toLowerCase() || "";

      // Menampilkan baris jika cocok dengan input pencarian
      if (idPesanan.includes(input) || metode.includes(input)) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
</script>

</body>
</html>

<?php
$conn->close();
?>
