<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionAccess extends Model
{
    use HasFactory;

    protected $connection = 'access';

    protected $table = 'position_rights';

    public function access_data()
    {
        return $this->hasOne(AccessList::class, 'id', 'access_id');
    }
}
