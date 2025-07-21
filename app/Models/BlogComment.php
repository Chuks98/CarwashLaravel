<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $fillable = [
        'blog_id',
        'name',
        'email',
        'comment'
    ];

    // Each comment belongs to a blog
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}