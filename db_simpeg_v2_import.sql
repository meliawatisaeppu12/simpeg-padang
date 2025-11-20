-- -------------------------------------------------------------
-- TablePlus 6.7.1(636)
--
-- https://tableplus.com/
--
-- Database: db_simpeg_v2
-- Generation Time: 2025-10-09 14:24:32.3960
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `auth`;
CREATE TABLE `auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authToken` text CHARACTER SET latin1,
  `authorizationToken` text CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15085 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `data_bl_app`;
CREATE TABLE `data_bl_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_aplikasi` varchar(255) NOT NULL,
  `nama_package` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = allowed, 1 = blocked',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `data_nip_pegawai`;
CREATE TABLE `data_nip_pegawai` (
  `nip` varchar(20) CHARACTER SET latin1 NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `data_non_asn`;
CREATE TABLE `data_non_asn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jabatan_id` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL DEFAULT 'Laki-Laki',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `data_uri`;
CREATE TABLE `data_uri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(255) CHARACTER SET latin1 NOT NULL,
  `uri` varchar(255) CHARACTER SET latin1 NOT NULL,
  `target_table` varchar(255) CHARACTER SET latin1 NOT NULL,
  `where` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'nipBaru',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `data_utama`;
CREATE TABLE `data_utama` (
  `id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nip_baru` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nip_lama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelar_depan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelar_belakang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tempat_lahir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_lahir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `agama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `agama_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nik` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `alamat` text CHARACTER SET latin1,
  `no_hp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_telpon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_pegawai_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_pegawai_nama` text CHARACTER SET latin1,
  `mk_tahun` int(11) DEFAULT NULL,
  `mk_bulan` int(11) DEFAULT NULL,
  `kedudukan_pns_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kedudukan_pns_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_pegawai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_kelamin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_id_dokumen_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_id_dokumen_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomor_id_document` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_seri_karpeg` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tk_pendidikan_terakhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tk_pendidikan_terakhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikan_terakhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikan_terakhir_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun_lulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sk_pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_cpns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sk_cpns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_induk_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_induk_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kanreg_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kanreg_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_kode_cepat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_kerja_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_nama` text CHARACTER SET latin1,
  `unor_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_induk_nama` text CHARACTER SET latin1,
  `jenis_jabatan_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_jabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_nama` text CHARACTER SET latin1,
  `jabatan_struktural_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_struktural_nama` text CHARACTER SET latin1,
  `jabatan_fungsional_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_nama` text CHARACTER SET latin1,
  `jabatan_fungsional_umum_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_umum_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_jabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasi_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasi_kerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_awal_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_awal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_akhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_gol_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masa_kerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon_level` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gaji_pokok` text CHARACTER SET latin1,
  `kpkn_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kpkn_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `ktua_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `ktua_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `taspen_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `taspen_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_kawin_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_perkawinan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_hidup` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_surat_keterangan_dokter` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_surat_keterangan_dokter` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlah_istri_suami` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlah_anak` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_surat_keterangan_bebas_narkoba` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_surat_keterangan_bebas_narkoba` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skck` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_skck` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akte_kelahiran` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akte_meninggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_meninggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_npwp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_npwp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_askes` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bpjs` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kode_pos` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_spmt` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_taspen` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bahasa` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kppn_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kppn_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pangkat_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sttpl` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_sttpl` text CHARACTER SET latin1,
  `no_sk_cpns` text CHARACTER SET latin1,
  `no_sk_pns` text CHARACTER SET latin1,
  `jenjang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_asn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kartu_asn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`nip_baru`),
  UNIQUE KEY `id_idx` (`id`) USING BTREE,
  KEY `idx_unor_id_active_jabatan` (`unor_id`,`jabatan_nama`(255),`is_active`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `device_library`;
CREATE TABLE `device_library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) NOT NULL DEFAULT 'Apple',
  `device` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=740 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) DEFAULT NULL,
  `nip_baru` varchar(255) NOT NULL,
  `app_version` varchar(255) DEFAULT NULL,
  `device` varchar(255) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_brand` varchar(255) NOT NULL,
  `device_model` varchar(255) NOT NULL,
  `device_abis` varchar(255) NOT NULL,
  `android_release` varchar(255) NOT NULL,
  `android_sdkInt` varchar(255) NOT NULL,
  `token_id` varchar(255) NOT NULL,
  `notification_token` text,
  `ads_id` varchar(255) DEFAULT NULL,
  `identifier_for_vendor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_ads_id` (`ads_id`) USING BTREE,
  KEY `idx_uuid` (`uuid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41514 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `devices_non_asn`;
CREATE TABLE `devices_non_asn` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `app_version` varchar(255) DEFAULT NULL,
  `device` varchar(255) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_brand` varchar(255) NOT NULL,
  `device_model` varchar(255) NOT NULL,
  `device_abis` varchar(255) NOT NULL,
  `android_release` varchar(255) NOT NULL,
  `android_sdkInt` varchar(255) NOT NULL,
  `token_id` varchar(255) NOT NULL,
  `notification_token` text,
  `ads_id` varchar(255) DEFAULT NULL,
  `identifier_for_vendor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `kelompok_absen`;
