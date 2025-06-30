-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 29, 2025 at 11:56 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

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
(9, 'Kaos Olah Raga', 132000, 0, 0, 0, 9),
(10, 'Kerudung', 234000, 78000, 0, 0, 10),
(11, 'Kaos Kaki', 28000, 0, 0, 0, 11),
(12, 'Pakaian Lab. ( Farmasi )', 148000, 0, 0, 0, 12),
(15, 'Atribut', 122000, 0, 0, 0, 8);

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
) ENGINE=MyISAM AUTO_INCREMENT=431 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `kode_transaksi`, `tanggal_transaksi`, `kasir_id`, `siswa_id`, `metode_pembayaran`, `total_harga`, `bukti_pembayaran`, `status`, `diambil`, `created_at`, `updated_at`) VALUES
(1, 'TRX-1750924012', '2025-06-26 07:46:52', 1, 409, 'cash', 557000, NULL, 'batal', NULL, '2025-06-26 07:46:52', '2025-06-26 13:27:03'),
(2, 'TRX-1750942851', '2025-06-26 13:00:51', 20, 399, 'cash', 1864000, NULL, 'batal', NULL, '2025-06-26 13:00:51', '2025-06-29 06:58:39'),
(3, 'TRX-1750944052', '2025-06-26 13:20:52', 1, 367, 'cash', 1864000, NULL, 'lunas', NULL, '2025-06-26 13:20:52', '2025-06-28 04:21:17'),
(4, 'TRX-1751163390', '2025-06-29 02:16:30', 27, 348, 'cash', 1922000, NULL, 'batal', NULL, '2025-06-29 02:16:30', '2025-06-29 07:05:24'),
(5, 'TRX-1751170283', '2025-06-29 04:11:23', 20, 398, 'cash', 1986000, NULL, 'diambil', NULL, '2025-06-29 04:11:23', '2025-06-29 07:46:52'),
(6, 'TRX-1751174221', '2025-06-29 05:17:01', 20, 411, 'cash', 1986000, NULL, 'batal', NULL, '2025-06-29 05:17:01', '2025-06-29 07:05:20');

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
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(66, 6, 12, 'standar', 0, 148000, 'diambil');

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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`, `last_login`, `last_ip`, `login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$GAX0CMhimQrMNnTtwZhrNu46PXw//7BFArzQGyZP4vFI3j8HxOaLW', 'Willy', 'admin', '2025-06-29 02:39:25', '::1', 0, NULL, '2025-03-18 20:30:53', '2025-06-29 02:39:25'),
(29, 'Asri', '$2y$10$kB/B8Mh15xZfat46gWBnKOM86q2hOLYmUa410byk1ZBdiUDXjsMDC', 'Asri', 'kasir', NULL, NULL, 0, NULL, '2025-06-29 04:55:35', '2025-06-29 04:55:35'),
(17, 'gudang', '$2y$10$yaOqHLuGNygj1tNM71ExLus69zwCFZMAdEGEeDGfBIrGRVHftFD1W', 'Dirwan', 'gudang', '2025-06-29 02:23:07', '::1', 0, NULL, '2025-03-19 19:49:04', '2025-06-29 02:23:07'),
(20, 'kasir', '$2y$10$.RfH5Ix1uph7VwgQjfFTg.Fn7RvEQfkQNWZOHBdkEhF5siUpyBLA6', 'Jundi', 'kasir', '2025-06-29 03:02:06', '::1', 0, NULL, '2025-04-10 20:30:25', '2025-06-29 03:02:06'),
(27, 'adi', '$2y$10$79E2bex1WB81L.iX2qbBse22zgSal5I8O2AGa4JRULTDEdiCXpGWq', 'Adi Irianto', 'kasir', '2025-06-29 02:15:16', '::1', 0, NULL, '2025-04-27 08:28:03', '2025-06-29 02:15:16');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
