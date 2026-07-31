-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 31 Jul 2026 pada 21.38
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.5.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kantin_bu_emi`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cari_penjualan` (IN `p_tanggal` DATE)   BEGIN
SELECT *
FROM penjualan
WHERE tanggal_penjualan=p_tanggal;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tambah_menu` (IN `p_nama_menu` VARCHAR(100), IN `p_harga` DECIMAL(10,2))   BEGIN
INSERT INTO menu(nama_menu,harga)
VALUES(p_nama_menu,p_harga);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tampil_pembelian` ()   BEGIN
SELECT
pb.id_pembelian,
pb.tanggal_pembelian,
s.nama_supplier,
pg.nama_pegawai,
pb.total_pembelian
FROM pembelian pb
JOIN supplier s
ON pb.id_supplier=s.id_supplier
JOIN pegawai pg
ON pb.id_pegawai=pg.id_pegawai;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tampil_penjualan` ()   BEGIN
SELECT
p.id_penjualan,
p.tanggal_penjualan,
pg.nama_pegawai,
p.total_penjualan
FROM penjualan p
JOIN pegawai pg
ON p.id_pegawai=pg.id_pegawai;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `total_pendapatan` ()   BEGIN
SELECT
SUM(total_penjualan) AS total_pendapatan
FROM penjualan;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan_bakus`
--

CREATE TABLE `bahan_bakus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_bahan` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bahan_bakus`
--

INSERT INTO `bahan_bakus` (`id`, `nama_bahan`, `stok`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 'beras', 21, 'kg', '2026-07-30 01:52:18', '2026-07-30 20:19:33'),
(3, 'ayam', 89, 'kg', '2026-07-30 12:27:01', '2026-07-30 20:36:13'),
(4, 'cabai merah', 3, 'kg', '2026-07-30 12:30:07', '2026-07-30 12:30:07'),
(5, 'cabai kecil', 7, 'kg', '2026-07-30 12:31:00', '2026-07-30 12:31:00'),
(6, 'minyak makan', 16, 'liter', '2026-07-30 12:31:35', '2026-07-30 20:19:19'),
(8, 'beras', 15, 'kg', '2026-07-30 20:18:50', '2026-07-30 20:18:50'),
(9, 'tepung', 8, 'kg', '2026-07-30 20:26:50', '2026-07-30 20:26:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pembelians`
--

CREATE TABLE `detail_pembelians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pembelian_id` bigint(20) UNSIGNED NOT NULL,
  `bahan_baku_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_pembelians`
--

