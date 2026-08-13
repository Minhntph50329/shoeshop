<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'author_id',
        'category_id',
        'views',
        'is_active',
        'alow_comments',
    ];

    protected $casts = [
        'views' => 'integer',
        'is_active' => 'boolean',
        'alow_comments' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
