<?php

namespace App\Http\Livewire;

use App\Models\V2\RefStatusUsulan;
use App\Models\V2\UsulanRiwayatJabatan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

class ListUsulan extends Component
{
    use WithPagination;

    public $search;
    public $perPage;
    public $statusUsulan;
    protected $updatesQueryString;

    public function mount()
    {
        $this->search = '';
        $this->updatesQueryString = ['search'];
        $this->perPage = 10;
        $this->statusUsulan = 1;
    }

    public function render()
    {
        $akses = app(DashboardController::class)->cekAkses();

        if ($akses) {
            $query = UsulanRiwayatJabatan::select(
                'usulan_rw_jabatan.id',
                'data_utama.nama',
                'log_usulan.nipPengusul',
                'pengusul.nama as namaPengusul',
                'log_usulan.tanggalUsulan',
                'ref_jenis_riwayat.nama as jenisRiwayat',
                'usulan_rw_jabatan.nip',
                'ref_status_usulan.nama as statusUsulan'
            )
                ->join('log_usulan', 'log_usulan.usulanId', '=', 'usulan_rw_jabatan.id')
                ->join('ref_jenis_riwayat', 'ref_jenis_riwayat.id', '=', 'log_usulan.jenisRiwayatId')
                ->join('data_utama', 'data_utama.nip_baru', '=', 'usulan_rw_jabatan.nip')
                ->join('data_utama as pengusul', 'pengusul.nip_baru', '=', 'log_usulan.nipPengusul')
                ->join('ref_status_usulan', 'ref_status_usulan.id', '=', 'log_usulan.statusUsulanId')
                ->where('log_usulan.statusUsulanId', $this->statusUsulan)
                ->orderBy('usulan_rw_jabatan.created_at', 'DESC');
        } else {
            $query = UsulanRiwayatJabatan::select(
                'usulan_rw_jabatan.id',
                'data_utama.nama',
                'log_usulan.nipPengusul',
                'pengusul.nama as namaPengusul',
                'log_usulan.tanggalUsulan',
                'ref_jenis_riwayat.nama as jenisRiwayat',
                'usulan_rw_jabatan.nip',
                'ref_status_usulan.nama as statusUsulan'
            )
                ->join('log_usulan', 'log_usulan.usulanId', '=', 'usulan_rw_jabatan.id')
                ->join('ref_jenis_riwayat', 'ref_jenis_riwayat.id', '=', 'log_usulan.jenisRiwayatId')
                ->join('data_utama', 'data_utama.nip_baru', '=', 'usulan_rw_jabatan.nip')
                ->join('data_utama as pengusul', 'pengusul.nip_baru', '=', 'log_usulan.nipPengusul')
                ->join('ref_status_usulan', 'ref_status_usulan.id', '=', 'log_usulan.statusUsulanId')
                ->where('log_usulan.statusUsulanId', $this->statusUsulan)
                ->where('usulan_rw_jabatan.nip', Auth::user()->username)
                ->orderBy('usulan_rw_jabatan.created_at', 'DESC');
        }

        $usulan_riwayat_jabatan = $query->paginate($this->perPage);
        $total_data = $query->count();

        $ref_status_usulan = RefStatusUsulan::select('id', 'nama')->get();

        return view('livewire.list-usulan', [
            'usulan_riwayat_jabatan' => $usulan_riwayat_jabatan,
            'page_links' => $this->getPageLinks($usulan_riwayat_jabatan->lastPage(), $usulan_riwayat_jabatan->currentPage()),
            'current_page' => $usulan_riwayat_jabatan->currentPage(),
            'total_data' => $total_data,
            'filtered_data' => $query->count(),
            'ref_status_usulan' => $ref_status_usulan,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    private function getPageLinks($lastPage, $currentPage)
    {
        $range = 10;
        $start = max(1, $currentPage - 4);
        $end = min($lastPage, $start + $range - 1);

        if ($end - $start < $range - 1) {
            $start = max(1, $end - $range + 1);
        }

        return range($start, $end);
    }

    public function goTo($id)
    {
        return redirect()->route('detail-usulan', $id);
    }
}
