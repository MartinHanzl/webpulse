<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkTranslation extends Model
{
    use HasFactory;

    protected $table = 'link_translations';
    protected $fillable = [
        'link_id',
        'locale',
        'title',
        'link'
    ];
}
