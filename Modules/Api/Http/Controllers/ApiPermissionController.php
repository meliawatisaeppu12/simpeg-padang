<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\V2\DataUtama;
use App\Models\V2\NonAsn;
use App\Models\V2\NonAsnAccess;
use App\Models\V2\PersonalAccess;
use App\Models\V2\PositionAccess;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ApiPermissionController extends Controller
{
    public function get()
    {
        $user = Auth::user();

        $username = $user->username;

        if ($user->jenis_kepegawaian == 'asn') {
            $data_pegawai = DataUtama::select('id', 'jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id')
                ->where('nip_baru', $username)
                ->first();

            $jabatan_struktural_id = $data_pegawai->jabatan_struktural_id;
            $jabatan_fungsional_id = $data_pegawai->jabatan_fungsional_id;
            $jabatan_fungsional_umum_id = $data_pegawai->jabatan_fungsional_umum_id;

            $jabatan_id = !empty($jabatan_struktural_id) ? $jabatan_struktural_id : (!empty($jabatan_fungsional_id) ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

            $personal_access = new Collection();
            $access = new Collection();

            $personal_access = PersonalAccess::select('access_id', 'access_status')
                ->where('pns_id', $data_pegawai->id)
                ->where('access_status', true)
                ->with('access_data')
                ->get()->toArray();


            $access = PositionAccess::select('access_id', 'access_status')
                ->where('jabatan_id', $jabatan_id)
                ->where('access_status', true)
                ->with('access_data')
                ->get()->toArray();

            $merged_data = collect($personal_access)->merge(collect($access));
        } else {
            $data_pegawai = NonAsn::select('id', 'jabatan_id','username')
                ->where('username', $username)
                ->first();

            $jabatan_id = $data_pegawai->jabatan_id;

            $non_asn_access = NonAsnAccess::select('access_id', 'access_status')
                ->where('username', $data_pegawai->username)
                ->where('access_status', true)
                ->with('access_data')
                ->get()->toArray();
            
            $merged_data = collect($non_asn_access);
        }

        if ($merged_data->count() == 0) {
            return response()->json([
                'message' => 'Data ditemukan.',
                'data' => collect([array(
                    'id' => '12',
                    'accessKode' => 'create_aktivitas',
                )]),
            ], 200);
        }

        $mapped_data = $merged_data->map(function ($val) {
            return array(
                'id' => (string)$val['access_id'],
                'accessKode' => $val['access_data']['kode_akses'],
            );
        });

        $create_aktivitas = collect([array(
            'id' => '12',
            'accessKode' => 'create_aktivitas',
        )]);


        return response()->json([
            'message' => 'Data ditemukan.',
            'data' => $mapped_data->merge($create_aktivitas),
        ], 200);
    }
}
