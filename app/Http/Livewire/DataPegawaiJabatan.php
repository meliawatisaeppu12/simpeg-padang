<?php

namespace App\Http\Livewire;

use App\Models\V2\DataUtama;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class DataPegawaiJabatan extends Component
{
    public $nip_baru;

    public function mount($nip_baru)
    {
        $this->nip_baru = Crypt::decrypt($nip_baru);
    }

    public function render()
    {
        $user = DataUtama::select('id', 'nip_baru', 'nama', 'jabatan_nama', 'unor_induk_nama', 'gelar_belakang')
        ->where('nip_baru',$this->nip_baru)
        ->first();
        
        return view('livewire.data-pegawai-jabatan')
            ->layout('components.admin', [
                'user' => json_encode($user),
            ])->slot('slot');
    }
}