CREATE TABLE `kelompok_absen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unor_id` varchar(255) NOT NULL,
  `unor_nama` varchar(255) NOT NULL,
  `unor_induk_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1571 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `log_data_utama`;
CREATE TABLE `log_data_utama` (
  `id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nip_baru` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nip_lama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelar_depan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelar_belakang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tempat_lahir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_lahir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `agama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `agama_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nik` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `alamat` text CHARACTER SET latin1,
  `no_hp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_telpon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_pegawai_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_pegawai_nama` text CHARACTER SET latin1,
  `mk_tahun` int(11) DEFAULT NULL,
  `mk_bulan` int(11) DEFAULT NULL,
  `kedudukan_pns_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kedudukan_pns_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_pegawai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_kelamin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_id_dokumen_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_id_dokumen_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomor_id_document` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_seri_karpeg` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tk_pendidikan_terakhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tk_pendidikan_terakhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikan_terakhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikan_terakhir_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun_lulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sk_pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_cpns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sk_cpns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_induk_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_induk_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kanreg_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kanreg_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_kode_cepat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_kerja_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_nama` text CHARACTER SET latin1,
  `unor_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_induk_nama` text CHARACTER SET latin1,
  `jenis_jabatan_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_jabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_nama` text CHARACTER SET latin1,
  `jabatan_struktural_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_struktural_nama` text CHARACTER SET latin1,
  `jabatan_fungsional_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_umum_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_umum_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_jabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasi_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasi_kerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_awal_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_awal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_akhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_gol_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masa_kerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon_level` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gaji_pokok` text CHARACTER SET latin1,
  `kpkn_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kpkn_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `ktua_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `ktua_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `taspen_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `taspen_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_kawin_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_perkawinan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_hidup` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_surat_keterangan_dokter` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_surat_keterangan_dokter` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlah_istri_suami` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlah_anak` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_surat_keterangan_bebas_narkoba` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_surat_keterangan_bebas_narkoba` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skck` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_skck` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akte_kelahiran` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akte_meninggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_meninggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_npwp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_npwp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_askes` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bpjs` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kode_pos` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_spmt` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_taspen` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bahasa` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kppn_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kppn_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pangkat_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sttpl` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_sttpl` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_sk_cpns` text CHARACTER SET latin1,
  `no_sk_pns` text CHARACTER SET latin1,
  `jenjang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_asn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kartu_asn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_multi_login`;
CREATE TABLE `log_multi_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `device_uuid` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5260 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `log_rw_angkakredit`;
CREATE TABLE `log_rw_angkakredit` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bulanMulaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunMulaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bulanSelesaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunSelesaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditUtamaBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditPenunjangBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditBaruTotal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `rwJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `isAngkaKreditPertama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_rw_diklat`;
CREATE TABLE `log_rw_diklat` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `latihanStrukturalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `latihanStrukturalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `tanggalSelesai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `institusiPenyelenggara` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahJam` varchar(255) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_rw_golongan`;
CREATE TABLE `log_rw_golongan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golonganId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pangkat` varchar(255) DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtGolongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `noPertekBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tglPertekBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahKreditUtama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahKreditTambahan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKPId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKPNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaGolonganTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaGolonganBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_rw_hukdis`;
CREATE TABLE `log_rw_hukdis` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `rwHukumanDisiplin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kedudukanHukum` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisHukuman` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hukumanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akhirHukumTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorPp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golonganLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPembatalanNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPembatalanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `alasanHukumanDisiplin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisHukumanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `nipBaru` varchar(255) DEFAULT NULL,
  `alasanHukumanDisiplinNama` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `jenisTingkatHukumanId` varchar(255) DEFAULT NULL,
  `createdAt` varchar(255) DEFAULT NULL,
  `updatedAt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_rw_jabatan`;
CREATE TABLE `log_rw_jabatan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisPenugasanId` varchar(255) DEFAULT NULL,
  `jenisMutasiId` varchar(255) DEFAULT NULL,
  `instansiKerjaId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiKerjaNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorIndukId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorIndukNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselonId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaUnor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtPelantikan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `subJabatanId` varchar(255) DEFAULT NULL,
  `tmtMutasi` varchar(255) DEFAULT NULL,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_rw_kursus`;
