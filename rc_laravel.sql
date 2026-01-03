-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 03 Jan 2026 pada 10.57
-- Versi server: 8.4.3
-- Versi PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `rc_laravel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aturan`
--

CREATE TABLE `aturan` (
  `id` bigint UNSIGNED NOT NULL,
  `syarat_ketentuan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `larangan_dan_denda` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `aturan`
--

INSERT INTO `aturan` (`id`, `syarat_ketentuan`, `larangan_dan_denda`, `created_at`, `updated_at`) VALUES
(1, 'A.	Pemesanan\r\n1.	Pastikan akun Instagram anda tidak dikunci, fake, dan kosong. Akun minimal memiliki sorotan dan feeds disertai wajah anda.\r\n2.	Booking tidak bisa secara mendadak, booking hanya bisa Min. H-7 hari dan Max. H-30 hari sebelum tanggal pemakaian.\r\n3.	Kostum bisa dibooking untuk weekdays (hanya untuk kota sukabumi).\r\n4.	Kostum bisa sewa half set, tetapi harga tetap sama.\r\n5.	Menanyakan terlebih dahulu terkait tanggal ketersediaan kostum dan detail ongkir kepada Owner Via DM Instagram atau Chat WhatsApp.\r\n6.	Pembayaran ongkir akan disatukan dengan harga kostum.\r\n7.	Jika kostum tersedia dan anda setuju, Owner akan memberikan formulir berupa link google drive.  Pengisian wajib sesuai dengan data diri, lengkap, dan teliti. Jika ada kendala bisa tanyakan kepada owner dan kesalahan pengisian bukan tanggung jawab Owner.\r\n8.	Jika form sudah diisi, Owner akan menawarkan opsi pembayaran berupa DP atau Tunai:\r\nA.	DP minimal 50% harga kostum, kostum akan dikirim jika DP Lunas, pelunasan DP max H-5 pemakaian dan jika batal DP akan hangus.\r\nB.	Kostum dengan pembayaran Tunai akan langsung dikirim pada hari pelunasan atau hari besoknya dan jika batal akan kembali 25%.\r\n9.	Pembayaran hanya bisa melalui Dana, GoPay, ShopeePay, dan Transfer Bank.\r\n10.	Kostum yang sudah dibooking dan dibayar DP maupun Tunai tidak dapat ganti hari.\r\n11.	Tidak ada pengembalian dana jika keterlambatan dari pihak ekspedisi.\r\n\r\nB.	Pemakaian\r\n1.	Durasi penyewaan hanya 3 hari (terdapat bonus 1 hari jika durasi event 2 hari), dihitung saat kostum sudah sampai. Contoh:\r\nA.	Hari Jumat		: Kostum telah sampai\r\nB.	Hari Sabtu/Minggu	: Kostum digunakan\r\nC.	Hari Senin		: Kostum dikembalikan\r\n2.	Jika kostum sampai lebih cepat dianggap bonus (pengembalian tetap hari senin).\r\n3.	Kostum yang sudah sampai wajib membuat dokumentasi berupa video unboxing dan foto kostum secara lengkap, serta wajib konfirmasi jika ada kesalahan dan kekurangan pada kostum.\r\n4.	Wajib menjaga, merawat, serta memperhatikan kostum yang digunakan, terutama kostum yang mempunyai banyak aksesoris.\r\n5.	Wajib melaporkan kepada Owner jika ada kerusakan, kehilangan, atau kendala lainnya (wajib sertakan dokumentasi).\r\n\r\nC.	Pengembalian\r\n1.	Wajib memperhatikan kostum jika ingin dikembalikan.\r\n2.	Kostum tidak perlu dicuci, hanya diangin-angin saja (boleh diwangikan dengan parfum atau sejenisnya).\r\n3.	Selipkan uang laundry sebesar Rp10.000.\r\n4.	Pastikan kostum dalam keadaan kering, bersih, dan tidak bau.\r\n5.	Pengemasan kostum wajib menggunakan plastik berwarna hitam atau boleh menggunakan kembali plastik yang Owner gunakan.\r\n6.	Pengembalian wajib menggunakan salah satu ekspedisi sebagai berikut:\r\nA.	COD/GoSend/GrabExpress (untuk kota sukabumi).\r\nB.	JNE	: (Reguler/YES).\r\nC.	J&T	: (EZ/Super).\r\nD.	Paxel	: (Same Day/Next Day).\r\n7.	Wajib mengirimkan resi pengembalian kepada owner.\r\n8.	Maksimal pengembalian untuk seluruh ekspedisi dan pengiriman resi adalah pukul 21:00 WIB, untuk COD/GoSend/GrabExpress adalah pukul 18:00 WIB.\r\n9.	Pengembalian kostum wajib tepat waktu.', 'A.	Larangan saat Pemesanan \r\n1.	Dilarang memberikan data yang tidak lengkap atau tidak sesuai pada formulir.\r\n2.	Dilarang menawar harga kostum dan ongkir.\r\n3.	Dilarang melebihi durasi masa penyewaan yang telah ditentukan.\r\n\r\nB.	Larangan saat Pemakaian\r\n1.	Dilarang meminjamkan/mengoper/bertukar kostum kepada selain identitas penyewa atau orang lain.\r\n2.	Dilarang memotong/menggunting wig.\r\n3.	Dilarang menggunakan kostum saat anda kehujanan.\r\n4.	Dilarang menodai/mengotori kostum.\r\n5.	Dilarang menghilangkan tote bag, plastik zip, dan plastik wig.\r\n\r\nC.	Larangan saat Pengembalian\r\n1.	Dilarang telat mengembalikan kostum.\r\n2.	Dilarang memasukkan paksa kostum kedalam plastik zip dan tote bag, kostum harus dilipat rapi.\r\n3.	Dilarang mengembalikan kostum dengan ekspedisi yang tidak ditentukan.\r\n\r\nDenda:\r\n1.	Tidak membuat dokumentasi video atau foto kostum: Rp20.000.\r\n2.	Melebihi durasi masa penyewaan atau telat pengembalian: Rp20.000 perhari.\r\n3.	Meminjamkan/mengoper/bertukar kostum kepada selain identitas penyewa atau orang lain: Dua kali lipat harga sewa.\r\n4.	Wig kusut/kotor ringan: Rp25.000.\r\n5.	Wig kusut/kotor/bau parah: Rp60.000.\r\n6.	Wig rusak terpotong: Ganti baru.\r\n7.	Kostum kotor/rusak/bau ringan: Rp25.000.\r\n8.	Kostum kotor/rusak/bau parah: Rp100.000 - Ganti baru.\r\n9.	Aksesoris hilang/rusak: Harga Menyesuaikan.\r\n10.	Menghilangkan Tote bag/plastik zip/plastik wig: Rp10.000.\r\n11.	Mengembalikan kostum dengan ekspedisi yang tidak ditentukan tanpa konfirmasi: Rp30.000.', '2025-12-26 11:03:18', '2025-12-29 05:38:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_katalog`
--

CREATE TABLE `data_katalog` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `data_katalog`
--

INSERT INTO `data_katalog` (`id`, `name`, `kategori`, `description`, `image`) VALUES
(1, 'Anime', 'Anime', 'Katalog Kostum Anime', 'storage/1766760401_c267fce8-dfc1-412d-be8b-d796728e0079.webp'),
(2, 'Manga', 'Manga', 'Katalog Kostum Manga', 'storage/1766760432_Fxi3w-FaQAAMcvH.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kostum`
--

CREATE TABLE `data_kostum` (
  `id_kostum` bigint UNSIGNED NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kostum` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_sewa` decimal(10,2) NOT NULL,
  `durasi_penyewaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ukuran_kostum` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('Pria','Wanita','Unisex') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `include` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `exclude` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `domisili` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `data_kostum`
--

INSERT INTO `data_kostum` (`id_kostum`, `kategori`, `nama_kostum`, `judul`, `harga_sewa`, `durasi_penyewaan`, `ukuran_kostum`, `jenis_kelamin`, `include`, `exclude`, `domisili`, `brand`, `gambar`) VALUES
(1, 'Anime', 'Gojo Satoru', 'Jujutsu Kaisen', 70000.00, '3 hari', 'L', 'Pria', 'Wig (Manmei), Kacamata bulat hitam, Baju (L), dan Celana.', '-', 'Kota Sukabumi, Jawa Barat', '-', 'storage/1766757876_SnapInsta.to_486462158_17894161014191641_2440637046493285361_n.jpg'),
(2, 'Anime', 'Megumi Fushiguro', 'Jujutsu Kaisen', 65000.00, '3 hari', 'L', 'Pria', 'Wig (Manmei), Baju (L), dan Celana.', '-', 'Kota Sukabumi, Jawa Barat', '-', 'storage/1766758140_SnapInsta.to_486411766_17894160321191641_4866022261319975491_n.jpg'),
(3, 'Anime', 'Yuta Okkotsu', 'Jujutsu Kaisen', 55000.00, '3 hari', 'XL', 'Pria', 'Baju (XL), Celana, Sabuk putih (panjang: 175 cm), Kalung, (panjang: 60 cm), dan Cincin.', 'Katana & Bag = 15.000', 'Kota Sukabumi, Jawa Barat', '-', 'storage/1766758286_SnapInsta.to_487323031_17894162040191641_1376162627196208926_n.jpg'),
(4, 'Anime', 'Guren Ichinose', 'Owari No Seraph', 85000.00, '3 hari', 'L', 'Pria', 'Baju (L), Celana, Sarung tangan, Acc baju, dan Acc celana.', 'Katana (belum tersedia)', 'Kota Sukabumi, Jawa Barat', 'Xinlaisen', 'storage/1766758440_SnapInsta.to_500629973_17901424425191641_7525233393312456475_n.jpg'),
(5, 'Anime', 'Shinya Hiiragi', 'Owari No Seraph', 95000.00, '3 hari', 'L', 'Pria', 'Wig (Manmei), Baju (L), Celana, Sarung tangan, dan Acc baju.', 'Senapan = Rp20.000', 'Kota Sukabumi, Jawa Barat', 'Xinlaisen', 'storage/1766758563_SnapInsta.to_501275211_17901424572191641_847831286802635238_n.jpg'),
(6, 'Anime', 'Denji', 'Chainsaw Man', 50000.00, '3 hari', 'M & L', 'Pria', 'Wig (Manmei), Baju (M & L), Dasi hitam, & Celana', 'Boneka Pochita & Kapak', 'Kota Sukabumi, Jawa Barat', '-', 'storage/1766760282_7_20251124_172451_0000.png'),
(7, 'Manga', 'Kishibe (young version)', 'Chainsaw Man', 55000.00, '3 hari', 'M & L', 'Pria', 'Anting jepit (3 buah), Baju (M & L), Dasi hitam, Body harness, & Celana.', 'Katana', 'Kota Sukabumi, Jawa Barat', '-', 'storage/1766761007_8_20251124_172451_0001.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `denda`
--

CREATE TABLE `denda` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nama_kostum` varchar(100) NOT NULL,
  `jenis_denda` varchar(100) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jumlah_denda` decimal(12,2) NOT NULL,
  `status` enum('Belum Lunas','Lunas') DEFAULT 'Belum Lunas',
  `bukti_foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bukti_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `denda`
--

INSERT INTO `denda` (`id`, `nama`, `nama_kostum`, `jenis_denda`, `keterangan`, `jumlah_denda`, `status`, `bukti_foto`, `bukti_pembayaran`, `created_at`, `updated_at`) VALUES
(3, 'rehan wangsaf', 'Gojo Satoru', 'telat balikin', 'telat wok', 15000.00, 'Lunas', '', 'denda/bukti_denda_3_1767267848.jfif', '2026-01-01 04:41:25', '2026-01-01 04:44:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `formulir`
--

CREATE TABLE `formulir` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `nomor_telepon` varchar(20) NOT NULL,
  `nomor_telepon_2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Nomor pihak lain (orang tua/teman/tetangga)',
  `nama_kostum` varchar(100) NOT NULL,
  `tanggal_pemakaian` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `total_harga` decimal(12,2) NOT NULL COMMENT 'Kostum + ongkir',
  `metode_pembayaran` varchar(50) NOT NULL,
  `kartu_identitas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Jenis identitas yang akan digunakan',
  `foto_kartu_identitas` varchar(255) NOT NULL COMMENT 'Path foto kartu identitas',
  `selfie_kartu_identitas` varchar(255) NOT NULL COMMENT 'Path selfie dengan kartu identitas',
  `pernyataan` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'proses',
  `keterangan` text,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `formulir`
--

INSERT INTO `formulir` (`id`, `nama`, `email`, `alamat`, `nomor_telepon`, `nomor_telepon_2`, `nama_kostum`, `tanggal_pemakaian`, `tanggal_pengembalian`, `total_harga`, `metode_pembayaran`, `kartu_identitas`, `foto_kartu_identitas`, `selfie_kartu_identitas`, `pernyataan`, `status`, `keterangan`, `bukti_pembayaran`, `created_at`, `updated_at`) VALUES
(10, 'rehan wangsaf', 'reisatoru.cosu@gmail.com', 'jl. bumisuki', '08123456789', '080808 - uia', 'Gojo Satoru', '2026-01-03', '2026-01-04', 89098.00, 'Transfer Bank', 'KTP', 'formulir_identitas/ZdORwfYjtHvDBIkuYfT1bvHHAhHfvTYLMpmThhpu.jpg', 'formulir_selfie/V4JiDEDSRrjgZl1oyY95kfm8hY9XIVn9kEMNsl3Z.jpg', 'Dengan ini Saya menyatakan bahwa:\r\n1. Wajib Membayar Lunas\r\n2. Menggunakan/Menjaga/Merawat Secara Baik\r\n3. Mengembalikan Secara Tepat Waktu\r\n\r\nApabila Saya Melanggar maka:\r\n1. Siap Bertanggung Jawab\r\n2. Siap Ganti Rugi\r\n3. Menerima Konsekuensi', 'selesai', 'yes king', '', '2026-01-01 02:35:14', '2026-01-01 06:21:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_21_120000_rename_name_to_username_on_users_table', 1),
(5, '2025_12_22_090000_add_nick_name_to_users_table', 1),
(6, '2025_12_22_100000_fix_nick_name_default', 1),
(7, '2025_12_22_110000_add_nomor_telepon_jenis_kelamin_to_users', 1),
(8, '2025_12_22_120000_add_google_oauth_fields_to_users', 1),
(9, '2025_12_24_130000_add_alamat_to_users_table', 1),
(10, '2025_12_27_090000_add_user_id_and_status_to_formulir', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `profile_contacts`
--

CREATE TABLE `profile_contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vision` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_ewallet` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_bank` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qris` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `profile_contacts`
--

INSERT INTO `profile_contacts` (`id`, `name`, `password`, `title`, `photo`, `vision`, `address`, `phone`, `email`, `nomor_ewallet`, `nomor_bank`, `qris`) VALUES
(1, 'Reithesley', '25122004', 'Pemilik', 'profile_photos/remWB4mrRws3X85hybYyPXwdV0fOLbVInzqJOWgG.webp', 'Pemilik Rei Cosrent', 'Jl. Rumah', '08123456789', 'admin@gmail.com', '08123456789', '08123456789 - Bank', 'payment_qris/VBhl06DmT0SzWEYR5puOxPPlv34XU5BOmnrdGDd2.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sewrLFIcXDM6QTDHnBk5kudM6JDuXNVn7IVFf9aK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiTHMyaWVXeVVPQldFaUgxcDBoQVcxZzlWRXhadXg1MUtha3lndXdtRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZXNhbmFuLXNheWEiO3M6NToicm91dGUiO3M6MTI6InVzZXIucGVzYW5hbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTQ6InVzZXJfbG9nZ2VkX2luIjtiOjE7czo3OiJ1c2VyX2lkIjtpOjU7czo5OiJ1c2VyX25hbWUiO3M6MzoicmVpIjtzOjEwOiJ1c2VyX2VtYWlsIjtzOjI0OiJyZWlzYXRvcnUuY29zdUBnbWFpbC5jb20iO3M6MTg6InVzZXJfZ2FtYmFyX3Byb2ZpbCI7czo1OToicHJvZmlsZV9pbWFnZXMvNTQ4OGtERUFWY3JFSkM0RDg5ZTJZUkY1UnhKTlRRSTBtYzZPejc3Qy5qcGciO3M6MTc6InVsYXNhbl9mb3Jfb3JkZXJzIjthOjE6e2k6MDtzOjI6IjEwIjt9fQ==', 1767437733),
('ZjEeQtp7PljqOshndsQUDCrZcjgCowzPEXkKN1xs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0YXRzP3BlcmlvZD13ZWVrIjtzOjU6InJvdXRlIjtzOjExOiJhZG1pbi5zdGF0cyI7fXM6NjoiX3Rva2VuIjtzOjQwOiJ6UG5pazRXS3h3bTlZVEJMUnhZemdORkFjcnFzdHZUazNPS21RUjFFIjtzOjE1OiJhZG1pbl9sb2dnZWRfaW4iO2I6MTtzOjEwOiJhZG1pbl9uYW1lIjtzOjU6ImFkbWluIjt9', 1767437800);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int NOT NULL,
  `rating` int NOT NULL,
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `balasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `gambar_1` varchar(255) DEFAULT NULL,
  `gambar_2` varchar(255) DEFAULT NULL,
  `gambar_3` varchar(255) DEFAULT NULL,
  `gambar_4` varchar(255) DEFAULT NULL,
  `gambar_5` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `ulasan`
--

INSERT INTO `ulasan` (`id`, `rating`, `review`, `balasan`, `gambar_1`, `gambar_2`, `gambar_3`, `gambar_4`, `gambar_5`, `created_at`, `updated_at`) VALUES
(10, 5, 'bagus', NULL, 'ulasan/ulasan_10_1_1767437433.jpg', 'ulasan/ulasan_10_2_1767437433.jfif', NULL, NULL, NULL, '2026-01-03 03:17:24', '2026-01-03 03:50:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nick_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nomor_telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('Pria','Wanita') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_profil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `google_id`, `username`, `nick_name`, `email`, `alamat`, `nomor_telepon`, `jenis_kelamin`, `email_verified_at`, `password`, `gambar_profil`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(5, NULL, 'rei', 'rehan wangsaf', 'reisatoru.cosu@gmail.com', 'jl. bumisuki', '08123456789', 'Pria', NULL, '$2y$12$Px5TX7kT1ylqINLJ6iyTWeauooRFoYTF6.Pc./RZ6Zpqrp7EQ7SJi', 'profile_images/5488kDEAVcrEJC4D89e2YRF5RxJNTQI0mc6Oz77C.jpg', NULL, NULL, '2026-01-01 02:29:49', '2026-01-01 05:18:55');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `data_katalog`
--
ALTER TABLE `data_katalog`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_kostum`
--
ALTER TABLE `data_kostum`
  ADD PRIMARY KEY (`id_kostum`);

--
-- Indeks untuk tabel `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `formulir`
--
ALTER TABLE `formulir`
  ADD PRIMARY KEY (`id`);

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
-- Indeks untuk tabel `profile_contacts`
--
ALTER TABLE `profile_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aturan`
--
ALTER TABLE `aturan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `data_katalog`
--
ALTER TABLE `data_katalog`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `data_kostum`
--
ALTER TABLE `data_kostum`
  MODIFY `id_kostum` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `denda`
--
ALTER TABLE `denda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `formulir`
--
ALTER TABLE `formulir`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `profile_contacts`
--
ALTER TABLE `profile_contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
