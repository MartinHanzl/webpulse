<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Language extends Model implements TranslatableContract
{
    use Translatable;

    // init basic model
    protected $table = 'languages';

    protected $fillable = [
        'code',
        'iso',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // init translation model
    public $translatedAttributes = [
        'name'
    ];

    public function getFullCodeAttribute(): string
    {
        return $this->code . '-' . $this->iso;
    }
}