CREATE TABLE `log_rw_kursus` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusSertifikat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `institusiPenyelenggara` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahJam` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaKursus` text CHARACTER SET latin1,
  `noSertipikat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `jenisDiklatId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSelesaiKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_rw_masakerja`;
CREATE TABLE `log_rw_masakerja` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `dinilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pengalaman` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalAwal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSelesai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tasaKerjaTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_rw_pendidikan`;
CREATE TABLE `log_rw_pendidikan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tkPendidikanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tkPendidikanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunLulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tglLulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `isPendidikanPertama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorIjasah` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaSekolah` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelarDepan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelarBelakang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `log_usulan`;
CREATE TABLE `log_usulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenisRiwayatId` int(11) NOT NULL,
  `usulanId` int(11) NOT NULL,
  `nip` varchar(255) NOT NULL,
  `nipPengusul` varchar(255) NOT NULL,
  `tanggalUsulan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nipVerifikator` varchar(255) DEFAULT NULL,
  `tanggalVerifikasi` datetime DEFAULT NULL,
  `statusUsulanId` int(11) NOT NULL DEFAULT '1',
  `keteranganUsul` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_berkas`;
CREATE TABLE `m_berkas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_berkas` varchar(255) NOT NULL,
  `ekstensi` varchar(255) NOT NULL,
  `ukuran_maksimal` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `file_baru` tinyint(1) NOT NULL DEFAULT '0',
  `nama_form` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_data_kecamatan`;
