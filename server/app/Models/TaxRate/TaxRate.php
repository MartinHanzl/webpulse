<?php

namespace App\Models\TaxRate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $table = 'tax_rates';

    protected $fillable = [
        'name',
        'rate',
    ];

    protected $casts = [
        'rate' => 'float',
    ];
}
