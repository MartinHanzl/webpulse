<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'author',
        'active',
        'image',
        'status'
    ];

    protected function scopeActive($query)
    {
        return $query->where('active', '=', 1);
    }
}
