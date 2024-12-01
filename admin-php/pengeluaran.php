<?php
// Koneksi ke database
include 'database.php';

// Query untuk mengambil data pengeluaran
$sql = "SELECT ID_Pengeluaran, Nama_Barang, Harga_Satuan, Total, Jumlah_Harga FROM pengeluaran";
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

<!-- Sidebar -->
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

  <input type="text" id="searchInput" placeholder="Cari berdasarkan Nama Barang" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
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
                        <td>" . htmlspecialchars($row['Nama_Barang'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['Harga_Satuan'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['Total'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($row['Jumlah_Harga'], ENT_QUOTES, 'UTF-8') . "</td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='6' style='text-align: center;'>Data pengeluaran tidak ditemukan</td></tr>";
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
