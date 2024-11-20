<?php
// Ambil data dari URL
$va_number = htmlspecialchars($_GET['va']);
$tagihan = htmlspecialchars($_GET['tagihan']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Virtual Account</title>
</head>
<body>
    <h1>Pembayaran Virtual Account</h1>
    <p>Silakan lakukan pembayaran menggunakan detail berikut:</p>
    <table>
        <tr>
            <td>Nomor Virtual Account:</td>
            <td><strong><?php echo $va_number; ?></strong></td>
        </tr>
        <tr>
            <td>Total Tagihan:</td>
            <td><strong>Rp <?php echo number_format($tagihan, 0, ',', '.'); ?></strong></td>
        </tr>
    </table>
    <p>Setelah pembayaran selesai, status pesanan Anda akan diperbarui secara otomatis.</p>
</body>
</html>
