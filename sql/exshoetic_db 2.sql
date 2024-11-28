-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 01:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exshoetic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `idadmin` int(11) NOT NULL,
  `username` varchar(12) NOT NULL,
  `password` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`idadmin`, `username`, `password`) VALUES
(1, 'Dyahna', '123'),
(2, 'yahnadiever', '000'),
(3, 'kaka', '12345678'),
(4, 'ghozahimma', 'akushlek'),
(5, 'ainur', '55555'),
(6, 'E31230781', '123'),
(7, 'pingki', '123');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(11) NOT NULL,
  `Nama` varchar(255) NOT NULL,
  `No_Hp` varchar(255) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `ID_Pesanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `Nama`, `No_Hp`, `Alamat`, `keterangan`, `ID_Pesanan`) VALUES
(14, 'kaka gonzales', '081310384433', 'jl. jalanin aja dulu', '', 1011),
(15, 'ainur kipli', '081233786656', 'jl. jalan-jalan ', '', 1012),
(16, 'kunti', '081330384433', 'jl. jalan-jalan ', '', 1013),
(17, 'anis baswedan', '0813103834433', 'jl panjaitan', 'test', 1014),
(18, 'ganjar baswedan', '085981654433', 'jl kadita ', '', 1015),
(19, 'mochammad salah', '085766610266', 'jl. jalan ', '', 1016),
(20, 'mochammad salah', '081310384433', 'jl. jalan ', 'p', 1017),
(21, 'pedri photer', '081310384433', 'jl. mastrip,no 58 ', '', 1018),
(22, 'pedri photer', '081310383443', 'jl. jalan ', '', 1019),
(23, 'mochammad salah', '0000813003', 'jl. mastrip,no 58 ', '', 1020),
(24, 'ainur ', '0000813003', 'jl. mastrip,no 58 ', '', 1021);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `ID_Pesanan` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Pembayaran_ID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`ID_Pesanan`, `Customer_ID`, `Pembayaran_ID`) VALUES
(1011, 14, 'PM1011'),
(1012, 15, 'PM1012'),
(1013, 16, 'PM1013'),
(1014, 17, 'PM1014'),
(1015, 18, 'PM1015'),
(1016, 19, 'PM1016'),
(1017, 20, 'PM1017'),
(1018, 21, 'PM1018'),
(1019, 22, 'PM1019'),
(1020, 23, 'PM1020'),
(1021, 24, 'PM1021');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `ID_Invoice` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `ID_Pesanan` int(11) NOT NULL,
  `Pembayaran_ID` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `Pembayaran_ID` varchar(255) NOT NULL,
  `ID_Pesanan` int(11) NOT NULL,
  `Tanggal_Pembayaran` date NOT NULL,
  `Metode_Pembayaran` enum('BRI','Mandiri','BCA','DANA','ShopeePay','SeaBank','COD','QRIS') NOT NULL,
  `Total_Tagihan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`Pembayaran_ID`, `ID_Pesanan`, `Tanggal_Pembayaran`, `Metode_Pembayaran`, `Total_Tagihan`) VALUES
('PM1011', 1011, '2024-11-20', 'COD', 70000),
('PM1012', 1012, '2024-11-20', 'ShopeePay', 15000),
('PM1013', 1013, '2024-11-21', 'COD', 40000),
('PM1014', 1014, '2024-11-27', 'COD', 70000),
('PM1015', 1015, '2024-11-27', 'COD', 70000),
('PM1016', 1016, '2024-11-28', 'COD', 40000),
('PM1017', 1017, '2024-11-28', 'COD', 40000),
('PM1018', 1018, '2024-11-28', 'COD', 35000),
('PM1019', 1019, '2024-11-28', 'COD', 50000),
('PM1020', 1020, '2024-11-28', 'COD', 15000),
('PM1021', 1021, '2024-11-28', 'COD', 70000);

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `ID_Pengeluaran` int(11) NOT NULL,
  `Nama_Barang` varchar(255) NOT NULL,
  `Harga_Satuan` decimal(10,0) NOT NULL,
  `Total` int(11) NOT NULL,
  `Jumlah_Harga` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`ID_Pengeluaran`, `Nama_Barang`, `Harga_Satuan`, `Total`, `Jumlah_Harga`) VALUES
