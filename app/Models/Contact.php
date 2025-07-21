<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'date_sent',
    ];

    public $timestamps = true; // Keeps created_at/updated_at
}
