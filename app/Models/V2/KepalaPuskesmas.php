<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaPuskesmas extends Model
{
    use HasFactory;

    protected $connection = 'esdm';

    protected $table = 'tb_kepala_puskesmas';
}
