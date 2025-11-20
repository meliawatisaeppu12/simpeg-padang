<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SSO\AccessLevel;

class NonAsn extends Model
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'data_non_asn';

    protected $primaryKey = 'username';

    public $incrementing = false;

    public function device()
    {
        return $this->hasMany(DeviceNonAsn::class, 'username', 'username');
    }

    public function unknownDevice()
    {
        return $this->hasMany(UnknownDevice::class, 'nip_baru', 'username');
    }

    // public function accessLevel()
    // {
    //     return $this->hasOne(AccessLevel::class, 'jabatan_id', 'jabatan_struktural_id');
    // }
}
