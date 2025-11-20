<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanRiwayatHukdis extends Model
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'usulan_rw_hukdis';

    public $timestamps = false;
}
