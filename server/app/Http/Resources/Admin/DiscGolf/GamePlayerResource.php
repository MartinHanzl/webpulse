<?php

namespace App\Http\Resources\Admin\DiscGolf;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GamePlayerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'game_id' => $this->game_id,
            'player_id' => $this->player_id,
            'player_name' => $this->whenLoaded('player', fn () => $this->player->name),
            'handicap' => $this->handicap,
            'total_throws' => $this->total_throws,
            'net_score' => $this->net_score,
            'relative_to_par' => $this->relative_to_par,
            'position' => $this->position,
            'scores' => $this->whenLoaded('scores', fn () => $this->scores->map(fn ($s) => [
                'id' => $s->id,
                'game_hole_id' => $s->game_hole_id,
                'throws' => $s->throws,
            ])),
        ];
    }
}
