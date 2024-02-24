<?php

namespace App\Models\Settings;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory, Translatable;

    protected $table = 'links';

    public $translationModel = LinkTranslation::class;

    protected $fillable = [
        'type',
        'active'
    ];

    protected $translatedAttributes = [
        'title',
        'link'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
}
