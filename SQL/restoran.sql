-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 07:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restoran`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(10) NOT NULL,
  `categoryName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`) VALUES
(1, 'Makanan Utama'),
(2, 'Camilan'),
(3, 'Minuman'),
(4, 'Dessert');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menuID` int(10) NOT NULL,
  `categoryID` int(10) NOT NULL,
  `menuName` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','out of stock') NOT NULL DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menuID`, `categoryID`, `menuName`, `description`, `price`, `status`, `image`) VALUES
(1, 1, 'Nasi Goreng', 'Nasi goreng spesial dengan telur dan ayam suwir (Disajikan bersama kerupuk)', 25000.00, 'available', 'FriedRice.jpg'),
(2, 1, 'Mie Goreng Ayam', 'Mie goreng ayam adalah mie kuning goreng dengan ayam dan bumbu gurih.', 22000.00, 'available', 'ChickenNoodles.jpg'),
(3, 1, 'Beef Burger', 'Burger daging sapi asli dengan keju lumer.', 30000.00, 'available', 'BeefBurger.jpg'),
(4, 2, 'Kentang Goreng', 'Kentang goreng renyah dengan saus sambal.', 15000.00, 'available', 'FrenchFries.jpg'),
(5, 2, 'Spring Roll', 'Lumpia goreng isi sayuran dan ayam.', 12000.00, 'available', 'SpringRolls.jpg'),
(6, 3, 'Es Teh', 'Es teh manis segar dengan lemon.', 8000.00, 'available', 'IcedTea.jpg'),
(7, 3, 'Jus Jeruk', 'Jus jeruk murni tanpa pemanis buatan.', 12000.00, 'available', 'OrangeJuice.jpg'),
(8, 4, 'Puding Cokelat', 'Puding coklat lembut dengan vla vanilla.', 15000.00, 'out of stock', 'pudding.jpg'),
(9, 4, 'Es Krim Vanilla', 'Satu scoop es krim vanilla premium.', 10000.00, 'available', 'IceCreamVanilla.jpg'),
(10, 1, 'Sop Buntut', 'Sop Buntut dengan kualitas daging terbaik.', 64000.00, 'available', 'SopBuntut.jpg'),
(11, 3, 'Air Mineral', 'Air Mineral terbaik dari pegunungan Fiji.', 30000.00, 'available', 'mineral water.jpg'),
(12, 1, 'Cumi Goreng Tepung', 'Cumi Goreng Tepung dengan saus khas Flavor Haven', 50000.00, 'available', 'cumi_goreng_tepung_crispy.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `roomID` int(11) NOT NULL,
  `roomName` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`roomID`, `roomName`, `capacity`, `type`) VALUES
(1, 'Regular Area', 50, 'Regular'),
(2, 'VIP Room 1', 12, 'VIP'),
(3, 'VIP Room 2', 12, 'VIP');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transactionID` int(10) NOT NULL,
  `userID` int(10) DEFAULT NULL,
  `transactionDate` date DEFAULT curdate(),
  `totalAmount` decimal(10,2) NOT NULL,
  `paymentMethod` enum('QRIS','Debit/Credit Card','Transfer') NOT NULL,
  `status` enum('pending','process','completed') NOT NULL DEFAULT 'pending',
  `type` enum('Delivery','Reservation') NOT NULL DEFAULT 'Reservation',
  `bookingDate` date DEFAULT NULL,
  `bookingTime` time DEFAULT NULL,
  `pax` int(11) DEFAULT NULL,
  `deliveryAddress` text DEFAULT NULL,
  `deliveryPhone` varchar(20) DEFAULT NULL,
  `roomID` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactionID`, `userID`, `transactionDate`, `totalAmount`, `paymentMethod`, `status`, `type`, `bookingDate`, `bookingTime`, `pax`, `deliveryAddress`, `deliveryPhone`, `roomID`, `duration`) VALUES
