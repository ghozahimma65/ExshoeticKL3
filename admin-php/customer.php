<?php
// Koneksi ke database
include 'database.php';

// Query untuk mengambil data customer
$sql = "SELECT Nama, No_Hp AS Telepon, Alamat, ID_Pesanan FROM customer";
$result = $conn->query($sql);
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
        <i class="fas fa-database"></i>
        Data Master
      </a>
      <ul id="data-master" class="show">
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
        <li><a href="#"><i class="fas fa-arrow-down"></i>Pengeluaran</a></li>
      </ul>
    </li>
    <li style="--i:3">
      <a onclick="toggleSubmenu('transaksi')">
        <i class="fas fa-exchange-alt"></i>
        Transaksi
      </a>
      <ul id="transaksi">
        <li><a href="../admin-php/pesanan.php"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="#"><i class="fas fa-truck"></i>Pengiriman</a></li>
      </ul>
    </li>
        <!-- Tambahan: Pengaturan Admin -->
        <li style="--i:4">
      <a onclick="toggleSubmenu('pengaturan-admin')">
        <i class="fas fa-cogs"></i>
        Pengaturan
      </a>
      <ul id="pengaturan-admin">
        <li><a href="../admin-php/profile.php"><i class="fas fa-user"></i>Profil Admin</a></li>
        <li><a href="../admin-php/settings.php"><i class="fas fa-tools"></i>Pengaturan Sistem</a></li>
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
  <h1 class="page-title">Data Customer</h1>
  <a href="../admin-php/admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
  <a href="../input_customer.html" class="btn-add-customer"><i class="fas fa-user-plus"></i> Tambah Pelanggan</a>

  <input type="text" id="searchInput" placeholder="Cari berdasarkan Nama, Telepon, atau Alamat" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Telepon</th>
          <th>Alamat</th>
          <th>ID Pesanan</th>
          <th>Aksi</th>
         
        </tr>
      </thead>
      <tbody  id="customerTable">
        <?php
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['Nama']}</td>
                        <td>{$row['Telepon']}</td>
                        <td>{$row['Alamat']}</td>
                        <td>{$row['ID_Pesanan']}</td>
          <td class='p-3'>
                            <a href='delete.php?id={$row['ID_Pesanan']}' class='action-button delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='6' style='text-align: center;'>Data customer tidak ditemukan</td></tr>";
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
    document.getElementById('data-master').classList.add('show');
  });

  // Fungsi pencarian
  function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.getElementById("customerTable").getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
      const nama = rows[i].getElementsByTagName("td")[1]?.innerText.toLowerCase() || "";
      const telepon = rows[i].getElementsByTagName("td")[2]?.innerText.toLowerCase() || "";
      const alamat = rows[i].getElementsByTagName("td")[3]?.innerText.toLowerCase() || "";

      // Menampilkan baris jika cocok dengan input pencarian
      if (nama.includes(input) || telepon.includes(input) || alamat.includes(input)) {
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