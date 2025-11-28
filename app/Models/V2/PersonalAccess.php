<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccess extends Model
{
    use HasFactory;

    protected $connection = 'access';

    protected $table = 'personal_rights';

    public function access_data()
    {
        return $this->hasOne(AccessList::class, 'id', 'access_id');
    }
}
