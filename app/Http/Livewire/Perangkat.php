<?php

namespace App\Http\Livewire;

use App\Models\V2\DataUtama;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Perangkat extends Component
{

    use WithPagination;

    public $search;
    public $perPage;
    protected $updatesQueryString;

    public function mount()
    {
        $this->search = Auth::user()->V2Profile->nama;
        $this->updatesQueryString = ['search'];
        $this->perPage = 1;
    }

    public function render()
    {
        $data_utama = DataUtama::select('nip_baru', 'nama', 'jabatan_nama', 'unor_nama', 'unor_induk_nama')
            ->where('nip_baru', 'like', '%' . $this->search . '%')
            ->has('device')
            ->with('device', function ($query) {
                $query->select('devices.uuid', 'devices.nip_baru', 'devices.device', 'devices.device_brand', 'device_library.nama as device_name', 'devices.created_at')->join('device_library', function ($join) {
                    $join->on('device_library.brand', '=', 'devices.device_brand');
                    $join->on('device_library.device', '=', 'devices.device');
                })->orderBy('devices.created_at', 'ASC');;
            })
            ->with('unknownDevice', function ($query) {
                $query->select('unknown_devices.uuid', 'unknown_devices.nip_baru', 'unknown_devices.device', 'unknown_devices.device_brand', 'device_library.nama as device_name', 'unknown_devices.created_at')->join('device_library', function ($join) {
                    $join->on('device_library.brand', '=', 'unknown_devices.device_brand');
                    $join->on('device_library.device', '=', 'unknown_devices.device');
                })->orderBy('unknown_devices.created_at', 'ASC');;
            })
            ->orWhere('nama', 'like', '%' . $this->search . '%')
            ->has('device')
            ->with('device', function ($query) {
                $query->select('devices.uuid', 'devices.nip_baru', 'devices.device', 'devices.device_brand', 'device_library.nama as device_name', 'devices.created_at')->join('device_library', function ($join) {
                    $join->on('device_library.brand', '=', 'devices.device_brand');
                    $join->on('device_library.device', '=', 'devices.device');
                })->where('is_active',true)->orderBy('devices.created_at', 'ASC');;
            })
            ->with('unknownDevice', function ($query) {
                $query->select('unknown_devices.uuid', 'unknown_devices.nip_baru', 'unknown_devices.device', 'unknown_devices.device_brand', 'device_library.nama as device_name', 'unknown_devices.created_at')->join('device_library', function ($join) {
                    $join->on('device_library.brand', '=', 'unknown_devices.device_brand');
                    $join->on('device_library.device', '=', 'unknown_devices.device');
                })->orderBy('unknown_devices.created_at', 'ASC');;
            })
            ->limit($this->perPage)->get();

        $mapped_data = $data_utama->map(function ($value) {
            return array(
                'nip_baru' => $value['nip_baru'],
                'nama' => $value['nama'],
                'jabatan_nama' => $value['jabatan_nama'],
                'unor_nama' => $value['unor_nama'],
                'unor_induk_nama' => $value['unor_induk_nama'],
                'device' => collect($value['device'])->groupBy('device')->values(),
                'unknown_device' => collect($value['unknownDevice'])->groupBy('device')->values(),
            );
        });

        return view('livewire.perangkat', [
            'data_utama' => $data_utama,
            'mapped_data' => $mapped_data->values(),
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
