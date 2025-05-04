<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageTranslation extends Model
{
    use HasFactory;

    protected $table = 'language_translations';

    protected $fillable = [
        'language_id',
        'locale',
        'name',
    ];
}
