<?php

namespace App\Http\Livewire;

use App\Models\V2\DataUtama;
use Livewire\Component;
use Livewire\WithPagination;

class DataPegawai extends Component
{

    use WithPagination;

    public $search;
    public $perPage;
    protected $updatesQueryString;

    public function mount()
    {
        $this->search = '';
        $this->updatesQueryString = ['search'];
        $this->perPage = 10;
    }

    public function render()
    {
        $total_data = DataUtama::where('is_active', true)->count();
        $query = DataUtama::select('id', 'nip_baru', 'nama', 'jabatan_nama', 'unor_induk_nama')
            ->where('nip_baru', 'like', '%' . $this->search . '%')
            ->where('is_active', true)
            ->orWhere('nama', 'like', '%' . $this->search . '%')
            ->where('is_active', true)
            ->orWhere('jabatan_nama', 'like', '%' . $this->search . '%')
            ->where('is_active', true)
            ->orWhere('unor_induk_nama', 'like', '%' . $this->search . '%')
            ->where('is_active', true);

        $data = $query->paginate($this->perPage);
        return view('livewire.data-pegawai', [
            'data' => $data,
            'page_links' => $this->getPageLinks($data->lastPage(), $data->currentPage()),
            'current_page' => $data->currentPage(),
            'total_data' => $total_data,
            'filtered_data' => $query->count(),
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

    public function goTo($nip_baru){
        return redirect()->route('jabatan.pegawai',$nip_baru);
    }
}
