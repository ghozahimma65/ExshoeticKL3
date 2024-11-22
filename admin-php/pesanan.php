<?php
// Koneksi ke database
include 'database.php';

// Query untuk mengambil data customer dengan kolom tambahan
$sql = "SELECT c.Nama, c.No_Hp AS Telepon, c.Alamat, c.ID_Pesanan, 
        p.Tanggal_Pesanan, p.Treatment_ID, p.Merk_Sepatu, p.Status 
        FROM customer c
        LEFT JOIN pesanan p ON c.ID_Pesanan = p.ID_Pesanan";
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
        <li><a href="#"><i class="fas fa-arrow-down"></i>Pengeluaran</a></li>
      </ul>
    </li>
    <li style="--i:3">
      <a onclick="toggleSubmenu('transaksi')">
        <i class="fas fa-exchange-alt"></i>
        Transaksi
      </a>
      <ul id="transaksi" class="show">
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
  <h1 class="page-title">Data Pesanan</h1>
  <a href="../admin-php/admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>

  <input type="text" id="searchInput" placeholder="Cari berdasarkan Nama, Telepon, atau Alamat" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Telepon</th>
          <th>Alamat</th>
          <th>ID</th>
          <th>Tanggal</th>
          <th>TM_ID</th>
          <th>Merk</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="customerTable">
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
                        <td>{$row['Tanggal_Pesanan']}</td>
                        <td>{$row['Treatment_ID']}</td>
                        <td>{$row['Merk_Sepatu']}</td>
                        <td><span class='status-badge status-{$row['Status']}'>{$row['Status']}</span></td>
                      <td class='p-3'>
                            <form method='POST' action='../php/update_status.php'>
                              <input type='hidden' name='id_pesanan' value='{$row['ID_Pesanan']}'>
                              <select name='status' onchange='this.form.submit()'>
                                <option value='Diambil' " . ($row['Status'] == 'Diambil' ? 'selected' : '') . ">Diambil</option>
                                <option value='Proses' " . ($row['Status'] == 'Proses' ? 'selected' : '') . ">Proses</option>
                                <option value='Diantar' " . ($row['Status'] == 'Diantar' ? 'selected' : '') . ">Diantar</option>
                                <option value='Selesai' " . ($row['Status'] == 'Selesai' ? 'selected' : '') . ">Selesai</option>
                              </select>
                            </form>
                        </td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='11' style='text-align: center;'>Data customer tidak ditemukan</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<style>
.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.status-diambil {
    background-color: #FFF3CD;
    color: #856404;
}

.status-proses {
    background-color: #CCE5FF;
    color: #004085;
}

.status-diantar {
    background-color: #D1ECF1;
    color: #0C5460;
}

.status-selesai {
    background-color: #D4EDDA;
    color: #155724;
}

.table-container {
    overflow-x: auto;
}

table {
    min-width: 100%;
    white-space: nowrap;
}

td, th {
    padding: 10px;
}

.action-button {
    margin: 0 3px;
}
</style>

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

  // Membiarkan submenu transkasi tetap terbuka saat halaman dimuat
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('transaksi').classList.add('show');
  });


  // Membuat koneksi WebSocket untuk update status secara real-time
  const ws = new WebSocket('ws://your-websocket-server');

  ws.onmessage = function(event) {
    const data = JSON.parse(event.data);
    if (data.type === 'status_update') {
      updateCustomerStatus(data.customer_id, data.new_status);
    }
  };

  // Fungsi untuk memperbarui status pelanggan
  function updateCustomerStatus(customerId, newStatus) {
    const rows = document.getElementById("customerTable").getElementsByTagName("tr");

    for (let row of rows) {
      const idCell = row.getElementsByTagName("td")[4]; // Kolom ID Pesanan
      if (idCell && idCell.innerText === customerId) {
        const statusCell = row.getElementsByTagName("td")[5];
        const statusBadge = statusCell.getElementsByClassName('status-badge')[0];

        // Menghapus kelas status lama
        statusBadge.classList.remove('status-active', 'status-inactive', 'status-pending', 'status-completed');

        // Menambahkan kelas status baru
        statusBadge.classList.add(`status-${newStatus.toLowerCase()}`);

        // Memperbarui teks status
        statusBadge.innerText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
      }
    }
  }

// Tambahan untuk fungsi pencarian yang diperbarui
function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.getElementById("customerTable").getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        const nama = rows[i].getElementsByTagName("td")[1]?.innerText.toLowerCase() || "";
        const telepon = rows[i].getElementsByTagName("td")[2]?.innerText.toLowerCase() || "";
        const alamat = rows[i].getElementsByTagName("td")[3]?.innerText.toLowerCase() || "";
        const merkSepatu = rows[i].getElementsByTagName("td")[7]?.innerText.toLowerCase() || "";
        const status = rows[i].getElementsByTagName("td")[9]?.innerText.toLowerCase() || "";

        if (nama.includes(input) || 
            telepon.includes(input) || 
            alamat.includes(input) || 
            merkSepatu.includes(input) || 
            status.includes(input)) {
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