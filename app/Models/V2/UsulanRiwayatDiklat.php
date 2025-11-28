<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanRiwayatDiklat extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'usulan_rw_diklat';

    public $timestamps = false;
}
