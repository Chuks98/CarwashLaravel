<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
