<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'image_post',
        'description',
        'posted_time',
        'likes_count',
        'hashtags',
        'type',
    ];

    protected $casts = [
        'hashtags' => 'array',
        'posted_time' => 'datetime',
            'image_post' => 'array',
    ];

    
}
