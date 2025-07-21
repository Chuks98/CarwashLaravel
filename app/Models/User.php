<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'email',
        'password',
        'role',
        'address',
        'carName',
        'carModel',
        'plateNumber',
        'status',
        'authorizationCode',
        'cardType',
        'last4',
        'expMonth',
        'expYear',
        'customerCode',
        'autoBilling',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ Relationships
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