(11, 2, '2025-12-01', 72600.00, 'QRIS', 'completed', 'Reservation', '2025-12-01', '12:00:00', 2, NULL, NULL, 1, 120),
(12, 3, '2025-12-02', 99000.00, 'Debit/Credit Card', 'completed', 'Delivery', NULL, NULL, NULL, 'Jl. Anggrek No 5', '081299998888', NULL, NULL),
(13, 4, '2025-12-03', 46200.00, 'QRIS', 'completed', 'Reservation', '2025-12-03', '13:00:00', 2, NULL, NULL, 1, 120),
(14, 5, '2025-12-05', 132000.00, 'Transfer', 'completed', 'Reservation', '2025-12-05', '18:00:00', 4, NULL, NULL, 1, 120),
(15, 2, '2025-12-08', 39600.00, 'QRIS', 'completed', 'Delivery', NULL, NULL, NULL, 'Apt. Mediterania Lt 5', '081233334444', NULL, NULL),
(16, 3, '2025-12-10', 203500.00, 'Debit/Credit Card', 'completed', 'Reservation', '2025-12-10', '19:00:00', 6, NULL, NULL, 2, 180),
(18, 5, '2025-12-15', 85800.00, 'QRIS', 'completed', 'Reservation', '2025-12-15', '19:30:00', 2, NULL, NULL, 1, 120),
(19, 2, '2025-12-18', 154000.00, 'Transfer', 'completed', 'Delivery', NULL, NULL, NULL, 'Kantor Gojek Lt 2', '085677778888', NULL, NULL),
(20, 3, '2025-12-20', 63800.00, 'QRIS', 'completed', 'Reservation', '2025-12-20', '20:00:00', 2, NULL, NULL, 1, 120),
(21, 4, '2025-12-24', 242000.00, 'Debit/Credit Card', 'completed', 'Reservation', '2025-12-24', '20:00:00', 8, NULL, NULL, 3, 120),
(22, 5, '2025-12-25', 110000.00, 'QRIS', 'completed', 'Reservation', '2025-12-25', '12:00:00', 4, NULL, NULL, 1, 120),
(23, 2, '2025-12-28', 49500.00, 'QRIS', 'completed', 'Delivery', NULL, NULL, NULL, 'Kos Ibu Budi', '081200001111', NULL, NULL),
(24, 3, '2025-12-30', 308000.00, 'Transfer', 'completed', 'Reservation', '2025-12-31', '21:00:00', 10, NULL, NULL, 2, 240),
(25, 4, '2025-12-31', 88000.00, 'QRIS', 'completed', 'Delivery', NULL, NULL, NULL, 'Jl. Kembang Api', '081399990000', NULL, NULL),
(27, 3, '2026-01-03', 112200.00, 'QRIS', 'completed', 'Reservation', '2026-01-10', '10:00:00', 12, NULL, NULL, 2, 60),
(28, 3, '2026-01-03', 127600.00, 'Debit/Credit Card', 'completed', 'Reservation', '2026-01-10', '13:00:00', 12, NULL, NULL, 2, 60),
(29, 3, '2026-01-03', 101200.00, 'Transfer', 'completed', 'Delivery', NULL, NULL, NULL, 'Jalan Kelapa Gading', '08123456789', NULL, NULL),
(30, 3, '2026-01-03', 57200.00, 'QRIS', 'completed', 'Delivery', NULL, NULL, NULL, 'Jalan Raymond', '08111111111', NULL, NULL),
(31, 3, '2026-01-04', 84700.00, 'QRIS', 'completed', 'Delivery', NULL, NULL, NULL, 'Jalan Malay 3', '081092381124', NULL, NULL),
(32, 3, '2026-01-05', 68200.00, 'QRIS', 'completed', 'Delivery', NULL, NULL, NULL, 'Jalan Mangga Besar No 10', '081188887777', NULL, NULL),
(33, 3, '2026-01-05', 133100.00, 'QRIS', 'completed', 'Reservation', '2026-01-10', '15:00:00', 12, NULL, NULL, 2, 90),
(34, 3, '2026-01-08', 213400.00, 'QRIS', 'pending', 'Delivery', NULL, NULL, NULL, 'Jalan Kemanggisan Selatan No 15 RT 1/RW 9', '0812345678901', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactionsdetail`
--

CREATE TABLE `transactionsdetail` (
  `detailID` int(10) NOT NULL,
  `transactionID` int(10) NOT NULL,
  `menuID` int(10) NOT NULL,
  `quantity` int(5) NOT NULL,
  `note` text DEFAULT NULL,
  `unitPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactionsdetail`
--

INSERT INTO `transactionsdetail` (`detailID`, `transactionID`, `menuID`, `quantity`, `note`, `unitPrice`) VALUES
(1, 11, 1, 2, NULL, 25000.00),
(2, 11, 6, 2, NULL, 8000.00),
(3, 12, 3, 3, NULL, 30000.00),
(4, 13, 3, 1, NULL, 30000.00),
(5, 13, 7, 1, NULL, 12000.00),
(6, 14, 2, 4, NULL, 22000.00),
(7, 14, 6, 4, NULL, 8000.00),
(8, 15, 5, 3, NULL, 12000.00),
(9, 16, 1, 5, NULL, 25000.00),
(10, 16, 7, 5, NULL, 12000.00),
(11, 18, 3, 2, NULL, 30000.00),
(12, 18, 9, 2, NULL, 10000.00),
(13, 19, 1, 2, NULL, 25000.00),
(14, 19, 2, 2, NULL, 22000.00),
(15, 19, 4, 2, NULL, 15000.00),
(16, 19, 6, 4, NULL, 8000.00),
(17, 20, 1, 2, NULL, 25000.00),
(18, 20, 6, 1, NULL, 8000.00),
(19, 21, 3, 4, NULL, 30000.00),
(20, 21, 4, 4, NULL, 15000.00),
(21, 21, 9, 4, NULL, 10000.00),
(22, 22, 1, 4, NULL, 25000.00),
(23, 23, 8, 3, NULL, 15000.00),
(24, 24, 3, 5, NULL, 30000.00),
(25, 24, 2, 5, NULL, 22000.00),
(26, 24, 7, 2, NULL, 12000.00),
(27, 25, 2, 2, NULL, 22000.00),
(28, 25, 5, 3, NULL, 12000.00),
(29, 27, 6, 1, NULL, 8000.00),
(30, 27, 7, 1, NULL, 12000.00),
(31, 27, 2, 1, NULL, 22000.00),
(32, 27, 3, 2, NULL, 30000.00),
(33, 28, 2, 1, NULL, 22000.00),
(34, 28, 3, 1, NULL, 30000.00),
(35, 28, 4, 1, NULL, 15000.00),
(36, 28, 1, 1, NULL, 25000.00),
(37, 28, 6, 3, NULL, 8000.00),
(38, 29, 1, 1, NULL, 25000.00),
(39, 29, 2, 1, NULL, 22000.00),
(40, 29, 3, 1, NULL, 30000.00),
(41, 29, 4, 1, NULL, 15000.00),
(42, 30, 2, 1, NULL, 22000.00),
(43, 30, 3, 1, NULL, 30000.00),
(44, 31, 1, 1, '', 25000.00),
(45, 31, 2, 1, '', 22000.00),
(46, 31, 3, 1, 'ga pakai selada', 30000.00),
(47, 32, 1, 2, 'Ga pakai sayur', 25000.00),
(48, 32, 7, 1, '', 12000.00),
(49, 33, 1, 2, '', 25000.00),
(50, 33, 6, 2, '', 8000.00),
(51, 33, 9, 1, '', 10000.00),
(52, 33, 3, 1, 'tidak pakai selada', 30000.00),
(53, 33, 4, 1, 'sausnya dipisah', 15000.00),
(54, 34, 12, 2, '', 50000.00),
(55, 34, 6, 1, 'Less Ice', 8000.00),
(56, 34, 10, 1, '', 64000.00),
(57, 34, 2, 1, '', 22000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `role` enum('admin','pelanggan') NOT NULL DEFAULT 'pelanggan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `nama`, `email`, `password`, `gender`, `DOB`, `role`) VALUES
(1, 'Admin Resto', 'admin@resto.com', 'admin123', 'Male', '1999-01-01', 'admin'),
(2, 'Budi Santoso', 'budi@mail.com', '123456', 'Male', '2001-05-10', 'pelanggan'),
(3, 'Siti Aisyah', 'siti@mail.com', '1234567', 'Female', '2002-08-21', 'pelanggan'),
(4, 'raymond g', 'cello020@gmail.com', 'cello011', 'Female', '2025-12-26', 'pelanggan'),
(5, 'Boby', 'boby@gmail.com', '123456', 'Male', '2006-02-09', 'pelanggan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menuID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`roomID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `roomID` (`roomID`);

--
-- Indexes for table `transactionsdetail`
--
ALTER TABLE `transactionsdetail`
  ADD PRIMARY KEY (`detailID`),
  ADD KEY `transactionID` (`transactionID`),
  ADD KEY `menuID` (`menuID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menuID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `roomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactionID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `transactionsdetail`
--
ALTER TABLE `transactionsdetail`
  MODIFY `detailID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`roomID`) REFERENCES `rooms` (`roomID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transactionsdetail`
--
ALTER TABLE `transactionsdetail`
  ADD CONSTRAINT `transactionsdetail_ibfk_1` FOREIGN KEY (`transactionID`) REFERENCES `transactions` (`transactionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactionsdetail_ibfk_2` FOREIGN KEY (`menuID`) REFERENCES `menu` (`menuID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
