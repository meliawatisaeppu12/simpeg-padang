<?php

namespace Modules\Api\Http\Controllers;

use App\Models\UserDevelopment;
use App\Models\V2\DataUtama;
use App\Models\V2\KelompokAbsenReadOnly;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

class ApiDevController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:18|max:18',
            'password' => 'required|string|min:8|max:255'
        ]);

        if ($validator->fails()) {
            return response(array(
                'success' => false,
                'message' => $validator->errors()->first()
            ), 400);
        }

        $user = UserDevelopment::where('username', $request->username)->first();

        if (empty($user)) {
            return response(array(
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ), 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response([
                'success' => false,
                'message' => 'Username atau Password salah.'
            ], 400);
        }

        $data_utama = DataUtama::select('nip_baru as nip', 'nama', 'unor_id', 'jabatan_struktural_id', 'jabatan_nama')->where('nip_baru',$request->username)->first();

        if (empty($data_utama)) {
            return response(array(
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ), 404);
        }

        $kelompok_absen = KelompokAbsenReadOnly::select('unor_id', 'unor_induk_id')->where('unor_id', $data_utama->unor_id)->first();

        if (empty($kelompok_absen)) {
            return response(array(
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ), 404);
        }

        return response(array(
            'success' => true,
            'message' => 'Login berhasil.',
            'username' => $request->username,
            'nip' => $request->username,
            'nama' => strtoupper($data_utama->nama),
            'jabatan_id' => strtoupper($data_utama->jabatan_struktural_id),
            'jabatan_nama' => strtoupper($data_utama->jabatan_nama),
            'unit_organisasi_id' => $kelompok_absen->unor_induk_id,
            'unit_organisasi_nama' => strtoupper($kelompok_absen->unor_induk_nama),
        ), 200);
    }

    public function logout(Request $request){

        $user = UserDevelopment::where('username', $request->username)->first();

        if (empty($user)) {
            return response(array(
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ), 404);
        }

        return response(array(
            'success' => true,
            'message' => 'Logout berhasil.'
        ),200);
    }
}
