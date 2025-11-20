<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AturSandi extends Component
{

    use WithPagination;

    public $search;
    public $perPage;
    public $status;
    public $showInfo;
    public $username;
    protected $updatesQueryString;

    public function mount()
    {
        $this->search = Auth::user()->V2Profile->nama;
        $this->username = Auth::user()->V2Profile->nip_baru;
        $this->updatesQueryString = ['search'];
        $this->perPage = 1;
        $this->status = 0;
        $this->showInfo = false;
    }

    public function render()
    {
        $data = User::select('username', 'nama', 'unor_nama', 'unor_induk_nama', 'jabatan_nama')
            ->join('data_utama', 'data_utama.nip_baru', '=', 'users.username')
            ->where('data_utama.nip_baru', 'like', '%' . $this->search . '%')
            ->where('data_utama.jabatan_nama', 'not like', '%guru%')
            ->where('data_utama.is_active', true)
            ->orWhere('data_utama.nama', 'like', '%' . $this->search . '%')
            ->where('data_utama.jabatan_nama', 'not like', '%guru%')
            ->where('data_utama.is_active', true)
            ->limit($this->perPage)
            ->get();

        return view('livewire.atur-sandi', [
            'data' => $data,
        ]);
    }

    public function resetSandi($username)
    {
        sleep(2);
        $pass = Hash::make('1234Padang');
        $this->status = User::where('username', $username)->update(['password' => $pass]);
        $this->showInfo = true;
        Log::info($this->username);
        Log::info($this->status);
    }

    public function updatingSearch()
    {
        $this->status = 0;
        $this->showInfo = false;
        $this->resetPage();
    }
}
