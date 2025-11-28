<?php

namespace App\Http\Controllers;

use App\Models\V2\DataUtama;
use App\Models\V2\LogUsulan;
use App\Models\V2\RiwayatHukdis;
use App\Models\V2\RiwayatJabatan;
use App\Models\V2\UsulanRiwayatJabatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RwJabatanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asn' => 'required:string',
            'jenis-jabatan' => 'required:string',
            'jenis-mutasi' => 'required:string',
            'unit-kerja' => 'required:string',
            'nama-jabatan' => 'required:string',
            'no-sk' => 'required:string',
            'tanggal-sk' => 'required:date_format:Y-m-d',
            'tmt-mutasi' => 'required:date_format:Y-m-d',
            'tmt-pelantikan' => 'required:date_format:Y-m-d',
            'sk-jabatan' => 'required|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => false,
                'message' => $validator->errors()->first()
            ), 400);
        }

        $data_utama = DataUtama::select('id')->where('nip_baru', $request->asn)->first();

        if (!isset($data_utama)) {
            return response(array(
                'success' => false,
                'message' => 'Permintaan gagal diproses'
            ), 400);
        }

        $tmt_jabatan = Carbon::createFromFormat('j F Y', $request->input('tmt-mutasi'), 'Asia/Jakarta')->format('Y-m-d');
        $tanggal_sk = Carbon::createFromFormat('j F Y', $request->input('tanggal-sk'), 'Asia/Jakarta')->format('Y-m-d');
        $tmt_pelantikan = Carbon::createFromFormat('j F Y', $request->input('tmt-pelantikan'), 'Asia/Jakarta')->format('Y-m-d');
        $tmt_mutasi = Carbon::createFromFormat('j F Y', $request->input('tmt-mutasi'), 'Asia/Jakarta')->format('Y-m-d');

        $usulan_rw_jabatan = new UsulanRiwayatJabatan();
        $usulan_rw_jabatan->eselonId = 99;
        $usulan_rw_jabatan->jenisJabatan = $request->input('jenis-jabatan');

        if ($request->input('jenis-jabatan') == 2) {
            $usulan_rw_jabatan->jabatanFungsionalId = $request->input('nama-jabatan');
            $usulan_rw_jabatan->jenisMutasiId = $request->input('jenis-mutasi');
        } else if ($request->input('jenis-jabatan') == 4) {
            $usulan_rw_jabatan->jabatanFungsionalUmumId = $request->input('nama-jabatan');
            $usulan_rw_jabatan->jenisMutasiId = $request->input('jenis-mutasi');
        }

        $file_name = 'sk-' . time() . '-jabatan.' . $request->file('sk-jabatan')->getClientOriginalExtension();

        $usulan_rw_jabatan->nomorSk = $request->input('no-sk');
        $usulan_rw_jabatan->pnsId = $data_utama->id;
        $usulan_rw_jabatan->subJabatanId = $request->input('sub-jabatan');
        $usulan_rw_jabatan->tanggalSk = $tanggal_sk;
        $usulan_rw_jabatan->tmtJabatan = $tmt_jabatan;
        $usulan_rw_jabatan->tmtMutasi = $tmt_mutasi;
        $usulan_rw_jabatan->tmtPelantikan = $tmt_pelantikan;
        $usulan_rw_jabatan->unorId = $request->input('unit-kerja');
        $usulan_rw_jabatan->namaJabatan = $request->input('id-jabatan');
        $usulan_rw_jabatan->nip = $request->input('asn');
        $usulan_rw_jabatan->skJabatan = $file_name;

        $request->file('sk-jabatan')->move(base_path("/storage/app/public/usulan/" . $request->input('asn') . "/"), $file_name);


        if ($usulan_rw_jabatan->save()) {
            $log_usulan = new LogUsulan();
            $log_usulan->jenisRiwayatId = $request->input('jenis-riwayat');
            $log_usulan->usulanId = $usulan_rw_jabatan->id;
            $log_usulan->nip = $request->input('asn');
            $log_usulan->nipPengusul = Auth::user()->username;
            $log_usulan->tanggalUsulan = Date('Y-m-d', strtotime(now()));
            $log_usulan->nipVerifikator = Auth::user()->username;
            $log_usulan->tanggalVerifikasi = Date('Y-m-d', strtotime(now()));
            $log_usulan->save();
        }

        return response(array(
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ), 201);
    }
}
