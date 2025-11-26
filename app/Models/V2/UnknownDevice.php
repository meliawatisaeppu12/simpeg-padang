<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnknownDevice extends Model
{
    use HasFactory;

    protected $table = 'unknown_devices';

    public function dataUtama()
    {
        return $this->belongsTo(DataUtama::class, 'nip_baru', 'nip_baru');
    }

    public function library()
    {
        return $this->hasOne(DeviceLibrary::class,'device','device');
    }
}
