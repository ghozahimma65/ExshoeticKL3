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
  <link rel="stylesheet" href="/exshoetic/admin-css/sidebar.css"> 
  <link rel="stylesheet" href="/exshoetic/admin-css/cont-treatment.css"> 
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
        <li><a href="pesanan.php"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="#"><i class="fas fa-truck"></i>Pengiriman</a></li>
      </ul>
    </li>
  </ul>
</div>

<!-- Content -->
<div class="content" id="content">
  <h1 class="page-title">Data Treatment</h1>
  <a href="../admin-php/admin.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
  <a href="tambah_customer.php" class="btn-add-customer"><i class="fas fa-user-plus"></i> Tambah Treatment</a>

<input type="text" id="searchInput" placeholder="Cari berdasarkan Nama Treatment dan Deskripsi" onkeyup="searchFunction()" style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px;">

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Treatment</th>
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
      if (menu.id !== id) {
        menu.classList.remove('show');
      }
    });
    submenu.classList.toggle('show');
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