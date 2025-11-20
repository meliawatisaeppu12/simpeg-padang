<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeLayanan extends Model
{
    use HasFactory;

    protected $table = 'tb_periode_layanan';

    protected $primaryKey = 'id';
}
