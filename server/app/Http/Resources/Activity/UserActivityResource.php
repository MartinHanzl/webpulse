<?php

namespace App\Http\Resources\Activity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'activity' => ActivityResource::make($this->activity),
            'description' => $this->description,
            'duration' => $this->duration,
            'completed' => $this->completed,
            'created_at' => $this->created_at,
            'formatted_day' => $this->created_at->format('d'),
        ];
    }
}
