<?php
// Koneksi ke database
include('database.php');

// Ambil bulan dan tahun dari input atau gunakan nilai default
$bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date('m');
$tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');


// Query dengan filter bulan dan tahun
$sql = "SELECT c.Nama, c.No_Hp AS Telepon, c.Alamat, c.ID_Pesanan, 
        p.Tanggal_Pesanan, p.Treatment_ID, p.Merk_Sepatu, p.Status 
        FROM customer c
        LEFT JOIN pesanan p ON c.ID_Pesanan = p.ID_Pesanan
        WHERE MONTH(p.Tanggal_Pesanan) = ? AND YEAR(p.Tanggal_Pesanan) = ?";

// Query untuk menghitung jumlah pesanan yang sudah selesai
$query_selesai = "SELECT COUNT(*) AS jumlah_selesai FROM pesanan WHERE Status = 'Sudah Selesai'";
$result_selesai = $conn->query($query_selesai);
$jumlah_selesai = 0;

if ($result_selesai->num_rows > 0) {
    $row_selesai = $result_selesai->fetch_assoc();
    $jumlah_selesai = $row_selesai['jumlah_selesai'];
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bulan, $tahun);
$stmt->execute();
$result = $stmt->get_result();



if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo "<div class='popup success'>
            <span class='close-btn' onclick='closePopup()'>&times;</span>
            Pesanan berhasil dihapus!
          </div>";
} elseif (isset($_GET['status']) && $_GET['status'] == 'error') {
    echo "<div class='popup error'>
            <span class='close-btn' onclick='closePopup()'>&times;</span>
            Terjadi kesalahan. Pesanan tidak dapat dihapus.
          </div>";
} elseif (isset($_GET['status']) && $_GET['status'] == 'invalid') {
    echo "<div class='popup invalid'>
            <span class='close-btn' onclick='closePopup()'>&times;</span>
            ID Pesanan tidak valid!
          </div>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Customer - Exshoetic Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="admin-css/sidebar.css"> 
  <link rel="stylesheet" href="admin-css/cont-treatment.css"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


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
  <h1 class="page-title">Data Pesanan</h1>
  <a href="../admin-php/adminPowerBi.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>

  <form method="GET" class="filter-container">
    <label for="bulan">Pilih Bulan</label>
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

    <h4>Treatment Selesai</h4>
      <div class="value" id="total-treatment-selesai">Selesai</div>
      <p><?= $jumlah_selesai ?> Pesanan</p>
  </form>

  <!-- Notifikasi -->
  <div id="notification" class="notification hidden"></div>

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
                <td>
                    <span class='status-badge status-" . strtolower(str_replace(' ', '-', $row['Status'])) . "'>{$row['Status']}</span>
                </td>
                <td>
                    <!-- Form to update the status -->
                    <form method='POST' action='update_status.php' style='display:inline-block;'>
                        <input type='hidden' name='id_pesanan' value='{$row['ID_Pesanan']}'>
                        <select name='status' onchange='updateStatus(this, {$row['ID_Pesanan']})'>
                            <option value='Belum Selesai' " . ($row['Status'] == 'Belum Selesai' ? 'selected' : '') . ">Belum Selesai</option>
                            <option value='Sudah Selesai' " . ($row['Status'] == 'Sudah Selesai' ? 'selected' : '') . ">Sudah Selesai</option>
                        </select>
                    </form>
                    <!-- Delete order with trash icon -->
                    <form method='POST' action='delete_pesanan.php' onsubmit='return confirm(\"Apakah Anda yakin ingin menghapus pesanan ini?\");' style='display:inline-block;'>
                        <input type='hidden' name='id_pesanan' value='{$row['ID_Pesanan']}'>
                        <button type='submit' style='background-color: red; border: none; cursor: pointer; padding: 5px 10px; border-radius: 5px;'>
                            <i class='fas fa-trash' style='color: white; font-size: 20px;'></i>
                        </button>
                    </form>
                </td>
            </tr>";
        $no++;
    }
} else {
    echo "<tr><td colspan='10' style='text-align: center;'>Data customer tidak ditemukan</td></tr>";
}
?>


      </tbody>
    </table>
  </div>
</div>

<style>
/* Gaya notifikasi */
.notification {
    padding: 15px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
    margin: 10px 0;
    transition: all 0.5s ease;
}

.notification.success {
    background-color: #D4EDDA;
    color: #155724;
    border: 1px solid #C3E6CB;
}

.notification.error {
    background-color: #F8D7DA;
    color: #721C24;
    border: 1px solid #F5C6CB;
}

.notification.hidden {
    opacity: 0;
    visibility: hidden;
}

/* Gaya lainnya */
.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
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

/* Popup styles */
.popup {
    position: fixed;
    top: 20px; /* Bisa disesuaikan untuk mengatur jarak dari atas */
    left: 50%;
    transform: translateX(-50%); /* Untuk memposisikan pop-up tepat di tengah secara horizontal */
    background-color: #4CAF50;
    color: white;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 300px;
    font-size: 16px;
    font-weight: bold;
    text-align: center; /* Agar teks berada di tengah */
}

.popup.success {
    background-color: #4CAF50; /* Green */
}

.popup.error {
    background-color: #f44336; /* Red */
}

.popup.invalid {
    background-color: #ff9800; /* Orange */
}

.popup .close-btn {
    background-color: transparent;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

.popup .close-btn:hover {
    color: #ddd;
}

/* Hide the popup */
.popup.hidden {
    display: none;
}

</style>

<script>
function updateStatus(selectElement, idPesanan) {
    const newStatus = selectElement.value;
    const statusCell = selectElement.closest('tr').querySelector('.status-badge');

    // Update tampilan status badge
    statusCell.className = `status-badge status-${newStatus.replace(/\s+/g, '-').toLowerCase()}`;
    statusCell.textContent = newStatus;

    // Kirim data ke server
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`id_pesanan=${idPesanan}&status=${encodeURIComponent(newStatus)}`);

    // Tampilkan notifikasi
    const notification = document.getElementById('notification');
    xhr.onload = function () {
        if (xhr.status === 200) {
            if (xhr.responseText.includes("Status berhasil diperbarui")) {
                notification.textContent = "Status berhasil diperbarui.";
                notification.className = "notification success";
            } else {
                notification.textContent = "Gagal memperbarui status: " + xhr.responseText;
                notification.className = "notification error";
            }
        } else {
            notification.textContent = "Kesalahan server: " + xhr.statusText;
            notification.className = "notification error";
        }
        notification.classList.remove('hidden');
        setTimeout(() => notification.classList.add('hidden'), 3000);
    };

    xhr.onerror = function () {
        notification.textContent = "Kesalahan jaringan.";
        notification.className = "notification error";
        notification.classList.remove('hidden');
        setTimeout(() => notification.classList.add('hidden'), 3000);
    };
}

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



  // Close popup when the close button is clicked
function closePopup() {
    var popup = document.querySelector('.popup');
    popup.classList.add('hidden');
}

// Optional: Auto close the popup after 5 seconds
setTimeout(function() {
    var popup = document.querySelector('.popup');
    if (popup) {
        popup.classList.add('hidden');
    }
}, 5000); // 5000ms = 5 seconds

</script>

</body>
</html>
