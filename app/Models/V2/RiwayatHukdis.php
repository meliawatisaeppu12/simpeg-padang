<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatHukdis extends Model
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'rw_hukdis';

    public function dataUtama()
    {
        return $this->hasOne(DataUtama::class, 'id', 'pnsOrang');
    }
}
