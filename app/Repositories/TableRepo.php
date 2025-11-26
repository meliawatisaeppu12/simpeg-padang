<?php

namespace App\Repositories;

use App\Models\Simpeg\UploadHistory;
use App\Models\Simpeg\DataAsn;
use Illuminate\Support\Facades\DB;

class TableRepo
{
    public static function dataAsnToUploadHistory()
    {
        $array_data = DataAsn::select(
            'PNS_ID',
            'NIP_BARU',
            'NIP_LAMA',
            'NAMA',
            'GELAR_DEPAN',
            'GELAR_BLK',
            'TEMPAT_LAHIR_ID',
            'TEMPAT_LAHIR_NAMA',
            'TGL_LAHIR',
            'JENIS_KELAMIN',
            'AGAMA_ID',
            'AGAMA_NAMA',
            'JENIS_KAWIN_ID',
            'JENIS_KAWIN_NAMA',
            'NIK',
            'NOMOR_HP',
            'EMAIL',
            'EMAIL_GOV',
            'ALAMAT',
            'NPWP_NO',
            'BPJS',
            'JENIS_PEGAWAI_ID',
            'JENIS_PEGAWAI_NAMA',
            'KEDUDUKAN_HUKUM_ID',
            'KEDUDUKAN_HUKUM_NAMA',
            'STATUS_CPNS_PNS',
            'KARTU_PEGAWAI',
            'NOMOR_SK_CPNS',
            'TGL_SK_CPNS',
            'TMT_CPNS',
            'NOMOR_SK_PNS',
            'TGL_SK_PNS',
            'TMT_PNS',
            'GOL_AWAL_ID',
            'GOL_AWAL_NAMA',
            'GOL_ID',
            'GOL_NAMA',
            'TMT_GOLONGAN',
            'MK_TAHUN',
            'MK_BULAN',
            'JENIS_JABATAN_ID',
            'JENIS_JABATAN_NAMA',
            'JABATAN_ID',
            'JABATAN_NAMA',
            'TMT_JABATAN',
            'TINGKAT_PENDIDIKAN',
            'TINGKAT_PENDIDIKAN_NAMA',
            'PENDIDIKAN_ID',
            'PENDIDIKAN_NAMA',
            'TAHUN_LULUS',
            'KPKN_ID',
            'KPKN_NAMA',
            'LOKASI_KERJA_ID',
            'LOKASI_KERJA_NAMA',
            'UNOR_ID',
            'UNOR_NAMA',
            'UNOR_INDUK_ID',
            'UNOR_INDUK_NAMA',
            'INSTANSI_INDUK_ID',
            'INSTANSI_INDUK_NAMA',
            'INSTANSI_KERJA_ID',
            'INSTANSI_KERJA_NAMA',
            'SATUAN_KERJA_INDUK_ID',
            'SATUAN_KERJA_INDUK_NAMA',
            'SATUAN_KERJA_ID',
            'SATUAN_KERJA_NAMA'
            )->get()->toArray();
        return UploadHistory::insert($array_data);
    }
}