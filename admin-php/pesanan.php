<?php
// Koneksi ke database
include 'database.php';

// Query untuk mengambil data customer dengan kolom tambahan
$sql = "SELECT c.Nama, c.No_Hp AS Telepon, c.Alamat, c.ID_Pesanan, 
        p.Tanggal_Pesanan, p.Treatment_ID, p.Merk_Sepatu, p.Total_Tagihan, p.Status 
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
        <i class="fas fa-database"></i> Data Master
      </a>
      <ul id="data-master" class="show">
        <li><a href="customer.php" class="active"><i class="fas fa-users"></i>Customer</a></li>
        <li><a href="treatment.php"><i class="fas fa-shoe-prints"></i>Treatment</a></li>
      </ul>
    </li>
    <li style="--i:2">
      <a onclick="toggleSubmenu('keuangan')">
        <i class="fas fa-chart-line"></i> Keuangan
      </a>
      <ul id="keuangan">
        <li><a href="#"><i class="fas fa-arrow-up"></i>Pemasukan</a></li>
        <li><a href="#"><i class="fas fa-arrow-down"></i>Pengeluaran</a></li>
      </ul>
    </li>
    <li style="--i:3">
      <a onclick="toggleSubmenu('transaksi')">
        <i class="fas fa-exchange-alt"></i> Transaksi
      </a>
      <ul id="transaksi">
        <li><a href="../admin-php/pesanan.php"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="#"><i class="fas fa-truck"></i>Pengiriman</a></li>
      </ul>
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
          <th>ID_Pesanan</th>
          <th>Tanggal_Pesanan</th>
          <th>Treatment_ID</th>
          <th>Merk_Sepatu</th>
          <th>Total_Tagihan</th>
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
                        <td>Rp " . number_format($row['Total_Tagihan'], 0, ',', '.') . "</td>
                        <td><span class='status-badge status-{$row['Status']}'>{$row['Status']}</span></td>
                        <td class='p-3'>
                            <a href='detail.php?id={$row['ID_Pesanan']}' class='action-button detail'><i class='fas fa-info-circle'></i></a>
                            <a href='edit.php?id={$row['ID_Pesanan']}' class='action-button edit'><i class='fas fa-edit'></i></a>
                            <a href='delete.php?id={$row['ID_Pesanan']}' class='action-button delete'><i class='fas fa-trash'></i></a>
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

.status-pending {
    background-color: #FFF3CD;
    color: #856404;
}

.status-process {
    background-color: #CCE5FF;
    color: #004085;
}

.status-completed {
    background-color: #D4EDDA;
    color: #155724;
}

.status-cancelled {
    background-color: #F8D7DA;
    color: #721C24;
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
  // Fungsi untuk toggle submenu
  function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    const allSubmenus = document.querySelectorAll('.sidebar ul ul');

    // Menutup semua submenu yang bukan milik submenu yang dipilih
    allSubmenus.forEach(menu => {
      if (menu !== submenu) {
        menu.classList.remove('show');
      }
    });

    // Toggle submenu yang dipilih
    submenu.classList.toggle('show');
  }

  // Membiarkan submenu transaksi tetap terbuka saat halaman dimuat
  document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan kondisi untuk submenu yang aktif, misalnya 'transaksi'
    const activeMenu = window.location.href.includes("pesanan.php") ? 'transaksi' : '';
    if (activeMenu) {
      document.getElementById(activeMenu).classList.add('show');
    }
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