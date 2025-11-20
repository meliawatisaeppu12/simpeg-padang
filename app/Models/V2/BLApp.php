<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BLApp extends Model
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'data_bl_app';
}
