<?php

namespace App\Models\SSO;

use App\Models\V2\DataUtama;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $connection = 'dbsurat';

    protected $table = 'surat_masuk';

    public function tujuan()
    {
        return $this->hasMany(TujuanSurat::class, 'surat_masuk_id', 'id');
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id', 'id');
    }

    public function asisten()
    {
        return $this->hasOne(DataUtama::class,'nip_baru','asisten_nip');
    }
}
