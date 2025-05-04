<?php

namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Currency extends Model implements TranslatableContract
{
    use Translatable;

    // init basic model
    protected $table = 'currencies';

    protected $fillable = [
        'code',
        'rate',
        'decimals',
        'active',
        'bank_account_number',
        'bank_account_name',
        'bank_account_iban',
        'bank_account_swift',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // init translation model
    public $translatedAttributes = [
        'name',
        'symbol_before',
        'symbol_after',
    ];
}
