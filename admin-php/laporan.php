<?php
// Koneksi ke database
include 'database.php';

// Ambil bulan dari input (default bulan saat ini)
$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');

// Query untuk mengambil data pemasukan
$query_pemasukan = "SELECT 'Pemasukan' AS jenis, Pembayaran_ID AS id, Tanggal_Pembayaran AS tanggal, Metode_Pembayaran AS metode, Total_Tagihan AS nominal 
                    FROM pembayaran 
                    WHERE DATE_FORMAT(Tanggal_Pembayaran, '%Y-%m') = ?";
$stmt_pemasukan = $conn->prepare($query_pemasukan);
$stmt_pemasukan->bind_param('s', $bulan_filter);
$stmt_pemasukan->execute();
$result_pemasukan = $stmt_pemasukan->get_result();

// Query untuk mengambil data pengeluaran
$query_pengeluaran = "SELECT 'Pengeluaran' AS jenis, ID_Pengeluaran AS id, Tanggal_Pembelian AS tanggal, NULL AS metode, Jumlah_Harga AS nominal 
                      FROM pengeluaran 
                      WHERE DATE_FORMAT(Tanggal_Pembelian, '%Y-%m') = ?";
$stmt_pengeluaran = $conn->prepare($query_pengeluaran);
$stmt_pengeluaran->bind_param('s', $bulan_filter);
$stmt_pengeluaran->execute();
$result_pengeluaran = $stmt_pengeluaran->get_result();

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
  <title>Laporan Keuangan - Exshoetic Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/exshoetic/admin-css/sidebar.css"> 
  <link rel="stylesheet" href="/exshoetic/admin-css/cont-treatment.css"> 
</head>
<body>
<style>
        .table-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-top: 0px;
        overflow-x: auto; /* Scroll secara horizontal jika tabel terlalu lebar */
        overflow-y: auto; /* Scroll secara vertikal jika tabel terlalu tinggi */
        max-height: 400px; /* Atur tinggi maksimum untuk membuat tabel lebih ringkas */
      }
  
      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
      }
  
      th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
      }
  
      th {
        background-color: #3b82f6;
        color: white;
        font-weight: 500;
      }
  
      tr:hover {
        background-color: #f9fafb;
      }
  
      .page-title {
        color: #1f2937;
        margin-bottom: 20px;
        font-size: 1.5em;
        font-weight: 600;
      }
      .btn-add-customer {
    display: inline-block;
    margin-bottom: 20px;
    padding: 8px 16px;
    background-color: #3b82f6;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
    margin-left: 10px; /* Beri jarak dari tombol "Kembali" */
  }
  
  .btn-add-customer:hover {
    background-color: #2563eb;
    transform: scale(1.05);
  }
  .filter-container {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    background-color: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.filter-container label {
    font-weight: 500;
    color: #1f2937;
    margin-right: 10px;
}

.filter-container select {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.95em;
    background-color: #f9fafb;
    transition: all 0.2s ease-in-out;
}

.filter-container select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.filter-container button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.filter-container button:hover {
    background-color: #2563eb;
    transform: scale(1.05);
}
</style>

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

  <form method="GET" class="filter-container">
    <label for="bulan">Pilih Bulan</label>
    <input type="month" id="bulan" name="bulan" value="<?= htmlspecialchars($bulan_filter) ?>">
    <button type="submit">Terapkan</button>
  </form>

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
      <tbody>
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
</script>

</body>
</html>

<?php
$conn->close();
?>
