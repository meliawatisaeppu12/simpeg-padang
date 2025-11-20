<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJenisJabatan extends Model
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'ref_jenis_jabatan';
}
