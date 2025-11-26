<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferensiUnor extends Model
{
    use HasFactory;

    protected $table = 'referensi_unit_organisasi';

    public $appends = [
        'kepala_unit',
        'asn'
    ];

    public function getKepalaUnitAttribute()
    {
        return ReferensiPegawai::select('nip','nama','jabatan')->where('unor_id',$this->unor_id)
                        ->where('jenis_jabatan',1)->first();
    }

    public function getAsnAttribute()
    {
        return ReferensiPegawai::select('nip','nama','jabatan')->where('unor_id',$this->unor_id)
                        ->where('jenis_jabatan',2)->get();
    }
}
