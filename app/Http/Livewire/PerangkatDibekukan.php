<?php

namespace App\Http\Livewire;

use App\Models\V2\LockedAccount;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerangkatDibekukan extends Component
{

    use WithPagination;

    public $search;
    public $perPage;
    public $isSaving;
    public $jumlahData;
    private $data;
    protected $updatesQueryString;

    public function mount()
    {
        $this->search = '';
        $this->updatesQueryString = ['search'];
        $this->perPage = 10;
        $this->isSaving = false;
    }

    public function render()
    {
        $this->data = $this->getData();

        return view('livewire.perangkat-dibekukan', [
            'data' => $this->data,
            'page_links' => $this->getPageLinks($this->data->lastPage(), $this->data->currentPage()),
            'currentPage' => $this->data->currentPage(),
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function update($id)
    {
        sleep(2);
        $result = LockedAccount::where('id', $id)->update(['is_locked' => false]);
        if ($result) {
            $this->search = '';
            $this->data = $this->getData();
        }
    }

    private function getData()
    {
        $this->jumlahData = LockedAccount::select('nip', 'data_utama.nama')->join('data_utama', 'data_utama.nip_baru', '=', 'tb_locked_account.nip')->where('is_locked', true)
            ->where('nip', 'like', '%' . $this->search . '%')
            ->orWhere('is_locked', true)
            ->where('data_utama.nama', 'like', '%' . $this->search . '%')->count();

        return DB::table('v_locked_account')->select(
            'id',
            'nama',
            'nip',
            'unor_nama',
            'unor_induk_nama',
            'is_locked',
            'counted',
            'created_at',
            'updated_at'
        )
            ->where('is_locked', true)
            ->where('nip', 'like', '%' . $this->search . '%')
            ->orWhere('is_locked', true)
            ->where('nama', 'like', '%' . $this->search . '%')
            ->orderBy('counted', 'desc')
            ->paginate($this->perPage);
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
