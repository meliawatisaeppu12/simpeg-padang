<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockedAccount extends Model
{
    use HasFactory;

    protected $table = 'tb_locked_account';
}
