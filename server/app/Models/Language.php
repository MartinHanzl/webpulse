<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'name',
        'code',
        'iso',
        'active'
    ];

//    protected $guarded = ['name'];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $with = ['translations'];

    public function translations(): HasMany
    {
        return $this->hasMany(LanguageTranslation::class, 'language_id', 'id');
    }
}
