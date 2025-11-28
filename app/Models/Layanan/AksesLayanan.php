<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesLayanan extends Model
{
    use HasFactory;

    protected $table = 'tb_akses_layanan';

    protected $primaryKey = 'id';

    public function jenisLayanan()
    {
        return $this->hasOne(JenisLayanan::class,'id','id_jenis_layanan');
    }
}
