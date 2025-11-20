<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationList extends Model
{
    use HasFactory;

    protected $connection = 'access';

    protected $table = 'application_list';
}
