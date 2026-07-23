<?php

namespace App\Http\Resources\Admin\DiscGolf;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'include_in_stats' => (bool) $this->include_in_stats,
            'position' => $this->position,
            'games_count' => $this->game_players_count
                ?? ($this->relationLoaded('gamePlayers') ? $this->gamePlayers->count() : 0),
            'sites' => $this->sites,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
