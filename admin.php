<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke login jika tidak ada session
    exit();
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
  <link rel="stylesheet" href="css/admin.css"> 
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
        <li><a href="php/customer.php"><i class="fas fa-users"></i>Customer</a></li>
        <li><a href="php/treatment.php"><i class="fas fa-shoe-prints"></i>Treatment</a></li>
      </ul>
    </li>
    <li style="--i:2">
      <a onclick="toggleSubmenu('keuangan')">
        <i class="fas fa-chart-line"></i>
        Keuangan
      </a>
      <ul id="keuangan">
        <li><a href="#"><i class="fas fa-arrow-up"></i>Pemasukan</a></li>
        <li><a href="#"><i class="fas fa-arrow-down"></i>Pengeluaran</a></li>
      </ul>
    </li>
    <li style="--i:3">
      <a onclick="toggleSubmenu('transaksi')">
        <i class="fas fa-exchange-alt"></i>
        Transaksi
      </a>
      <ul id="transaksi">
        <li><a href="#"><i class="fas fa-shopping-cart"></i>Pesanan</a></li>
        <li><a href="#"><i class="fas fa-truck"></i>Pengiriman</a></li>
      </ul>
    </li>
  </ul>
</div>

<div class="content" id="content">
  <div class="stats-container">
    <div class="stat-card">
      <h4>Total Pesanan</h4>
      <div class="value">2,845</div>
    </div>
    <div class="stat-card">
      <h4>Pendapatan</h4>
      <div class="value">$10,840</div>
    </div>
    <div class="stat-card">
      <h4>Total Customer</h4>
      <div class="value">1,250</div>
    </div>
    <div class="stat-card">
      <h4>Treatment Selesai</h4>
      <div class="value">985</div>
    </div>
  </div>

  <div class="chart-container">
    <div class="card">
      <h3>Grafik Pesanan</h3>
      <p>Total Profit <span style="color: #3b82f6; font-weight: 600; font-size: 1.2em;">$10,840</span></p>
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

  // Line Chart
  const lineCtx = document.getElementById('lineChart').getContext('2d');
  const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Total Pesanan',
        data: [120, 150, 180, 200, 250, 300, 270, 290, 320, 340, 380, 400],
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

  // Pie Chart
  const pieCtx = document.getElementById('pieChart').getContext('2d');
  const pieChart = new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['Sneakers', 'Boots', 'Sandals', 'Formal'],
      datasets: [{
        label: 'Jenis Sepatu',
        data: [40, 20, 25, 15],
        backgroundColor: ['#3b82f6', '#2563eb', '#1e40af', '#1d4ed8'],
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });
</script>
<script>
  // Ambil data dari API menggunakan fetch
  async function fetchData() {
    try {
      const response = await fetch('http://localhost/Exshoetic/php/dashboard_data.php');
      const data = await response.json();

      // Ambil data pesanan harian
      const dailyOrders = data.daily_orders;
      const labels = dailyOrders.map(item => item.tanggal);
      const ordersData = dailyOrders.map(item => parseInt(item.jumlah_pesanan));
      const revenueData = dailyOrders.map(item => parseInt(item.total_pendapatan));

      // Update line chart
      updateLineChart(labels, ordersData, revenueData);

    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }

  // Fungsi untuk memperbarui grafik line chart
  function updateLineChart(labels, ordersData, revenueData) {
  const lineCtx = document.getElementById('lineChart').getContext('2d');

  // Hancurkan chart sebelumnya hanya jika sudah merupakan instance Chart
  if (window.lineChart instanceof Chart) {
    window.lineChart.destroy();
  }

  // Buat line chart baru
  window.lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Jumlah Pesanan',
          data: ordersData,
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.3,
          fill: true
        },
        {
          label: 'Total Pendapatan (Rp)',
          data: revenueData,
          borderColor: '#1d4ed8',
          backgroundColor: 'rgba(29, 78, 216, 0.1)',
          tension: 0.3,
          fill: true
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' }
      },
      scales: {
        y: { 
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString('id-ID');
            }
          }
        }
      }
    }
  });
}


  // Panggil fetchData saat halaman dimuat
  document.addEventListener('DOMContentLoaded', fetchData);
</script>

  
</body>
</html>