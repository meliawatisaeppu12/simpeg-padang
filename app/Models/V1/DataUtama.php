<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataUtama extends Model
{
    use HasFactory;

    protected $connection = 'esdm';

    protected $table = 'data_utama';
    
}
