-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jul 2026 pada 15.31
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_info_kepegawaian`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `id_user`, `tanggal`, `jam_masuk`, `jam_pulang`, `jam_keluar`, `status`) VALUES
(1, 7, '2026-07-07', '05:51:06', NULL, NULL, 'Hadir'),
(2, 7, '2026-07-08', '07:46:07', '07:49:30', NULL, 'Hadir'),
(3, 8, '2026-07-08', '09:28:40', '10:26:07', NULL, 'Hadir');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cuti`
--

CREATE TABLE `cuti` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `cuti`
--

INSERT INTO `cuti` (`id`, `id_user`, `tanggal_mulai`, `tanggal_selesai`, `alasan`, `status`) VALUES
(1, 7, '2026-07-08', '2026-07-09', 'kepentingan mendesak', 'pending'),
(2, 7, '2026-07-09', '2026-07-10', 'mendesak', 'pending'),
(3, 7, '2026-07-08', '2026-07-09', 'meriang', 'disetujui'),
(4, 7, '2026-07-10', '2026-07-11', 'saya ingin liburan', 'pending'),
(5, 10, '2026-07-09', '2026-07-10', 'capek', 'pending'),
(6, 11, '2026-07-09', '2026-07-18', 'yomannnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gaji`
--

CREATE TABLE `gaji` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `gaji_pokok` decimal(10,2) NOT NULL,
  `tunjangan` decimal(10,2) DEFAULT 0.00,
  `potongan` decimal(10,2) DEFAULT 0.00,
  `total_gaji` decimal(10,2) NOT NULL,
  `status_bayar` enum('belum','dibayar') DEFAULT 'belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `sisa_cuti` int(11) DEFAULT 12
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pegawai') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(3, 'dasi', '$2y$10$iLRO6Pnvdli7M8QJWhBJCOs4X.F9g/JHm7YkFSNMoSdVVbja5hm8u', 'pegawai', '2026-07-06 15:14:51'),
(4, 'admin', 'admin123', 'admin', '2026-07-06 16:16:17'),
(6, 'dasga', '$2y$10$jN.elpBJaAshGd13jiD8/.NtrkilRdM7g0A/yCEVr.zbaO/VIf.xG', 'pegawai', '2026-07-06 16:56:18'),
(7, 'fikri', '$2y$10$URmgX6CLqh58UYZparxOmuVvO/pScEFmovxOCZWPidxEih9rNet0S', 'pegawai', '2026-07-06 17:19:29'),
(8, 'kiki', '$2y$10$DIkQHvIp7svpL94KARVgPuauMrQbKVCbgdvIQQWbVxptMdTWtQDaO', 'pegawai', '2026-07-08 07:27:12'),
(9, 'alfikri', '$2y$10$x2f1yZea6DKNfTf211zIOepy3x5.ms8jlw5Bth2ADYpno5bqMixru', 'pegawai', '2026-07-08 12:42:55'),
(10, 'yuyu', '$2y$10$e5LmJZ4uAAvgT3INmKnMpuHbJFVfNN8f0KdgoR9YDjAP1RWOuas46', 'pegawai', '2026-07-08 12:45:46'),
(11, 'oman', '$2y$10$YMq1biYIyu3Yl0Pb.QwN6u.dA4LN/B03i9yVKa5UpTUqzmDS84sP2', 'pegawai', '2026-07-08 13:01:07'),
(12, 'cicu', '$2y$10$n5t2zID9qX.ICggb8NGkv.I3vSKHtyY.JB26MZj2nTWKuVyY4.hiO', 'pegawai', '2026-07-08 13:29:00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `cuti`
--
ALTER TABLE `cuti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `cuti`
--
ALTER TABLE `cuti`
  ADD CONSTRAINT `cuti_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `gaji`
--
ALTER TABLE `gaji`
  ADD CONSTRAINT `gaji_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
