<?php

namespace Modules\HukumanDisiplin\Http\Controllers;

use App\Http\Controllers\DashboardController;
use App\Models\V2\RiwayatHukdis;
use App\View\Components\app;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HukumanDisiplinController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $hak_akses = app(DashboardController::class)->cekAkses();

        $data_hukdis = RiwayatHukdis::select('id', 'pnsOrang', 'jenisHukumanNama')
            ->with('dataUtama', function ($query) {
                $query->select('id', 'nip_baru', 'nama', 'jabatan_nama');
            })
            ->orderBy('rw_hukdis.id', 'DESC')
            ->take(10)
            ->get();

        $add_hukdis_modal = view('hukumandisiplin::add_hukdis_modal');

        return view('hukumandisiplin::index', compact('data_hukdis', 'add_hukdis_modal', 'hak_akses'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('hukumandisiplin::create');
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
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hukumandisiplin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hukumandisiplin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
