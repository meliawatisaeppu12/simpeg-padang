<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'm_data_kecamatan';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];
}
