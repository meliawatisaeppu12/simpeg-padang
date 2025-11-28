<?php

namespace Modules\DataPegawai\Http\Controllers;

use App\Models\V2\DataUtama;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Repositories\PegawaiRepo;
use App\Repositories\TableRepo;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\V2\KelompokAbsenReadOnly;

class DataPegawaiController extends Controller
{
    public function index()
    {
        return view('datapegawai::index');
    }

    public function indexPeremajaan()
    {
        return view('datapegawai::index_peremajaan');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file-data-pegawai' => 'required|max:100024|mimes:xls,xlsx,csv,txt',
        ]);

        if (!app(TableRepo::class)->dataAsnToUploadHistory()) {
            throw new Exception("Error! Gagal diproses", 1);
        }

        return [
            'success' => true,
            'message' => 'Import file berhasil.'
        ];
        // $import = Excel::import(new DataPegawai, $request->file('file-data-pegawai'));
        // return json_encode($import);
    }

    public function datatables()
    {
        $unor_id = DataUtama::select('unor_id')->where('nip_baru', Auth::user()->username)->first()->unor_id;
        return DataTables::of(json_decode(PegawaiRepo::perOrganisasi($unor_id)))->make(true);
    }

    public function datatables_2()
    {
        return DataTables::of(json_decode(PegawaiRepo::all()))->make(true);
    }
}
