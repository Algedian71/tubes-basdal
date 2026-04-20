-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Apr 19, 2026 at 02:16 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `tanggal_checkin` date DEFAULT NULL,
  `tanggal_checkout` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `total_harga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `tanggal_checkin`, `tanggal_checkout`, `status`, `total_harga`) VALUES
(1, 4, 1, '2026-07-12', '2026-12-13', 'success', 130900000),
(2, 5, 2, '2026-07-12', '2026-07-13', 'success', 650000),
(3, 5, 2, '2026-07-12', '2026-07-13', 'success', 650000),
(4, 5, 2, '2026-07-12', '2026-07-13', 'success', 650000),
(5, 5, 3, '2026-07-12', '2026-07-13', 'success', 750000),
(6, 5, 4, '2026-07-12', '2026-07-13', 'success', 850000),
(7, 5, 5, '2026-07-12', '2026-07-13', 'success', 850000),
(8, 5, 6, '2026-07-12', '2026-07-13', 'success', 7600),
(9, 5, 8, '2026-07-12', '2026-07-13', 'success', 850000),
(10, 5, 8, '2026-07-12', '2026-07-13', 'success', 850000),
(11, 5, 8, '2026-07-12', '2026-07-13', 'success', 850000),
(12, 5, 8, '2026-08-19', '2026-08-20', 'success', 850000);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `nama_kamar` varchar(100) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `harga` int NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'tersedia',
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `nama_kamar`, `tipe`, `harga`, `status`, `deskripsi`, `gambar`) VALUES
(1, 'Room 101', 'Deluxe', 850000, 'dipesan', 'Ac, TV, Wifi, Shower', 'room_1776429893.jpg'),
(2, '100', 'Standard', 650000, 'tidak tersedia', 'Ac, Wifi, Tv', 'room_1776431961.jpg'),
(3, '100', 'Deluxe', 750000, 'dipesan', 'Ac, Tv, Wifi', 'room_1776432850.jpg'),
(4, '1022', 'Standard', 850000, 'dipesan', 'Ac', 'room_1776433103.jpg'),
(5, '100', 'Deluxe', 850000, 'dipesan', 'Ac', 'room_1776433302.jpg'),
(6, '801', 'Standard', 7600, 'dipesan', 'Ac', 'room_1776433608.jpg'),
(7, '201', 'Standard', 850000, 'tersedia', 'ac', 'room_1776433725.jpg'),
(8, '100', 'Standard', 850000, 'terisi', 'Ac', 'room_1776434429.jpg'),
(9, 'Deluxe Room 101', 'Deluxe', 1250000, 'tersedia', 'Ac, Tv, Wifi, Shower', 'room_1776527562.jpg'),
(10, 'Suite Room 105', 'Standard', 1450000, 'tersedia', 'Ac, Tv, Wifi, Sarapan', 'room_1776527613.jpg'),
(11, 'Standard Room 109', 'Standard', 850000, 'tersedia', 'Ac, TV, Wifi, Sarapan', 'room_1776527654.jpg'),
(12, 'Presedential', 'Presidential', 1500000, 'tersedia', 'Ac, TV, Wifi, Sarapan', 'room_1776527805.jpg'),
(13, 'Presedential Room106', 'Presidential', 1600000, 'tersedia', 'Ac, TV, Wifi, Sarapan', 'room_1776527852.jpg'),
(14, 'Suite Room 104', 'Suite', 1100000, 'tersedia', 'Ac, TV, Wifi, Sarapan', 'room_1776527890.jpg'),
(15, 'Deluxe Room 102', 'Deluxe', 900000, 'tersedia', 'Ac, TV, Wifi, Sarapan', 'room_1776527934.jpg'),
(16, 'Standard Room 106', 'Standard', 800000, 'tersedia', 'Ac, TV, Wifi, Sarapan', 'room_1776527968.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(3, 'Administrator', 'admin@hotel.com', '$2y$10$JlNHhJDblkoyyTj6Ch2hn.9yUDqcm8zu8AM4oCPwGkkYp/NLZIGFi', 'admin'),
(4, 'egy', 'egyalgedian@gmail.com', '$2y$10$g8d0csYQVc1jhzMtzXmZnOkcxUnC/gsqv27KZKH5iul8YZl51t0xy', 'customer'),
(5, 'algedian', 'alge@gmail.com', '$2y$10$4ABklabHE5r/uuKQR6RT2OHbMDEJml475KsnlU818Zkwr7ZB6.WqG', 'customer'),
(6, 'zahra', 'zahra@gmail.com', '$2y$10$bo16VjBDwnWnpSe2JN27y.qHoKMwcMQcgpnL6XKAW4A3FNIOXwOFe', 'customer'),
(7, 'zahraaa', 'zahhhraaa@gmail.com', '$2y$10$AJun8nYgvA1N5mDFG8ViweaUoKwc3rTCf9YLMRrPKqIktT.WnydoC', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
