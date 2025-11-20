<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferensiUnitOrganisasi extends Model
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'referensi_unit_organisasi';

    public $appends = ['unor_induk_nama'];

    public function getUnorIndukNamaAttribute()
    {
        $induk = $this->where('unor_id', $this->unor_induk_id)->first();

        return !empty($induk) ? $induk->unor_nama : null;
    }
}
