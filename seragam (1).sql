-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 30, 2025 at 04:39 AM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seragam`
--

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

DROP TABLE IF EXISTS `jurusan`;
CREATE TABLE IF NOT EXISTS `jurusan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_jurusan` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_jurusan` (`nama_jurusan`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `nama_jurusan`) VALUES
(1, 'AKUNTANSI DAN KEUANGAN LEMBAGA'),
(2, 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS'),
(3, 'PEMASARAN'),
(4, 'DESAIN KOMUNIKASI VISUAL'),
(5, 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI'),
(6, 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM'),
(7, 'TEKNOLOGI FARMASI');

-- --------------------------------------------------------

--
-- Table structure for table `seragam`
--

DROP TABLE IF EXISTS `seragam`;
CREATE TABLE IF NOT EXISTS `seragam` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `harga_tambahan` decimal(10,0) DEFAULT NULL,
  `berhijab` tinyint(1) NOT NULL DEFAULT '0',
  `harga_berhijab` decimal(10,0) DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `seragam`
--

INSERT INTO `seragam` (`id`, `nama`, `harga`, `harga_tambahan`, `berhijab`, `harga_berhijab`, `sort_order`) VALUES
(1, 'Bahan OSIS atas ( Putih )', 122000, 8200, 1, 142000, 0),
(2, 'Bahan OSIS bawah ( Abu-abu )', 119000, 9900, 0, 0, 1),
(3, 'Bahan Pramuka atas (coklat muda)', 146000, 9700, 1, 168000, 2),
(4, 'Bahan Jas atas ( merah )', 168000, 9600, 0, 0, 4),
(5, 'Bahan Batik', 146000, 9700, 1, 168000, 5),
(6, 'Bahan celana jas bawah ( hitam )', 119000, 9600, 0, 0, 6),
(7, 'Bahan Executive jurusan', 314000, 10500, 0, 0, 7),
(8, 'Bahan Pramuka Bawah (coklat tua)', 124000, 9900, 0, 0, 3),
(9, 'Kaos Olah Raga', 132000, 0, 0, 0, 8),
(10, 'Kerudung', 234000, 78000, 0, 0, 10),
(11, 'Kaos Kaki', 28000, 0, 0, 0, 11),
(12, 'Pakaian Lab. ( Farmasi )', 148000, 0, 0, 0, 12),
(15, 'Atribut', 122000, 0, 0, 0, 9);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
CREATE TABLE IF NOT EXISTS `siswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(30) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `wali` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_pendaftaran` (`no_pendaftaran`),
  UNIQUE KEY `nis` (`nis`)
) ENGINE=MyISAM AUTO_INCREMENT=432 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `no_pendaftaran`, `nis`, `nama`, `jenis_kelamin`, `jurusan`, `wali`, `no_hp`, `created_at`, `updated_at`) VALUES
(411, '20240086', '1319', 'Kartika Utami', 'P', 'AKUNTANSI DAN KEUANGAN LEMBAGA', 'Utamii', '08676031404', '2025-04-17 00:43:53', '2025-04-30 04:57:10'),
(409, '20240084', '1317', 'Desi Wijaya', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Wijaya', '08140833390', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(410, '20240085', '1318', 'Wulan Saputra', 'P', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Saputra', '08981723989', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(408, '20240083', '1316', 'Aulia Putri', 'P', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Putri', '08348429010', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(406, '20240081', '1314', 'Desi Lestari', 'P', 'AKUNTANSI KEUANGAN DAN LEMBAGA', 'Lestari', '08225665213', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(407, '20240082', '1315', 'Eko Maulana', 'L', 'PEMASARAN', 'Maulana', '08782514470', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(405, '20240080', '1313', 'Satria Pratama', 'L', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Pratama', '08382164968', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(403, '20240078', '1311', 'Citra Putri', 'P', 'TEKNOLOGI FARMASI', 'Putri', '08583316045', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(404, '20240079', '1312', 'Siti Maulana', 'P', 'TEKNOLOGI FARMASI', 'Maulana', '08807840954', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(402, '20240077', '1310', 'Lestari Handayani', 'P', 'PEMASARAN', 'Handayani', '08784907854', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(400, '20240075', '1308', 'Lestari Utami', 'P', 'AKUNTANSI KEUANGAN DAN LEMBAGA', 'Utami', '08597034371', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(401, '20240076', '1309', 'Satria Lestari', 'L', 'AKUNTANSI KEUANGAN DAN LEMBAGA', 'Lestari', '08894328972', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(399, '20240074', '1307', 'Siti Aminah', 'P', 'PEMASARAN', 'Aminah', '08823105631', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(398, '20240073', '1306', 'Dina Pratama', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Pratama', '08801792111', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(396, '20240071', '1304', 'Budi Maulana', 'L', 'AKUNTANSI KEUANGAN DAN LEMBAGA', 'Maulana', '08566482672', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(397, '20240072', '1305', 'Fajar Saputra', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Saputra', '08582722712', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(394, '20240069', '1302', 'Dedi Wijaya', 'L', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Wijaya', '08347336763', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(395, '20240070', '1303', 'Agus Aminah', 'L', 'PEMASARAN', 'Aminah', '08732354331', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(393, '20240068', '1301', 'Nadia Utami', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Utami', '08184397186', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(390, '20240065', '1298', 'Dina Putri', 'P', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Putri', '08212370120', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(392, '20240067', '1300', 'Ilham Santoso', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Santoso', '08259715597', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(391, '20240066', '1299', 'Budi Pratama', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Pratama', '08269007104', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(389, '20240064', '1297', 'Kartika Handayani', 'P', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Handayani', '08308815313', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(387, '20240062', '1295', 'Bayu Santoso', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Santoso', '08267747700', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(388, '20240063', '1296', 'Rizky Saputra', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Saputra', '08501004165', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(386, '20240061', '1294', 'Nadia Maulana', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Maulana', '08796338228', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(385, '20240060', '1293', 'Dedi Wijaya', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Wijaya', '08786898928', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(384, '20240059', '1292', 'Fajar Maulana', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Maulana', '08303056821', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(381, '20240056', '1289', 'Aulia Pratama', 'P', 'PEMASARAN', 'Pratama', '08820689270', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(382, '20240057', '1290', 'Dina Wijaya', 'P', 'DESAIN KOMUNIKASI VISUAL', 'Wijaya', '08524970837', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(383, '20240058', '1291', 'Eko Utami', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Utami', '08776289909', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(380, '20240055', '1288', 'Ahmad Putri', 'L', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Putri', '08976004864', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(379, '20240054', '1287', 'Aulia Aminah', 'P', 'PEMASARAN', 'Aminah', '08911190375', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(378, '20240053', '1286', 'Dedi Putri', 'L', 'TEKNOLOGI FARMASI', 'Putri', '08252912002', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(376, '20240051', '1284', 'Bayu Maulana', 'L', 'TEKNOLOGI FARMASI', 'Maulana', '08612036100', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(377, '20240052', '1285', 'Ahmad Utami', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Utami', '08323315376', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(375, '20240050', '1283', 'Rizky Saputra', 'L', 'TEKNOLOGI FARMASI', 'Saputra', '08141572224', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(374, '20240049', '1282', 'Dina Wijaya', 'P', 'TEKNOLOGI FARMASI', 'Wijaya', '08463806896', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(373, '20240048', '1281', 'Budi Lestari', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Lestari', '08682510375', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(372, '20240047', '1280', 'Lestari Putri', 'P', 'PEMASARAN', 'Putri', '08533852157', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(369, '20240044', '1277', 'Nadia Aminah', 'P', 'TEKNOLOGI FARMASI', 'Aminah', '08558701984', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(371, '20240046', '1279', 'Bayu Santoso', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Santoso', '08488249532', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(370, '20240045', '1278', 'Lestari Maulana', 'P', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Maulana', '08839082117', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(368, '20240043', '1276', 'Desi Wijaya', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Wijaya', '08224433618', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(367, '20240042', '1275', 'Desi Saputra', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Saputra', '08981524790', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(366, '20240041', '1274', 'Dina Utami', 'P', 'AKUNTANSI KEUANGAN DAN LEMBAGA', 'Utami', '08193549399', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(364, '20240039', '1272', 'Dedi Putri', 'L', 'AKUNTANSI KEUANGAN DAN LEMBAGA', 'Putri', '08525696389', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(365, '20240040', '1273', 'Eko Santoso', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Santoso', '08380180216', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(363, '20240038', '1271', 'Satria Aminah', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Aminah', '08880211797', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(362, '20240037', '1270', 'Nadia Aminah', 'P', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Aminah', '08867550306', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(359, '20240034', '1267', 'Desi Wijaya', 'P', 'DESAIN KOMUNIKASI VISUAL', 'Wijaya', '08467011808', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(360, '20240035', '1268', 'Wulan Wijaya', 'P', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Wijaya', '08629718720', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(361, '20240036', '1269', 'Ahmad Maulana', 'L', 'PEMASARAN', 'Maulana', '08465563319', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(358, '20240033', '1266', 'Aulia Saputra', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Saputra', '08117641499', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(356, '20240031', '1264', 'Bayu Utami', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Utami', '08425916954', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(357, '20240032', '1265', 'Eko Saputra', 'L', 'PEMASARAN', 'Saputra', '08489260150', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(355, '20240030', '1263', 'Bayu Utami', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Utami', '08466005424', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(354, '20240029', '1262', 'Rizky Saputra', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Saputra', '08576515396', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(353, '20240028', '1261', 'Ilham Lestari', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Lestari', '08927262988', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(352, '20240027', '1260', 'Ahmad Handayani', 'L', 'TEKNOLOGI FARMASI', 'Handayani', '08934824822', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(350, '20240025', '1258', 'Satria Maulana', 'L', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Maulana', '08697265556', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(351, '20240026', '1259', 'Satria Utami', 'L', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Utami', '08841404884', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(349, '20240024', '1257', 'Kartika Aminah', 'P', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Aminah', '08522600862', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(347, '20240022', '1255', 'Rizky Maulana', 'L', 'PEMASARAN', 'Maulana', '08880943267', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(348, '20240023', '1256', 'Agus Wijaya', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Wijaya', '08828066305', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(345, '20240020', '1253', 'Ahmad Putri', 'L', 'TEKNOLOGI FARMASI', 'Putri', '08337299268', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(346, '20240021', '1254', 'Siti Lestari', 'P', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Lestari', '08655648479', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(341, '20240016', '1249', 'Wulan Maulana', 'P', 'DESAIN KOMUNIKASI VISUAL', 'Maulana', '08320178410', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(342, '20240017', '1250', 'Dina Lestari', 'P', 'PEMASARAN', 'Lestari', '08899761496', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(343, '20240018', '1251', 'Ahmad Saputra', 'L', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Saputra', '08508359467', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(344, '20240019', '1252', 'Kartika Utami', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Utami', '08221267995', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(340, '20240015', '1248', 'Rizky Wijaya', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Wijaya', '08409559568', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(339, '20240014', '1247', 'Rizky Aminah', 'L', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Aminah', '08659186008', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(338, '20240013', '1246', 'Bayu Santoso', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Santoso', '08589441445', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(337, '20240012', '1245', 'Lestari Handayani', 'P', 'PEMASARAN', 'Handayani', '08145670625', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(335, '20240010', '1243', 'Satria Utami', 'L', 'PEMASARAN', 'Utami', '08615650260', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(336, '20240011', '1244', 'Ahmad Putri', 'L', 'TEKNOLOGI FARMASI', 'Putri', '08833057303', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(334, '20240009', '1242', 'Budi Pratama', 'L', 'PENGEMBANGAN PERANGKAT LUNAK DAN GIM', 'Pratama', '08156118451', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(332, '20240007', '1240', 'Bayu Saputra', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Saputra', '08259225059', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(333, '20240008', '1241', 'Desi Pratama', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Pratama', '08473206131', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(331, '20240006', '1239', 'Siti Lestari', 'P', 'AKUNTANSI KEUANGAN DAN LEMBAGA', 'Lestari', '08424236090', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(329, '20240004', '1237', 'Siti Handayani', 'P', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Handayani', '08575636380', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(330, '20240005', '1238', 'Satria Lestari', 'L', 'PEMASARAN', 'Lestari', '08113720537', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(328, '20240003', '1236', 'Bayu Santoso', 'L', 'DESAIN KOMUNIKASI VISUAL', 'Santoso', '08736090940', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(327, '20240002', '1235', 'Agus Wijaya', 'L', 'MANAJEMEN PERKANTORAN DAN LAYANAN BISNIS', 'Wijaya', '08931987987', '2025-04-17 00:43:53', '2025-04-17 00:43:53'),
(326, '20240001', '1234', 'Aulia Santoso', 'P', 'TEKNIK JARINGAN KOMPUTER DAN TELEKOMUNIKASI', 'Santoso', '08376455295', '2025-04-17 00:43:53', '2025-04-17 00:43:53');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(30) NOT NULL,
  `tanggal_transaksi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kasir_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `metode_pembayaran` enum('cash','qris') NOT NULL,
  `total_harga` decimal(10,0) NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` enum('lunas','batal','diambil') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'lunas',
  `diambil` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  KEY `kasir_id` (`kasir_id`),
  KEY `siswa_id` (`siswa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `kode_transaksi`, `tanggal_transaksi`, `kasir_id`, `siswa_id`, `metode_pembayaran`, `total_harga`, `bukti_pembayaran`, `status`, `diambil`, `created_at`, `updated_at`) VALUES
(1, 'TRX-1750924012', '2025-06-26 07:46:52', 1, 409, 'cash', 557000, NULL, 'batal', NULL, '2025-06-26 07:46:52', '2025-06-26 13:27:03'),
(2, 'TRX-1750942851', '2025-06-26 13:00:51', 20, 399, 'cash', 1864000, NULL, 'batal', NULL, '2025-06-26 13:00:51', '2025-06-29 06:58:39'),
(3, 'TRX-1750944052', '2025-06-26 13:20:52', 1, 367, 'cash', 1864000, NULL, 'lunas', NULL, '2025-06-26 13:20:52', '2025-06-28 04:21:17'),
(4, 'TRX-1751163390', '2025-06-29 02:16:30', 27, 348, 'cash', 1922000, NULL, 'batal', NULL, '2025-06-29 02:16:30', '2025-06-29 07:05:24'),
(5, 'TRX-1751170283', '2025-06-29 04:11:23', 20, 398, 'cash', 1986000, NULL, 'diambil', NULL, '2025-06-29 04:11:23', '2025-06-29 07:46:52'),
(6, 'TRX-1751174221', '2025-06-29 05:17:01', 20, 411, 'cash', 1986000, NULL, 'batal', NULL, '2025-06-29 05:17:01', '2025-06-29 07:05:20'),
(7, 'TRX-1751244264', '2025-06-30 00:44:24', 1, 398, 'cash', 132000, NULL, 'lunas', NULL, '2025-06-30 00:44:24', '2025-06-30 00:44:24'),
(8, 'TRX-1751244358', '2025-06-30 00:45:58', 1, 364, 'cash', 122000, NULL, 'lunas', NULL, '2025-06-30 00:45:58', '2025-06-30 00:45:58'),
(9, 'TRX-1751244418', '2025-06-30 00:46:58', 20, 405, 'cash', 146000, NULL, 'lunas', NULL, '2025-06-30 00:46:58', '2025-06-30 00:46:58'),
(10, 'TRX-1751244450', '2025-06-30 00:47:30', 29, 397, 'cash', 270000, NULL, 'lunas', NULL, '2025-06-30 00:47:30', '2025-06-30 00:47:30');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

DROP TABLE IF EXISTS `transaksi_detail`;
CREATE TABLE IF NOT EXISTS `transaksi_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaksi_id` int NOT NULL,
  `seragam_id` int NOT NULL,
  `ukuran` varchar(10) NOT NULL,
  `berhijab` tinyint(1) NOT NULL DEFAULT '0',
  `harga` decimal(10,0) NOT NULL,
  `status_ambil` enum('belum','diambil') NOT NULL DEFAULT 'belum',
  PRIMARY KEY (`id`),
  KEY `transaksi_id` (`transaksi_id`),
  KEY `seragam_id` (`seragam_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id`, `transaksi_id`, `seragam_id`, `ukuran`, `berhijab`, `harga`, `status_ambil`) VALUES
(1, 1, 8, 'standar', 0, 124000, 'belum'),
(2, 1, 7, 'standar', 0, 314000, 'belum'),
(3, 1, 2, 'standar', 0, 119000, 'belum'),
(4, 2, 1, 'standar', 1, 142000, 'diambil'),
(5, 2, 2, 'standar', 0, 119000, 'diambil'),
(6, 2, 3, 'standar', 1, 168000, 'diambil'),
(7, 2, 4, 'standar', 0, 168000, 'diambil'),
(8, 2, 5, 'standar', 1, 168000, 'diambil'),
(9, 2, 6, 'standar', 0, 119000, 'diambil'),
(10, 2, 7, 'standar', 0, 314000, 'diambil'),
(11, 2, 8, 'standar', 0, 124000, 'diambil'),
(12, 2, 9, 'standar', 0, 132000, 'diambil'),
(13, 2, 10, 'standar', 0, 234000, 'diambil'),
(14, 2, 11, 'standar', 0, 28000, 'diambil'),
(15, 2, 12, 'standar', 0, 148000, 'diambil'),
(16, 3, 1, 'standar', 1, 142000, 'diambil'),
(17, 3, 2, 'standar', 0, 119000, 'diambil'),
(18, 3, 3, 'standar', 1, 168000, 'diambil'),
(19, 3, 4, 'standar', 0, 168000, 'diambil'),
(20, 3, 5, 'standar', 1, 168000, 'diambil'),
(21, 3, 6, 'standar', 0, 119000, 'diambil'),
(22, 3, 7, 'standar', 0, 314000, 'diambil'),
(23, 3, 8, 'standar', 0, 124000, 'diambil'),
(24, 3, 9, 'standar', 0, 132000, 'diambil'),
(25, 3, 10, 'standar', 0, 234000, 'diambil'),
(26, 3, 11, 'standar', 0, 28000, 'belum'),
(27, 3, 12, 'standar', 0, 148000, 'belum'),
(28, 4, 1, 'standar', 0, 122000, 'diambil'),
(29, 4, 2, 'standar', 0, 119000, 'diambil'),
(30, 4, 3, 'standar', 0, 146000, 'diambil'),
(31, 4, 8, 'standar', 0, 124000, 'diambil'),
(32, 4, 4, 'standar', 0, 168000, 'diambil'),
(33, 4, 5, 'standar', 0, 146000, 'diambil'),
(34, 4, 6, 'standar', 0, 119000, 'diambil'),
(35, 4, 7, 'standar', 0, 314000, 'diambil'),
(36, 4, 15, 'standar', 0, 122000, 'diambil'),
(37, 4, 9, 'standar', 0, 132000, 'diambil'),
(38, 4, 10, 'standar', 0, 234000, 'diambil'),
(39, 4, 11, 'standar', 0, 28000, 'diambil'),
(40, 4, 12, 'standar', 0, 148000, 'diambil'),
(41, 5, 1, 'standar', 1, 142000, 'diambil'),
(42, 5, 2, 'standar', 0, 119000, 'diambil'),
(43, 5, 3, 'standar', 1, 168000, 'diambil'),
(44, 5, 8, 'standar', 0, 124000, 'diambil'),
(45, 5, 4, 'standar', 0, 168000, 'diambil'),
(46, 5, 5, 'standar', 1, 168000, 'diambil'),
(47, 5, 6, 'standar', 0, 119000, 'diambil'),
(48, 5, 7, 'standar', 0, 314000, 'diambil'),
(49, 5, 15, 'standar', 0, 122000, 'diambil'),
(50, 5, 9, 'standar', 0, 132000, 'diambil'),
(51, 5, 10, 'standar', 0, 234000, 'diambil'),
(52, 5, 11, 'standar', 0, 28000, 'diambil'),
(53, 5, 12, 'standar', 0, 148000, 'diambil'),
(54, 6, 1, 'standar', 1, 142000, 'diambil'),
(55, 6, 2, 'standar', 0, 119000, 'diambil'),
(56, 6, 3, 'standar', 1, 168000, 'diambil'),
(57, 6, 8, 'standar', 0, 124000, 'diambil'),
(58, 6, 4, 'standar', 0, 168000, 'diambil'),
(59, 6, 5, 'standar', 1, 168000, 'diambil'),
(60, 6, 6, 'standar', 0, 119000, 'diambil'),
(61, 6, 7, 'standar', 0, 314000, 'diambil'),
(62, 6, 15, 'standar', 0, 122000, 'diambil'),
(63, 6, 9, 'standar', 0, 132000, 'diambil'),
(64, 6, 10, 'standar', 0, 234000, 'diambil'),
(65, 6, 11, 'standar', 0, 28000, 'diambil'),
(66, 6, 12, 'standar', 0, 148000, 'diambil'),
(67, 7, 9, 'standar', 0, 132000, 'belum'),
(68, 8, 15, 'standar', 0, 122000, 'belum'),
(69, 9, 5, 'standar', 0, 146000, 'belum'),
(70, 10, 3, 'standar', 0, 146000, 'belum'),
(71, 10, 8, 'standar', 0, 124000, 'belum');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','kasir','gudang') NOT NULL DEFAULT 'kasir',
  `last_login` datetime DEFAULT NULL,
  `last_ip` varchar(45) DEFAULT NULL,
  `login_attempts` int DEFAULT '0',
  `locked_until` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=257 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`, `last_login`, `last_ip`, `login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$GAX0CMhimQrMNnTtwZhrNu46PXw//7BFArzQGyZP4vFI3j8HxOaLW', 'Willy', 'admin', '2025-06-30 02:16:43', '::1', 0, NULL, '2025-03-18 20:30:53', '2025-06-30 02:16:43'),
(29, 'Asri', '$2y$10$kB/B8Mh15xZfat46gWBnKOM86q2hOLYmUa410byk1ZBdiUDXjsMDC', 'Asri', 'kasir', '2025-06-30 00:47:15', '::1', 0, NULL, '2025-06-29 04:55:35', '2025-06-30 00:47:15'),
(17, 'Dirwan', '$2y$10$yaOqHLuGNygj1tNM71ExLus69zwCFZMAdEGEeDGfBIrGRVHftFD1W', 'Dirwan', 'gudang', '2025-06-30 02:22:38', '::1', 0, NULL, '2025-03-19 19:49:04', '2025-06-30 02:22:38'),
(20, 'kasir', '$2y$10$.RfH5Ix1uph7VwgQjfFTg.Fn7RvEQfkQNWZOHBdkEhF5siUpyBLA6', 'Jundi', 'kasir', '2025-06-30 00:46:31', '::1', 0, NULL, '2025-04-10 20:30:25', '2025-06-30 00:46:31'),
(27, 'Adi', '$2y$12$F4lCYp3EonTJMofpdQ1Q5..NH/IAjzjX99qih4KUwcXZ9BD7feVH.', 'Adi Irianto', 'kasir', '2025-06-29 02:15:16', '::1', 0, NULL, '2025-04-27 08:28:03', '2025-06-30 02:21:52'),
(237, 'MuhammadJundi', '$2y$12$i/lPzbhbcMutXKREYbvNVOTGjwNnvsdpc6lRMGoIDux/NAgN6NvYi', 'Muhammad Jundi Hanif', 'admin', '2025-06-30 02:18:33', '::1', 0, NULL, '2025-06-30 02:13:39', '2025-06-30 02:18:33'),
(236, 'YogaWilly', '$2y$12$.LRbPWNGhxbJFJBsX8h9D.5/tzgKBtnoRheOqTdEjypwuUDWDkbXW', 'Yoga Willy Utomo', 'admin', NULL, NULL, 0, NULL, '2025-06-30 02:13:39', '2025-06-30 02:13:39'),
(235, 'Eva', '$2y$12$drntZVzBya90qFIJuloI1eGu5iaahveIEw0Pd0Ka9wFO9e2K5uKSi', 'Eva Nurvaizah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:38', '2025-06-30 02:13:38'),
(234, 'Hanif', '$2y$12$EsWpKwpsMTeXao0PtoqWMOSWFQ4LYtmtmmwBwsDtY0jarEpxIbwQa', 'Hanif Saeful Amin', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:38', '2025-06-30 02:13:38'),
(233, 'Sukarno', '$2y$12$owLSTQr8rOEa5MSfCobCH..Y8VTTfRu8gyo8ChYZ/FE3fs7k7VyjG', 'Sukarno', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:37', '2025-06-30 02:13:37'),
(232, 'Yuliati', '$2y$12$4X0Z1w7FriiTmncUJgAzbOSALDucQM0Ia92/3BkK8nR4xfn94wyiC', 'Yuliati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:37', '2025-06-30 02:13:37'),
(231, 'Suwondo', '$2y$12$kKmV5R/L/YIzCVmSVlXAfOZyKbGIf6Uf.Ml0HXLjTuodtnH1dF.9C', 'Suwondo', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:37', '2025-06-30 02:13:37'),
(230, 'TriPuji', '$2y$12$AC5mb4E.tcm/ZnWmcUeXquN8JXYP/Z6ZFu7HuiC06xJKZo3drTqGi', 'Tri Puji Astuti', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:36', '2025-06-30 02:13:36'),
(229, 'Teguh', '$2y$12$HNFM6mszaV5K1L1HXnT4LO94kkuUmAHGBQwgG4x.YBiFY4ktWktaG', 'Teguh Wibowo', 'admin', NULL, NULL, 0, NULL, '2025-06-30 02:13:36', '2025-06-30 02:13:36'),
(228, 'Singgih', '$2y$12$qACEiQ40QzE1zTN.uEgnSOgbXS8vSGsEDz2mScQJx2VLxyHXft67S', 'Singgih Yoga Putranto', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:35', '2025-06-30 02:13:35'),
(227, 'Roman', '$2y$12$SbVxTldLEwDnI4PZeosL5e6tMbdMhlNyFf3fDvunny/8MzXP9QOdq', 'Roman Boby Pradita', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:35', '2025-06-30 02:13:35'),
(226, 'Sugeng', '$2y$12$Ng9Il.d1K8pUfUtzphz0zeAtA4OThhVdijcCsl8UCMUmrFr0yYNgK', 'Sugeng Paryanto', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:35', '2025-06-30 02:13:35'),
(225, 'Gunawan', '$2y$12$uXe65lR/TUE6xpYnKX8jv.xqeOnhqvIkQG.dyaAScO6bQfCkcvmIS', 'Gunawan', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:34', '2025-06-30 02:13:34'),
(224, 'Priyanto', '$2y$12$3GcW26aqN.o/tHZbsPHpUOncQdxjNXXJvVNJtYZ/H82VPBeonq6fu', 'Priyanto', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:34', '2025-06-30 02:13:34'),
(223, 'Achmad', '$2y$12$H8rqDkgIx/7kKoRcXncCeev4sCXIU7FaF2ZIJs9deX4OndeycV4AW', 'Achmad Purtama Andana', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:33', '2025-06-30 02:13:33'),
(222, 'Sumiati', '$2y$12$Mq0g6TcCMLkomyL5c2krruu4tJikix5BKqaMo.g5p5OpmBnnM5J02', 'Sumiati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:33', '2025-06-30 02:13:33'),
(221, 'Okkie', '$2y$12$JptnOhHR/3Ju3Tgezng2MeXxfvU7.lzaL8DVqeibDesiiSebv72ay', 'Okkie Cahya Milana', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:32', '2025-06-30 02:13:32'),
(220, 'Arief', '$2y$12$kkSolGW4Zdm2QfRLweOQLuWp/wHdwY7dkA0THo1Q0rQRJ..ahkITe', 'Arief Ritade Aswas', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:32', '2025-06-30 02:13:32'),
(219, 'Sholihah', '$2y$12$dse9xL1FDc3/VEbPN3YZVuB6HF8u0TnhjMhn3nsfNBHYoVsdiLZeK', 'Sholihah Husnul Chotimah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:32', '2025-06-30 02:13:32'),
(218, 'Jamalusurur', '$2y$12$rrsFzzldM3M1VXr50GjkEe9YukkKymLOldhp6fqHnR5tl9orz7N4G', 'Jamalusurur', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:31', '2025-06-30 02:13:31'),
(217, 'Rowita', '$2y$12$9j71V7Wb9tB9pytYmFzi7.OEyPyBMuBDMAs9SNaCABQMMZ5IWzQn6', 'Rowita Arifia', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:31', '2025-06-30 02:13:31'),
(216, 'Indwi', '$2y$12$MMS99JQbRWK8hrgrIUORIuJ1dp6jXjCYn92aNF4dUv4n/c08H9G8i', 'Indwi Kurniati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:30', '2025-06-30 02:13:30'),
(215, 'Arif', '$2y$12$KJS2CkiQUnfqYg67o7r09.L5H22bq1xRUEYRelCAfgRv6sU2xNodq', 'Arif Danang Prianggono', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:30', '2025-06-30 02:13:30'),
(214, 'Farida', '$2y$12$LMlc/9FztbBwtKyjWAe7B.roULD6JZaiAy614/gPGSMfYbWBao0Vm', 'Farida Kurniati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:29', '2025-06-30 02:13:29'),
(213, 'Raras', '$2y$12$Xff5mZolrQF8qOi8QIxG1e5BiNf1NhSL54muk.TOuefM1Ufd3j99q', 'Raras Nuring Sasongkowati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:29', '2025-06-30 02:13:29'),
(212, 'Menik', '$2y$12$wS9IzI0aMhV9f6EdZS0v2uhxiwpskRCtH1PJpDD/IDkLpjGBTCrhC', 'Menik Mugiwati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:29', '2025-06-30 02:13:29'),
(211, 'Tutut', '$2y$12$B0MlHcE/iH6aLwJ9gL3AHOUT4mJjvAAM72GCpBuXIsGXMMaaU9aYu', 'Tutut Sinarsih', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:28', '2025-06-30 02:13:28'),
(210, 'Yoyok', '$2y$12$Ma5ejY7N5GDqVbPf7uPp3.8gG/RSPdo82rBaWM4Il6lL1Cgk84M4.', 'Yoyok Kushandoyo Gatot Budi S', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:28', '2025-06-30 02:13:28'),
(209, 'Naelussyfa', '$2y$12$Ss2EixwJsyzISf1ybZH8lOMfuTeu2aoeJPhNcbri7FVnUUVde2eDS', 'Naelussyfa Rohana', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:27', '2025-06-30 02:13:27'),
(208, 'Sulis', '$2y$12$l4EV3bV1K7cPb.D.5x1Hre4b5u18ykukvNBh.PTVJ/9EfKHuyi40q', 'Sulis Haryati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:27', '2025-06-30 02:13:27'),
(207, 'SriMaryani', '$2y$12$dGLUGGNMLB4fMHzv7NHrB.llh..lLH5p16HRJGIKa0KDUOLBYWjUG', 'Sri Maryani', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:26', '2025-06-30 02:13:26'),
(206, 'Rosiatuningsih', '$2y$12$iOozC4Ig9HsV0tOGISooOuhEZIRpRug.u1dlj.4jBw7ozLJxu4n2i', 'Rosiatuningsih', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:26', '2025-06-30 02:13:26'),
(205, 'DwiAndi', '$2y$12$elKwF.ZA0evL85YVvzbVsOsX4fmXTzFOg5JcR.1a.uUgWiJRzDtj.', 'Dwi Andi Purnomo', 'admin', NULL, NULL, 0, NULL, '2025-06-30 02:13:26', '2025-06-30 02:13:26'),
(204, 'Eko', '$2y$12$kezDUY8TOZqy8uDRyjxA1uecn3MGNIfWT7nKD4FqFXDL56cgjZgW.', 'Eko Budi Setiyanto', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:25', '2025-06-30 02:13:25'),
(203, 'Nurfaedah', '$2y$12$sLYzugjbABlquJX96Ic8j.La2.Kas7EzI5xz/bgACFToq6B0WiE36', 'Nurfaedah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:25', '2025-06-30 02:13:25'),
(202, 'Akhmad', '$2y$12$fU3Wy03jCtff.Hl25BLc3.aIxD9lCxRbzh9wnG./vZbMfmyOxpJp6', 'Akhmad Syauqy', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:24', '2025-06-30 02:13:24'),
(201, 'Kustinah', '$2y$12$IeKsgZkiQR7/Wdrcv8XNL.UkEs/lNo60UTaddKQ7BFTlQoamlmsdu', 'Kustinah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:24', '2025-06-30 02:13:24'),
(200, 'Budhi', '$2y$12$sUTwlV/EhWb4Nkpg/zHX3uysEUIvwrry5QcWIYqvJpE1SkLCP7OPO', 'Budhi Cahyono', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:23', '2025-06-30 02:13:23'),
(199, 'Rustri', '$2y$12$nSZJ6mSJodEYsfEZTy6hte9.jIeymqsjNghJQzTHDc5ag3VgioZ9G', 'Rustri Sulistiyaningsih', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:23', '2025-06-30 02:13:23'),
(198, 'Angga', '$2y$12$aIKqHh1g6THhBGAhlZTYuunpvlNp7jX7mH7LPAZ1SZ7VTROKjNNJS', 'Angga Sukma Gilang', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:23', '2025-06-30 02:13:23'),
(197, 'Indah', '$2y$12$N86IG7XkD6yFiSXN0h7CyeUktP3X2FbamwSiBzZSgcbj.d28GZppC', 'Indah Saptanti', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:22', '2025-06-30 02:13:22'),
(196, 'Yusuf', '$2y$12$9TMStYmDZxsuf7QmFna9eO7PUVbxRpHMJaO3MAFJOK7l8S.ASxfya', 'Yusuf Achmadi', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:22', '2025-06-30 02:13:22'),
(195, 'TriRahyuningsih', '$2y$12$9uIOEUxv9I/.rabAoynNCeMSWRd64r/MUr9Ei.6jyFy52YElMOElu', 'Tri Rahyuningsih', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:21', '2025-06-30 02:13:21'),
(194, 'Sukirman', '$2y$12$X4e4arUdl/1QKFap2uTuCOonuMX4b3VLKCdH3cT8699J0E3wCvLay', 'Sukirman', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:21', '2025-06-30 02:13:21'),
(193, 'Widiastuti', '$2y$12$B0zf1LZQDS9VZyw4KjMBuOs1c43y.aVp/HlyYL.78dAclnVc2BL4.', 'Widiastuti S', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:20', '2025-06-30 02:13:20'),
(192, 'Faradina', '$2y$12$ZYFDbOxxfTp/uPo3U4s8c.9yGFX5mD5vuTJYARHLzaI2niRHTEuY2', 'Faradina', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:20', '2025-06-30 02:13:20'),
(191, 'Sudijat', '$2y$12$.0dxZct3P6EhlaOgDDpMouJjKEwCjp8.5nTYcWzqdUpGY5tl2mBGa', 'Sudijat', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:20', '2025-06-30 02:13:20'),
(190, 'Ayu', '$2y$12$haR0DfVCAqk9fsWWPnqnH.Yh.4jfAoiVEGmvBovDgQP2KyuiUqFxG', 'Ayu Swandini', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:19', '2025-06-30 02:13:19'),
(189, 'Uthiya', '$2y$12$ZvaEeEbgo2XOI69.r/t1V.AE08TXh36prvid6PZ4uoFPyLS89ECQ6', 'Uthiya Rahma', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:19', '2025-06-30 02:13:19'),
(188, 'NurHepti', '$2y$12$zeH2Be4/eicDuTfLOhGff.T2XiCI9gQBdseX1XvPUorlKbJS/QMfG', 'Nur Hepti Istiqomah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:18', '2025-06-30 02:13:18'),
(187, 'Tanton', '$2y$12$5DFotQyVAfbtQYHZQ0HoeembetIEq2N0aZlHBCyW5Bx/InF6/VOam', 'Tanton Cahyanto', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:18', '2025-06-30 02:13:18'),
(186, 'TitiEndri', '$2y$12$577cyMCUw4oXE8U0limYY.Zr6uWymhkvGkckdr7FYfUM8QsAdb1x6', 'Titi Endri Astuti', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:17', '2025-06-30 02:13:17'),
(185, 'Hayu', '$2y$12$h1H6Nm4R2Sz1Y.myV17.CeMETd7cPqinwa61VprQ.CzlIIBhsj39e', 'Hayu Almar\'atus Sholihah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:17', '2025-06-30 02:13:17'),
(184, 'Ratsongko', '$2y$12$GgGH7KZE2eFUFzvO3m/Jr.kG.Ie7KsAQu/PYoSHeIogU6vi4rCHjy', 'Ratsongko Mawi Tantri', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:17', '2025-06-30 02:13:17'),
(183, 'Cahyono', '$2y$12$EEy4Iy5nEz9iTPP5mY9mIuKo4TDec21I9A.kdnAYUtql7C1xfy3KW', 'Tri Cahyono', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:16', '2025-06-30 02:13:16'),
(182, 'Fajri', '$2y$12$GWJMbLWrAgV5Wv7L/MSQiebmmpEi7ACJylRbtcdShaufSakn4hMKC', 'Fajri Tri Khoiriyatun', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:16', '2025-06-30 02:13:16'),
(181, 'Afridian', '$2y$12$dkqiFMWd63AhOIla31C90.LzXkpXl53BmmY//IM8PQMxRxKwoJ3gy', 'Afridian', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:15', '2025-06-30 02:13:15'),
(180, 'Sofwan', '$2y$12$.ia53PF/E.77ge0OvuAKTu1BZDJiYA74D8SZBaiFsnCYUVnLAtgP.', 'Sofwan Akhmad', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:15', '2025-06-30 02:13:15'),
(179, 'TitiErnawati', '$2y$12$GVYGDDXLih4LNiJ9ZGD2j.xWr4i4q8ajmji0788t7m9OY0e3ZuNXm', 'Titi Ernawati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:14', '2025-06-30 02:13:14'),
(178, 'Agus', '$2y$12$KX1dLgNf080YOTd1k0x3WOXUb7zL8v9nxOXG7C.mDmflQTzGExTB6', 'Agus Nuryanto', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:14', '2025-06-30 02:13:14'),
(177, 'Kikie', '$2y$12$fjqIyibEuGJ57LcceTBSreMD6v0VefRsAKj2127tmcoV0DKixlCwy', 'Kikie Astri Mahdalika', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:14', '2025-06-30 02:13:14'),
(176, 'Muji', '$2y$12$.gBXF1njlTRu2eixsA/Jxep1t7EqDqpaw1.IJC9yImAUaWJPFuqbK', 'Muji Lestari', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:13', '2025-06-30 02:13:13'),
(175, 'Fajriatun', '$2y$12$tiODyI2F.80qG5bHB9rOEehwqI59tn0eoCQoWi3pRptwpGoXf3Xue', 'Dwi Prastanti Fajriatun', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:13', '2025-06-30 02:13:13'),
(174, 'Haryono', '$2y$12$1xX5w6VXSphpioMGF6pRf.N0mSF0BPE3wOT2AnxISYPkBUo.OzWUS', 'Haryono', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:12', '2025-06-30 02:13:12'),
(173, 'Roslina', '$2y$12$43UIx76DFF7UdHguZqsM/OMzI1q9UjQZboB3Uex4qSifvirWWwaL.', 'Roslina Saptaningrum', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:12', '2025-06-30 02:13:12'),
(172, 'Dani', '$2y$12$WG0ZKE2jIlxbQNkDJPoh8.KW/TZNtcYGMW7FG4KhJz9zUQHUorsGS', 'M. Dani Ismail', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:11', '2025-06-30 02:13:11'),
(171, 'Maisatul', '$2y$12$RYFxVWNTBo4doq7bccfjn.WL/Ilb2zeJw7J/5i/ZCg53dPWkX4zmu', 'Maisatul Rochmah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:11', '2025-06-30 02:13:11'),
(170, 'Palupi', '$2y$12$piZIdQTNYXJovDXSawf4vuMkHiZpP/Z7oq6yhIz/FCY3wo5irJNoS', 'Palupi Nilasari', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:10', '2025-06-30 02:13:10'),
(169, 'Riyad', '$2y$12$/Zm4oX0GVw79VWfu/ITywOVo9C.VxlPRfKXqht.VCq7FU8UB/jpuC', 'Riyad Firdausi', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:10', '2025-06-30 02:13:10'),
(168, 'Nurul', '$2y$12$Nwn3K2Mbny7NhS7ebESNGe6CEt3dvgegK3sEcjidak0fdUzDoIP4m', 'Nurul Chasanah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:09', '2025-06-30 02:13:09'),
(167, 'Rumili', '$2y$12$CRokEzKJGzUnUmfGHUPU5.eQjwvn/thlS7KJaYxdm1zZnbixsntbC', 'Rumili', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:09', '2025-06-30 02:13:09'),
(166, 'Tati', '$2y$12$vAHBc85CCV3qX2XB0FLqGu8ylsaRr3gc5JX813AWvpywFHgfm.7gW', 'Tati Nurhayati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:08', '2025-06-30 02:13:08'),
(165, 'Nasiyah', '$2y$12$nIpMWwCchNXEH7rHglNwtOPJlidw6jURb5nUEl5paOh98GRfI1oCe', 'Nasiyah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:08', '2025-06-30 02:13:08'),
(164, 'Amalinda', '$2y$12$Cx3YbqQMLXA/0ga2rccDaO04MRTrS1.KW0T.VKzgAag2wvO.O/50W', 'Amalinda Hergiawati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:08', '2025-06-30 02:13:08'),
(163, 'Meliza', '$2y$12$r2LfkKJmKzjdJ0DqRvxnNekVW8QBmKkpaIj7BhbuLgvRuvaj5RN22', 'Meliza Dyah Puspitasari', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:07', '2025-06-30 02:13:07'),
(162, 'Musyaffa', '$2y$12$bT2D3y6NpDOz9L7TkjG5pO2iTGQC03YRgblybHMODaI6aqsmsBZ2e', 'Muhammad Musyaffa', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:07', '2025-06-30 02:13:07'),
(161, 'Lina', '$2y$12$IC7ozKufT7rpGIiq89XRfOMnGiD2rBSfKMivqa4ZOAF9gy6HsSPN.', 'Lina Nuryanti', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:06', '2025-06-30 02:13:06'),
(160, 'Rohmat', '$2y$12$R0jO0d5IzBWZrDvx48ZMA.p4TlWuQU64a9f9GElLuzmnYDhrYipbi', 'Rohmat Prayogi', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:06', '2025-06-30 02:13:06'),
(159, 'Sugiarti', '$2y$12$z9Nfsj0TJPKmW25Rqp6G1OSf93jytDjKjSJXKGmSTIdfzsKYaFeQ2', 'Sugiarti', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:05', '2025-06-30 02:13:05'),
(158, 'Soekristianti', '$2y$12$5CN.Yuf6HJEkLfL/FjjOUuCZsrnheNRn6A9Zf1qNO3JbFshKSY79i', 'Soekristianti Edi Siswati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:05', '2025-06-30 02:13:05'),
(157, 'Andit', '$2y$12$.CIaDMept7pT7gw0O/2pguz/nIePwQm1VZqyKaiS3Fywb6l6vutbi', 'Andit Dwi Susanto', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:05', '2025-06-30 02:13:05'),
(156, 'Nining', '$2y$12$Q1XDxFTajjK.fNuf52696OK4eSfHPKNLEYelUiiok6wijR1lHGoT2', 'Nining Umiyati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:04', '2025-06-30 02:13:04'),
(155, 'SriYulia', '$2y$12$1aLOoK/FMOKFaEQ9aM8nj./8wmuLCJzUYC4y1Hwj8TroGCyFseWCe', 'Sri Yulia Ningsih', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:04', '2025-06-30 02:13:04'),
(154, 'Fajar', '$2y$12$d.HAentxKI4isXX9DdsL9.LxgMAuNL0FQQNThIcGkdDAsJECfb8sC', 'Fajar Mintaraga', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:03', '2025-06-30 02:13:03'),
(153, 'Yunita', '$2y$12$QcgJC2Ve6IJ0.LPqkoVXAuDLRHZmGV0sk2aSr4VSMxmnXqPS13OS.', 'Yunita Sri Rahayu', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:03', '2025-06-30 02:13:03'),
(152, 'Feria', '$2y$12$OFQmQ9nrE7mqnkUMOvdnQuva3MbhFKVnrwI1aOhDKf8OQp7mhOhQG', 'Feria Faozah Susilowati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:03', '2025-06-30 02:13:03'),
(151, 'Diyah', '$2y$12$PVpPHv.NIbSz/f/QJYx7cONv.d6WvpbXWXJWmDLTnTBv251Nl4MZG', 'Diyah Chandra Ikawati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:02', '2025-06-30 02:13:02'),
(150, 'Karsono', '$2y$12$dcKWRQn6yaWdWbBlYt8U8.QPM1s5nTyY01UjS/68/sQ4TxLW6NBXm', 'Karsono', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:02', '2025-06-30 02:13:02'),
(149, 'Solikhah', '$2y$12$mImdMXPPXRtD3rWJ7lRCWOXXjSa1NcHbim2syCEaLs/kMh3xN5Rw6', 'Solikhah', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:01', '2025-06-30 02:13:01'),
(148, 'SriYulaeni', '$2y$12$rPeS42lbq.8bnQ5uNsjW4eK3wsLz6s2WANMspfX8a8j3B4edZM26S', 'Sri Yulaeni Susanti', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:01', '2025-06-30 02:13:01'),
(147, 'Eti', '$2y$12$2.3lz87E4gEGLsgWyJ2Jo.UW4VyJyRRNa7k2/5w7YezncUvviVZz2', 'Eti Umiyati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:00', '2025-06-30 02:13:00'),
(146, 'Alifah', '$2y$12$r.yk38M/HDlV7r3Kr0hI2uHmfnyQtU3uldvlvvsSL5.fShIkIrwxS', 'Alifah Purnami', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:00', '2025-06-30 02:13:00'),
(145, 'Lili', '$2y$12$LWTPGnW.ykpUdVwiF/S3pOnSnr8IOVl2WgayfHBG8I5sIIbBevk.K', 'Lili Suprihatin', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:00', '2025-06-30 02:13:00'),
(144, 'Insan', '$2y$12$kA8wgmphGXTcMFy3SwZLW.XfolV5AeSTW4HSApTGsLOrfQoSQSafu', 'Insan', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:12:59', '2025-06-30 02:12:59'),
(238, 'Ingga', '$2y$12$K7P45d5nHsf73NziuIPD9uqi/108aRCRurCsdkbCfLsZxRqk3l66y', 'Ingga Rossi Rahmadani', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:40', '2025-06-30 02:13:40'),
(239, 'Kusyono', '$2y$12$AmHHibu1EH98hFfjT67/hODb92qTgf6KFIde5NCv9PwHFALOyPlP.', 'Kusyono Trihantoro', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:40', '2025-06-30 02:13:40'),
(240, 'Kurniawan', '$2y$12$IHvGqf.mgGRWuN0Kr0A0NeWnQunaFp5KnO52L83j0AVEC4RrzWzeq', 'Kurniawan Setiyadi', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:41', '2025-06-30 02:13:41'),
(241, 'Marita', '$2y$12$IYKrPTotWn0ieJFuTS1kGe12CSS/cEgz1LKfdnxGS/w61widYzdVO', 'Marita Budi Susilowati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:41', '2025-06-30 02:13:41'),
(242, 'Anggita', '$2y$12$8gwtQ0EIlVUg09cLevNX1OdXbRNNmR7QiYMjInYyvuZDJ0CrFR2zK', 'Anggita Puspa Perdana', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:41', '2025-06-30 02:13:41'),
(243, 'Waluyo', '$2y$12$QYOrT081IslLSJInF14YgenaIGNeE0KdAGwdbW7agjgl3.wyuPS1u', 'Waluyo', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:42', '2025-06-30 02:13:42'),
(244, 'Imam', '$2y$12$nFK1seIJTXg32EJDO7xVeOs3aeR6blo//XuIwW55.CgBbAxfilhta', 'Imam Pudji Santosa', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:42', '2025-06-30 02:13:42'),
(245, 'Rosmiasih', '$2y$12$IcKs8uv0eYlTJ3QfqEr8.uBodVEsxd7TpsjUMhyltcAroHjyFA/5K', 'Rosmiasih', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:43', '2025-06-30 02:13:43'),
(246, 'Ratna', '$2y$12$55rgJxhKAnJSWRJ.Zmq7bef5w9aQ9qPz.Md/ux5d/mF51dKTn.tn6', 'Ratna Budi Susanti', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:43', '2025-06-30 02:13:43'),
(247, 'MuchamadAwaludin', '$2y$12$oK1D.H.1nLRkTBNE4Q89d.fBkhMTXKnLSM2/43ZRLGYlyGILVy1Ge', 'Muchamad Awaludin', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:44', '2025-06-30 02:13:44'),
(248, 'Seno', '$2y$12$coOwXkB0ODp/469YBZwOMuU2TAi0pbfnPgs7LcGTsxjaGKy2Ps5ZO', 'Seno Nugroho', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:44', '2025-06-30 02:13:44'),
(249, 'Asrining', '$2y$12$G.uanHp1.Ucl4I56ThLSBe7QHCBMPm.YQmWjrCHSc0QbF9gqbZSjG', 'Asrining Pratiwi', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:44', '2025-06-30 02:13:44'),
(250, 'Ichsan', '$2y$12$pWRcA8jsLb6OwIu8hIFrYOs3N9Hg95Gp7UQv6ERtOdmPt1GmPNm8S', 'Ichsan Romdhona', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:45', '2025-06-30 02:13:45'),
(251, 'Mardwityo', '$2y$12$7iBLIe6jdu6TAdeOeS3cRukUfuTyUcMLvMcQxHNk/Sku2/sp02xfO', 'Mardwityo Romadhona', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:45', '2025-06-30 02:13:45'),
(252, 'Kuwati', '$2y$12$MWtRxxc7r000BK3cjy07H.DIspL/MLr68VJ7e3r/c01DyNyprUKC2', 'Kuwati', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:46', '2025-06-30 02:13:46'),
(253, 'NurNgaini', '$2y$12$kHFQYmKI8PyPcUDqXYrdD.m29hHM0i/FKkQvTTcrp.sCNSpZHekvO', 'Nur Ngaini', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:46', '2025-06-30 02:13:46'),
(254, 'Rina', '$2y$12$2W4DKs.TgOdYcWwcfj2fA.1K2bVKj2WpB7NMpmSTqNHrvcFmjp8Ja', 'Rina Mulyani', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:47', '2025-06-30 02:13:47'),
(255, 'Abdul', '$2y$12$OLmfZlPrmZQS4o1e7IimnuAQoHt/FFieEe.gZYQyCQ7Q38NZUBpFy', 'Abdul Manan', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:47', '2025-06-30 02:13:47'),
(256, 'Titis', '$2y$12$m6K1oYOPdAsQIUn1BoqLKuraKh0z.o9SIewBDvUHwUnKf7rOC70Di', 'Titis Arum Ika YA', 'kasir', NULL, NULL, 0, NULL, '2025-06-30 02:13:47', '2025-06-30 02:13:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
