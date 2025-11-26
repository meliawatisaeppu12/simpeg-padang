<?php

namespace App\Http\Livewire;

use App\Models\V2\RefDiklatStruktural;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\V2\RiwayatDiklat as RwDiklat;


class RiwayatDiklat extends Component
{

    use WithPagination;

    public $search;
    public $perPage;
    protected $updatesQueryString;

    public function render()
    {

        $rwDiklat = RwDiklat::select('latihanStrukturalNama', 'institusiPenyelenggara', 'tanggalSelesai')
            ->where('nipBaru', Auth::user()->username)
            ->where('latihanStrukturalNama', 'like', '%' . $this->search . '%')
            ->orWhere('nipBaru', Auth::user()->username)
            ->where('institusiPenyelenggara', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        $ref_diklat_struktural = RefDiklatStruktural::select('id','nama')->get();

        return view('livewire.riwayat-diklat', [ 
            'rw_diklat' => $rwDiklat,
            'ref_diklat_struktural' => $ref_diklat_struktural,
            'page_links' => $this->getPageLinks($rwDiklat->lastPage(), $rwDiklat->currentPage()),
            'currentPage' => $rwDiklat->currentPage(),
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
