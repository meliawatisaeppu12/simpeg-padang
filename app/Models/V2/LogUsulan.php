<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogUsulan extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'log_usulan';

    public $timestamps = false;
}
