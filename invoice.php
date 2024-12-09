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


<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Invar - Invoice HTML Template - Restaurant Bill</title>
    <meta name="author" content="themeholy">
    <meta name="description" content="Invar - Invoice HTML Template">
    <meta name="keywords" content="Invar - Invoice HTML Template" />
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/img/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/img/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!--==============================
	  Google Fonts
	============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">


    <!--==============================
	    All CSS File
	============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/invoice_bootstrap.min.css">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="css/invoice.css">

</head>

<body>


    <!--[if lte IE 9]>
    	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->


    <!--********************************
   		Code Start From Here 
	******************************** -->

    <div class="invoice-container-wrap">
        <div class="invoice-container">
            <main>
                <!--==============================
Invoice Area
==============================-->
                <div class="themeholy-invoice invoice_style3 color_blue">
                    <div class="download-inner" id="download_section">
                        <!--==============================
	Header Area
==============================-->
                        <header class="themeholy-header header-layout1">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto">
                                    <div class="header-logo">
                                        <a href="index.html"><img src="assets/img/logo.svg" alt="Invar"></a>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <h1 class="big-title text-white">Invoice</h1>
                                </div>
                            </div>
                            <div class="header-bottom">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-auto">
                                        <p class="invoice-number me-4"><b>Invoice No: </b>#745664</p>
                                    </div>
                                    <div class="col-auto">
                                        <p class="invoice-date"><b>Date: </b>22/03/2023</p>
                                    </div>
                                </div>
                            </div>
                        </header>
                        <div class="row justify-content-between mb-4">
                            <div class="col-auto">
                                <div class="invoice-left">
                                    <b>Invoiced To:</b>
                                    <address>
                                        Alex Farnandes <br>
                                        450 E 96th St, Indianapolis, <br>
                                        WRHX+8Q IN 46240, <br>
                                        United States
                                    </address>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="invoice-right">
                                    <b>Pay To:</b>
                                    <address>
                                        Invar Inc <br>
                                        4510 E 96th St, Indianapolis, <br>
                                        IN 46240, United States <br>
                                        info@Invar.com
                                    </address>
                                </div>
                            </div>
                        </div>
                        <table class="invoice-table table-stripe">
                            <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>Item Description</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Easy Chicken Masala</td>
                                    <td>$55.00</td>
                                    <td>1</td>
                                    <td>$55.00</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Boiled Organic Egg</td>
                                    <td>$25.00</td>
                                    <td>3</td>
                                    <td>$75.00</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Stuffed Strawberry</td>
                                    <td>$36.00</td>
                                    <td>2</td>
                                    <td>$72.00</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Grilled Smoked Chicken</td>
                                    <td>$90.00</td>
                                    <td>4</td>
                                    <td>$360.00</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Antioxidant Fruits Mix</td>
                                    <td>$29.00</td>
                                    <td>5</td>
                                    <td>$145.00</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Party Platter Wings</td>
                                    <td>$75.00</td>
                                    <td>1</td>
                                    <td>$75.00</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <div class="invoice-left">
                                    <b>Payment Info:</b>
                                    <p class="mb-0">Account No: 124567893 <br>
                                        A/C Name: Anthony Mithon <br>
                                        Bank Details: 1970 Village Center, <br>
                                        Las Vegas, United States</p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <table class="total-table">
                                    <tr>
                                        <th>Sub Total:</th>
                                        <td>$782.00</td>
                                    </tr>
                                    <tr>
                                        <th>Tax:</th>
                                        <td>$00.00</td>
                                    </tr>
                                    <tr>
                                        <th>Discount:</th>
                                        <td>$82.00</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>$700.00</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row mt-4 text-center">
                                <div class="col">
                                    <p style="font-weight: bold; font-size: 15pt;">Konfirmasi pesananmu dengan mengirim bukti transfer di link di bawah:</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col text-center">
                                    <a href="https://wa.link/chj4at" target="_blank" class="btn btn-primary">Konfirmasi Pesanan</a>
                                </div>
                            </div>
                            
                            

    <!--==============================
    All Js File
============================== -->
    <!-- Jquery -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- PDF Generator -->
    <script src="assets/js/jspdf.min.js"></script>
    <script src="assets/js/html2canvas.min.js"></script>
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>

</body>

</html>