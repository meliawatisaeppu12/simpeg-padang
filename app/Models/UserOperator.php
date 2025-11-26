<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class OperatorModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'user_non_asn';
}
