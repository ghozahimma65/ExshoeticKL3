<?php
// Ambil data dari URL
$va_number = isset($_GET['va']) ? $_GET['va'] : '';
$total_tagihan = isset($_GET['tagihan']) ? $_GET['tagihan'] : 0;

if (!$va_number || !$total_tagihan) {
    echo "Data tidak valid.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Virtual Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .va-box {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Informasi Pembayaran Virtual Account</h2>
        <p>Silakan lakukan pembayaran menggunakan detail berikut:</p>
        <div class="va-box">
            <p><strong>Nomor VA:</strong> <?php echo $va_number; ?></p>
            <p><strong>Total Tagihan:</strong> Rp <?php echo number_format($total_tagihan, 0, ',', '.'); ?></p>
        </div>
        <p>Harap selesaikan pembayaran sebelum 24 jam untuk menghindari pembatalan pesanan.</p>
    </div>
</body>
</html>
