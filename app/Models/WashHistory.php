<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WashHistory extends Model
{
    protected $fillable = [
        'firstname',
        'email',
        'carName',
        'carModel',
        'washedBy',
        'notes',
    ];
}
