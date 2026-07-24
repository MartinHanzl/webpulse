<?php

namespace App\Models\Food\CalorieMeasurement;

use App\Traits\Siteable;
use Illuminate\Database\Eloquent\Model;

class CalorieMeasurement extends Model
{
    use Siteable;

    protected $table = 'calorie_measurements';

    protected $fillable = [
        'name',
        'portions',
        'items',
    ];

    protected $casts = [
        'portions' => 'integer',
        'items' => 'array',
    ];

    public function sites()
    {
        return $this->morphToMany('App\Models\Site\Site', 'siteable');
    }
}
