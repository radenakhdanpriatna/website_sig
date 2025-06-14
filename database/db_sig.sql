-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2025 at 01:46 PM
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
-- Database: `db_sig`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_rumah`
--

CREATE TABLE `data_rumah` (
  `id` int NOT NULL,
  `nama_pemilik` varchar(100) DEFAULT NULL,
  `alamat` text,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kab`
--

CREATE TABLE `kab` (
  `id` int NOT NULL,
  `kec` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kab`
--

INSERT INTO `kab` (`id`, `kec`) VALUES
(1, 'Waled'),
(2, 'ciledug'),
(3, 'losari'),
(4, 'pabedilan'),
(5, 'babakan'),
(6, 'Karangsembung'),
(7, 'lemahabang'),
(8, 'susukanlebak'),
(9, 'sedong'),
(10, 'astanajapura'),
(11, 'pangenan'),
(12, 'mundu'),
(13, 'beber'),
(14, 'talun'),
(15, 'sumber'),
(16, 'dukupuntang'),
(17, 'palimanan'),
(18, 'plumbon'),
(19, 'weru'),
(20, 'kedawung'),
(21, 'gunungjati'),
(22, 'kapetakan'),
(23, 'klangenan'),
(24, 'arjawinangun'),
(25, 'panguragan'),
(26, 'ciwaringin'),
(27, 'susukan'),
(28, 'gegesik'),
(29, 'kaliwedi'),
(30, 'gebang'),
(31, 'depok'),
(32, 'pasaleman'),
(33, 'pabuaran'),
(34, 'karangwareng'),
(35, 'tengahtani'),
(36, 'plered'),
(37, 'gempol'),
(38, 'greged'),
(39, 'suranenggala'),
(40, 'jamblang');

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kontak`
--

INSERT INTO `kontak` (`id`, `nama`, `email`, `pesan`, `tanggal`) VALUES
(28, 'raka ananda', 'radenakhdan369@gmail.com', 'bagus', '2025-05-25 02:43:20'),
(29, 'raka ananda', 'radenakhdan369@gmail.com', 'bagus', '2025-05-25 02:46:55'),
(30, 'raka ananda', 'radenakhdan369@gmail.com', 'bagus', '2025-05-25 02:49:26'),
(31, 'raka ananda', 'radenakhdan369@gmail.com', 'bagus', '2025-05-25 02:51:45');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int NOT NULL,
  `Desa` varchar(100) NOT NULL,
  `Kecamatan` varchar(100) NOT NULL,
  `Lat` double NOT NULL,
  `Long` double NOT NULL,
  `L` int NOT NULL,
  `P` int NOT NULL,
  `Jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penghuni_rumah`
--

CREATE TABLE `penghuni_rumah` (
  `id` int NOT NULL,
  `rumah_id` int NOT NULL,
  `nama_penghuni` varchar(100) DEFAULT NULL,
  `umur` int DEFAULT NULL,
  `hubungan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rumah`
--

CREATE TABLE `rumah` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int NOT NULL,
  `rumah_id` int NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `komentar` text NOT NULL,
  `rating` int NOT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(3, 'admin', '$2y$10$jeHmpWtc399V6Iklc13qWeV374eiLZtZxNLQ4.neleBJHddotqPjK'),
(4, 'akhdan_bgr', '$2y$10$fMm1U.MRxcF3ZNL6Kq7N0.oCtGvtkgDQ8mmreGxPAj9w.kx9aCfqO');

-- --------------------------------------------------------

--
-- Table structure for table `warga`
--

CREATE TABLE `warga` (
  `id` int NOT NULL,
  `rumah_id` int NOT NULL,
  `nama_warga` varchar(100) NOT NULL,
  `umur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_rumah`
--
ALTER TABLE `data_rumah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kab`
--
ALTER TABLE `kab`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penghuni_rumah`
--
ALTER TABLE `penghuni_rumah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rumah_id` (`rumah_id`);

--
-- Indexes for table `rumah`
--
ALTER TABLE `rumah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rumah_id` (`rumah_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rumah_id` (`rumah_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_rumah`
--
ALTER TABLE `data_rumah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kab`
--
ALTER TABLE `kab`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penghuni_rumah`
--
ALTER TABLE `penghuni_rumah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rumah`
--
ALTER TABLE `rumah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warga`
--
ALTER TABLE `warga`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `penghuni_rumah`
--
ALTER TABLE `penghuni_rumah`
  ADD CONSTRAINT `penghuni_rumah_ibfk_1` FOREIGN KEY (`rumah_id`) REFERENCES `data_rumah` (`id`);

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`rumah_id`) REFERENCES `rumah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warga`
--
ALTER TABLE `warga`
  ADD CONSTRAINT `warga_ibfk_1` FOREIGN KEY (`rumah_id`) REFERENCES `data_rumah` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
