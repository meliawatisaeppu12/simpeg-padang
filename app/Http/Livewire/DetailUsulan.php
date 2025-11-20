<?php

namespace App\Http\Livewire;

use App\Http\Controllers\DashboardController;
use App\Models\V2\UsulanRiwayatJabatan;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class DetailUsulan extends Component
{
    public $idUsulan;

    public function mount($id)
    {
        $this->idUsulan = Crypt::decrypt($id);
    }

    public function render()
    {
        $akses = app(DashboardController::class)->cekAkses();

        $data = UsulanRiwayatJabatan::select(
            'usulan_rw_jabatan.id',
            'usulan_rw_jabatan.nip',
            'data_utama.nama',
            'ref_jenis_jabatan.nama as jenisJabatan',
            'jenisMutasiId',
            'ref_unor.unor_nama',
            'usulan_rw_jabatan.namaJabatan',
            'usulan_rw_jabatan.nomorSk',
            'usulan_rw_jabatan.tanggalSk',
            'usulan_rw_jabatan.tmtMutasi',
            'usulan_rw_jabatan.tmtPelantikan',
            'usulan_rw_jabatan.skJabatan',
            'ref_jenis_riwayat.nama as jenisRiwayat'
        )
            ->join('data_utama', 'data_utama.nip_baru', '=', 'usulan_rw_jabatan.nip')
            ->join('ref_jenis_jabatan', 'ref_jenis_jabatan.id', '=', 'usulan_rw_jabatan.jenisJabatan')
            ->join('ref_unor', 'ref_unor.unor_id', '=', 'usulan_rw_jabatan.unorId')
            ->join('log_usulan', 'log_usulan.usulanId', '=', 'usulan_rw_jabatan.id')
            ->join('ref_jenis_riwayat', 'ref_jenis_riwayat.id', '=', 'log_usulan.jenisRiwayatId')
            ->where('usulan_rw_jabatan.id', $this->idUsulan)
            ->first();

        return view('livewire.detail-usulan', [
            'data' => $data,
            'akses' => $akses
        ]);
    }
}
