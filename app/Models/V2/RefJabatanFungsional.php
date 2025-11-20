<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJabatanFungsional extends Model
{
    use HasFactory;

    public static $withoutAppends = false;

    protected $connection = 'simpegv2';

    protected $table = 'ref_jabatan_fungsional';
}
