-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Nov 2024 pada 17.32
-- Versi server: 8.1.0
-- Versi PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exshoetic`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `Username` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Nama` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int NOT NULL,
  `Nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `No_Hp` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Alamat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ID_Pesanan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`Customer_ID`, `Nama`, `No_Hp`, `Alamat`, `ID_Pesanan`) VALUES
(1, 'Ghoza Himma Al-Farizqi', '081552950506', 'Jl. Gunung Batu no. 54 Sumbersari ', 1001),
(2, 'Nauval Zaky Rayhan', '081310384433', 'Jl. Kenanga no. 34 Kaliwates - Jember', 1002),
(3, 'Muhammad Ainur Rokhmad', '0895339370507', 'Jl. Diponegoro no. 135 Kertosari - Pasuruan', 1003),
(4, 'Halimatus Sa’diah', '085791645963', 'Jl. Kota Blater no. 83 Jenggawah - Jember', 1004),
(5, 'Adinda Chintya Firdausi', '082323443640', 'Jl. Letjen Suprapto no. 110 Ambulu - Jember', 1005),
(6, 'Iqbal Maulana', '085237338987', 'Kencong - Jember', 1006),
(7, 'Muhammad Riski', '085377271282', 'Jl. Sumatera no. 95 Sumbersari - Jember', 1007);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `DetailPesanan_ID` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ID_Pesanan` int NOT NULL,
  `Customer_ID` int NOT NULL,
  `Pembayaran_ID` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `Pembayaran_ID` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ID_Pesanan` int NOT NULL,
  `Tanggal_Pembayaran` date NOT NULL,
  `Metode_Pembayaran` enum('BRI','Mandiri','BCA','DANA','ShopeePay','SeaBank','COD','QRIS') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Total_Tagihan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`Pembayaran_ID`, `ID_Pesanan`, `Tanggal_Pembayaran`, `Metode_Pembayaran`, `Total_Tagihan`) VALUES
('PM001', 1002, '2024-11-10', 'BCA', 15000),
('PM002', 1003, '2024-11-10', 'BRI', 35000),
('PM003', 1001, '2024-11-09', 'QRIS', 35000),
('PM004', 1004, '2024-11-11', 'Mandiri', 45000),
('PM005', 1005, '2024-11-09', 'QRIS', 120000),
('PM006', 1006, '2024-11-10', 'DANA', 20000),
('PM007', 1007, '2024-11-13', 'BCA', 30000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `ID_Pesanan` int NOT NULL,
  `Tanggal_Pesanan` date NOT NULL,
  `Treatment_ID` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Total_Tagihan` int NOT NULL,
  `Status` enum('Diambil','Proses','Diantar','Selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`ID_Pesanan`, `Tanggal_Pesanan`, `Treatment_ID`, `Total_Tagihan`, `Status`) VALUES
(1001, '2024-11-08', 'TM003', 30000, 'Proses'),
(1002, '2024-11-09', 'TM001', 20000, 'Selesai'),
(1003, '2024-11-09', 'TM004', 35000, 'Selesai'),
(1004, '2024-11-10', 'TM006', 45000, 'Diambil'),
(1005, '2024-11-11', 'TM008', 120000, 'Diambil'),
(1006, '2024-11-10', 'TM001', 20000, 'Proses'),
(1007, '2024-11-10', 'TM003', 30000, 'Diambil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `treatmen`
--

CREATE TABLE `treatmen` (
  `Treatment_ID` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `Nama_Treatment` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `Deskripsi` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Harga` decimal(27,0) DEFAULT NULL,
  `Estimasi` varchar(225) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `treatmen`
--

INSERT INTO `treatmen` (`Treatment_ID`, `Nama_Treatment`, `Deskripsi`, `Harga`, `Estimasi`) VALUES
('TM001', 'Deep Clean', 'Pencucian sepatu secara menyeluruh, dari bagian luar hingga dalam, termasuk tali sepatu.', 20000, '2-3 Hari'),
('TM002', 'Fast Cleaning', 'Pencucian cepat yang hanya membersihkan bagian atas dan bagian tengah sepatu ', 15000, '2-3 Hari'),
('TM003', 'Repaint', 'Memperpanjang umur sepatu dengan mengecat ulang sepatu yang warnanya sudah memudar atau permukaannya tergores. ', 30000, '4-5 Hari'),
('TM004', 'Leather Care', 'Membersihkan sepatu yang berbahan kulit', 35000, '2-3 Hari'),
('TM005', 'Whitening', 'Membersihkan sepatu khusus berwarna putih berbahan canvas/mesh.', 35000, '2-3 Hari'),
('TM006', 'Unyellowing', 'Memutihkan sol yang telah menguning', 45000, '6-7 hari'),
('TM007', 'REGLUE', 'Pemasangan kembali bagian sepatu yang terbuka dengan cara di lem', 35000, '6-7 Hari'),
('TM008', 'Repaint Custom', 'Pengecatan ulang sepatu yang dibuat sesuai dengan keinginan pelanggan', 120000, '13-14 Hari'),
('TM009', 'Repair Sol', 'Reparasi sepatu atau perbaikan yang dilakukan pada sepatu yang mengalami kerusakan', 40000, '13-14 Hari');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Username`);

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`),
  ADD KEY `ID_Pesanan` (`ID_Pesanan`);

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`DetailPesanan_ID`),
  ADD KEY `ID_Pesanan` (`ID_Pesanan`,`Customer_ID`,`Pembayaran_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `Pembayaran_ID` (`Pembayaran_ID`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`Pembayaran_ID`),
  ADD KEY `ID_Pesanan` (`ID_Pesanan`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`ID_Pesanan`),
  ADD KEY `Treatment_ID` (`Treatment_ID`);

--
-- Indeks untuk tabel `treatmen`
--
ALTER TABLE `treatmen`
  ADD PRIMARY KEY (`Treatment_ID`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `ID_Pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`ID_Pesanan`) REFERENCES `pesanan` (`ID_Pesanan`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`ID_Pesanan`) REFERENCES `pesanan` (`ID_Pesanan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_3` FOREIGN KEY (`Pembayaran_ID`) REFERENCES `pembayaran` (`Pembayaran_ID`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`ID_Pesanan`) REFERENCES `pesanan` (`ID_Pesanan`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`Treatment_ID`) REFERENCES `treatmen` (`Treatment_ID`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
