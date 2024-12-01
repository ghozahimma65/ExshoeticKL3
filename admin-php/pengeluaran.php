<?php
// Koneksi ke database
include 'database.php';

// Ambil nilai bulan dan tahun dari parameter GET
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

// Query untuk mengambil data pengeluaran berdasarkan filter bulan dan tahun
$sql = "SELECT ID_Pengeluaran, Tanggal_Pembelian, Nama_Barang, Harga_Satuan, Total, Jumlah_Harga 
        FROM pengeluaran 
        WHERE MONTH(Tanggal_Pembelian) = $bulan AND YEAR(Tanggal_Pembelian) = $tahun";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pengeluaran - Admin</title>
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
      <ul id="keuangan" class="show">
        <li><a href="../admin-php/pemasukan.php"><i class="fas fa-arrow-up"></i>Pemasukan</a></li>
        <li><a href="../admin-php/pengeluaran.php"><i class="fas fa-arrow-down"></i>Pengeluaran</a></li>
      </ul>
    </li>
    <li style="--i:3">
      <a onclick="toggleSubmenu('transaksi')">
        <i class="fas fa-exchange-alt"></i>
        Transaksi
      </a>
      <ul id="transaksi">
        <li><a href="../admin-php/pesanan.php"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="#"><i class="fas fa-file-alt"></i> Laporan</a></li>
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
  <h1 class="page-title">Data Pengeluaran</h1>
  <a href="../admin-php/admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
  <a href="tambah_pengeluaran.php"class="btn-add-customer"><i class="fas fa-user-plus"></i> Tambah Pengeluaran</a>

  <form method="GET" class="filter-container">
  <label for="bulan">Pilih Bulan:</label>
  <select name="bulan" id="bulan">
    <?php
    for ($i = 1; $i <= 12; $i++) {
        $selected = ($i == $bulan) ? 'selected' : '';
        echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . "</option>";
    }
    ?>
  </select>
  
  <label for="tahun">Pilih Tahun:</label>
  <select name="tahun" id="tahun">
    <?php
    $currentYear = date('Y');
    for ($i = $currentYear - 2; $i <= $currentYear; $i++) {
        $selected = ($i == $tahun) ? 'selected' : '';
        echo "<option value='$i' $selected>$i</option>";
    }
    ?>
  </select>

  <button type="submit">Filter</button>
</form>


  <input type="text" id="searchInput" placeholder="Cari berdasarkan Nama Barang" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal Beli</th>
        <th>Nama Barang</th>
        <th>Harga Satuan</th>
        <th>Total</th>
        <th>Jumlah Harga</th>
      </tr>
    </thead>
    <tbody id="PengeluaranTable">
  <?php
  if ($result->num_rows > 0) {
      $no = 1;
      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$no}</td>
                  <td>" . htmlspecialchars($row['Tanggal_Pembelian'], ENT_QUOTES, 'UTF-8') . "</td>
                  <td>" . htmlspecialchars($row['Nama_Barang'], ENT_QUOTES, 'UTF-8') . "</td>
                  <td>" . htmlspecialchars($row['Harga_Satuan'], ENT_QUOTES, 'UTF-8') . "</td>
                  <td>" . htmlspecialchars($row['Total'], ENT_QUOTES, 'UTF-8') . "</td>
                  <td>" . htmlspecialchars($row['Jumlah_Harga'], ENT_QUOTES, 'UTF-8') . "</td>
                </tr>";
          $no++;
      }
  } else {
      echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data pengeluaran untuk bulan dan tahun yang dipilih</td></tr>";
  }
  ?>
</tbody>

  </table>
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

  // Membiarkan submenu data-master tetap terbuka saat halaman dimuat
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('keuangan').classList.add('show');
  });

  // Fungsi pencarian
  function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.getElementById("PengeluaranTable").getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        const namaBarang = rows[i].getElementsByTagName("td")[1]?.innerText.toLowerCase() || "";

        if (namaBarang.includes(input)) {
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
