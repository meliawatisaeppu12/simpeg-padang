<?php

namespace App\Http\Controllers;

use App\Models\V2\DataUtama;
use App\Models\V2\UsulanRiwayatDiklat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\V2\LogUsulan;
use Illuminate\Support\Facades\Auth;

class RwDiklatControler extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asn' => 'required:string',
            'jenis-diklat' => 'required:string',
            'nama-diklat' => 'required:string',
            'institusi-penyelenggara' => 'required:string',
            'no-sertifikat' => 'required:string',
            'tanggal-mulai' => 'required:date',
            'tanggal-selesai' => 'required:date',
            'scan-sertifikat' => 'required|mimes:pdf|max:10240',
            'durasi-jam' => 'required:number',
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

        $tanggal_mulai = Carbon::createFromFormat('j F Y', $request->input('tanggal-mulai'), 'Asia/Jakarta')->format('Y-m-d');
        $tanggal_selesai = Carbon::createFromFormat('j F Y', $request->input('tanggal-selesai'), 'Asia/Jakarta')->format('Y-m-d');

        $file_name = 'sk-'.time().'-jabatan.'.$request->file('scan-sertifikat')->getClientOriginalExtension();

        $usulan_rw_diklat = new UsulanRiwayatDiklat();
        $usulan_rw_diklat->institusiPenyelenggara = $request->input('institusi-penyelenggara');
        $usulan_rw_diklat->jenisDiklatId = $request->input('jenis-diklat');
        $usulan_rw_diklat->latihanStrukturalId = $request->input('nama-diklat');
        $usulan_rw_diklat->jenisKursusSertipikat = $request->input('id-diklat');
        $usulan_rw_diklat->jumlahJam = $request->input('durasi-jam');
        $usulan_rw_diklat->namaKursus = $request->input('id-diklat');
        $usulan_rw_diklat->nomorSertipikat = $request->input('no-sertifikat');
        $usulan_rw_diklat->pnsOrangId = $data_utama->id;
        $usulan_rw_diklat->tanggalKursus = $tanggal_mulai;
        $usulan_rw_diklat->tanggalSelesaiKursus = $tanggal_selesai;
        $usulan_rw_diklat->dokumenSertipikat = $file_name;
        $usulan_rw_diklat->nip = $request->input('asn');

        if ($usulan_rw_diklat->save()) {
            $log_usulan = new LogUsulan();
            $log_usulan->jenisRiwayatId = $request->input('jenis-riwayat');
            $log_usulan->usulanId = $usulan_rw_diklat->id;
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