CREATE TABLE `m_data_kecamatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regency_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9471041 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_data_kelurahan`;
CREATE TABLE `m_data_kelurahan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelurahan_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175854 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_data_kota`;
CREATE TABLE `m_data_kota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9472 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_data_provinsi`;
CREATE TABLE `m_data_provinsi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_jabatan`;
CREATE TABLE `m_jabatan` (
  `id` varchar(255) NOT NULL,
  `jabatan_nama` text NOT NULL,
  `unor_id` varchar(255) NOT NULL,
  `jenis_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_jenis`;
CREATE TABLE `m_jenis` (
  `JENIS_ID` tinyint(1) NOT NULL AUTO_INCREMENT,
  `JENIS_NAMA` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`JENIS_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `m_jenis_layanan`;
CREATE TABLE `m_jenis_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(255) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_jenis_unor`;
CREATE TABLE `m_jenis_unor` (
  `id` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_kecamatan`;
CREATE TABLE `m_kecamatan` (
  `unor_id` varchar(255) NOT NULL,
  `unor_nama` varchar(255) NOT NULL,
  PRIMARY KEY (`unor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_kelas_jabatan`;
CREATE TABLE `m_kelas_jabatan` (
  `id` int(11) NOT NULL,
  `jabatan_id` varchar(255) NOT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_kelurahan`;
CREATE TABLE `m_kelurahan` (
  `unor_id` varchar(255) NOT NULL,
  `unor_nama` varchar(255) NOT NULL,
  `unor_atasan_id` varchar(255) NOT NULL,
  PRIMARY KEY (`unor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_layanan`;
CREATE TABLE `m_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(255) NOT NULL,
  `kode_layanan` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_organisasi`;
CREATE TABLE `m_organisasi` (
  `unor_induk_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unor_induk_nama` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`unor_induk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `m_puskesmas`;
CREATE TABLE `m_puskesmas` (
  `unor_id` varchar(255) NOT NULL,
  `unor_nama` varchar(255) NOT NULL,
  PRIMARY KEY (`unor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `m_unit_organisasi`;
CREATE TABLE `m_unit_organisasi` (
  `UNOR_ID` varchar(255) CHARACTER SET latin1 NOT NULL,
  `UNOR_NAMA` varchar(255) CHARACTER SET latin1 NOT NULL,
  `UNOR_INDUK_ID` varchar(255) CHARACTER SET latin1 NOT NULL,
  `JENIS_ID` varchar(255) NOT NULL,
  PRIMARY KEY (`UNOR_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 NOT NULL,
  `abilities` text CHARACTER SET utf8mb4,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ref_alasan_hukdis`;
CREATE TABLE `ref_alasan_hukdis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idSiasn` varchar(255) NOT NULL,
  `nama` text NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_diklat_struktural`;
CREATE TABLE `ref_diklat_struktural` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_eselon`;
CREATE TABLE `ref_eselon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_jabatan_fungsional`;
CREATE TABLE `ref_jabatan_fungsional` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jabatan_id` varchar(255) NOT NULL,
  `jabatan_nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `JABATAN_NAMA` (`jabatan_nama`)
) ENGINE=InnoDB AUTO_INCREMENT=1517 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_jabatan_fungsional_umum`;
CREATE TABLE `ref_jabatan_fungsional_umum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jabatan_id` varchar(255) NOT NULL,
  `jabatan_nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `JABATAN_NAMA` (`jabatan_nama`)
) ENGINE=InnoDB AUTO_INCREMENT=69906 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_jenis_jabatan`;
CREATE TABLE `ref_jenis_jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_jenis_riwayat`;
CREATE TABLE `ref_jenis_riwayat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_opd`;
CREATE TABLE `ref_opd` (
  `id` int(11) NOT NULL,
  `unor_id` varchar(255) DEFAULT NULL,
  `opd` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_status_usulan`;
CREATE TABLE `ref_status_usulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_sub_jabatan`;
CREATE TABLE `ref_sub_jabatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subJabatanId` varchar(255) NOT NULL,
  `nama` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=886 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ref_unor`;
CREATE TABLE `ref_unor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unor_id` varchar(255) DEFAULT NULL,
  `unor_nama` varchar(255) DEFAULT NULL,
  `eselon_id` varchar(255) DEFAULT NULL,
  `jabatan_nama` varchar(255) DEFAULT NULL,
  `unor_atasan_id` varchar(255) DEFAULT NULL,
  `unor_induk_id` varchar(255) DEFAULT NULL,
  `instansi_id` varchar(255) DEFAULT NULL,
  `jenis_unor_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1835 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `referensi_unit_organisasi`;
CREATE TABLE `referensi_unit_organisasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unor_id` varchar(255) NOT NULL,
  `unor_nama` varchar(255) NOT NULL,
  `unor_induk_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1505 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `rw_angkakredit`;
CREATE TABLE `rw_angkakredit` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bulanMulaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunMulaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bulanSelesaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunSelesaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditUtamaBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditPenunjangBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditBaruTotal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `rwJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `isAngkaKreditPertama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_cltn`;
CREATE TABLE `rw_cltn` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `cltnId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalAwal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalAkhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalAktif` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorLetterBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalLetterBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrangId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_diklat`;
CREATE TABLE `rw_diklat` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `latihanStrukturalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `latihanStrukturalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `tanggalSelesai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `institusiPenyelenggara` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahJam` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_golongan`;
CREATE TABLE `rw_golongan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golonganId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pangkat` varchar(255) DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtGolongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `noPertekBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tglPertekBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahKreditUtama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahKreditTambahan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKPId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKPNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaGolonganTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaGolonganBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_hukdis`;
CREATE TABLE `rw_hukdis` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `rwHukumanDisiplin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kedudukanHukum` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisHukuman` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hukumanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akhirHukumTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorPp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golonganLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPembatalanNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPembatalanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `alasanHukumanDisiplin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisHukumanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `nipBaru` varchar(255) DEFAULT NULL,
  `alasanHukumanDisiplinNama` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `jenisTingkatHukumanId` varchar(255) DEFAULT NULL,
  `createdAt` varchar(255) DEFAULT NULL,
  `updatedAt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_jabatan`;
CREATE TABLE `rw_jabatan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisPenugasanId` varchar(255) DEFAULT NULL,
  `jenisMutasiId` varchar(255) DEFAULT NULL,
  `instansiKerjaId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiKerjaNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorIndukId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorIndukNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselonId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaUnor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtPelantikan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `subJabatanId` varchar(255) DEFAULT NULL,
  `tmtMutasi` varchar(255) DEFAULT NULL,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_kursus`;
CREATE TABLE `rw_kursus` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusSertifikat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `institusiPenyelenggara` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahJam` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaKursus` text CHARACTER SET latin1,
  `noSertipikat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `jenisDiklatId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSelesaiKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_masakerja`;
CREATE TABLE `rw_masakerja` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `dinilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pengalaman` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalAwal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSelesai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tasaKerjaTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_pemberhentian`;
CREATE TABLE `rw_pemberhentian` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `pnsOrang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `asalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `asalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `asalNamaLabel` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisHenti` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kedudukanHukumPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_pendidikan`;
CREATE TABLE `rw_pendidikan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tkPendidikanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tkPendidikanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunLulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tglLulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `isPendidikanPertama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorIjasah` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaSekolah` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelarDepan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelarBelakang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_penghargaan`;
CREATE TABLE `rw_penghargaan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `tahun` date DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skDate` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hargaNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrangId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_pindahinstansi`;
CREATE TABLE `rw_pindahinstansi` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `instansiKerjaLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisPegawai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaIndukBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaIndukLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisJabatanLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasiKerjaBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasiKerjaLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kpknBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisJabatanBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiKerjaBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiIndukBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiIndukLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skUsulNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skUsulTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skBknNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skBknTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtPi` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skAsalNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skAsalTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTujuanNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTujuanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisPi` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `orangUsulPeremajaanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skAsalProvNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skAsalProvTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_pnsunor`;
CREATE TABLE `rw_pnsunor` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `unorBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaUnorBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `asalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `asalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `asalNamaLabel` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_pwk`;
CREATE TABLE `rw_pwk` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `instansi` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `kpknBaru` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `lokasiKerjaBaru` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrang` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerja` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `tmtPwk` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `unorBaru` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `asalId` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `asalNama` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_skp`;
CREATE TABLE `rw_skp` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `tahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nilaiSkp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `orientasiPelayanan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `integritas` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `komitmen` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `disiplin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kerjasama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nilaiPerilakuKerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nilaiPrestasiKerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kepemimpinan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlah` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nilairatarata` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanPejabatPenilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pejabatPenilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanNonPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiNonPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiNipNrp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanPenilaiNipNrp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanPenilaiNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiUnorNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanPenilaiUnorNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanPenilaiJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiGolongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanPenilaiGolongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiTmtGolongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `atasanPenilaiTmtGolongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `statusPenilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `statusAtasanPenilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rw_skp22`;
CREATE TABLE `rw_skp22` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `hasilKinerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hasilKinerjaNilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kuadranKinerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `KuadranKinerjaNilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaPenilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipNrpPenilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiGolonganId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiJabatanNm` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `penilaiUnorNm` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `perilakuKerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `PerilakuKerjaNilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsDinilaiId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `statusPenilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `saml2_tenants`;
CREATE TABLE `saml2_tenants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `idp_entity_id` varchar(255) NOT NULL,
  `idp_login_url` varchar(255) NOT NULL,
  `idp_logout_url` varchar(255) NOT NULL,
  `idp_x509_cert` text NOT NULL,
  `metadata` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `relay_state_url` varchar(255) DEFAULT NULL,
  `name_id_format` varchar(255) NOT NULL DEFAULT 'persistent',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sso_app_store_version`;
CREATE TABLE `sso_app_store_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version_name` varchar(25) NOT NULL,
  `version_code` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `os` varchar(255) NOT NULL DEFAULT 'android',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `sso_app_version_notif`;
CREATE TABLE `sso_app_version_notif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `version_name` varchar(255) NOT NULL,
  `device_uuid` varchar(255) NOT NULL,
  `installer_store` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=917038 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `sso_users`;
CREATE TABLE `sso_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(18) CHARACTER SET utf8mb4 NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tb_akses_layanan`;
CREATE TABLE `tb_akses_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jabatan_id` varchar(255) NOT NULL,
  `unor_induk_id` varchar(255) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `id_jenis_layanan` int(11) NOT NULL,
  `is_verifikator` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_berita`;
CREATE TABLE `tb_berita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` text NOT NULL,
  `page_url` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_berkas_layanan`;
CREATE TABLE `tb_berkas_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_berkas` int(11) NOT NULL,
  `id_jenis_layanan` int(11) NOT NULL,
  `catatan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_berkas_pegawai`;
CREATE TABLE `tb_berkas_pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `id_berkas` int(11) NOT NULL,
  `nama_berkas` varchar(255) NOT NULL,
  `nama_berkas_asli` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_chat`;
CREATE TABLE `tb_chat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `offset` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tb_kepala_puskesmas`;
CREATE TABLE `tb_kepala_puskesmas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unor_id` varchar(255) NOT NULL,
  `nip` varchar(18) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_locked_account`;
CREATE TABLE `tb_locked_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `device` text NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '1',
  `counted` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=646 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_log`;
CREATE TABLE `tb_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pegawai` varchar(255) CHARACTER SET latin1 NOT NULL,
  `keterangan` text CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19047 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tb_periode_layanan`;
CREATE TABLE `tb_periode_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_periode` varchar(255) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `tanggal_awal` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_plt`;
CREATE TABLE `tb_plt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `unor_id` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_proses_layanan`;
CREATE TABLE `tb_proses_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trans_layanan` varchar(255) NOT NULL,
  `jabatan_id` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = menunggu, 1 = acc, 2 = ditolak, 3 dikembalikan',
  `verifikator_nip` varchar(255) DEFAULT NULL,
  `verifikator_jabatan_id` varchar(255) DEFAULT NULL,
  `disposisi` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_rekomendasi_layanan`;
CREATE TABLE `tb_rekomendasi_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trans_layanan` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `nama_file_asli` varchar(255) NOT NULL,
  `verifikator_nip` varchar(255) NOT NULL,
  `keterangan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_slide_app`;
CREATE TABLE `tb_slide_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `slide_no` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status_slide` (`status`,`slide_no`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_status_layanan`;
CREATE TABLE `tb_status_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_status` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_trans_berkas`;
CREATE TABLE `tb_trans_berkas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_trans_layanan` int(11) NOT NULL,
  `id_berkas_pegawai` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = draft, 1 dikirim',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `tb_trans_layanan`;
CREATE TABLE `tb_trans_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_layanan` int(11) NOT NULL,
  `nip` varchar(255) NOT NULL,
  `kode_draft` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 = draft, 1 dikirim',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `telescope_entries`;
CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `telescope_entries_tags`;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `telescope_monitoring`;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `temp_data_utama`;
CREATE TABLE `temp_data_utama` (
  `id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nip_baru` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nip_lama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelar_depan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelar_belakang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tempat_lahir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_lahir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `agama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `agama_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nik` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `alamat` text CHARACTER SET latin1,
  `no_hp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_telpon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_pegawai_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_pegawai_nama` text CHARACTER SET latin1,
  `mk_tahun` int(11) DEFAULT NULL,
  `mk_bulan` int(11) DEFAULT NULL,
  `kedudukan_pns_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kedudukan_pns_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_pegawai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_kelamin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_id_dokumen_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_id_dokumen_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomor_id_document` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_seri_karpeg` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tk_pendidikan_terakhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tk_pendidikan_terakhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikan_terakhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikan_terakhir_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun_lulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sk_pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_cpns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sk_cpns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_induk_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_induk_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kanreg_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kanreg_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansi_kerja_kode_cepat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuan_kerja_kerja_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_nama` text CHARACTER SET latin1,
  `unor_induk_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unor_induk_nama` text CHARACTER SET latin1,
  `jenis_jabatan_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_jabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_nama` text CHARACTER SET latin1,
  `jabatan_struktural_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_struktural_nama` text CHARACTER SET latin1,
  `jabatan_fungsional_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_umum_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_fungsional_umum_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_jabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasi_kerja_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lokasi_kerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_awal_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_awal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_akhir_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gol_ruang_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_gol_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masa_kerja` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon_level` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmt_eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gaji_pokok` text CHARACTER SET latin1,
  `kpkn_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kpkn_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `ktua_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `ktua_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `taspen_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `taspen_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenis_kawin_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_perkawinan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `status_hidup` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_surat_keterangan_dokter` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_surat_keterangan_dokter` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlah_istri_suami` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlah_anak` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_surat_keterangan_bebas_narkoba` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_surat_keterangan_bebas_narkoba` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skck` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_skck` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akte_kelahiran` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akte_meninggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_meninggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_npwp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_npwp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_askes` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bpjs` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kode_pos` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_spmt` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_taspen` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bahasa` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kppn_id` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kppn_nama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pangkat_akhir` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal_sttpl` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_sttpl` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `no_sk_cpns` text CHARACTER SET latin1,
  `no_sk_pns` text CHARACTER SET latin1,
  `jenjang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatan_asn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kartu_asn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_m_jabatan`;
CREATE TABLE `temp_m_jabatan` (
  `id` varchar(255) NOT NULL,
  `jabatan_nama` text NOT NULL,
  `unor_id` varchar(255) NOT NULL,
  `jenis_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `temp_rw_angkakredit`;
CREATE TABLE `temp_rw_angkakredit` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `pns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bulanMulaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunMulaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `bulanSelesaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunSelesaiPenailan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditUtamaBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditPenunjangBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kreditBaruTotal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `rwJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `isAngkaKreditPertama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_rw_diklat`;
CREATE TABLE `temp_rw_diklat` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `latihanStrukturalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `latihanStrukturalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `tanggalSelesai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `institusiPenyelenggara` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahJam` varchar(255) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_rw_golongan`;
CREATE TABLE `temp_rw_golongan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golonganId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pangkat` varchar(255) DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtGolongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `noPertekBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tglPertekBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahKreditUtama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahKreditTambahan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKPId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKPNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaGolonganTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaGolonganBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_rw_hukdis`;
CREATE TABLE `temp_rw_hukdis` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `rwHukumanDisiplin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golongan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `kedudukanHukum` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisHukuman` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hukumanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akhirHukumTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorPp` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golonganLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPembatalanNomor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPembatalanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `alasanHukumanDisiplin` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisHukumanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `nipBaru` varchar(255) DEFAULT NULL,
  `alasanHukumanDisiplinNama` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `jenisTingkatHukumanId` varchar(255) DEFAULT NULL,
  `createdAt` varchar(255) DEFAULT NULL,
  `updatedAt` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_rw_jabatan`;
CREATE TABLE `temp_rw_jabatan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisPenugasanId` varchar(255) DEFAULT NULL,
  `jenisMutasiId` varchar(255) DEFAULT NULL,
  `instansiKerjaId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiKerjaNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `satuanKerjaNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorIndukId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `unorIndukNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselon` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `eselonId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaUnor` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tmtPelantikan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `subJabatanId` varchar(255) DEFAULT NULL,
  `tmtMutasi` varchar(255) DEFAULT NULL,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_rw_kursus`;
CREATE TABLE `temp_rw_kursus` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusSertifikat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `institusiPenyelenggara` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jumlahJam` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaKursus` text CHARACTER SET latin1,
  `noSertipikat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `jenisDiklatId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSelesaiKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_rw_masakerja`;
CREATE TABLE `temp_rw_masakerja` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `dinilai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `masaKerjaBulan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pengalaman` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalAwal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalBkn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSelesai` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tasaKerjaTahun` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_rw_pendidikan`;
CREATE TABLE `temp_rw_pendidikan` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idPns` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipBaru` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nipLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pendidikanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tkPendidikanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tkPendidikanNama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahunLulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tglLulus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `isPendidikanPertama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nomorIjasah` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaSekolah` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelarDepan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `gelarBelakang` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `path` blob,
  `createdAt` varchar(255) DEFAULT '',
  `updatedAt` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `tes-table`;
CREATE TABLE `tes-table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `unknown_devices`;
CREATE TABLE `unknown_devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) DEFAULT NULL,
  `nip_baru` varchar(255) NOT NULL,
  `app_version` varchar(255) DEFAULT NULL,
  `device` varchar(255) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_brand` varchar(255) NOT NULL,
  `device_model` varchar(255) NOT NULL,
  `device_abis` varchar(255) NOT NULL,
  `android_release` varchar(255) NOT NULL,
  `android_sdkInt` varchar(255) NOT NULL,
  `token_id` varchar(255) NOT NULL,
  `notification_token` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `ads_id` varchar(255) DEFAULT NULL,
  `identifier_for_vendor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4822 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(18) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `notification_token` text,
  `jenis_kepegawaian` enum('asn','nonasn') NOT NULL DEFAULT 'asn',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17752 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users_activity`;
CREATE TABLE `users_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `activity` text NOT NULL,
  `affected_table` varchar(255) DEFAULT NULL,
  `affected_field` varchar(255) DEFAULT NULL,
  `device` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49529 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `users_bl_app`;
CREATE TABLE `users_bl_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) DEFAULT NULL,
  `device_uuid` varchar(255) DEFAULT NULL,
  `bl_app_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `usulan_rw_diklat`;
CREATE TABLE `usulan_rw_diklat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idSiasn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiId` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'Pemko Padang',
  `institusiPenyelenggara` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisDiklatId` varchar(255) CHARACTER SET latin1 NOT NULL,
  `jenisKursus` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `latihanStrukturalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisKursusSertipikat` varchar(255) CHARACTER SET latin1 NOT NULL,
  `jumlahJam` int(11) DEFAULT NULL,
  `lokasiId` varchar(255) DEFAULT NULL,
  `namaKursus` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nomorSertipikat` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrangId` varchar(255) CHARACTER SET latin1 NOT NULL,
  `tahunKursus` int(11) DEFAULT NULL,
  `tanggalKursus` date DEFAULT NULL,
  `tanggalSelesaiKursus` date DEFAULT NULL,
  `dokumenSertipikat` varchar(255) DEFAULT NULL,
  `rumpuanId` varchar(255) DEFAULT NULL,
  `nip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `usulan_rw_hukdis`;
CREATE TABLE `usulan_rw_hukdis` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `idSiasn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `akhirHukumanTanggal` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `alasanHukumanDisiplinId` varchar(255) CHARACTER SET latin1 NOT NULL,
  `golonganId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `golonganLama` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hukdisYangDiberhentikanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hukumanTanggal` date NOT NULL,
  `jenisHukumanId` varchar(255) CHARACTER SET latin1 NOT NULL,
  `jenisTingkatHukumanId` varchar(255) CHARACTER SET latin1 NOT NULL,
  `kedudukanHukumId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 NOT NULL,
  `masaBulan` int(11) NOT NULL,
  `masaTahun` int(11) NOT NULL,
  `nomorPp` varchar(255) CHARACTER SET latin1 NOT NULL,
  `dokumenSkHukdis` varchar(255) CHARACTER SET latin1 NOT NULL,
  `dokumenSkPengaktifanKembali` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pnsOrangId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skNomor` blob NOT NULL,
  `skPembatalanNomor` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `skPembatalanTanggal` date DEFAULT NULL,
  `skTanggal` date NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `usulan_rw_jabatan`;
CREATE TABLE `usulan_rw_jabatan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idSiasn` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `eselonId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `instansiId` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'Pemko Padang',
  `jabatanFungsionalId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jabatanFungsionalUmumId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `jenisJabatan` varchar(255) CHARACTER SET latin1 NOT NULL,
  `jenisMutasiId` varchar(255) CHARACTER SET utf8 NOT NULL,
  `jenisPenugasanId` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `nomorSk` varchar(255) CHARACTER SET latin1 NOT NULL,
  `pnsId` varchar(255) CHARACTER SET latin1 NOT NULL,
  `satuanKerjaId` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'Pemko Padang',
  `subJabatanId` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tanggalSk` date NOT NULL,
  `tmtJabatan` date DEFAULT NULL,
  `tmtMutasi` date DEFAULT NULL,
  `tmtPelantikan` date DEFAULT NULL,
  `unorId` varchar(255) CHARACTER SET latin1 NOT NULL,
  `skJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPelantikan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `skPemberhentian` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `namaJabatan` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `nip` varchar(255) CHARACTER SET latin1 NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

DROP VIEW IF EXISTS `v_device_202521`;


DROP VIEW IF EXISTS `v_devices`;


DROP VIEW IF EXISTS `v_kepala_puskesmas`;


DROP VIEW IF EXISTS `v_locked_account`;


DROP VIEW IF EXISTS `v_log_multi_login`;


DROP VIEW IF EXISTS `v_log_multi_login_v2`;


DROP VIEW IF EXISTS `v_nip`;


DROP VIEW IF EXISTS `v_plt`;


DROP VIEW IF EXISTS `v_ref_unor`;


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_device_202521` AS select `devices`.`nip_baru` AS `nip_baru`,count(`devices`.`nip_baru`) AS `jumlah` from `devices` where (`devices`.`nip_baru` like '%202521%') group by `devices`.`nip_baru` order by `jumlah` desc;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_devices` AS select `data_utama`.`nama` AS `nama`,`devices`.`nip_baru` AS `nip_baru`,`data_utama`.`unor_nama` AS `unor_nama`,`data_utama`.`unor_induk_nama` AS `unor_induk_nama`,`devices`.`device` AS `device`,`devices`.`device_model` AS `device_model`,`devices`.`device_brand` AS `device_brand`,`devices`.`uuid` AS `uuid` from (`devices` join `data_utama` on((`data_utama`.`nip_baru` = `devices`.`nip_baru`)));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kepala_puskesmas` AS select `tb_kepala_puskesmas`.`unor_id` AS `unor_id`,`m_puskesmas`.`unor_nama` AS `unor_nama`,`data_utama`.`nip_baru` AS `nip_baru`,`data_utama`.`nama` AS `nama` from ((`tb_kepala_puskesmas` join `m_puskesmas` on((`m_puskesmas`.`unor_id` = `tb_kepala_puskesmas`.`unor_id`))) join `data_utama` on((convert(`data_utama`.`nip_baru` using utf8mb4) = `tb_kepala_puskesmas`.`nip`)));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_locked_account` AS select `tb_locked_account`.`id` AS `id`,`tb_locked_account`.`nip` AS `nip`,`data_utama`.`nama` AS `nama`,`data_utama`.`unor_nama` AS `unor_nama`,`data_utama`.`unor_induk_nama` AS `unor_induk_nama`,`tb_locked_account`.`is_locked` AS `is_locked`,`tb_locked_account`.`counted` AS `counted`,`devices`.`device_brand` AS `device_brand`,`devices`.`device_model` AS `device_model`,`tb_locked_account`.`created_at` AS `created_at`,`tb_locked_account`.`updated_at` AS `updated_at` from ((`tb_locked_account` join `data_utama` on((`tb_locked_account`.`nip` = convert(`data_utama`.`nip_baru` using utf8mb4)))) join `devices` on((`tb_locked_account`.`device` = convert(`devices`.`uuid` using utf8mb4))));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_multi_login` AS select `log_multi_login`.`nip` AS `nip`,`data_utama`.`nama` AS `nama`,`log_multi_login`.`created_at` AS `created_at` from (`log_multi_login` join `data_utama` on((`log_multi_login`.`nip` = convert(`data_utama`.`nip_baru` using utf8mb4)))) where (cast(`log_multi_login`.`created_at` as date) = cast(now() as date)) group by `log_multi_login`.`nip`,`data_utama`.`nama`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_multi_login_v2` AS select `v_log_multi_login`.`nip` AS `nip`,`v_log_multi_login`.`nama` AS `nama`,`data_utama`.`unor_nama` AS `unor_nama`,`data_utama`.`unor_induk_nama` AS `unor_induk_nama`,`v_log_multi_login`.`created_at` AS `created_at` from (`v_log_multi_login` join `data_utama` on((`v_log_multi_login`.`nip` = convert(`data_utama`.`nip_baru` using utf8mb4))));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_nip` AS select `data_utama`.`nip_baru` AS `nip_data_utama`,`users`.`username` AS `nip_user` from (`users` left join `data_utama` on((convert(`data_utama`.`nip_baru` using utf8mb4) = `users`.`username`)));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_plt` AS select `data_utama`.`nip_baru` AS `nip`,`data_utama`.`nama` AS `nama`,`m_unit_organisasi`.`UNOR_NAMA` AS `unit_plh_plt`,if((`tb_plt`.`status` = '1'),'aktif','nonaktif') AS `status`,`tb_plt`.`created_at` AS `tanggal_dibuat` from ((`tb_plt` join `data_utama` on((`tb_plt`.`nip` = convert(`data_utama`.`nip_baru` using utf8mb4)))) join `m_unit_organisasi` on((`tb_plt`.`unor_id` = convert(`m_unit_organisasi`.`UNOR_ID` using utf8mb4))));
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ref_unor` AS (select `ref_unor`.`unor_id` AS `id`,`ref_unor`.`unor_nama` AS `unor_nama`,`ref_unor`.`eselon_id` AS `eselon_id`,`ref_unor`.`unor_induk_id` AS `ref`,`ref_unor`.`unor_induk_id` AS `unor_induk_id`,(select `ref_unor`.`unor_nama` from `ref_unor` where (`ref_unor`.`unor_id` = `ref`)) AS `unor_induk_nama` from `ref_unor`);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;