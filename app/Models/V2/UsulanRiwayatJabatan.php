<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanRiwayatJabatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'simpegv2';

    protected $table = 'usulan_rw_jabatan';

    public function jenisJabatan(){
        return $this->hasOne(RefJenisJabatan::class,'id','jenisJabatanId');
    }

    public function logUsulan(){
        return $this->belongsTo(LogUsulan::class,'usulanId','id');
    }
}
