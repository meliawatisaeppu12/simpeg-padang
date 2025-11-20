<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransBerkas extends Model
{
    use HasFactory;

    protected $table = 'tb_trans_berkas';
    
    protected $primaryKey = 'id';
}
