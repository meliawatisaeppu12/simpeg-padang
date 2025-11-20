<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPegawai extends Model
{
    use HasFactory;

    protected $table = 'tb_berkas_pegawai';

    protected $primaryKey = 'id';
}
