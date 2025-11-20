<?php

namespace Modules\KelompokAbsen\Http\Controllers;

use App\Models\V1\DataUtama;
use App\Models\V2\KelompokAbsen;
use App\Models\V2\KelompokAbsenReadOnly;
use App\Models\V2\ReferensiUnitOrganisasi;
use App\Models\V2\RefUnor;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelompokAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = DataUtama::select('unor_id', 'unor_induk_id')->where('nip_baru', Auth::user()->username)->first();
        $unor_id = $data->unor_id;
        $unor_induk_id = $data->unor_induk_id;
        $data = DB::table('db_simpeg_v2.data_utama')
            ->select('id', 'nip_baru', 'nama')
            ->where('unor_id', $unor_id)
            ->orWhere('unor_induk_id', $unor_induk_id)
            ->orWhere('unor_id', $unor_induk_id)
            ->orWhere('unor_induk_id', $unor_id)
            ->get();
        return view('kelompokabsen::400', compact('data'));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function cekAsn(Request $request)
    {
        $authenticated_user = DataUtama::select('unor_id', 'unor_induk_id')->where('nip_baru', Auth::user()->username)->first();

        RefUnor::$withoutAppends = true;
        $data = DB::table('db_simpeg_v2.data_utama')->select('id', 'nip_baru', 'nama', 'unor_id')->whereIn('id', $request->id)->get();
        $refUnor = KelompokAbsenReadOnly::select('unor_id', 'unor_nama', 'unor_induk_id', 'updated_at')->whereIn('unor_id', $data->pluck('unor_id')->toArray())->get();
        $return = $refUnor->map(function ($val) use ($data) {
            return array(
                'unor_id' => $val['unor_id'],
                'unor_induk_id' => $val['unor_induk_id'],
                'unor_nama' => $val['unor_nama'],
                'unor_induk_nama' => $val['unor_induk_nama'],
                'updated_at' => $val['updated_at'],
                'asn_unit' => $data->where('unor_id', $val['unor_id'])->values()
            );
        });
        $dropdown_data = DB::table('db_simpeg_v2.referensi_unit_organisasi')
            ->select('unor_id', 'unor_nama')
            ->where('unor_induk_id', $refUnor[0]['unor_induk_id'])
            ->orWhere('unor_id', $refUnor[0]['unor_induk_id'])
            ->orWhere('unor_induk_id', $data[0]->unor_id)
            ->orWhere('unor_id', $data[0]->unor_id)
            ->orWhere('unor_id', $authenticated_user->unor_id)
            ->orWhere('unor_induk_id', $authenticated_user->unorr_induk_id)
            ->get();
        return view('kelompokabsen::row', compact('return', 'dropdown_data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function updateKelAbsen(Request $request)
    {

        $index = 0;

        foreach ($request->unor_id as $key => $value) {
            KelompokAbsen::where('unor_id', $value)->update(['unor_induk_id' => $request->unor_induk_id[$key]]);
            $index++;
        }

        return $index;
    }
}
