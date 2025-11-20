<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Controllers\DashboardController;
use App\Models\V2\DataUtama;
use App\Models\V2\RefAlasanHukdis;
use App\Models\V2\RiwayatHukdis as RwHukdis;
use Illuminate\Support\Facades\Auth;

class RiwayatHukdis extends Component
{
    public function render()
    {
        $hak_akses = app(DashboardController::class)->cekAkses();

        $pnsId = DataUtama::select('id')->where('nip_baru',Auth::user()->username)->first()->id;

        $data_hukdis = RwHukdis::select('id', 'pnsOrang', 'jenisHukumanNama')
            ->where('pnsOrang', $pnsId)
            ->orderBy('rw_hukdis.id', 'DESC')
            ->get();

        $ref_alasan_hukdis = RefAlasanHukdis::select('idSiasn','nama')->get();

        return view('livewire.riwayat-hukdis', [
            'data_hukdis' => $data_hukdis,
            'hak_akses' => $hak_akses,
            'ref_alasan_hukdis' => $ref_alasan_hukdis,
        ]);
    }
}
