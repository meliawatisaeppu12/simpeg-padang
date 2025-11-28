<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    use HasFactory;

    protected $table = 'upload_history';

    protected $fillable = [
        'PNS_ID',
        'NIP_BARU',
        'NAMA',
        'JABATAN_NAMA'
    ];
}
