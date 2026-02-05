-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2026 at 12:55 PM
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
-- Database: `db_rfid_muhammadazam`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_guru_muhammadazam`
--

CREATE TABLE `tb_guru_muhammadazam` (
  `id_guru_muhammadAzam` varchar(10) NOT NULL,
  `nama_lengkap_muhammadAzam` varchar(100) DEFAULT NULL,
  `nip_muhammadAzam` varchar(20) DEFAULT NULL,
  `tempat_lahir_muhammadAzam` varchar(50) DEFAULT NULL,
  `tanggal_lahir_muhammadAzam` date DEFAULT NULL,
  `jenis_kelamin_muhammadAzam` varchar(15) DEFAULT NULL,
  `agama_muhammadAzam` varchar(20) DEFAULT NULL,
  `alamat_muhammadAzam` text DEFAULT NULL,
  `no_hp_guru_muhammadAzam` varchar(20) DEFAULT NULL,
  `id_mapel_muhammadAzam` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_guru_muhammadazam`
--

INSERT INTO `tb_guru_muhammadazam` (`id_guru_muhammadAzam`, `nama_lengkap_muhammadAzam`, `nip_muhammadAzam`, `tempat_lahir_muhammadAzam`, `tanggal_lahir_muhammadAzam`, `jenis_kelamin_muhammadAzam`, `agama_muhammadAzam`, `alamat_muhammadAzam`, `no_hp_guru_muhammadAzam`, `id_mapel_muhammadAzam`) VALUES
('GR-001', 'Alvin Nugraha', '1234567890', 'Surabaya', '1985-04-12', 'Laki-laki', 'Islam', 'Jl. Kemenangan 1', '081234567890', 'MPL001'),
('GR-002', 'Yulia Sari', '1234567891', 'Jakarta', '1986-07-21', 'Perempuan', 'Islam', 'Jl. Merdeka 2', '081234567891', 'MPL002'),
('GR-003', 'Dedi Pranata', '1234567892', 'Bandung', '1984-10-30', 'Laki-laki', 'Islam', 'Jl. Pahlawan 3', '081234567892', 'MPL003'),
('GR-004', 'Siska Wijaya', '1234567893', 'Medan', '1987-03-25', 'Perempuan', 'Kristen', 'Jl. Anggrek 4', '081234567893', 'MPL004'),
('GR-005', 'Bima Setiawan', '1234567894', 'Semarang', '1990-05-10', 'Laki-laki', 'Islam', 'Jl. Mawar 5', '081234567894', 'MPL005'),
('GR-006', 'Diana Andriani', '1234567895', 'Malang', '1985-02-14', 'Perempuan', 'Islam', 'Jl. Melati 6', '081234567895', 'MPL006'),
('GR-007', 'Ahmad Fikri', '1234567896', 'Surabaya', '1984-08-22', 'Laki-laki', 'Islam', 'Jl. Cempaka 7', '081234567896', 'MPL007'),
('GR-008', 'Maya Rizki', '1234567897', 'Yogyakarta', '1991-12-04', 'Perempuan', 'Islam', 'Jl. Rawa 8', '081234567897', 'MPL008'),
('GR-009', 'Fajar Prabowo', '1234567898', 'Bandung', '1989-01-15', 'Laki-laki', 'Kristen', 'Jl. Melati 9', '081234567898', 'MPL009'),
('GR-010', 'Tia Rahmawati', '1234567899', 'Surakarta', '1987-06-18', 'Perempuan', 'Islam', 'Jl. Raya 10', '081234567899', 'MPL010'),
('GR-011', 'Rudy Santosa', '1234567900', 'Bekasi', '1985-03-07', 'Laki-laki', 'Islam', 'Jl. Indah 11', '081234568000', 'MPL011'),
('GR-012', 'Rina Yuliana', '1234567901', 'Bogor', '1988-11-22', 'Perempuan', 'Islam', 'Jl. Raya 12', '081234568001', 'MPL001'),
('GR-013', 'Ardiansyah Faiz', '1234567902', 'Surabaya', '1990-09-04', 'Laki-laki', 'Islam', 'Jl. Sejahtera 13', '081234568002', 'MPL002'),
('GR-014', 'Nina Suryani', '1234567903', 'Jakarta', '1986-12-10', 'Perempuan', 'Islam', 'Jl. Merdeka 14', '081234568003', 'MPL003'),
('GR-015', 'Mochammad Arif', '1234567904', 'Bandung', '1984-07-02', 'Laki-laki', 'Islam', 'Jl. Asri 15', '081234568004', 'MPL004');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kehadiran_muhammadazam`
--

CREATE TABLE `tb_kehadiran_muhammadazam` (
  `id_kehadiran_muhammadAzam` varchar(10) NOT NULL,
  `id_murid_muhammadAzam` varchar(10) NOT NULL,
  `kelas_muhammadAzam` varchar(10) DEFAULT NULL,
  `semester_muhammadAzam` varchar(10) DEFAULT NULL,
  `tahun_ajaran_muhammadAzam` varchar(20) DEFAULT NULL,
  `status_muhammadAzam` enum('Hadir','Sakit','Izin','Alpha') DEFAULT NULL,
  `tanggal_muhammadAzam` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kehadiran_muhammadazam`
