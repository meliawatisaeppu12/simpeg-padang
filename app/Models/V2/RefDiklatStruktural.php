<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefDiklatStruktural extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'ref_diklat_struktural';
}
