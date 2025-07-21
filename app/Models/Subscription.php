<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'userId',
        'plan',
        'status',
        'price',
        'startDate',
        'nextBillingDate',
        'reference',
    ];

    // Relationships
        public function user()
        {
            return $this->belongsTo(User::class, 'userId');
        }
}
