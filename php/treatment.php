<?php
// Koneksi ke database
include 'database.php';

// Query untuk mengambil data customer
$sql = "SELECT Nama_Treatment, Treatment_ID, Deskripsi, Harga, Estimasi FROM treatmen";
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
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f3f4f6;
      min-height: 100vh;
      display: flex;
    }

    /* Sidebar Styles */
    .sidebar {
      width: 260px;
      background: #fff;
      padding: 20px;
      height: 100vh;
      position: fixed;
      transition: all 0.3s ease;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
      z-index: 1000;
    }

    .sidebar.hidden {
      transform: translateX(-230px);
    }

    .toggle-sidebar {
      position: absolute;
      right: -15px;
      top: 20px;
      background: #3b82f6;
      border: none;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      color: white;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .toggle-sidebar:hover {
      background: #2563eb;
      transform: scale(1.1);
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar > ul > li {
      margin-bottom: 15px;
      animation: slideIn 0.3s ease forwards;
      opacity: 0;
      animation-delay: calc(var(--i) * 0.1s);
    }

    .sidebar a {
      color: #4b5563;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 8px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .sidebar a:hover, .sidebar a.active {
      background: #f3f4f6;
      color: #3b82f6;
    }

    .sidebar i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar ul ul {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      margin-left: 30px;
    }

    .sidebar ul ul.show {
      max-height: 200px;
    }

    .sidebar ul ul li {
      margin: 5px 0;
    }

    .sidebar ul ul a {
      font-size: 0.9em;
      padding: 8px 10px;
    }

    /* Content Styles */
    .content {
      margin-left: 260px;
      padding: 30px;
      width: calc(100% - 260px);
      transition: margin-left 0.2s ease-in-out, width 0.2s ease-in-out;
    }

    .content.full-width {
      margin-left: 30px;
      width: calc(100% - 30px);
    }

    /* Table Styles */
    .table-container {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      margin-top: 0px;
      overflow-x: auto;
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


    /* Animations */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-10px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .sidebar {
        width: 230px;
      }
      
      .content {
        margin-left: 230px;
        width: calc(100% - 230px);
        padding: 20px;
      }
      
      .content.full-width {
        margin-left: 30px;
        width: calc(100% - 30px);
      }

      .table-container {
        padding: 10px;
      }

      th, td {
        padding: 8px;
        font-size: 0.9em;
      }
        }
        .btn-back {
    display: inline-block;
    margin-bottom: 20px;
    padding: 8px 16px;
    background-color: #3b82f6;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
    }

    .btn-back:hover {
    background-color: #2563eb;
    transform: scale(1.05);
    }

      /* Warna khusus untuk ikon di kolom aksi */
.text-yellow-500 {
    color: #f59e0b; /* Warna kuning */
}

.text-yellow-700 {
    color: #d97706; /* Warna kuning lebih gelap */
}

.text-blue-500 {
    color: #3b82f6; /* Warna biru */
}

.text-blue-700 {
    color: #2563eb; /* Warna biru lebih gelap */
}

.text-red-500 {
    color: #ef4444; /* Warna merah */
}

.text-red-700 {
    color: #dc2626; /* Warna merah lebih gelap */
}

/* Hover effects */
.text-yellow-500:hover {
    color: #d97706;
}

.text-blue-500:hover {
    color: #2563eb;
}

.text-red-500:hover {
    color: #dc2626;
}
/* Style untuk tombol aksi di kolom Aksi */
.action-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    margin-right: 5px;
    border-radius: 5px;
    font-size: 1.1em;
    color: #fff;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.action-button.detail {
    background-color: #f59e0b; /* Warna kuning */
}

.action-button.detail:hover {
    background-color: #d97706;
}

.action-button.edit {
    background-color: #3b82f6; /* Warna biru */
}

.action-button.edit:hover {
    background-color: #2563eb;
}

.action-button.delete {
    background-color: #ef4444; /* Warna merah */
}

.action-button.delete:hover {
    background-color: #dc2626;
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

  </style>
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
        <li><a href="customer.php" ><i class="fas fa-users"></i>Customer</a></li>
        <li><a href="treatment.php"class="active"><i class="fas fa-shoe-prints"></i>Treatment</a></li>
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
        <li><a href="#"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="#"><i class="fas fa-truck"></i>Pengiriman</a></li>
      </ul>
    </li>
  </ul>
</div>

<!-- Content -->
<div class="content" id="content">
  <h1 class="page-title">Data Customer</h1>
  <a href="../admin.html" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
  <a href="tambah_customer.php" class="btn-add-customer"><i class="fas fa-user-plus"></i> Tambah Pelanggan</a>

<input type="text" id="searchInput" placeholder="Cari berdasarkan Nama, Telepon, atau Alamat" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama_Treatment</th>
          <th>ID</th>
          <th>Deskripsi</th>
          <th>Harga</th>
          <th>Estimasi</th>
          <th>Aksi</th>
       
        </tr>
      </thead>
      <tbody id="TreatmentTable">
        <?php
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['Nama_Treatment']}</td>
                        <td>{$row['Treatment_ID']}</td>
                        <td>{$row['Deskripsi']}</td>
                        <td>{$row['Harga']}</td>
                        <td>{$row['Estimasi']}</td>

                                      <td class='p-3'>
    <a href='detail.php?id={$row['Treatment_ID']}' class='action-button detail'><i class='fas fa-info-circle'></i> </a>
    <a href='edit.php?id={$row['Treatment_ID']}' class='action-button edit'><i class='fas fa-edit'></i> </a>
    <a href='delete.php?id={$row['Treatment_ID']}' class='action-button delete'><i class='fas fa-trash'></i> </a>
</td>
                   
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='6' style='text-align: center;'>Data treatment tidak ditemukan</td></tr>";
        }
        ?>
      </tbody>
    </table>
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
      if (menu.id !== id && menu.id !== 'data-master') {
        menu.classList.remove('show');
      }
    });
    
    if (id !== 'data-master') {
      submenu.classList.toggle('show');
    }
  }

  // Keep data-master submenu open on page load
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('data-master').classList.add('show');
  });

  // Fungsi pencarian
  function searchFunction() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.getElementById("TreatmentTable").getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        const namaTreatment = rows[i].getElementsByTagName("td")[1]?.innerText.toLowerCase() || "";
        const deskripsi = rows[i].getElementsByTagName("td")[3]?.innerText.toLowerCase() || "";

        if (namaTreatment.includes(input) || deskripsi.includes(input)) {
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