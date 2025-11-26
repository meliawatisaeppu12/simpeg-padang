-- -------------------------------------------------------------
-- TablePlus 6.7.4(642)
--
-- https://tableplus.com/
--
-- Database: db_access
-- Generation Time: 2025-11-26 10:38:43.9610
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `access_list`;
CREATE TABLE `access_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `access_name` varchar(255) NOT NULL,
  `access_desc` text NOT NULL,
  `kode_akses` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `application_list`;
CREATE TABLE `application_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_code` varchar(255) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `app_type_id` int(11) NOT NULL DEFAULT '1',
  `app_desc` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO `access_list` (`id`, `app_id`, `access_name`, `access_desc`, `kode_akses`, `created_at`, `updated_at`) VALUES
(1, 2, 'acc cuti', 'Hak akses untuk proses persetujuan cuti pegawai.', 'acc_cuti', '2024-02-02 12:15:07', '2024-02-02 12:15:07'),
(2, 2, 'acc izin', 'Hak akses untuk persetujuan izin pegawai.', 'acc_izin', '2024-02-02 12:15:33', '2024-02-02 12:15:33'),
(3, 1, 'download rekap absen', 'Hak akses untuk mendownload rekap absen OPD', 'download_rekap', '2024-02-19 12:59:42', '2024-02-19 12:59:42'),
(4, 1, 'Set koordinat', 'Hak akses untuk mengatur koordinat kantor', 'set_koordinat', '2024-02-19 13:00:16', '2024-02-19 13:00:16'),
(5, 1, 'Atur shift', 'Hak akses untuk mengatur shift pegawai', 'atur_shift', '2024-02-19 15:52:48', '2024-02-19 15:52:48'),
(6, 3, 'Atur data kelompok absen', 'Hak akses untuk memperbarui data kelompok absen.', 'atur_kelompok_absen', '2024-02-20 00:44:36', '2024-02-20 00:44:36'),
(7, 2, 'Nilai Aktivitas Harian', 'Hak akses untuk memberikan nilai aktivitas harian bawahan', 'nilai_ah', '2024-11-06 22:29:10', '2024-11-06 22:29:10'),
(8, 3, 'Peremajaan Data', 'Hak akses untuk memperbarui data pegawai.', 'peremajaan_data', '2024-02-20 00:44:36', '2024-02-20 00:44:36'),
(9, 3, 'Manajemen Perangkat', 'Hak akses untuk manajemen perangkat.', 'manajemen_perangkat', '2024-02-20 00:44:36', '2024-02-20 00:44:36'),
(10, 2, 'Input surat masuk', 'Hak akses untuk penginput data surat masuk.', 'input_surat', '2025-03-19 10:59:21', '2025-03-19 10:59:21'),
(11, 2, 'Disposisi surat masuk', 'Hak akses untuk disposisi surat masuk.', 'disposisi_surat', '2025-03-19 10:59:21', '2025-03-19 10:59:21'),
(12, 2, 'Create Aktivitas', 'Hak akses untuk membuat aktivitas harian.', 'create_aktivitas', '2025-03-19 10:59:21', '2025-03-19 10:59:21'),
(13, 2, 'Acc Aktivitas', 'Hak akses untuk memberi persetujuan aktivitas harian.', 'acc_aktivitas', '2025-03-19 10:59:21', '2025-03-19 10:59:21'),
(14, 2, 'Bypass Aktivitas', 'Hak akses untuk bypass aktivitas harian.', 'bypass_aktivitas', '2025-03-19 10:59:21', '2025-03-19 10:59:21');

INSERT INTO `application_list` (`id`, `app_code`, `app_name`, `app_type_id`, `app_desc`, `created_at`, `updated_at`) VALUES
(1, 'PDG2024ABSENSI', 'Absensi Kota Padang', 1, 'Aplikasi manajemen kehadiran pegawai di lingkungan pemerintah kota padang.', '2024-02-02 12:09:41', '2024-02-02 12:09:41'),
(2, 'PDG2024SSO', 'SSO Kota Padang', 2, 'Aplikasi Layanan Kepegawaian di lingkungan pemerintah kota padang.', '2024-02-02 12:11:47', '2024-02-02 12:11:47'),
(3, 'PDG2024SIMPEG', 'SIMPEG Kota Padang', 1, 'Aplikasi pengelola data kepegawaian pemerintah kota padang.', '2024-02-20 00:43:05', '2024-02-20 00:43:05');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;