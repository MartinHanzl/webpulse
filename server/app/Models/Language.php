<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory, Translatable;

    protected $table = 'languages';

    protected $fillable = [
        'default_locale',
        'default_name',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public $translationModel = LanguageTranslation::class;

    protected $translatedAttributes = [
        'locale',
        'name'
    ];
}
