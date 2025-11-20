<?php

namespace App\Models\SSO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $connection = 'dbsurat';

    protected $table = 'disposisi';
}
