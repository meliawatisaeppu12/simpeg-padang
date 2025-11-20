<?php

namespace App\Models\V2;

use App\Interfaces\PelaksanaInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SSO\AccessLevel;

class DataUtama extends Model implements PelaksanaInterface
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'data_utama';

    protected $primaryKey = 'nip_baru';

    public $incrementing = false;

    public function kelompok()
    {
        return $this->hasOne(KelompokAbsen::class, 'unor_id', 'unor_id');
    }

    public function device()
    {
        return $this->hasMany(Device::class, 'nip_baru', 'nip_baru');
    }

    public function unknownDevice()
    {
        return $this->hasMany(UnknownDevice::class, 'nip_baru', 'nip_baru');
    }

    public function accessLevel()
    {
        return $this->hasOne(AccessLevel::class, 'jabatan_id', 'jabatan_struktural_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNama(): String
    {
        return $this->nama;
    }

    public function getJabatan(): String
    {
        $jabatan_struktural_nama = $this->jabatan_struktural_nama;
        $jabatan_fungsional_nama = $this->jabatan_fungsional_nama;
        $jabatan_fungsional_umum_nama = $this->jabatan_fungsional_umum_nama;
        $jabatan = $jabatan_struktural_nama ?? ($jabatan_fungsional_nama ?? $jabatan_fungsional_umum_nama);
        return $jabatan;
    }

    public function getType(): String
    {
        return 'asn';
    }

    public function getIdentifier(): string
    {
        return $this->nip_baru;
    }
}
