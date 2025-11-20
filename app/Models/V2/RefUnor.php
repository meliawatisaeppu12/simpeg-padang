<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\V2\DataUtama;

class RefUnor extends Model
{
    use HasFactory;

    public static $withoutAppends = false;

    protected $connection = 'simpegv2';

    protected $table = 'ref_unor';

    public $appends = [];

    protected function getArrayableAppends()
    {
        if (self::$withoutAppends) {
            $this->appends = array_unique(array_merge($this->appends, [
                'unor_induk_nama',
            ]));
            return parent::getArrayableAppends();
        }

        $this->appends = array_unique(array_merge($this->appends, [
            'unor_atasan_nama',
            'unor_induk_nama',
            'kepala_unit',
            'asn',
            'sub_unit_organisasi',
        ]));
        return parent::getArrayableAppends();
    }

    public function getUnorAtasanNamaAttribute()
    {
        $atasan = $this->where('unor_id', $this->unor_atasan_id)->first();

        return !empty($atasan) ? $atasan->unor_nama : null;
    }

    public function getUnorIndukNamaAttribute()
    {
        $induk = $this->where('unor_id', $this->unor_induk_id)->first();

        return !empty($induk) ? $induk->unor_nama : null;
    }

    public function getKepalaUnitAttribute()
    {
        return DataUtama::select('nip_baru as nip', 'nama', 'jabatan_struktural_id as jabatan_id', 'jabatan_nama as jabatan')->where('unor_id', $this->unor_id)
            ->where('jenis_jabatan_id', 1)->first();
    }

    public function getAsnAttribute()
    {
        $data_asn = DataUtama::select('id as pns_id', 'nip_baru as nip', 'nama', 'jabatan_nama as jabatan')->where('unor_id', $this->unor_id)
            ->where('jenis_jabatan_id', '!=', 1)->get();
        return count($data_asn) > 0 ? $data_asn : null;
    }

    public function getSubUnitOrganisasiAttribute()
    {
        $sub_unit = $this->select('unor_id', 'unor_nama', 'unor_atasan_id')->where('unor_atasan_id', $this->unor_id)->get();
        return count($sub_unit) > 0 ? $sub_unit : null;
    }
}
