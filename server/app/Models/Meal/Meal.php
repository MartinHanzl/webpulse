<?php

namespace App\Models\Meal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'image',
        'summary',
        'instructions',
        'creator',
        'creator_email'
    ];
}