--

INSERT INTO `tb_kehadiran_muhammadazam` (`id_kehadiran_muhammadAzam`, `id_murid_muhammadAzam`, `kelas_muhammadAzam`, `semester_muhammadAzam`, `tahun_ajaran_muhammadAzam`, `status_muhammadAzam`, `tanggal_muhammadAzam`) VALUES
('HDR-001', 'MRD-001', 'X-A', 'Ganjil', '2024/2025', 'Hadir', '2024-07-01'),
('HDR-002', 'MRD-001', 'X-A', 'Ganjil', '2024/2025', 'Sakit', '2024-07-02'),
('HDR-003', 'MRD-001', 'X-A', 'Ganjil', '2024/2025', 'Izin', '2024-07-03'),
('HDR-004', 'MRD-001', 'X-A', 'Ganjil', '2024/2025', 'Alpha', '2024-07-04'),
('HDR-101', 'MRD-001', 'X-A', 'Genap', '2024/2025', 'Hadir', '2025-01-01'),
('HDR-102', 'MRD-001', 'X-A', 'Genap', '2024/2025', 'Hadir', '2025-01-02'),
('HDR-103', 'MRD-001', 'X-A', 'Genap', '2024/2025', 'Izin', '2025-01-03'),
('HDR-104', 'MRD-001', 'X-A', 'Genap', '2024/2025', 'Sakit', '2025-01-04'),
('HDR-105', 'MRD-001', 'X-A', 'Genap', '2024/2025', 'Alpha', '2025-01-05');

-- --------------------------------------------------------

--
-- Table structure for table `tb_mapel_muhammadazam`
--

