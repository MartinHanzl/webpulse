<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Translatable;

    protected $table = 'languages';

    protected $fillable = [
        'name',
        'code',
        'iso',
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
