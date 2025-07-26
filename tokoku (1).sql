-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2025 at 03:50 PM
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
-- Database: `tokoku`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(3, 'kopi', '2025-03-06 12:25:25', '2025-03-06 12:25:25'),
(7, 'makanan', '2025-07-17 16:43:14', '2025-07-17 16:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id_member` int(10) UNSIGNED NOT NULL,
  `kode_member` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `diskon` decimal(12,2) NOT NULL DEFAULT 0.00,
  `diskon_type` enum('percent','nominal') NOT NULL DEFAULT 'percent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id_member`, `kode_member`, `nama`, `alamat`, `telepon`, `created_at`, `updated_at`, `diskon`, `diskon_type`) VALUES
(11, '00001', 'fikri', 'kampung bulakwareng1', '08978223198', '2025-07-17 16:37:37', '2025-07-17 16:37:37', 90.00, 'percent'),
(12, '00002', 'elinzz', 'kampung bulak wareng 3', '08978223198', '2025-07-17 16:38:02', '2025-07-22 19:36:01', 800.00, 'nominal');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2021_03_05_194740_tambah_kolom_baru_to_users_table', 1),
(7, '2021_03_05_195441_buat_kategori_table', 1),
(8, '2021_03_05_195949_buat_produk_table', 1),
(9, '2021_03_05_200515_buat_member_table', 1),
(10, '2021_03_05_200706_buat_supplier_table', 1),
(11, '2021_03_05_200841_buat_pembelian_table', 1),
(12, '2021_03_05_200845_buat_pembelian_detail_table', 1),
(13, '2021_03_05_200853_buat_penjualan_table', 1),
(14, '2021_03_05_200858_buat_penjualan_detail_table', 1),
(15, '2021_03_05_200904_buat_setting_table', 1),
(16, '2021_03_05_201756_buat_pengeluaran_table', 1),
(17, '2021_03_11_225128_create_sessions_table', 1),
(18, '2021_03_24_115009_tambah_foreign_key_to_produk_table', 1),
(19, '2021_03_24_131829_tambah_kode_produk_to_produk_table', 1),
(20, '2021_05_08_220315_tambah_diskon_to_setting_table', 1),
(21, '2021_05_09_124745_edit_id_member_to_penjualan_table', 1),
(22, '2025_06_20_064949_add_snap_token_to_penjualan_detail', 2),
(23, '2025_06_20_072010_add_snap_token_to_penjualan_detail', 3),
(24, '2025_06_20_072644_add_snap_token_to_penjualan_detail', 4),
(25, '2025_06_20_072602_add_snap_token_to_pembelian_detail', 5),
(26, '2025_06_28_110059_add_status_to_penjualan_table', 5),
(27, '2025_06_28_204128_add_snap_token_to_penjualan_table', 6),
(28, '2025_07_04_172301_add_midtrans_columns_to_penjualan_table', 7),
(29, '2025_07_04_174110_add_payment_method_to_penjualan_table', 8),
(30, '2025_07_06_063127_add_metode_pembayaran_to_penjualan_table', 9),
(31, '2025_07_14_182113_add_status_to_pembelian_table', 10),
(32, '2025_07_14_184152_add_status_tgl_datang_to_pembelian_table', 11),
(33, '2025_07_15_132811_add_diskon_columns_to_member_table', 12),
(34, '2025_07_15_145722_add_diskon_columns_to_penjualan_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(10) UNSIGNED NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `total_item` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `diskon` tinyint(4) NOT NULL DEFAULT 0,
  `bayar` int(11) NOT NULL DEFAULT 0,
  `status` enum('pending','sukses') NOT NULL DEFAULT 'pending',
  `tgl_datang` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_supplier`, `total_item`, `total_harga`, `diskon`, `bayar`, `status`, `tgl_datang`, `created_at`, `updated_at`) VALUES
(23, 4, 100, 890000, 0, 890000, 'sukses', '2025-07-19', '2025-07-17 17:55:35', '2025-07-19 03:10:52'),
(24, 4, 90, 801000, 0, 801000, 'sukses', '2025-07-23', '2025-07-19 03:11:07', '2025-07-22 18:27:54');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `id_pembelian_detail` int(10) UNSIGNED NOT NULL,
  `id_pembelian` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`id_pembelian_detail`, `id_pembelian`, `id_produk`, `harga_beli`, `jumlah`, `subtotal`, `snap_token`, `created_at`, `updated_at`) VALUES
(26, 23, 9, 8900, 100, 890000, NULL, '2025-07-17 17:55:43', '2025-07-17 17:56:02'),
(27, 24, 9, 8900, 90, 801000, NULL, '2025-07-19 03:11:11', '2025-07-19 03:11:27');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(10) UNSIGNED NOT NULL,
  `deskripsi` text NOT NULL,
  `nominal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(10) UNSIGNED NOT NULL,
  `order_midtrans` varchar(255) DEFAULT NULL,
  `id_member` int(11) DEFAULT NULL,
  `total_item` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `diskon` decimal(10,2) DEFAULT NULL,
  `bayar` int(11) NOT NULL DEFAULT 0,
  `diterima` int(11) NOT NULL DEFAULT 0,
  `metode_pembayaran` varchar(255) DEFAULT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `diskon_type` enum('percent','nominal') NOT NULL DEFAULT 'percent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `order_midtrans`, `id_member`, `total_item`, `total_harga`, `diskon`, `bayar`, `diterima`, `metode_pembayaran`, `snap_token`, `payment_method`, `id_user`, `created_at`, `updated_at`, `diskon_type`) VALUES
(411, 'POS-411-012008', 12, 1, 25000, 90.00, 0, 8000, 'cash', '2eb3579b-73c6-4ec7-8cf7-3c5c6ee71ece', NULL, 1, '2025-07-22 18:04:34', '2025-07-22 18:21:52', 'percent'),
(412, NULL, NULL, 0, 0, 0.00, 0, 0, NULL, NULL, NULL, 1, '2025-07-22 18:22:04', '2025-07-22 18:22:04', 'percent'),
(413, NULL, NULL, 0, 0, 0.00, 0, 0, NULL, NULL, NULL, 1, '2025-07-22 18:24:31', '2025-07-22 18:24:31', 'percent'),
(414, 'POS-414-012724', 11, 5, 125000, 90.00, 12500, 0, 'QRIS', 'f56f02d0-36d5-4f0d-a42a-a11eeb7d8cb8', NULL, 1, '2025-07-22 18:25:25', '2025-07-22 18:27:26', 'percent'),
(415, 'POS-415-013020', 11, 80, 1512000, 90.00, 151200, 0, 'QRIS', '4a93d863-c519-4cc2-9e76-a72e9cc88269', NULL, 1, '2025-07-22 18:28:11', '2025-07-22 18:30:23', 'percent'),
(416, 'POS-416-013132', 11, 2, 50000, 8000.00, 5000, 6000, 'cash', '8765e2f1-b308-43cb-8aff-4135b9555cae', NULL, 1, '2025-07-22 18:31:15', '2025-07-22 18:32:35', 'nominal'),
(417, NULL, NULL, 0, 0, 0.00, 0, 0, NULL, NULL, NULL, 1, '2025-07-22 18:35:23', '2025-07-22 18:35:23', 'percent'),
(418, NULL, NULL, 100, 2500000, 0.00, 2500000, 0, NULL, NULL, NULL, 1, '2025-07-22 18:36:25', '2025-07-22 18:36:32', 'percent'),
(419, 'POS-419-013832', 12, 1, 18900, 80.00, 3780, 0, 'QRIS', '444e701d-42d4-4f68-ba59-3a62f69f073e', NULL, 1, '2025-07-22 18:38:13', '2025-07-22 18:38:36', 'nominal'),
(420, NULL, 12, 1, 18900, 80.00, 18820, 20000, 'cash', NULL, NULL, 1, '2025-07-22 18:41:42', '2025-07-22 18:55:48', 'percent'),
(421, 'POS-421-015856', 12, 1, 18900, 80.00, 18820, 0, 'QRIS', 'b8feced1-7329-4e1b-b6a2-4a909c98dc3c', NULL, 1, '2025-07-22 18:58:39', '2025-07-22 18:58:59', 'nominal'),
(422, 'POS-422-020453', 12, 1, 18900, 80.00, 18820, 0, 'QRIS', '5e9e7b37-f787-4d1c-8110-e9c015c724c3', NULL, 1, '2025-07-22 18:59:17', '2025-07-22 19:04:55', 'nominal'),
(423, NULL, 11, 1, 18900, 0.00, 1890, 2000, 'cash', NULL, NULL, 1, '2025-07-22 19:06:33', '2025-07-22 19:16:35', 'percent'),
(424, 'POS-424-021657', 11, 1, 18900, 90.00, 1890, 0, 'QRIS', 'e30ad097-923f-43ef-9966-2d6d406ec4fe', NULL, 1, '2025-07-22 19:16:45', '2025-07-22 19:16:59', 'percent'),
(425, NULL, 12, 1, 18900, 0.00, 18900, 20000, 'cash', NULL, NULL, 1, '2025-07-22 19:18:49', '2025-07-22 19:19:06', 'percent'),
(426, 'POS-426-021923', 11, 1, 18900, 90.00, 1890, 0, 'QRIS', '69bc5534-27fd-4bc0-ac72-1bed326e2207', NULL, 1, '2025-07-22 19:19:14', '2025-07-22 19:19:29', 'percent'),
(427, NULL, 12, 1, 25000, 0.00, 25000, 90000, 'cash', NULL, NULL, 1, '2025-07-22 19:19:35', '2025-07-22 19:21:16', 'percent'),
(428, NULL, 11, 1, 18900, 90.00, 1890, 2000, 'cash', NULL, NULL, 1, '2025-07-22 19:23:33', '2025-07-22 19:23:46', 'percent'),
(429, 'POS-429-022451', 12, 1, 25000, 80.00, 24920, 0, 'QRIS', '5ecfb266-2674-4f43-98ae-93f4eb840a1b', NULL, 1, '2025-07-22 19:23:59', '2025-07-22 19:24:53', 'nominal'),
(430, NULL, 12, 1, 18900, 80.00, 18820, 20000, 'cash', NULL, NULL, 1, '2025-07-22 19:25:01', '2025-07-22 19:27:48', 'nominal'),
(431, 'POS-431-023322', 11, 1, 18900, 90.00, 1890, 9000, 'cash', '1eec154d-9eb0-4875-81e6-bc62cce728b4', NULL, 1, '2025-07-22 19:32:51', '2025-07-22 19:33:46', 'percent'),
(432, NULL, 12, 10, 250000, 9000.00, 241000, 241000, 'cash', NULL, NULL, 1, '2025-07-22 19:34:26', '2025-07-22 19:34:48', 'nominal'),
(433, NULL, 12, 1, 18900, 800.00, 18100, 20000, 'cash', NULL, NULL, 1, '2025-07-22 19:36:03', '2025-07-22 19:39:14', 'nominal');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_detail`
--

CREATE TABLE `penjualan_detail` (
  `id_penjualan_detail` int(10) UNSIGNED NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `diskon` tinyint(4) NOT NULL DEFAULT 0,
  `subtotal` int(11) NOT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penjualan_detail`
--

INSERT INTO `penjualan_detail` (`id_penjualan_detail`, `id_penjualan`, `id_produk`, `harga_jual`, `jumlah`, `diskon`, `subtotal`, `snap_token`, `created_at`, `updated_at`) VALUES
(123, 322, 8, 1000, 10, 0, 10000, NULL, '2025-07-16 13:53:45', '2025-07-16 13:53:49'),
(124, 323, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 13:54:34', '2025-07-16 13:54:34'),
(125, 324, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 13:58:03', '2025-07-16 13:58:03'),
(126, 325, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:08:43', '2025-07-16 14:08:43'),
(127, 327, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:21:33', '2025-07-16 14:21:33'),
(128, 328, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:39:21', '2025-07-16 14:39:21'),
(129, 329, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:40:45', '2025-07-16 14:40:45'),
(130, 330, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:53:10', '2025-07-16 14:53:10'),
(131, 331, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:55:57', '2025-07-16 14:55:57'),
(132, 332, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:56:35', '2025-07-16 14:56:35'),
(133, 333, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 14:58:39', '2025-07-16 14:58:39'),
(134, 334, 8, 1000, 10, 5, 10000, NULL, '2025-07-16 15:02:07', '2025-07-16 15:09:34'),
(135, 335, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 15:27:15', '2025-07-16 15:27:15'),
(136, 336, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 15:40:04', '2025-07-16 15:45:50'),
(137, 337, 8, 1000, 10000, 9, 10000000, NULL, '2025-07-16 15:49:34', '2025-07-16 15:50:03'),
(138, 339, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 15:52:21', '2025-07-16 15:52:21'),
(139, 340, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 16:23:06', '2025-07-16 16:23:06'),
(140, 342, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 16:23:48', '2025-07-16 16:23:48'),
(141, 343, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 16:24:39', '2025-07-16 16:24:39'),
(142, 344, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 16:26:34', '2025-07-16 16:26:34'),
(143, 346, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 16:32:33', '2025-07-16 16:32:33'),
(144, 351, 8, 1000, 2, 0, 2000, NULL, '2025-07-16 16:59:03', '2025-07-16 17:11:02'),
(147, 351, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:10:59', '2025-07-16 17:10:59'),
(148, 351, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:11:29', '2025-07-16 17:11:29'),
(155, 353, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:19:50', '2025-07-16 17:19:50'),
(156, 355, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:20:07', '2025-07-16 17:20:07'),
(157, 356, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:21:10', '2025-07-16 17:21:10'),
(158, 357, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:27:10', '2025-07-16 17:27:10'),
(159, 359, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:28:41', '2025-07-16 17:28:41'),
(160, 359, 8, 1000, 1, 0, 1000, NULL, '2025-07-16 17:28:46', '2025-07-16 17:28:46'),
(164, 361, 8, 1000, 1, 0, 1000, NULL, '2025-07-17 16:01:40', '2025-07-17 16:01:40'),
(165, 362, 8, 1000, 1, 0, 1000, NULL, '2025-07-17 16:03:39', '2025-07-17 16:03:39'),
(166, 363, 8, 1000, 1, 0, 1000, NULL, '2025-07-17 16:09:24', '2025-07-17 16:09:24'),
(167, 366, 8, 1000, 1, 0, 1000, NULL, '2025-07-17 16:15:27', '2025-07-17 16:15:27'),
(168, 371, 8, 1000, 1, 0, 1000, NULL, '2025-07-17 16:32:42', '2025-07-17 16:32:42'),
(169, 372, 8, 1000, 1, 0, 1000, NULL, '2025-07-17 16:33:15', '2025-07-17 16:33:15'),
(170, 375, 9, 18900, 1, 90, 18900, NULL, '2025-07-17 19:20:04', '2025-07-17 19:20:34'),
(171, 375, 10, 25000, 1, 90, 25000, NULL, '2025-07-17 19:20:08', '2025-07-17 19:20:34'),
(172, 376, 9, 18900, 10, 0, 189000, NULL, '2025-07-17 20:02:17', '2025-07-17 20:03:22'),
(173, 377, 9, 18900, 1, 90, 18900, NULL, '2025-07-17 20:11:18', '2025-07-17 20:11:32'),
(174, 378, 9, 18900, 1, 90, 18900, NULL, '2025-07-17 20:13:33', '2025-07-17 20:13:44'),
(175, 379, 9, 18900, 1, 0, 18900, NULL, '2025-07-17 20:14:13', '2025-07-17 20:14:13'),
(176, 380, 9, 18900, 1, 90, 18900, NULL, '2025-07-17 20:29:54', '2025-07-17 20:30:06'),
(177, 381, 9, 18900, 1, 90, 18900, NULL, '2025-07-17 20:30:42', '2025-07-17 20:31:14'),
(178, 382, 9, 18900, 1, 0, 18900, NULL, '2025-07-18 17:21:10', '2025-07-18 17:21:10'),
(179, 383, 9, 18900, 1, 0, 18900, NULL, '2025-07-19 03:09:05', '2025-07-19 03:09:05'),
(180, 386, 9, 18900, 3, 90, 56700, NULL, '2025-07-19 03:12:27', '2025-07-19 03:13:01'),
(181, 387, 9, 18900, 2, 0, 37800, NULL, '2025-07-19 03:14:35', '2025-07-19 03:14:45'),
(182, 387, 10, 25000, 1, 0, 25000, NULL, '2025-07-19 03:14:39', '2025-07-19 03:14:39'),
(183, 389, 9, 18900, 1, 0, 18900, NULL, '2025-07-19 03:17:12', '2025-07-19 03:17:12'),
(184, 391, 9, 18900, 1, 90, 18900, NULL, '2025-07-21 17:12:54', '2025-07-21 17:13:18'),
(186, 395, 10, 25000, 1, 0, 25000, NULL, '2025-07-22 14:32:36', '2025-07-22 14:32:36'),
(191, 396, 9, 18900, 1, 0, 18900, NULL, '2025-07-22 14:42:25', '2025-07-22 14:42:25'),
(192, 398, 9, 18900, 2, 0, 37800, NULL, '2025-07-22 14:47:55', '2025-07-22 14:48:09'),
(193, 398, 10, 25000, 10, 0, 250000, NULL, '2025-07-22 14:48:25', '2025-07-22 14:48:37'),
(195, 402, 9, 18900, 1, 0, 18900, NULL, '2025-07-22 16:29:55', '2025-07-22 16:29:55'),
(196, 403, 9, 18900, 2, 0, 37800, NULL, '2025-07-22 16:30:08', '2025-07-22 16:30:43'),
(197, 404, 10, 25000, 80, 0, 2000000, NULL, '2025-07-22 16:34:22', '2025-07-22 16:38:06'),
(198, 406, 10, 25000, 1, 0, 25000, NULL, '2025-07-22 16:40:24', '2025-07-22 16:40:24'),
(202, 408, 10, 25000, 8, 0, 200000, NULL, '2025-07-22 17:14:09', '2025-07-22 17:14:32'),
(204, 409, 10, 25000, 1, 0, 25000, NULL, '2025-07-22 17:20:45', '2025-07-22 17:20:45'),
(205, 410, 10, 25000, 1, 0, 25000, NULL, '2025-07-22 17:57:23', '2025-07-22 17:57:23'),
(206, 411, 10, 25000, 1, 0, 25000, NULL, '2025-07-22 18:04:42', '2025-07-22 18:04:42'),
(207, 414, 10, 25000, 5, 90, 125000, NULL, '2025-07-22 18:27:01', '2025-07-22 18:27:26'),
(208, 415, 9, 18900, 80, 90, 1512000, NULL, '2025-07-22 18:28:15', '2025-07-22 18:30:23'),
(209, 416, 10, 25000, 1, 90, 25000, NULL, '2025-07-22 18:31:23', '2025-07-22 18:32:35'),
(210, 416, 10, 25000, 1, 90, 25000, NULL, '2025-07-22 18:32:21', '2025-07-22 18:32:35'),
(211, 418, 10, 25000, 100, 0, 2500000, NULL, '2025-07-22 18:36:29', '2025-07-22 18:36:32'),
(212, 419, 9, 18900, 1, 80, 18900, NULL, '2025-07-22 18:38:17', '2025-07-22 18:38:36'),
(213, 420, 9, 18900, 1, 80, 18900, NULL, '2025-07-22 18:41:47', '2025-07-22 18:55:48'),
(214, 421, 9, 18900, 1, 80, 18900, NULL, '2025-07-22 18:58:53', '2025-07-22 18:58:59'),
(215, 422, 9, 18900, 1, 80, 18900, NULL, '2025-07-22 19:01:58', '2025-07-22 19:04:55'),
(216, 423, 9, 18900, 1, 0, 18900, NULL, '2025-07-22 19:06:37', '2025-07-22 19:06:37'),
(217, 424, 9, 18900, 1, 90, 18900, NULL, '2025-07-22 19:16:51', '2025-07-22 19:16:59'),
(218, 425, 9, 18900, 1, 0, 18900, NULL, '2025-07-22 19:18:55', '2025-07-22 19:18:55'),
(219, 426, 9, 18900, 1, 90, 18900, NULL, '2025-07-22 19:19:22', '2025-07-22 19:19:29'),
(220, 427, 10, 25000, 1, 0, 25000, NULL, '2025-07-22 19:21:01', '2025-07-22 19:21:01'),
(221, 428, 9, 18900, 1, 90, 18900, NULL, '2025-07-22 19:23:38', '2025-07-22 19:23:46'),
(222, 429, 10, 25000, 1, 80, 25000, NULL, '2025-07-22 19:24:03', '2025-07-22 19:24:53'),
(223, 430, 9, 18900, 1, 80, 18900, NULL, '2025-07-22 19:25:05', '2025-07-22 19:27:48'),
(224, 431, 9, 18900, 1, 90, 18900, NULL, '2025-07-22 19:32:54', '2025-07-22 19:33:46'),
(225, 432, 10, 25000, 10, 0, 250000, NULL, '2025-07-22 19:34:30', '2025-07-22 19:34:32'),
(226, 433, 9, 18900, 1, 0, 18900, NULL, '2025-07-22 19:36:08', '2025-07-22 19:36:08');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(10) UNSIGNED NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `kode_produk` varchar(255) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `harga_beli` int(11) NOT NULL,
  `diskon` tinyint(4) NOT NULL DEFAULT 0,
  `harga_jual` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `kode_produk`, `nama_produk`, `merk`, `harga_beli`, `diskon`, `harga_jual`, `stok`, `created_at`, `updated_at`) VALUES
(9, 3, 'P000001', 'americano', 'original', 8900, 0, 18900, 69, '2025-07-17 16:42:47', '2025-07-22 19:33:46'),
(10, 7, 'P000010', 'kentaki goreng pedas manis', 'Reguler', 8900, 0, 25000, 998, '2025-07-17 16:43:52', '2025-07-22 19:24:53');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('pk1pVuSrMQO9YiFOFd6Wo51Q5tqghTS3hNGuV6yF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiczN3ZGpLMWJJdnhIOURkaUJhcjVOanN1UGFNTjRQRHR4MlBEQWc4cSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1753215582);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id_setting` int(10) UNSIGNED NOT NULL,
  `nama_perusahaan` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(255) NOT NULL,
  `tipe_nota` tinyint(4) NOT NULL,
  `diskon` smallint(6) NOT NULL DEFAULT 0,
  `path_logo` varchar(255) NOT NULL,
  `path_kartu_member` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id_setting`, `nama_perusahaan`, `alamat`, `telepon`, `tipe_nota`, `diskon`, `path_logo`, `path_kartu_member`, `created_at`, `updated_at`) VALUES
(1, 'frekuensi Kopi', 'Kp.Karang Mulya, Rt001/011 No.52\r\nKec. Karang Tengah, Koa Tangerang, Prov Banten', '081234779987', 1, 5, '/img/logo-20250717004340.png', '/img/logo-2025-07-17004427.png', NULL, '2025-07-16 17:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(10) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama`, `alamat`, `telepon`, `created_at`, `updated_at`) VALUES
(4, 'asep', '08888', '0897', '2025-07-14 07:53:40', '2025-07-14 07:53:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `level` tinyint(4) NOT NULL DEFAULT 0,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `foto`, `level`, `two_factor_secret`, `two_factor_recovery_codes`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', NULL, '$2y$10$AnMNrIhX3ppFJCUKmrKBjuK43PD2QLzpXP.eTbTPWxpC3B5xD.uv.', '/img/logo-20241115040445.png', 1, NULL, NULL, NULL, NULL, NULL, '2024-11-14 15:28:39', '2024-11-14 21:04:45'),
(9, 'elin', 'elin@gmail.com', NULL, '$2y$10$NLEJPByURlouloGtXuc/Pu1yFH05FFEKgtlg4qf8IQm035Lv39s3u', '/img/user.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-07-15 18:00:11', '2025-07-15 18:00:11'),
(10, 'fikri', 'fikri@gmail.com', NULL, '$2y$10$5XVJpE.sl13GUJAjT.bmPuC0O0WwSQMyey.sNAkurAkgCVPSmQMAu', '/img/user.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-07-15 18:00:56', '2025-07-15 18:00:56'),
(11, 'kepo', 'hilih@gmail.com', NULL, '$2y$10$j3Wpq7YzYXFZdpx2DkF5jexUEWH/BTustUy9KoRUNsUNLuJ.FkDPe', '/img/user.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-07-15 18:01:36', '2025-07-15 18:01:36'),
(13, 'kapok', 'elin1@gmail.com', NULL, '$2y$10$OGBtcfmj/.dEIn1jCxzX8.jkVqPmAQ90fLcre4GEvu9O443Qk/f8G', '/img/user.jpg', 1, NULL, NULL, NULL, NULL, NULL, '2025-07-15 18:14:01', '2025-07-15 18:14:01'),
(14, 'TAMI', 'a@a.com', NULL, '$2y$10$E0RNjGE6j9slORfyKbAbdulNvPNfdcxmDnrqvfgBq3292iOQt5dHW', '/img/logo-20250716233802.png', 0, NULL, NULL, NULL, NULL, NULL, '2025-07-15 18:21:44', '2025-07-16 16:38:02'),
(15, 'kepo', 'kepo@gmail.com', NULL, '$2y$10$kJ6jryi7vRtKqq1K9bQX2et3zymo2etOIKX0eWvqcU5VSA/sHVD22', NULL, 0, NULL, NULL, NULL, NULL, NULL, '2025-07-15 18:33:58', '2025-07-15 18:33:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `kategori_nama_kategori_unique` (`nama_kategori`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id_member`),
  ADD UNIQUE KEY `member_kode_member_unique` (`kode_member`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indexes for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD PRIMARY KEY (`id_pembelian_detail`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD PRIMARY KEY (`id_penjualan_detail`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `produk_nama_produk_unique` (`nama_produk`),
  ADD UNIQUE KEY `produk_kode_produk_unique` (`kode_produk`),
  ADD KEY `produk_id_kategori_foreign` (`id_kategori`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id_member` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `id_pembelian_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=434;

--
-- AUTO_INCREMENT for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `id_penjualan_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id_setting` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
