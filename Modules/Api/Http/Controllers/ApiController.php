<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\V1\MasterOrganisasi;
use App\Models\V1\MasterJabatan;
use App\Models\V1\DataAsn;
use App\Models\V1\ReferensiUnor;
use App\Models\User;
use Laravel\Passport\Token;

class ApiController extends Controller
{
    public function index()
    {
        return redirect()->route('landing');
    }

    public function login(Request $request)
    {

        // $validation = $request->validate([
        //     'username' => 'required',
        //     'password' => 'required'
        // ]);

        if(empty($request->username) || empty($request->password)){
            return response([
                'login_success' => false,
                'message' => 'missing username/password'
            ],400); 
        }

        if( !Auth::attempt($request->toArray()) ){
            return response([
                'login_success' => false,
                'message' => 'Invalid Login'
            ],400);
        }

        $accessToken = Auth::user()->createToken('AccessToken')->accessToken;

        return response([
            'login_success' => true,
            'message' => "Login Success",
            'user' => [
                'PNS_ID' =>  Auth::user()->profile->PNS_ID,
                'NIP_BARU' =>  Auth::user()->profile->NIP_BARU,
                'NAMA' => Auth::user()->profile->NAMA,
                'JABATAN_NAMA' => Auth::user()->profile->JABATAN_NAMA,
                'UNOR_ID' => Auth::user()->profile->UNOR_ID,
                'UNOR_NAMA' => Auth::user()->profile->UNOR_NAMA,
                'UNOR_INDUK_ID' => Auth::user()->profile->UNOR_INDUK_ID,
                'UNOR_INDUK_NAMA' => Auth::user()->profile->UNOR_INDUK_NAMA,
            ],
            'access_token' => $accessToken
        ]);

    }

    public function logout(Request $request)
    {

        $login = $request->validate([
            'username' => 'required'
        ]);

        $user = User::where('username',$request->username);

        $success = false;
        $message = null;

        if($user->count()>0){

            $success = true;
            $message = 'Logout Success';

            $tokens = Token::where('user_id', $user->first()->id)->get();

            foreach ($tokens as $token) {
                $token->revoke();
            }

        }else{
            $success = false;
            $message = 'User tidak ditemukan!';
        }

        return response([
            'success' => $success,
            'message' => $message
        ]);

    }

