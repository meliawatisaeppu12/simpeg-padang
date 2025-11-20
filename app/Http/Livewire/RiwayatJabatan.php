<?php

namespace App\Http\Livewire;

use App\Models\V2\RefJenisJabatan;
use App\Models\V2\RefSubJabatan;
use App\Models\V2\RefUnor;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\V2\RiwayatJabatan as RwJabatanModel;

class RiwayatJabatan extends Component
{

    use WithPagination;

    public $search;
    public $perPage;
    protected $updatesQueryString;
    public $isSaving;

    public function mount()
    {
        $this->search = '';
        $this->updatesQueryString = ['search'];
        $this->perPage = 5;
        $this->isSaving = false;
    }

    public function render()
    {
        $rwJabatan = RwJabatanModel::select('unorNama', 'unorIndukNama', 'namaJabatan', 'jabatanFungsionalNama', 'jabatanFungsionalUmumNama')
            ->where('nipBaru', Auth::user()->username)
            ->where('namaJabatan', 'like', '%' . $this->search . '%')
            ->orWhere('nipBaru', Auth::user()->username)
            ->where('jabatanFungsionalNama', 'like', '%' . $this->search . '%')
            ->orWhere('nipBaru', Auth::user()->username)
            ->where('jabatanFungsionalUmumNama', 'like', '%' . $this->search . '%')
            ->orWhere('nipBaru', Auth::user()->username)
            ->where('unorNama', 'like', '%' . $this->search . '%')
            ->orWhere('nipBaru', Auth::user()->username)
            ->where('unorIndukNama', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        $refUnor = RefUnor::select('unor_id', 'unor_nama')->get();

        $refJenisJabatan = RefJenisJabatan::select('id','nama')->get();

        $refSubJabatan = RefSubJabatan::select('subJabatanId as id','nama')->get();

        return view('livewire.riwayat-jabatan', [
            'rw_jabatan' => $rwJabatan,
            'ref_unor' => $refUnor,
            'ref_jenis_jabatan' => $refJenisJabatan,
            'ref_sub_jabatan' => $refSubJabatan,
            'page_links' => $this->getPageLinks($rwJabatan->lastPage(), $rwJabatan->currentPage()),
            'currentPage' => $rwJabatan->currentPage(),
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
}
