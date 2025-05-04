<?php

namespace App\Models\Currency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyTranslation extends Model
{
    use HasFactory;

    protected $table = 'currency_translations';

    protected $fillable = [
        'currency_id',
        'locale',
        'name',
        'symbol_before',
        'symbol_after',
    ];
}
