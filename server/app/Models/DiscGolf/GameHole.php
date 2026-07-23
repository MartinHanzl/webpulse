<?php

namespace App\Models\DiscGolf;

use Illuminate\Database\Eloquent\Model;

class GameHole extends Model
{
    protected $table = 'game_holes';

    protected $fillable = [
        'game_id',
        'number',
        'par',
        'meters',
    ];

    protected $casts = [
        'number' => 'integer',
        'par' => 'integer',
        'meters' => 'integer',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function scores()
    {
        return $this->hasMany(GameHoleScore::class, 'game_hole_id', 'id');
    }
}