CREATE TABLE `tb_mapel_muhammadazam` (
  `id_mapel_muhammadAzam` varchar(10) NOT NULL,
  `nama_mapel_muhammadAzam` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_mapel_muhammadazam`
--

INSERT INTO `tb_mapel_muhammadazam` (`id_mapel_muhammadAzam`, `nama_mapel_muhammadAzam`) VALUES
('MPL001', 'Matematika'),
('MPL002', 'Bahasa Indonesia'),
('MPL003', 'Bahasa Inggris'),
('MPL004', 'IPA'),
('MPL005', 'IPS'),
('MPL006', 'Bahasa Sunda'),
('MPL007', 'Sejarah'),
('MPL008', 'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)'),
('MPL009', 'Pendidikan Pancasila dan Kewarganegaraan (PPKN)'),
('MPL010', 'Informatika'),
('MPL011', 'Basis Data');

-- --------------------------------------------------------

--
-- Table structure for table `tb_murid_muhammadazam`
--

CREATE TABLE `tb_murid_muhammadazam` (
  `id_murid_muhammadAzam` varchar(10) NOT NULL,
  `rfid_muhammadAzam` varchar(50) NOT NULL,
  `nama_lengkap_muhammadAzam` varchar(100) DEFAULT NULL,
  `nis_muhammadAzam` varchar(20) DEFAULT NULL,
  `nisn_muhammadAzam` varchar(20) DEFAULT NULL,
  `tempat_lahir_muhammadAzam` varchar(50) DEFAULT NULL,
  `tanggal_lahir_muhammadAzam` date DEFAULT NULL,
  `jenis_kelamin_muhammadAzam` varchar(15) DEFAULT NULL,
  `agama_muhammadAzam` varchar(20) DEFAULT NULL,
  `alamat_muhammadAzam` text DEFAULT NULL,
  `nama_ayah_muhammadAzam` varchar(100) DEFAULT NULL,
  `nama_ibu_muhammadAzam` varchar(100) DEFAULT NULL,
  `pekerjaan_ayah_muhammadAzam` varchar(50) DEFAULT NULL,
  `pekerjaan_ibu_muhammadAzam` varchar(50) DEFAULT NULL,
  `no_hp_ortu_muhammadAzam` varchar(20) DEFAULT NULL,
  `kelas_muhammadAzam` varchar(10) DEFAULT NULL,
  `tahun_ajaran_muhammadAzam` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_murid_muhammadazam`
--

INSERT INTO `tb_murid_muhammadazam` (`id_murid_muhammadAzam`, `rfid_muhammadAzam`, `nama_lengkap_muhammadAzam`, `nis_muhammadAzam`, `nisn_muhammadAzam`, `tempat_lahir_muhammadAzam`, `tanggal_lahir_muhammadAzam`, `jenis_kelamin_muhammadAzam`, `agama_muhammadAzam`, `alamat_muhammadAzam`, `nama_ayah_muhammadAzam`, `nama_ibu_muhammadAzam`, `pekerjaan_ayah_muhammadAzam`, `pekerjaan_ibu_muhammadAzam`, `no_hp_ortu_muhammadAzam`, `kelas_muhammadAzam`, `tahun_ajaran_muhammadAzam`) VALUES
('MRD-001', 'RFIDMURID001', 'Ahmad Fadli', '10243001', '99887766', 'Bandung', '2007-03-12', 'Laki-laki', 'Islam', 'Jl. Melati 1', 'Hendra', 'Siti Aminah', 'Wiraswasta', 'Ibu Rumah Tangga', '081234567890', 'X-A', '2024/2025'),
('MRD-002', 'RFIDMURID002', 'Rizky Ramadhan', '10243002', '99887767', 'Garut', '2007-06-21', 'Laki-laki', 'Islam', 'Jl. Mawar 2', 'Budi', 'Rina', 'Karyawan', 'Ibu Rumah Tangga', '081234567891', 'X-A', '2024/2025'),
('MRD-003', 'RFIDMURID003', 'Siti Nurhaliza', '10243003', '99887768', 'Tasikmalaya', '2007-01-10', 'Perempuan', 'Islam', 'Jl. Kenanga 3', 'Agus', 'Yuni', 'Petani', 'Ibu Rumah Tangga', '081234567892', 'X-B', '2024/2025'),
('MRD-004', 'RFIDMURID004', 'Budi Santoso', '10243004', '99887769', 'Jakarta', '2007-07-25', 'Laki-laki', 'Islam', 'Jl. Anggrek 4', 'Santoso', 'Siti', 'Pegawai Negeri', 'Ibu Rumah Tangga', '081234567893', 'XI-A', '2024/2025'),
('MRD-005', 'RFIDMURID005', 'Lia Pramudita', '10243005', '99887770', 'Bogor', '2007-10-14', 'Perempuan', 'Kristen', 'Jl. Mawar 5', 'Eko', 'Dewi', 'Wiraswasta', 'Ibu Rumah Tangga', '081234567894', 'XI-B', '2024/2025'),
('MRD-006', 'RFIDMURID006', 'Rina Kurniawati', '10243006', '99887771', 'Semarang', '2007-04-06', 'Perempuan', 'Islam', 'Jl. Pahlawan 6', 'Rudi', 'Sari', 'Pedagang', 'Ibu Rumah Tangga', '081234567895', 'XII-A', '2024/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tb_nilai_muhammadazam`
--

CREATE TABLE `tb_nilai_muhammadazam` (
  `id_nilai_muhammadAzam` varchar(10) NOT NULL,
  `id_murid_muhammadAzam` varchar(10) NOT NULL,
  `id_mapel_muhammadAzam` varchar(10) NOT NULL,
  `kelas_muhammadAzam` varchar(10) DEFAULT NULL,
  `semester_muhammadAzam` varchar(10) DEFAULT NULL,
  `tahun_ajaran_muhammadAzam` varchar(20) DEFAULT NULL,
  `nilai_angka_muhammadAzam` int(11) DEFAULT NULL,
  `nilai_huruf_muhammadAzam` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_nilai_muhammadazam`
--

INSERT INTO `tb_nilai_muhammadazam` (`id_nilai_muhammadAzam`, `id_murid_muhammadAzam`, `id_mapel_muhammadAzam`, `kelas_muhammadAzam`, `semester_muhammadAzam`, `tahun_ajaran_muhammadAzam`, `nilai_angka_muhammadAzam`, `nilai_huruf_muhammadAzam`) VALUES
('NL-045', 'MRD-004', 'MPL001', 'XI-A', 'Ganjil', '2024/2025', 84, 'B'),
('NL-046', 'MRD-004', 'MPL002', 'XI-A', 'Ganjil', '2024/2025', 89, 'B'),
('NL-047', 'MRD-004', 'MPL003', 'XI-A', 'Ganjil', '2024/2025', 91, 'A'),
('NL-048', 'MRD-004', 'MPL004', 'XI-A', 'Ganjil', '2024/2025', 93, 'A'),
('NL-049', 'MRD-004', 'MPL005', 'XI-A', 'Ganjil', '2024/2025', 80, 'B'),
('NL-050', 'MRD-004', 'MPL006', 'XI-A', 'Ganjil', '2024/2025', 88, 'B'),
('NL-051', 'MRD-004', 'MPL007', 'XI-A', 'Ganjil', '2024/2025', 84, 'B'),
('NL-052', 'MRD-004', 'MPL008', 'XI-A', 'Ganjil', '2024/2025', 90, 'A'),
('NL-053', 'MRD-004', 'MPL009', 'XI-A', 'Ganjil', '2024/2025', 79, 'C'),
('NL-054', 'MRD-004', 'MPL010', 'XI-A', 'Ganjil', '2024/2025', 86, 'B'),
('NL-055', 'MRD-004', 'MPL011', 'XI-A', 'Ganjil', '2024/2025', 82, 'B'),
('NL-056', 'MRD-004', 'MPL001', 'XI-A', 'Genap', '2024/2025', 85, 'B'),
('NL-057', 'MRD-004', 'MPL002', 'XI-A', 'Genap', '2024/2025', 90, 'A'),
('NL-058', 'MRD-004', 'MPL003', 'XI-A', 'Genap', '2024/2025', 88, 'B'),
('NL-059', 'MRD-004', 'MPL004', 'XI-A', 'Genap', '2024/2025', 91, 'A'),
('NL-060', 'MRD-004', 'MPL005', 'XI-A', 'Genap', '2024/2025', 83, 'B'),
('NL-061', 'MRD-004', 'MPL006', 'XI-A', 'Genap', '2024/2025', 92, 'A'),
('NL-062', 'MRD-004', 'MPL007', 'XI-A', 'Genap', '2024/2025', 86, 'B'),
('NL-063', 'MRD-004', 'MPL008', 'XI-A', 'Genap', '2024/2025', 89, 'B'),
('NL-064', 'MRD-004', 'MPL009', 'XI-A', 'Genap', '2024/2025', 84, 'B'),
('NL-065', 'MRD-004', 'MPL010', 'XI-A', 'Genap', '2024/2025', 87, 'B'),
('NL-066', 'MRD-004', 'MPL011', 'XI-A', 'Genap', '2024/2025', 81, 'B'),
('NL-067', 'MRD-005', 'MPL001', 'XI-B', 'Ganjil', '2024/2025', 80, 'B'),
('NL-068', 'MRD-005', 'MPL002', 'XI-B', 'Ganjil', '2024/2025', 85, 'B'),
('NL-069', 'MRD-005', 'MPL003', 'XI-B', 'Ganjil', '2024/2025', 87, 'B'),
('NL-070', 'MRD-005', 'MPL004', 'XI-B', 'Ganjil', '2024/2025', 92, 'A'),
('NL-071', 'MRD-005', 'MPL005', 'XI-B', 'Ganjil', '2024/2025', 88, 'B'),
('NL-072', 'MRD-005', 'MPL006', 'XI-B', 'Ganjil', '2024/2025', 90, 'A'),
('NL-073', 'MRD-005', 'MPL007', 'XI-B', 'Ganjil', '2024/2025', 81, 'B'),
('NL-074', 'MRD-005', 'MPL008', 'XI-B', 'Ganjil', '2024/2025', 89, 'B'),
('NL-075', 'MRD-005', 'MPL009', 'XI-B', 'Ganjil', '2024/2025', 85, 'B'),
('NL-076', 'MRD-005', 'MPL010', 'XI-B', 'Ganjil', '2024/2025', 92, 'A'),
('NL-077', 'MRD-005', 'MPL011', 'XI-B', 'Ganjil', '2024/2025', 88, 'B'),
('NL-078', 'MRD-005', 'MPL001', 'XI-B', 'Genap', '2024/2025', 84, 'B'),
('NL-079', 'MRD-005', 'MPL002', 'XI-B', 'Genap', '2024/2025', 80, 'B'),
('NL-080', 'MRD-005', 'MPL003', 'XI-B', 'Genap', '2024/2025', 90, 'A'),
('NL-081', 'MRD-005', 'MPL004', 'XI-B', 'Genap', '2024/2025', 93, 'A'),
('NL-082', 'MRD-005', 'MPL005', 'XI-B', 'Genap', '2024/2025', 85, 'B'),
('NL-083', 'MRD-005', 'MPL006', 'XI-B', 'Genap', '2024/2025', 92, 'A'),
('NL-084', 'MRD-005', 'MPL007', 'XI-B', 'Genap', '2024/2025', 86, 'B'),
('NL-085', 'MRD-005', 'MPL008', 'XI-B', 'Genap', '2024/2025', 80, 'B'),
('NL-086', 'MRD-005', 'MPL009', 'XI-B', 'Genap', '2024/2025', 83, 'B'),
('NL-087', 'MRD-005', 'MPL010', 'XI-B', 'Genap', '2024/2025', 87, 'B'),
('NL-088', 'MRD-005', 'MPL011', 'XI-B', 'Genap', '2024/2025', 85, 'B'),
('NL-089', 'MRD-006', 'MPL001', 'XII-A', 'Ganjil', '2024/2025', 91, 'A'),
('NL-090', 'MRD-006', 'MPL002', 'XII-A', 'Ganjil', '2024/2025', 88, 'B'),
('NL-091', 'MRD-006', 'MPL003', 'XII-A', 'Ganjil', '2024/2025', 85, 'B'),
('NL-092', 'MRD-006', 'MPL004', 'XII-A', 'Ganjil', '2024/2025', 93, 'A'),
('NL-093', 'MRD-006', 'MPL005', 'XII-A', 'Ganjil', '2024/2025', 87, 'B'),
('NL-094', 'MRD-006', 'MPL006', 'XII-A', 'Ganjil', '2024/2025', 90, 'A'),
('NL-095', 'MRD-006', 'MPL007', 'XII-A', 'Ganjil', '2024/2025', 82, 'B'),
('NL-096', 'MRD-006', 'MPL008', 'XII-A', 'Ganjil', '2024/2025', 80, 'B'),
('NL-097', 'MRD-006', 'MPL009', 'XII-A', 'Ganjil', '2024/2025', 88, 'B'),
('NL-098', 'MRD-006', 'MPL010', 'XII-A', 'Ganjil', '2024/2025', 85, 'B'),
('NL-099', 'MRD-006', 'MPL011', 'XII-A', 'Ganjil', '2024/2025', 90, 'A'),
('NL-100', 'MRD-006', 'MPL001', 'XII-A', 'Genap', '2024/2025', 93, 'A'),
('NL-101', 'MRD-006', 'MPL002', 'XII-A', 'Genap', '2024/2025', 89, 'B'),
('NL-102', 'MRD-006', 'MPL003', 'XII-A', 'Genap', '2024/2025', 84, 'B'),
('NL-103', 'MRD-006', 'MPL004', 'XII-A', 'Genap', '2024/2025', 92, 'A'),
('NL-104', 'MRD-006', 'MPL005', 'XII-A', 'Genap', '2024/2025', 88, 'B'),
('NL-105', 'MRD-006', 'MPL006', 'XII-A', 'Genap', '2024/2025', 91, 'A'),
('NL-106', 'MRD-006', 'MPL007', 'XII-A', 'Genap', '2024/2025', 86, 'B'),
('NL-107', 'MRD-006', 'MPL008', 'XII-A', 'Genap', '2024/2025', 83, 'B'),
('NL-108', 'MRD-006', 'MPL009', 'XII-A', 'Genap', '2024/2025', 90, 'A'),
('NL-109', 'MRD-006', 'MPL010', 'XII-A', 'Genap', '2024/2025', 85, 'B'),
('NL-110', 'MRD-006', 'MPL011', 'XII-A', 'Genap', '2024/2025', 92, 'A');

--
-- Triggers `tb_nilai_muhammadazam`
--
DELIMITER $$
CREATE TRIGGER `trg_nilai_huruf_insert` BEFORE INSERT ON `tb_nilai_muhammadazam` FOR EACH ROW BEGIN
    IF NEW.nilai_angka_muhammadAzam >= 90 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'A';
    ELSEIF NEW.nilai_angka_muhammadAzam >= 80 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'B';
    ELSEIF NEW.nilai_angka_muhammadAzam >= 70 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'C';
    ELSEIF NEW.nilai_angka_muhammadAzam >= 60 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'D';
    ELSE
        SET NEW.nilai_huruf_muhammadAzam = 'E';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_nilai_huruf_update` BEFORE UPDATE ON `tb_nilai_muhammadazam` FOR EACH ROW BEGIN
    IF NEW.nilai_angka_muhammadAzam >= 90 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'A';
    ELSEIF NEW.nilai_angka_muhammadAzam >= 80 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'B';
    ELSEIF NEW.nilai_angka_muhammadAzam >= 70 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'C';
    ELSEIF NEW.nilai_angka_muhammadAzam >= 60 THEN
        SET NEW.nilai_huruf_muhammadAzam = 'D';
    ELSE
        SET NEW.nilai_huruf_muhammadAzam = 'E';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_muhammadazam`
--

CREATE TABLE `tb_user_muhammadazam` (
  `id_user_muhammadAzam` varchar(10) NOT NULL,
  `rfid_muhammadAzam` varchar(50) NOT NULL,
  `role_muhammadAzam` enum('admin','murid') NOT NULL,
  `created_at_muhammadAzam` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user_muhammadazam`
--

INSERT INTO `tb_user_muhammadazam` (`id_user_muhammadAzam`, `rfid_muhammadAzam`, `role_muhammadAzam`, `created_at_muhammadAzam`) VALUES
('USR-001', 'RFIDADMIN001', 'admin', '2026-02-05 12:00:24'),
('USR-002', 'RFIDMURID001', 'murid', '2026-02-05 12:00:24'),
('USR-003', 'RFIDMURID002', 'murid', '2026-02-05 12:00:24'),
('USR-004', 'RFIDMURID003', 'murid', '2026-02-05 12:00:24'),
('USR-005', 'RFIDMURID004', 'murid', '2026-02-05 18:51:36'),
('USR-006', 'RFIDMURID005', 'murid', '2026-02-05 18:51:36'),
('USR-007', 'RFIDMURID006', 'murid', '2026-02-05 18:51:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_guru_muhammadazam`
--
ALTER TABLE `tb_guru_muhammadazam`
  ADD PRIMARY KEY (`id_guru_muhammadAzam`),
  ADD KEY `fk_mapel` (`id_mapel_muhammadAzam`);

--
-- Indexes for table `tb_kehadiran_muhammadazam`
--
ALTER TABLE `tb_kehadiran_muhammadazam`
  ADD PRIMARY KEY (`id_kehadiran_muhammadAzam`),
  ADD KEY `id_murid_muhammadAzam` (`id_murid_muhammadAzam`);

--
-- Indexes for table `tb_mapel_muhammadazam`
--
ALTER TABLE `tb_mapel_muhammadazam`
  ADD PRIMARY KEY (`id_mapel_muhammadAzam`);

--
-- Indexes for table `tb_murid_muhammadazam`
--
ALTER TABLE `tb_murid_muhammadazam`
  ADD PRIMARY KEY (`id_murid_muhammadAzam`),
  ADD UNIQUE KEY `rfid_muhammadAzam` (`rfid_muhammadAzam`);

--
-- Indexes for table `tb_nilai_muhammadazam`
--
ALTER TABLE `tb_nilai_muhammadazam`
  ADD PRIMARY KEY (`id_nilai_muhammadAzam`),
  ADD KEY `id_murid_muhammadAzam` (`id_murid_muhammadAzam`),
  ADD KEY `id_mapel_muhammadAzam` (`id_mapel_muhammadAzam`);

--
-- Indexes for table `tb_user_muhammadazam`
--
ALTER TABLE `tb_user_muhammadazam`
  ADD PRIMARY KEY (`id_user_muhammadAzam`),
  ADD UNIQUE KEY `rfid_muhammadAzam` (`rfid_muhammadAzam`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_guru_muhammadazam`
--
ALTER TABLE `tb_guru_muhammadazam`
  ADD CONSTRAINT `fk_mapel` FOREIGN KEY (`id_mapel_muhammadAzam`) REFERENCES `tb_mapel_muhammadazam` (`id_mapel_muhammadAzam`);

--
-- Constraints for table `tb_kehadiran_muhammadazam`
--
ALTER TABLE `tb_kehadiran_muhammadazam`
  ADD CONSTRAINT `fk_absen_murid` FOREIGN KEY (`id_murid_muhammadAzam`) REFERENCES `tb_murid_muhammadazam` (`id_murid_muhammadAzam`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_nilai_muhammadazam`
--
ALTER TABLE `tb_nilai_muhammadazam`
  ADD CONSTRAINT `fk_nilai_mapel` FOREIGN KEY (`id_mapel_muhammadAzam`) REFERENCES `tb_mapel_muhammadazam` (`id_mapel_muhammadAzam`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nilai_murid` FOREIGN KEY (`id_murid_muhammadAzam`) REFERENCES `tb_murid_muhammadazam` (`id_murid_muhammadAzam`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
