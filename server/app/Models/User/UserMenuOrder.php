<?php

namespace App\Models\User;

use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;

class UserMenuOrder extends Model
{
    protected $table = 'user_menu_orders';

    protected $fillable = [
        'user_id',
        'site_id',
        'section_key',
        'item_key',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }
}
