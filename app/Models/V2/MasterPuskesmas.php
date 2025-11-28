<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPuskesmas extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'm_puskesmas';
}