INSERT INTO `detail_pembelians` (`id`, `pembelian_id`, `bahan_baku_id`, `jumlah`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 10, 25.00, 250.00, '2026-07-30 12:27:01', '2026-07-30 12:27:01'),
(2, 3, 4, 3, 8000.00, 24000.00, '2026-07-30 12:30:07', '2026-07-30 12:30:07'),
(3, 4, 5, 7, 18000.00, 126000.00, '2026-07-30 12:31:00', '2026-07-30 12:31:00'),
(4, 5, 6, 6, 22000.00, 132000.00, '2026-07-30 12:31:35', '2026-07-30 12:31:35'),
(5, 6, 3, 25, 29000.00, 725000.00, '2026-07-30 20:36:13', '2026-07-30 20:36:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualans`
--

CREATE TABLE `detail_penjualans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penjualan_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `level_sambal` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_penjualans`
--

INSERT INTO `detail_penjualans` (`id`, `penjualan_id`, `menu_id`, `jumlah`, `level_sambal`, `catatan`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 1, 'Sedang', NULL, 15000.00, 15000.00, '2026-07-29 23:41:56', '2026-07-29 23:41:56'),
(3, 3, 1, 2, 'Pedas', NULL, 15000.00, 30000.00, '2026-07-29 23:42:25', '2026-07-29 23:42:25'),
(4, 4, 1, 12, 'Sedang', NULL, 15000.00, 180000.00, '2026-07-29 23:43:47', '2026-07-29 23:43:47'),
(5, 5, 1, 1, 'Sedang', NULL, 15000.00, 15000.00, '2026-07-29 23:47:49', '2026-07-29 23:47:49'),
(6, 6, 1, 4, 'Sedang', NULL, 15000.00, 60000.00, '2026-07-29 23:52:18', '2026-07-29 23:52:18'),
(7, 7, 1, 1, 'Sedang', NULL, 15000.00, 15000.00, '2026-07-30 00:13:23', '2026-07-30 00:13:23'),
(8, 8, 1, 1, 'Sedang', NULL, 15000.00, 15000.00, '2026-07-30 00:25:29', '2026-07-30 00:25:29'),
(9, 9, 1, 2, 'Pedas', 'sambal dipisah, dan ayamnya jangan di geprek', 10000.00, 20000.00, '2026-07-30 20:01:19', '2026-07-30 20:01:19'),
(10, 10, 1, 1, 'Pedas', 'sambalnya banyakin ya', 10000.00, 10000.00, '2026-07-31 02:35:15', '2026-07-31 02:35:15'),
(11, 11, 1, 1, 'Sangat Pedas', NULL, 10000.00, 10000.00, '2026-07-31 07:01:06', '2026-07-31 07:01:06'),
(12, 12, 1, 1, 'Sedang', 'sambal dipisah', 10000.00, 10000.00, '2026-07-31 10:15:49', '2026-07-31 10:15:49'),
(13, 13, 1, 3, 'Pedas', 'satu sambal sedang, 2 lagi pedas', 10000.00, 30000.00, '2026-07-31 10:42:34', '2026-07-31 10:42:34'),
(14, 14, 1, 3, 'Pedas', 'satu sambal sedang, 2 lagi pedas', 10000.00, 30000.00, '2026-07-31 10:43:16', '2026-07-31 10:43:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_menu` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('Tersedia','Habis') NOT NULL DEFAULT 'Tersedia',
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menus`
--

INSERT INTO `menus` (`id`, `nama_menu`, `harga`, `foto`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Ayam Geprek Bu Emi', 10000.00, NULL, 'Tersedia', 'Ayam crispy dengan sambal khas Bu Emi yang pedas, gurih, dan dibuat menggunakan bahan-bahan segar setiap hari.', '2026-07-29 23:41:56', '2026-07-29 23:41:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_30_061801_create_menus_table', 1),
(5, '2026_07_30_061802_create_pegawais_table', 1),
(6, '2026_07_30_061803_create_suppliers_table', 1),
(7, '2026_07_30_061804_create_bahan_bakus_table', 1),
(8, '2026_07_30_061805_create_pembelians_table', 1),
(9, '2026_07_30_061806_create_detail_pembelians_table', 1),
(10, '2026_07_30_061807_create_penjualans_table', 1),
(11, '2026_07_30_061808_create_detail_penjualans_table', 1),
(12, '2026_07_30_201426_create_pengaturans_table', 2),
(13, '2026_07_30_201445_add_foto_and_status_to_menus_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pegawai` varchar(255) NOT NULL,
  `posisi` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelians`
--

CREATE TABLE `pembelians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pegawai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_pembelian` datetime NOT NULL,
  `total_pembelian` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembelians`
--

INSERT INTO `pembelians` (`id`, `pegawai_id`, `supplier_id`, `tanggal_pembelian`, `total_pembelian`, `created_at`, `updated_at`) VALUES
(2, NULL, 1, '2026-07-30 19:27:01', 250.00, '2026-07-30 12:27:01', '2026-07-30 12:27:01'),
(3, NULL, 1, '2026-07-30 19:30:07', 24000.00, '2026-07-30 12:30:07', '2026-07-30 12:30:07'),
(4, NULL, 1, '2026-07-30 19:31:00', 126000.00, '2026-07-30 12:31:00', '2026-07-30 12:31:00'),
(5, NULL, 1, '2026-07-30 19:31:35', 132000.00, '2026-07-30 12:31:35', '2026-07-30 12:31:35'),
(6, NULL, 1, '2026-07-31 03:36:13', 725000.00, '2026-07-30 20:36:13', '2026-07-30 20:36:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturans`
--

CREATE TABLE `pengaturans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kantin` varchar(255) NOT NULL DEFAULT 'Kantin Bu Emi',
  `nama_pemilik` varchar(255) NOT NULL DEFAULT 'Bu Emi',
  `alamat` text DEFAULT NULL,
  `link_gmaps` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `jam_operasional` varchar(255) DEFAULT NULL,
  `deskripsi_singkat` text DEFAULT NULL,
  `dana_nomor` varchar(255) DEFAULT NULL,
  `dana_nama` varchar(255) DEFAULT NULL,
  `dana_qr` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengaturans`
--

INSERT INTO `pengaturans` (`id`, `nama_kantin`, `nama_pemilik`, `alamat`, `link_gmaps`, `no_hp`, `jam_operasional`, `deskripsi_singkat`, `dana_nomor`, `dana_nama`, `dana_qr`, `created_at`, `updated_at`) VALUES
(1, 'Kantin Bu Emi', 'Bu Emi', 'batuphat, lhokseumawe. sebelah zahra mart', NULL, '082174505204', 'senin - sabtu 09.00-22.00', NULL, '082174505204', 'dena', NULL, '2026-07-30 13:25:19', '2026-07-30 19:57:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualans`
--

CREATE TABLE `penjualans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_pesanan` varchar(255) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `metode_pembayaran` varchar(255) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `total_penjualan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status_pembayaran` varchar(255) NOT NULL DEFAULT 'Belum Dibayar',
  `status_pesanan` varchar(255) NOT NULL DEFAULT 'Menunggu Pembayaran',
  `waktu_pemesanan` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penjualans`
--

INSERT INTO `penjualans` (`id`, `no_pesanan`, `nama_pelanggan`, `no_hp`, `metode_pembayaran`, `bukti_transfer`, `total_penjualan`, `status_pembayaran`, `status_pesanan`, `waktu_pemesanan`, `created_at`, `updated_at`) VALUES
(1, 'ORD-20260730-7VRD', 'dena', '0821456789', NULL, NULL, 15000.00, 'Belum Dibayar', 'Menunggu Pembayaran', '2026-07-30 06:31:12', '2026-07-29 23:31:12', '2026-07-29 23:31:12'),
(2, 'ORD-20260730-PWRZ', 'dena', '0821456789', NULL, NULL, 15000.00, 'Belum Dibayar', 'Menunggu Pembayaran', '2026-07-30 06:41:56', '2026-07-29 23:41:56', '2026-07-29 23:41:56'),
(3, 'ORD-20260730-UEH3', 'duta', '0825678456', NULL, NULL, 30000.00, 'Belum Dibayar', 'Menunggu Pembayaran', '2026-07-30 06:42:25', '2026-07-29 23:42:25', '2026-07-29 23:42:25'),
(4, 'ORD-20260730-RLAE', 'raraa', '08234567890', NULL, NULL, 180000.00, 'Belum Dibayar', 'Selesai', '2026-07-30 06:43:47', '2026-07-29 23:43:47', '2026-07-29 23:48:32'),
(5, 'ORD-20260730-YQBF', 'raraa', '08234567890', NULL, NULL, 15000.00, 'Belum Dibayar', 'Menunggu Pembayaran', '2026-07-30 06:47:49', '2026-07-29 23:47:49', '2026-07-29 23:47:49'),
(6, 'ORD-20260730-9EN1', 'jia', '0821678905', NULL, NULL, 60000.00, 'Belum Dibayar', 'Menunggu Pembayaran', '2026-07-30 06:52:18', '2026-07-29 23:52:18', '2026-07-29 23:52:18'),
(7, 'ORD-20260730-QTXM', 'duta', '0825678456', NULL, NULL, 15000.00, 'Belum Dibayar', 'Menunggu Pembayaran', '2026-07-30 07:13:23', '2026-07-30 00:13:23', '2026-07-30 00:13:23'),
(8, 'ORD-20260730-NOK0', 'duta', '0825678456', 'DANA', 'bukti_transfer/iZ18jy5Y08O8RnH6nKKH0EmQ2yY2y1Re17upRCfm.jpg', 15000.00, 'Menunggu Verifikasi', 'Selesai', '2026-07-30 07:25:29', '2026-07-30 00:25:29', '2026-07-30 00:47:59'),
(9, 'ORD-20260731-EHZY', 'ririn', '0812987654321', 'DANA', 'bukti_transfer/0SxnYeHzlnLZKn9oBpKrkpuTuv2QZl6MxMp7UKED.png', 20000.00, 'Menunggu Verifikasi', 'Selesai', '2026-07-31 03:01:19', '2026-07-30 20:01:19', '2026-07-30 20:03:38'),
(10, 'ORD-20260731-HFZZ', 'nisa', '082189765432', 'DANA', 'bukti_transfer/Vq6DDjGbRiWjTklzVc8qOtkFtA7bZyWWvMP5va23.jpg', 10000.00, 'Menunggu Verifikasi', 'Menunggu Diproses', '2026-07-31 09:35:15', '2026-07-31 02:35:15', '2026-07-31 02:35:34'),
(11, 'ORD-20260731-B169', 'anggun', '08526543217', 'DANA', 'bukti_transfer/lfQc6qHRDu2jdNAM53bOQ3e8r4FzymWUBxla8p5p.png', 10000.00, 'Menunggu Verifikasi', 'Diproses', '2026-07-31 14:01:06', '2026-07-31 07:01:06', '2026-07-31 10:40:44'),
(12, 'ORD-20260731-UMQR', 'anggun', '08526543217', 'DANA', 'bukti_transfer/5wcGvyq0mCzyOrbbXStLTKydQZgMc9CRz7v1GlAq.png', 10000.00, 'Menunggu Verifikasi', 'Diproses', '2026-07-31 17:15:49', '2026-07-31 10:15:49', '2026-07-31 10:40:16'),
(13, 'ORD-20260731-32SF', 'siti khairunnisa', '08234567890', NULL, NULL, 30000.00, 'Belum Dibayar', 'Menunggu Pembayaran', '2026-07-31 17:42:34', '2026-07-31 10:42:34', '2026-07-31 10:42:34'),
(14, 'ORD-20260731-Y1XK', 'siti khairunnisa', '08234567890', 'DANA', 'bukti_transfer/we4wfu0Snlp1onkdIfdVKBY68Zoz9CbrStMvZr9H.png', 30000.00, 'Menunggu Verifikasi', 'Menunggu Diproses', '2026-07-31 17:43:16', '2026-07-31 10:43:16', '2026-07-31 10:51:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`id`, `nama_supplier`, `alamat`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 'Pajak Impres', 'Pasar Pajak Impres', '-', '2026-07-30 09:16:11', '2026-07-30 09:16:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Utama', 'admin@kantinbuemi.com', NULL, '$2y$12$MaXqbdXYE.ojifm.eJc4..kqaxVGrIrVaK31yZkFZWqTPLJWkUfM.', NULL, '2026-07-30 13:30:40', '2026-07-30 13:30:40');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_detail_penjualan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_detail_penjualan` (
`id_detail_penjualan` bigint(20) unsigned
,`nama_menu` varchar(255)
,`jumlah` int(11)
,`subtotal` decimal(12,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_laporan_penjualan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_laporan_penjualan` (
`tanggal_penjualan` date
,`jumlah_transaksi` bigint(21)
,`total_pendapatan` decimal(34,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_pembelian`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_pembelian` (
`id_pembelian` bigint(20) unsigned
,`tanggal_pembelian` datetime
,`nama_supplier` varchar(255)
,`nama_pegawai` varchar(255)
,`total_pembelian` decimal(12,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_penjualan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_penjualan` (
`id_penjualan` bigint(20) unsigned
,`tanggal_penjualan` datetime
,`nama_pegawai` varchar(5)
,`total_penjualan` decimal(12,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_stok_bahan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_stok_bahan` (
`id_bahan` bigint(20) unsigned
,`nama_bahan` varchar(255)
,`satuan` varchar(255)
,`stok` int(11)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `view_detail_penjualan`
--
DROP TABLE IF EXISTS `view_detail_penjualan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_detail_penjualan`  AS SELECT `dp`.`id` AS `id_detail_penjualan`, `m`.`nama_menu` AS `nama_menu`, `dp`.`jumlah` AS `jumlah`, `dp`.`subtotal` AS `subtotal` FROM (`detail_penjualans` `dp` join `menus` `m` on(`dp`.`menu_id` = `m`.`id`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_laporan_penjualan`
--
DROP TABLE IF EXISTS `view_laporan_penjualan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_laporan_penjualan`  AS SELECT cast(`penjualans`.`waktu_pemesanan` as date) AS `tanggal_penjualan`, count(`penjualans`.`id`) AS `jumlah_transaksi`, sum(`penjualans`.`total_penjualan`) AS `total_pendapatan` FROM `penjualans` GROUP BY cast(`penjualans`.`waktu_pemesanan` as date) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_pembelian`
--
DROP TABLE IF EXISTS `view_pembelian`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pembelian`  AS SELECT `pb`.`id` AS `id_pembelian`, `pb`.`tanggal_pembelian` AS `tanggal_pembelian`, `s`.`nama_supplier` AS `nama_supplier`, `pg`.`nama_pegawai` AS `nama_pegawai`, `pb`.`total_pembelian` AS `total_pembelian` FROM ((`pembelians` `pb` left join `suppliers` `s` on(`pb`.`supplier_id` = `s`.`id`)) left join `pegawais` `pg` on(`pb`.`pegawai_id` = `pg`.`id`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_penjualan`
--
DROP TABLE IF EXISTS `view_penjualan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_penjualan`  AS SELECT `p`.`id` AS `id_penjualan`, `p`.`waktu_pemesanan` AS `tanggal_penjualan`, 'Admin' AS `nama_pegawai`, `p`.`total_penjualan` AS `total_penjualan` FROM `penjualans` AS `p` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_stok_bahan`
--
DROP TABLE IF EXISTS `view_stok_bahan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_stok_bahan`  AS SELECT `bahan_bakus`.`id` AS `id_bahan`, `bahan_bakus`.`nama_bahan` AS `nama_bahan`, `bahan_bakus`.`satuan` AS `satuan`, `bahan_bakus`.`stok` AS `stok` FROM `bahan_bakus` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan_bakus`
--
ALTER TABLE `bahan_bakus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `detail_pembelians`
--
ALTER TABLE `detail_pembelians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_pembelians_pembelian_id_foreign` (`pembelian_id`),
  ADD KEY `detail_pembelians_bahan_baku_id_foreign` (`bahan_baku_id`);

--
-- Indeks untuk tabel `detail_penjualans`
--
ALTER TABLE `detail_penjualans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_penjualans_penjualan_id_foreign` (`penjualan_id`),
  ADD KEY `detail_penjualans_menu_id_foreign` (`menu_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembelians_pegawai_id_foreign` (`pegawai_id`),
  ADD KEY `pembelians_supplier_id_foreign` (`supplier_id`);

--
-- Indeks untuk tabel `pengaturans`
--
ALTER TABLE `pengaturans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualans`
--
ALTER TABLE `penjualans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penjualans_no_pesanan_unique` (`no_pesanan`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bahan_bakus`
--
ALTER TABLE `bahan_bakus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `detail_pembelians`
--
ALTER TABLE `detail_pembelians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `detail_penjualans`
--
ALTER TABLE `detail_penjualans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pengaturans`
--
ALTER TABLE `pengaturans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `penjualans`
--
ALTER TABLE `penjualans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pembelians`
--
ALTER TABLE `detail_pembelians`
  ADD CONSTRAINT `detail_pembelians_bahan_baku_id_foreign` FOREIGN KEY (`bahan_baku_id`) REFERENCES `bahan_bakus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pembelians_pembelian_id_foreign` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelians` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_penjualans`
--
ALTER TABLE `detail_penjualans`
  ADD CONSTRAINT `detail_penjualans_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_penjualans_penjualan_id_foreign` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualans` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  ADD CONSTRAINT `pembelians_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawais` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pembelians_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
