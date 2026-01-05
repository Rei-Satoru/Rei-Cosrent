-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 05 Jan 2026 pada 11.16
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
-- Struktur dari tabel `profile_contacts`
--

CREATE TABLE `profile_contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(1, 'Reithesley', '$2y$12$0RTAQ2qIXmzn1EHvKtceF.VLbn2YnCLJeTi9wcQhxSHF1aN91Nfi2', 'Pemilik', 'profile_photos/remWB4mrRws3X85hybYyPXwdV0fOLbVInzqJOWgG.webp', 'Pemilik Rei Cosrent', 'Jl. Rumah', '08123456789', 'admin@gmail.com', '08123456789', '08123456789 - Bank', 'payment_qris/VBhl06DmT0SzWEYR5puOxPPlv34XU5BOmnrdGDd2.jpg');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `profile_contacts`
--
ALTER TABLE `profile_contacts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `profile_contacts`
--
ALTER TABLE `profile_contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
