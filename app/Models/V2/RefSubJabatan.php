<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefSubJabatan extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'ref_sub_jabatan';
}
