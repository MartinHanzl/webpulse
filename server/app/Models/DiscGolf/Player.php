<?php

namespace App\Models\DiscGolf;

use App\Traits\Siteable;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use Siteable;

    protected $table = 'players';

    protected $fillable = [
        'name',
        'include_in_stats',
        'position',
    ];

    protected $casts = [
        'include_in_stats' => 'boolean',
    ];

    public function gamePlayers()
    {
        return $this->hasMany(GamePlayer::class, 'player_id', 'id');
    }

    public function sites()
    {
        return $this->morphToMany('App\Models\Site\Site', 'siteable');
    }
}
