<?php

namespace App\Models\Layanan;

use App\Models\BerkasPegawai;
use App\Models\V2\DataUtama;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransLayanan extends Model
{
    use HasFactory;

    protected $table = 'tb_trans_layanan';

    protected $primaryKey = 'id';

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'id_jenis_layanan', 'id');
    }

    public function berkasDraft()
    {
        return $this->belongsToMany(BerkasPegawai::class, 'tb_trans_berkas', 'id_trans_layanan', 'id_berkas_pegawai');
    }

    public function asn()
    {
        return $this->belongsTo(DataUtama::class, 'nip', 'nip_baru');
    }

    public function prosesLayanan()
    {
        return $this->hasMany(ProsesLayanan::class,'id_trans_layanan','id');
    }
}
