<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersActivity extends Model
{
    use HasFactory;

    protected $connection = 'simpegv2';

    protected $table = 'users_activity';

    protected $fillable = [
        'nip', 'activity', 'affected_table', 'affected_field', 'device'
    ];
}
