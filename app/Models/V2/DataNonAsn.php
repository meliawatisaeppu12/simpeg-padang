<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\PelaksanaInterface;
use App\Models\SSO\AccessLevel;

class DataNonAsn extends Model implements PelaksanaInterface
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'data_non_asn';

    public function accessLevel()
    {
        return $this->hasOne(AccessLevel::class, 'jabatan_id', 'jabatan_id');
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
        return $this->jabatan;
    }

    public function getType(): String
    {
        return 'non_asn';
    }

    public function getIdentifier(): string
    {
        return $this->username;
    }
}
