<?php
// Koneksi ke database
include 'database.php';

// Ambil bulan dari input (default bulan saat ini)
$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');

// Query untuk mengambil data pemasukan
$query_pemasukan = "SELECT 'Pemasukan' AS jenis, Pembayaran_ID AS id, Tanggal_Pembayaran AS tanggal, Metode_Pembayaran AS metode, Total_Tagihan AS nominal 
                    FROM pembayaran 
                    WHERE DATE_FORMAT(Tanggal_Pembayaran, '%Y-%m') = '$bulan_filter'";
$result_pemasukan = $conn->query($query_pemasukan);

// Query untuk mengambil data pengeluaran
$query_pengeluaran = "SELECT 'Pengeluaran' AS jenis, ID_Pengeluaran AS id, Tanggal_Pembelian AS tanggal, NULL AS metode, Jumlah_Harga AS nominal 
                      FROM pengeluaran 
                      WHERE DATE_FORMAT(Tanggal_Pembelian, '%Y-%m') = '$bulan_filter'";
$result_pengeluaran = $conn->query($query_pengeluaran);

// Gabungkan data pemasukan dan pengeluaran
$laporan_data = array_merge(
    $result_pemasukan->fetch_all(MYSQLI_ASSOC),
    $result_pengeluaran->fetch_all(MYSQLI_ASSOC)
);

// Urutkan berdasarkan tanggal
usort($laporan_data, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

// Hitung total pemasukan dan pengeluaran
$total_pemasukan = 0;
$total_pengeluaran = 0;

foreach ($laporan_data as $data) {
    if ($data['jenis'] === 'Pemasukan') {
        $total_pemasukan += $data['nominal'];
    } else {
        $total_pengeluaran += $data['nominal'];
    }
}

// Hitung saldo akhir
$saldo_akhir = $total_pemasukan - $total_pengeluaran;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Customer - Exshoetic Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/exshoetic/admin-css/sidebar.css"> 
  <link rel="stylesheet" href="/exshoetic/admin-css/cont-laporan.css"> 
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
        <i class="fas fa-database"></i>
        Data Master
      </a>
      <ul id="data-master">
        <li><a href="../admin-php/customer.php"><i class="fas fa-users"></i>Customer</a></li>
        <li><a href="../admin-php/treatment.php"><i class="fas fa-shoe-prints"></i>Treatment</a></li>
      </ul>
    </li>
    <li style="--i:2">
      <a onclick="toggleSubmenu('keuangan')">
        <i class="fas fa-chart-line"></i>
        Keuangan
      </a>
      <ul id="keuangan">
        <li><a href="../admin-php/pemasukan.php"><i class="fas fa-arrow-up"></i>Pemasukan</a></li>
        <li><a href="../admin-php/pengeluaran.php"><i class="fas fa-arrow-down"></i>Pengeluaran</a></li>
      </ul>
    </li>
    <li style="--i:3">
      <a onclick="toggleSubmenu('transaksi')">
        <i class="fas fa-exchange-alt"></i>
        Transaksi
      </a>
      <ul id="transaksi" class="show">
        <li><a href="../admin-php/pesanan.php"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="../admin-php/laporan.php"><i class="fas fa-file-alt"></i> Laporan</a></li>
      </ul>
    </li>

    <!-- Tambahkan Tombol Logout -->
    <li class="logout-menu">
      <form method="POST" action="logout.php">
        <button type="submit" name="logout" class="logout-btn">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </li>
  </ul>
</div>

<!-- Content -->
<div class="content" id="content">
  <h1 class="page-title">Laporan Keuangan</h1>
  <a href="../admin-php/adminPowerBi.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
  <button onclick="window.print()" class="btn-add-customer"><i class="fas fa-print"></i> Cetak Laporan</button>
  <div class="filter-container">
  <form method="GET" action="">
    <label for="bulan">Filter Bulan:</label>
    <input type="month" id="bulan" name="bulan" value="<?= htmlspecialchars($bulan_filter) ?>">
    <button type="submit">Terapkan</button>
  </form>
</div>


  <input type="text" id="searchInput" placeholder="Cari berdasarkan Jenis Transaksi, Tanggal, atau ID" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Jenis Transaksi</th>
          <th>ID Transaksi</th>
          <th>Tanggal</th>
          <th>Metode Pembayaran</th>
          <th>Nominal</th>
        </tr>
      </thead>
      <tbody id="laporanTable">
        <?php
        $no = 1;
        foreach ($laporan_data as $data) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$data['jenis']}</td>
                    <td>{$data['id']}</td>
                    <td>{$data['tanggal']}</td>
                    <td>" . ($data['metode'] ?? '-') . "</td>
                    <td>Rp " . number_format($data['nominal'], 0, ',', '.') . "</td>
                  </tr>";
            $no++;
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="summary">
    <p>Total Pemasukan: <strong>Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></strong></p>
    <p>Total Pengeluaran: <strong>Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></strong></p>
    <p>Saldo Akhir: <strong>Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></strong></p>
  </div>
</div>

<script>
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

  // Keep data-master submenu open on page load
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('transaksi').classList.add('show');
  });

  // Fungsi untuk pencarian
  function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.getElementById("laporanTable").getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
      const jenis = rows[i].getElementsByTagName("td")[1]?.innerText.toLowerCase() || "";
      const tanggal = rows[i].getElementsByTagName("td")[3]?.innerText.toLowerCase() || "";
      const id = rows[i].getElementsByTagName("td")[2]?.innerText.toLowerCase() || "";

      if (jenis.includes(input) || tanggal.includes(input) || id.includes(input)) {
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