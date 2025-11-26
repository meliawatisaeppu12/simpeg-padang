<?php

namespace App\Http\Controllers;

use App\Models\V2\DataUtama;
use App\Models\V2\UsulanRiwayatHukdis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RwHukdisControler extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asn' => 'required:string',
            'jenis-diklat' => 'required:string',
            'nama-diklat    ' => 'required:string',
            'institusi-penyelenggara' => 'required:string',
            'no-sertifikat' => 'required:string',
            'tanggal-mulai' => 'required:date',
            'tanggal-selesai' => 'required:date',
            'scan-sertifikat' => 'required|mimes:pdf|max:10240',
            'durasi' => 'required:number',
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

        return $request->all();

        $tanggal_mulai = Carbon::createFromFormat('j F Y', $request->input('tanggal-mulai'), 'Asia/Jakarta')->format('d-m-Y');
        $tanggal_selesai = Carbon::createFromFormat('j F Y', $request->input('tanggal-selesai'), 'Asia/Jakarta')->format('d-m-Y');

        $usulan_rw_jabatan = new UsulanRiwayatHukdis();
        

        // if ($usulan_rw_jabatan->save()) {
        //     $log_usulan = new LogUsulan();
            
        //     $log_usulan->save();
        // }

        return response(array(
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ), 201);
    }
}
