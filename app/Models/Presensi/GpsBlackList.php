<?php

namespace App\Models\Presensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsBlackList extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'gps_black_list';

    protected $primaryKey = 'id_block';
}
