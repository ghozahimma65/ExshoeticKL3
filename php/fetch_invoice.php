<?php
// Konfigurasi koneksi ke database
$host = 'localhost';
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda
$database = 'exshoetic_db'; // Ganti dengan nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data
$sql = "SELECT detail_pesanan.ID_Pesanan, detail_pesanan.Customer_ID, detail_pesanan.Pembayaran_ID, customer.Nama AS Nama_Customer, customer.Alamat AS Alamat_Customer, pembayaran.Metode_Pembayaran AS Metode_Pembayaran, pembayaran.Total_Tagihan AS TotalPembayaran_Pembayaran FROM detail_pesanan AS detail_pesanan INNER JOIN customer AS customer ON detail_pesanan.Customer_ID = customer.Customer_ID INNER JOIN pembayaran AS pembayaran ON detail_pesanan.Pembayaran_ID = pembayaran.Pembayaran_ID";


$result = $conn->query($sql);

// Periksa apakah data ditemukan
if ($result->num_rows > 0) {
    // Menampilkan data dalam bentuk tabel
    echo "<table border='1'>
            <tr>
                <th>Nama Customer</th>
                <th>No HP Customer</th>
                <th>Alamat Customer</th>
                <th>Keterangan Customer</th>
                <th>ID Pesanan</th>
                <th>Status Pesanan</th>
                <th>Total Tagihan</th>
                <th>Nama Treatment</th>
                <th>Harga Treatment</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['nama_customer'] . "</td>
                <td>" . $row['No_Hp_customer'] . "</td>
                <td>" . $row['alamat_customer'] . "</td>
                <td>" . $row['keterangan_customer'] . "</td>
                <td>" . $row['id_pesanan'] . "</td>
                <td>" . $row['status_pesanan'] . "</td>
                <td>" . $row['total_tagihan_pesanan'] . "</td>
                <td>" . $row['nama_treatment'] . "</td>
                <td>" . $row['harga_treatment'] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data ditemukan.";
}

// Menutup koneksi
$conn->close();
?>
