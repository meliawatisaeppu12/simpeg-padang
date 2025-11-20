<?php

namespace App\Repositories;

use App\Models\Simpeg\DataAsn;
use App\Models\V1\UploadHistory;
use App\Models\V2\DataUtama;
use App\Models\V2\KelompokAbsen;
use Illuminate\Support\Facades\Redis;

class PegawaiRepo
{
    public static function all()
    {
        // $data_pegawai = Redis::get('data_pegawai');

        // if(!empty($data_pegawai))
        // {
        //     return $data_pegawai;
        // }

        // Redis::set('data_pegawai',UploadHistory::select('PNS_ID','NIP_BARU','NAMA','JABATAN_NAMA')->get());

        // $data_pegawai = Redis::get('data_pegawai');
        $data_pegawai = DataUtama::select(
            'id as PNS_ID', 
            'nip_baru as NIP_BARU', 
            'nama as NAMA', 
            'jabatan_nama as JABATAN_NAMA', 
            'unor_id', 
            'unor_nama as UNOR_NAMA', 
            'unor_induk_nama as UNOR_INDUK_NAMA')
            ->where('JABATAN_NAMA', 'not like', '%guru%')
            ->with('kelompok', function ($query) {
                $query->select('unor_id', 'unor_induk_id');
            })
            ->get();

        $return  = $data_pegawai->map(function ($val) {
            $unor_induk_nama = !empty($val['kelompok']) ? $val['kelompok']->unor_induk_nama : null;
            return array(
                'NIP_BARU' => $val['NIP_BARU'],
                'NAMA' => $val['NAMA'],
                'UNOR_NAMA' => $val['UNOR_NAMA'],
                'UNOR_INDUK_NAMA' => $val['UNOR_INDUK_NAMA'],
                'KELOMPOK_ABSEN' => $unor_induk_nama,
            );
        });

        return $return;
    }

    public static function perOrganisasi($unor_id)
    {
        $kelompok_absen = KelompokAbsen::select('unor_id', 'unor_nama', 'unor_induk_id')->get();
        $unor_induk_id = $kelompok_absen->where('unor_id', $unor_id)->first()->unor_induk_id;
        $arr_unor_id = $kelompok_absen->where('unor_induk_id', $unor_induk_id)->pluck('unor_id')->toArray();
        $data_pegawai = DataUtama::select(
            'id as PNS_ID', 
            'nip_baru as NIP_BARU', 
            'nama as NAMA', 
            'jabatan_nama as JABATAN_NAMA', 
            'unor_id', 
            'unor_nama as UNOR_NAMA',
            'unor_induk_nama as UNOR_INDUK_NAMA')
            ->whereIn('unor_id', $arr_unor_id)
            ->with('kelompok', function ($query) {
                $query->select('unor_id', 'unor_induk_id');
            })
            ->orWhere('unor_id', $unor_induk_id)
            ->with('kelompok', function ($query) {
                $query->select('unor_id', 'unor_induk_id');
            })
            ->get();

        $return  = $data_pegawai->map(function ($val) {
            $unor_induk_nama = !empty($val['kelompok']) ? $val['kelompok']->unor_induk_nama : null;
            return array(
                'NIP_BARU' => $val['NIP_BARU'],
                'NAMA' => $val['NAMA'],
                'UNOR_NAMA' => $val['UNOR_NAMA'],
                'UNOR_INDUK_NAMA' => $val['UNOR_INDUK_NAMA'],
                'KELOMPOK_ABSEN' => $unor_induk_nama,
            );
        });
        return $return;
    }
}
