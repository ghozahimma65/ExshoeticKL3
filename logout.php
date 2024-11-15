<?php
session_start();
session_unset();
session_destroy();

// Menghancurkan semua session dan menghapus session
session_unset(); // Menghapus semua data sesi
session_destroy(); // Menghancurkan sesi

// Arahkan pengguna kembali ke halaman login
header("Location: login.php");
exit();
?>