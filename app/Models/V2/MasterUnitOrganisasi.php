<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUnitOrganisasi extends Model
{
    use HasFactory;

    public static $withoutAppends = false;

    protected $connection = 'simpegv2';

    protected $table = 'm_unit_organisasi';

    protected $primaryKey = 'unor_id';

    protected function getArrayableAppends()
    {
        if (self::$withoutAppends) {
            return [];
        }

        $this->appends = array_unique(array_merge($this->appends, ['kepala_unit', 'asn']));
        return parent::getArrayableAppends();
    }

    public function getKepalaUnitAttribute()
    {
        return DataUtama::select('nip_baru', 'nama', 'jabatan_nama')->where('unor_id', $this->UNOR_ID)
            ->where('jenis_jabatan_id', 1)->first();
    }

    public function getAsnAttribute()
    {
        return DataUtama::select('nip_baru', 'nama', 'jabatan_nama')->where('unor_id', $this->UNOR_ID)
            ->where('jenis_jabatan_id', 2)->get();
    }
}