(1, 'Sabun Pemutih', 45000, 5, 225000),
(2, 'Alat Pengering', 175000, 1, 175000);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `ID_Pesanan` int(11) NOT NULL,
  `Tanggal_Pesanan` date NOT NULL,
  `Treatment_ID` varchar(11) NOT NULL,
  `Merk_Sepatu` varchar(255) NOT NULL,
  `Total_Tagihan` int(11) NOT NULL,
  `Status` enum('Belum Selesai','Sudah Selesai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`ID_Pesanan`, `Tanggal_Pesanan`, `Treatment_ID`, `Merk_Sepatu`, `Total_Tagihan`, `Status`) VALUES
(1011, '2024-11-20', 'TM008', 'adidas', 70000, 'Sudah Selesai'),
(1012, '2024-11-20', 'TM002', 'nike', 15000, 'Sudah Selesai'),
(1013, '2024-11-21', 'TM007', 'adidas', 40000, 'Sudah Selesai'),
(1014, '2024-11-27', 'TM008', 'nike', 70000, 'Sudah Selesai'),
(1015, '2024-11-27', 'TM008', 'nike', 70000, 'Sudah Selesai'),
(1016, '2024-11-28', 'TM007', 'new balance', 40000, ''),
(1017, '2024-11-28', 'TM007', 'new balance', 40000, ''),
(1018, '2024-11-28', 'TM001', 'new balance', 35000, ''),
(1019, '2024-11-28', 'TM006', 'new balance', 50000, ''),
(1020, '2024-11-28', 'TM002', 'new balance', 15000, ''),
(1021, '2024-11-28', 'TM008', 'new balance', 70000, '');

-- --------------------------------------------------------

--
-- Table structure for table `treatmen`
--

CREATE TABLE `treatmen` (
  `Treatment_ID` varchar(11) NOT NULL,
  `Nama_Treatment` varchar(20) NOT NULL,
  `Deskripsi` varchar(255) NOT NULL,
  `Harga` decimal(27,0) DEFAULT NULL,
  `Estimasi` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatmen`
--

INSERT INTO `treatmen` (`Treatment_ID`, `Nama_Treatment`, `Deskripsi`, `Harga`, `Estimasi`) VALUES
('TM001', 'Deep Clean', 'Pencucian sepatu secara menyeluruh, dari bagian luar hingga dalam, termasuk tali sepatu.', 20000, '2-3 Hari'),
('TM002', 'Fast Cleaning', 'Pencucian cepat yang hanya membersihkan bagian atas dan bagian tengah sepatu ', 15000, '2-3 Hari'),
('TM003', 'Unyellowing', 'Memutihkan sol yang telah menguning', 45000, '6-7 Hari'),
('TM004', 'Repaint', 'Pengecatan ulang ketika warna sepatu sudah mulai usang/kusam.', 90000, '13-14 Hari'),
('TM005', 'Repair Sol', 'Reparasi sepatu atau perbaikan yang dilakukan pada sepatu yang mengalami kerusakan', 40000, '13-14 Hari'),
('TM006', 'Whitening', 'Membersihkan sepatu khusus berwarna putih berbahan canvas/mesh.', 35000, '2-3 Hari'),
('TM007', 'Leather Care', 'Membersihkan sepatu yang berbahan kulit', 35000, '2-3 Hari'),
('TM008', 'Reglue', 'Pemasangan kembali bagian sepatu yang terbuka dengan cara di lem.', 35000, '5-7 Hari');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idadmin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`),
  ADD KEY `ID_Pesanan` (`ID_Pesanan`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD KEY `fk_detail_pesanan_pesanan` (`ID_Pesanan`),
  ADD KEY `fk_detail_pesanan_customer` (`Customer_ID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD KEY `fk_invoice_pesanan` (`ID_Pesanan`),
  ADD KEY `fk_invoice_customer` (`Customer_ID`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD KEY `fk_pembayaran_pesanan` (`ID_Pesanan`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`ID_Pengeluaran`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`ID_Pesanan`),
  ADD KEY `fk_pesanan_treatmen` (`Treatment_ID`);

--
-- Indexes for table `treatmen`
--
ALTER TABLE `treatmen`
  ADD PRIMARY KEY (`Treatment_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `idadmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `ID_Pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `ID_Pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1022;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_pesanan` FOREIGN KEY (`ID_Pesanan`) REFERENCES `pesanan` (`ID_Pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `fk_detail_pesanan_customer` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_pesanan_pesanan` FOREIGN KEY (`ID_Pesanan`) REFERENCES `pesanan` (`ID_Pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_customer` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_pesanan` FOREIGN KEY (`ID_Pesanan`) REFERENCES `pesanan` (`ID_Pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`ID_Pesanan`) REFERENCES `pesanan` (`ID_Pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_treatmen` FOREIGN KEY (`Treatment_ID`) REFERENCES `treatmen` (`Treatment_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
