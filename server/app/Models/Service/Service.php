<?php

namespace App\Models\Service;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency\Currency', 'currency_id');
    }

    public function taxRate()
    {
        return $this->belongsTo('App\Models\TaxRate\TaxRate', 'tax_rate_id');
    }
}
