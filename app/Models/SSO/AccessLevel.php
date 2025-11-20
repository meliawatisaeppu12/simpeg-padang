<?php

namespace App\Models\SSO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V2\DataUtama;

class AccessLevel extends Model
{
    use HasFactory;

    protected $connection = 'access';

    protected $table = 'access_level';

    public function dataUtama()
    {
        return $this->hasOne(DataUtama::class,'jabatan_struktural_id','jabatan_id');
    }
}
