<?php
require_once 'database.php';

// Fungsi untuk membersihkan input
function cleanInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Mengambil data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data customer
    $nama = cleanInput($_POST['name']);
    $no_hp = cleanInput($_POST['phone']);
    $alamat = cleanInput($_POST['address']);
    
    // Data pesanan
    $treatment_id = intval(cleanInput($_POST['treatment_id']));
    $total_tagihan = floatval(cleanInput($_POST['total_bill']));
    $metode_pembayaran = cleanInput($_POST['payment_method']);
    
    try {
        // Mulai transaksi
        $conn->begin_transaction();
        
        // Insert data ke tabel customer
        $query_customer = "INSERT INTO customer (Nama, No_HP, Alamat) VALUES (?, ?, ?)";
        $stmt_customer = $conn->prepare($query_customer);
        $stmt_customer->bind_param("sss", $nama, $no_hp, $alamat);
        
        if (!$stmt_customer->execute()) {
            throw new Exception("Error saat menyimpan data customer: " . $stmt_customer->error);
        }
        
        // Ambil ID customer yang baru saja di-insert
        $customer_id = $conn->insert_id;
        
        // Insert data ke tabel pesanan
        $query_pesanan = "INSERT INTO pesanan (Customer_ID, Treatment_ID, Total_Tagihan) VALUES (?, ?, ?)";
        $stmt_pesanan = $conn->prepare($query_pesanan);
        $stmt_pesanan->bind_param("iid", $customer_id, $treatment_id, $total_tagihan);
        
        if (!$stmt_pesanan->execute()) {
            throw new Exception("Error saat menyimpan data pesanan: " . $stmt_pesanan->error);
        }
        
        // Ambil ID pesanan yang baru saja di-insert
        $pesanan_id = $conn->insert_id;
        
        // Insert data ke tabel pembayaran
        $query_pembayaran = "INSERT INTO pembayaran (Pesanan_ID, Metode_Pembayaran, Total_Tagihan) VALUES (?, ?, ?)";
        $stmt_pembayaran = $conn->prepare($query_pembayaran);
        $stmt_pembayaran->bind_param("isd", $pesanan_id, $metode_pembayaran, $total_tagihan);
        
        if (!$stmt_pembayaran->execute()) {
            throw new Exception("Error saat menyimpan data pembayaran: " . $stmt_pembayaran->error);
        }
        
        // Commit transaksi
        $conn->commit();
        
        // Redirect ke halaman sukses
        header("Location: ../success.html");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error
        $conn->rollback();
        
        // Tampilkan error (untuk debugging, bisa dihapus di production)
        echo "Error: " . $e->getMessage();
        
        // Redirect ke halaman error
        header("Location: ../error.html");
        exit();
    } finally {
        // Tutup statement dan koneksi
        if (isset($stmt_customer)) $stmt_customer->close();
        if (isset($stmt_pesanan)) $stmt_pesanan->close();
        if (isset($stmt_pembayaran)) $stmt_pembayaran->close();
        $conn->close();
    }
}
?>
