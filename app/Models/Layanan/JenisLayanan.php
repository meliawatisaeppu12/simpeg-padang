<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    use HasFactory;

    protected $table = 'm_jenis_layanan';

    protected $primaryKey = 'id';

    public function berkasLayanan()
    {
        return $this->belongsToMany(Berkas::class,'tb_berkas_layanan','id_jenis_layanan','id_berkas');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class,'id_layanan','id');
    }
}
