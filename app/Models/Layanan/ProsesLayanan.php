<?php

namespace App\Models\Layanan;

use App\Models\V2\DataUtama;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesLayanan extends Model
{
    use HasFactory;

    protected $table = 'tb_proses_layanan';

    protected $primaryKey = 'id';

    protected $appends = [
        'jabatan',
        'verifikator'
    ];

    public function transLayanan()
    {
        return $this->hasOne(TransLayanan::class,'id','id_trans_layanan');
    }

    public function getJabatanAttribute()
    {
        $data_utama = DataUtama::select('jabatan_nama')
        ->where('jabatan_struktural_id',$this->jabatan_id)
        ->orWhere('jabatan_fungsional_id',$this->jabatan_id)
        ->orWhere('jabatan_fungsional_umum_id',$this->jabatan_id)
        ->first();

        if(!empty($data_utama))
        {
            return $data_utama->jabatan_nama;
        }

        return null;
    }

    public function getVerifikatorAttribute()
    {
        $data_utama = DataUtama::select('jabatan_nama')
        ->where('jabatan_struktural_id',$this->verifikator_jabatan_id)
        ->orWhere('jabatan_fungsional_id',$this->verifikator_jabatan_id)
        ->orWhere('jabatan_fungsional_umum_id',$this->verifikator_jabatan_id)
        ->first();

        if(!empty($data_utama))
        {
            return $data_utama->jabatan_nama;
        }

        return null;
    }
}
