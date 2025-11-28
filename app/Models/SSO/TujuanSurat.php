<?php

namespace App\Models\SSO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanSurat extends Model
{
    use HasFactory;

    protected $connection = 'dbsurat';

    protected $table = 'tujuan_surat';

    protected $fillable = [
        'surat_masuk_id',
        'penerima_id',
        'penerima_nama',
    ];
}
