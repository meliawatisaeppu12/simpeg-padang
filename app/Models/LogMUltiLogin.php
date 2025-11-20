<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogMUltiLogin extends Model
{
    use HasFactory;

    protected $table = 'log_multi_login'; 
    
    protected $primaryKey = 'id'; 
}