    public function masterOrganisasi()
    {
        $data_organisasi = MasterOrganisasi::select('id_organisasi as UNOR_INDUK_ID','nama_organisasi as UNOR_INDUK_NAMA');
        
        $success = false;
        $message = null;
        $data = null;

        if($data_organisasi->count()>0){
            $success = true;
            $message = 'Data organisasi ditemukan';
            $data = $data_organisasi->get();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_organisasi->count(),
            'data' => $data
        ]);
    }

    public function masterJabatan(Request $request)
    {
        $data_jabatan = MasterJabatan::select('JABATAN_ID','JABATAN_NAMA','JENIS_JABATAN_NAMA')->where('UNOR_INDUK_ID',$request->unor_induk_id);
        
        $success = false;
        $message = null;
        $data = null;

        if($data_jabatan->count()>0){
            $success = true;
            $message = 'Data organisasi ditemukan';
            $data = $data_jabatan->get();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_jabatan->count(),
            'data' => $data
        ]);
    }

    public function asnPerOrganisasi(Request $request)
    {
        $data_asn =  DataAsn::select('PNS_ID','NIP_BARU','NAMA','JABATAN_NAMA')->where('UNOR_INDUK_ID',$request->unor_induk_id);
        
        $success = false;
        $message = null;
        $data = null;

        if($data_asn->count()>0){
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $data_asn->get();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_asn->count(),
            'data' => $data
        ]);
    }

    public function profileAsn($pns_id)
    {
        $profile_asn = DataAsn::select(
            'PNS_ID',
            'NIP_BARU',
            'NAMA',
            'JABATAN_NAMA',
            'GOL_NAMA',
            'JENIS_JABATAN_NAMA',
            'UNOR_INDUK_ID',
            'UNOR_INDUK_NAMA'
        )->where('PNS_ID',$pns_id);

        $success = false;
        $message = null;
        $data = null;

        if($profile_asn->count()>0){
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->first();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $profile_asn->count(),
            'data' => $data
        ]);
    }

    public function asn(Request $request)
    {
        $nip = $request->input('nip');

        $nama = $request->input('nama');

        $organisasi = $request->input('organisasi');

        $profile_asn = null;

        if(!empty($nip) && !empty($nama))
        {
            $profile_asn = DataAsn::select(
                'PNS_ID',
                'NIP_BARU',
                'NAMA',
                'JABATAN_NAMA',
                'GOL_NAMA',
                'JENIS_JABATAN_NAMA',
                'UNOR_ID',
                'UNOR_NAMA',
                'UNOR_INDUK_ID',
                'UNOR_INDUK_NAMA'
            )->where('NIP_BARU',$nip)->where('NAMA',$nama);
        }else if(!empty($nip)){
            $profile_asn = DataAsn::select(
                'PNS_ID',
                'NIP_BARU',
                'NAMA',
                'JABATAN_NAMA',
                'GOL_NAMA',
                'JENIS_JABATAN_NAMA',
                'UNOR_ID',
                'UNOR_NAMA',
                'UNOR_INDUK_ID',
                'UNOR_INDUK_NAMA'
            )->where('NIP_BARU',$nip);
        }else if(!empty($nama)){
            $profile_asn = DataAsn::select(
                'PNS_ID',
                'NIP_BARU',
                'NAMA',
                'JABATAN_NAMA',
                'GOL_NAMA',
                'JENIS_JABATAN_NAMA',
                'UNOR_ID',
                'UNOR_NAMA',
                'UNOR_INDUK_ID',
                'UNOR_INDUK_NAMA'
            )->where('NAMA','like',$nama.'%');
        }else if(!empty($organisasi) && !empty($nip)){
            $profile_asn = DataAsn::select(
                'PNS_ID',
                'NIP_BARU',
                'NAMA',
                'JABATAN_NAMA',
                'GOL_NAMA',
                'JENIS_JABATAN_NAMA',
                'UNOR_ID',
                'UNOR_NAMA',
                'UNOR_INDUK_ID',
                'UNOR_INDUK_NAMA'
            )->where('UNOR_NAMA','like','%'.$organisasi.'%')->where('NIP_BARU',$nip);
        }else if(!empty($organisasi)){
            $profile_asn = DataAsn::select(
                'PNS_ID',
                'NIP_BARU',
                'NAMA',
                'JABATAN_NAMA',
                'GOL_NAMA',
                'JENIS_JABATAN_NAMA',
                'UNOR_ID',
                'UNOR_NAMA',
                'UNOR_INDUK_ID',
                'UNOR_INDUK_NAMA'
            )->where('UNOR_NAMA','like','%'.$organisasi.'%');
        }else if(!empty($organisasi) && !empty($nama)){
            $profile_asn = DataAsn::select(
                'PNS_ID',
                'NIP_BARU',
                'NAMA',
                'JABATAN_NAMA',
                'GOL_NAMA',
                'JENIS_JABATAN_NAMA',
                'UNOR_ID',
                'UNOR_NAMA',
                'UNOR_INDUK_ID',
                'UNOR_INDUK_NAMA'
            )->where('UNOR_NAMA','like','%'.$organisasi.'%')->where('NIP_BARU','like','%'.$nama.'%');
        }

        $success = false;
        $message = null;
        $data = null;

        if($profile_asn->count()==1){
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->first();
        }else if($profile_asn->count()>1){
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->get();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $profile_asn->count(),
            'data' => $data
        ]);
    }

    public function semuaAsn()
    {
        $profile_asn = DataAsn::select(
            'PNS_ID',
            'NIP_BARU',
            'NAMA',
            'JABATAN_NAMA',
            'GOL_NAMA',
            'JENIS_JABATAN_NAMA',
            'UNOR_INDUK_ID',
            'UNOR_INDUK_NAMA'
        )->orderBy('NAMA','ASC');

        $success = false;
        $message = null;
        $data = null;

        if($profile_asn->count()>0){
            $success = true;
            $message = 'Data ASN ditemukan';
            $data = $profile_asn->get();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $profile_asn->count(),
            'data' => $data
        ]);
    }

    public function strukturOrganisasi($unor_induk_id)
    {
        $data_struktur = ReferensiUnor::select('unor_id','unor_nama','jenis','unor_atasan_id','unor_atasan_nama')->where('unor_induk_id',$unor_induk_id)
                                ->where('unor_nama','NOT LIKE','%SD N%')
                                ->where('unor_nama','NOT LIKE','%SMP N%')
                                ->where('unor_nama','NOT LIKE','%TK N%')
                                ->where('unor_nama','NOT LIKE','%SEKOLAH%')
                                ->orWhere('unor_id',$unor_induk_id)
                                ->where('unor_nama','NOT LIKE','%SD N%')
                                ->where('unor_nama','NOT LIKE','%SMP N%')
                                ->where('unor_nama','NOT LIKE','%TK N%')
                                ->where('unor_nama','NOT LIKE','%SEKOLAH%')
                                ->orderBy('jenis');

        $success = false;
        $message = null;
        $data = null;

        if($data_struktur->count()>0){
            $success = true;
            $message = 'Data Struktur Organisasi ditemukan';
            $data = $data_struktur->get();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $data_struktur->count(),
            'data' => $data
        ]);
    }

    public function masterPuskemas()
    {
        $dataPuskemas = DataAsn::select(
            'UNOR_ID',
            'UNOR_NAMA'
        )->where('UNOR_NAMA','like','UPTD PUSAT KESEHATAN MASY%')
        ->where('UNOR_NAMA','not like','sub bagian%')
        ->groupBy('UNOR_NAMA');

        $success = false;
        $message = null;
        $data = null;

        if($dataPuskemas->count()>0){
            $success = true;
            $message = 'Data Puskemas ditemukan';
            $data = $dataPuskemas->get();
        }else{
            $success = false;
            $message = 'Data tidak ditemukan';
        }

        return response([
            'success' => $success,
            'message' => $message,
            'total' => $dataPuskemas->get()->count(),
            'data' => $data
        ]);
    }

}
