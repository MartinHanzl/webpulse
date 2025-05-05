<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
    protected $table = 'country_translations';

    protected $fillable = [
        'country_id',
        'locale',
        'name',
    ];
}
