<?php

namespace App\Models\Service;

use App\Models\Currency\Currency;
use App\Models\TaxRate;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Translatable;

    protected $table = 'services';
    protected $fillable = [
        'type',
        'price_type',
        'price',
        'tax_rate_id',
        'currency_id',
        'image',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2',
        'price_type' => 'string',
        'type' => 'string',
    ];

    protected $translatedAttributes = [
        'name',
        'locale',
        'slug',
        'perex',
        'description',
        'meta_title',
        'meta_description',
    ];
    protected $translationForeignKey = 'service_id';
    protected $with = ['currency', 'taxRate'];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_id', 'id');
    }
}
