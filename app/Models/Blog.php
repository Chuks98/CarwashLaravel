<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'message',
        'image'
    ];

    // A blog has many comments
    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }
}
