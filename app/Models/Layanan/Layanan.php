<?php

namespace App\Models\Layanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'm_layanan';

    protected $primaryKey = 'id';

    public function jenisLayanan()
    {
        return $this->hasMany(JenisLayanan::class,'id_layanan','id');
    }
}
