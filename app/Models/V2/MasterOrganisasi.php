<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterOrganisasi extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'm_organisasi';
}
