
<?php
session_start();
include('database.php');

// Query untuk menghitung jumlah pesanan yang sudah selesai
$query_selesai = "SELECT COUNT(*) AS jumlah_selesai FROM pesanan WHERE Status = 'Sudah Selesai'";
$result_selesai = $conn->query($query_selesai);
$jumlah_selesai = 0;

if ($result_selesai->num_rows > 0) {
    $row_selesai = $result_selesai->fetch_assoc();
    $jumlah_selesai = $row_selesai['jumlah_selesai'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>ESAC Admin Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/exshoetic/admin-css/sidebar.css"> 
  <link rel="stylesheet" href="/exshoetic/admin-css/cont-admin.css"> 

</head>
<body>
  
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


<div class="content" id="content">
  <div class="stats-container">
    <div class="stat-card">
      <h4>Total Pesanan</h4>
      <div class="value" id="total-pesanan">Loading...</div>
    </div>
    <div class="stat-card">
      <h4>Pendapatan</h4>
      <div class="value" id="total-pendapatan">Loading...</div>
    </div>
    <div class="stat-card">
      <h4>Total Customer</h4>
      <div class="value" id="total-customer">Loading...</div>
    </div>
    <div class="stat-card">
      <h4>Treatment Selesai</h4>
      <div class="value" id="total-treatment-selesai">Selesai</div>
      <p><?= $jumlah_selesai ?> Pesanan</p>
    </div>
  </div>
  

  <div class="chart-container">
    <div class="card">
      <h3>Grafik Pesanan</h3>
      <p>Total Profit <span style="color: #3b82f6; font-weight: 600; font-size: 1.2em;" id="profit">Loading...</span></p>
      <canvas id="lineChart"></canvas>
    </div>
    <div class="card">
      <h3>Jenis Treatment</h3>
      <canvas id="pieChart"></canvas>
    </div>
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

    // Ambil data dari file PHP yang sudah dipisahkan
    async function fetchData() {
    try {
      const response = await fetch('dashboard_data.php'); // Mengambil data dari file PHP
      const data = await response.json();

      // Update nilai-nilai di dashboard
      document.getElementById('total-pesanan').innerText = data.total_pesanan;
      document.getElementById('total-pendapatan').innerText = 'Rp ' + data.total_pendapatan.toLocaleString('id-ID');
      document.getElementById('total-customer').innerText = data.total_customer;
      // document.getElementById('total-treatment-selesai').innerText = data.total_treatment_selesai;
      document.getElementById('profit').innerText = 'Rp ' + data.total_pendapatan.toLocaleString('id-ID');

      // Update grafik (Chart.js)
      updateLineChart(data);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }

  function updateLineChart(data) {
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Total Pesanan',
          data: Array(12).fill(100),  // Data dummy, bisa disesuaikan dengan data asli
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }

  // Panggil fetchData saat halaman dimuat
  document.addEventListener('DOMContentLoaded', fetchData);
</script>
 


  
</body>
</html>
