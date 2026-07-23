<?php

namespace App\Models\DiscGolf;

use Illuminate\Database\Eloquent\Model;

class GameHoleScore extends Model
{
    protected $table = 'game_hole_scores';

    protected $fillable = [
        'game_id',
        'game_player_id',
        'game_hole_id',
        'throws',
    ];

    protected $casts = [
        'throws' => 'integer',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function gamePlayer()
    {
        return $this->belongsTo(GamePlayer::class, 'game_player_id', 'id');
    }

    public function hole()
    {
        return $this->belongsTo(GameHole::class, 'game_hole_id', 'id');
    }
}
