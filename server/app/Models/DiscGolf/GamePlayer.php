<?php

namespace App\Models\DiscGolf;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    protected $table = 'game_players';

    protected $fillable = [
        'game_id',
        'player_id',
        'handicap',
        'total_throws',
        'position',
    ];

    protected $casts = [
        'handicap' => 'integer',
        'total_throws' => 'integer',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    public function scores()
    {
        return $this->hasMany(GameHoleScore::class, 'game_player_id', 'id');
    }

    /**
     * Net score = total throws PLUS handicap (handicap is a signed correction to the score:
     * a −4 handicap credits 4 strokes, so net throws are 4 fewer).
     */
    public function getNetScoreAttribute(): int
    {
        return (int) $this->total_throws + (int) $this->handicap;
    }

    /**
     * +/- par relative to the game par, based on the net (handicap-adjusted) score.
     * Equivalent to (throws − par) + handicap.
     */
    public function getRelativeToParAttribute(): ?int
    {
        $par = $this->game?->par;
        if ($par === null) {
            return null;
        }

        return $this->net_score - (int) $par;
    }
}
