<?php

namespace App\Http\Controllers;

use App\Models\V2\DataUtama;
use App\Models\V2\PersonalAccess;
use App\Models\V2\PositionAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $jumlah_pegawai;

    private $jumlah_struktural;

    private $jumlah_fungsional;

    private $jumlah_nonaktif;

    private $jumlah_aktif;

    private $persentase_struktural;

    private $persentase_fungsional;

    private $persentase_nonaktif;

    private $persentase_aktif;

    private $hak_akses;

    private $akses_id;

    public function __construct()
    {
        $this->akses_id = env('AKSES_ID_PEREMAJAAN', 8);
    }

    public function index()
    {

        $this->hak_akses = $this->cekAkses();

        $this->countDataPegawai();

        return view('dashboard', [
            'jumlah_struktural' => $this->jumlah_struktural,
            'jumlah_fungsional' => $this->jumlah_fungsional,
            'jumlah_aktif' => $this->jumlah_aktif,
            'jumlah_nonaktif' => $this->jumlah_nonaktif,
            'persentase_struktural' => $this->persentase_struktural,
            'persentase_fungsional' => $this->persentase_fungsional,
            'persentase_aktif' => $this->persentase_aktif,
            'persentase_nonaktif' => $this->persentase_nonaktif,
            'hak_akses' => $this->hak_akses,
        ]);
    }

    private function countDataPegawai()
    {
        $this->jumlah_pegawai = DataUtama::count();
        $this->jumlah_struktural = DataUtama::select('id')->where('jabatan_struktural_id', '!=', '')->get()->count();
        $this->jumlah_fungsional = DataUtama::where('jabatan_fungsional_id', '!=', '')->orWhere('jabatan_fungsional_umum_id', '!=', '')->count();
        $this->jumlah_aktif = DataUtama::where('is_active', true)->count();
        $this->jumlah_nonaktif = DataUtama::where('is_active', false)->count();
        $this->persentase_struktural = round(($this->jumlah_struktural / $this->jumlah_pegawai * 100), 2);
        $this->persentase_fungsional = round(($this->jumlah_fungsional / $this->jumlah_pegawai * 100), 2);
        $this->persentase_aktif = round(($this->jumlah_aktif / $this->jumlah_pegawai * 100), 2);
        $this->persentase_nonaktif = round(($this->jumlah_nonaktif / $this->jumlah_pegawai * 100), 2);
    }

    public function cekAkses()
    {
        if (!Auth::check()) {
            return false;
        } else {
            $data = DataUtama::select('jabatan_struktural_id', 'jabatan_fungsional_id', 'jabatan_fungsional_umum_id')->where('nip_baru', Auth::user()->username)->first();
            $jabatan_struktural_id = $data->jabatan_struktural_id;
            $jabatan_fungsional_id = $data->jabatan_fungsional_id;
            $jabatan_fungsional_umum_id = $data->jabatan_fungsional_umum_id;
            $jabatan_id = !empty($jabatan_struktural_id) ? $jabatan_struktural_id : (!empty($jabatan_fungsional_id) ? $jabatan_fungsional_id : $jabatan_fungsional_umum_id);

            if (empty($jabatan_id)) {
                return false;
            } else {
                $access = PositionAccess::whereIn('access_id', [$this->akses_id])->where('jabatan_id', $jabatan_id)->get();
                if ($access->count() > 0) {

                    return true;
                } else {
                    $personal_access = PersonalAccess::whereIn('access_id', [$this->akses_id])->where('pns_id', Auth::user()->v2Profile->id)->get();

                    if ($personal_access->count() > 0) {

                        return true;
                    }
                }
            }
        }
    }

    public function searchPegawai(Request $request)
    {
        $hak_akses = $this->cekAkses();

        if ($hak_akses) {
            $data = DataUtama::select('nip_baru as id', 'nama as text')
                ->where('nama', 'like', '%' . $request->q . '%')
                ->where('is_active', true)
                ->where('jabatan_nama', 'not like', '%guru%')
                ->orWhere('nip_baru', 'like', '%' . $request->q . '%')
                ->where('is_active', true)
                ->where('jabatan_nama', 'not like', '%guru%')
                ->get();
        } else {
            $data = DataUtama::select('nip_baru as id', 'nama as text')
                ->where('nip_baru', Auth::user()->username)
                ->where('nama', 'like', '%' . $request->q . '%')
                ->where('is_active', true)
                ->orWhere('nip_baru', Auth::user()->username)
                ->where('jabatan_nama', 'like', '%' . $request->q . '%')
                ->where('is_active', true)
                ->get();
        }

        if ($data->count() > 0) {
            return response(array(
                'items' => $data->map(
                    function ($val) {
                        return array(
                            'id' => $val['id'],
                            'text' => $val['text'] . ' - ' . $val['id'],
                        );
                    },
                ),
                'count_filtered' => count($data),
            ), 200);
        }

        return response([], 200);
    }
}
