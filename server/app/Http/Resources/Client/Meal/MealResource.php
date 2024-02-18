<?php

namespace App\Http\Resources\Client\Meal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'image' => $this->image,
            'summary' => $this->summary,
            'instructions' => $this->instructions,
            'creator' => $this->creator,
            'creator_email' => $this->creator_email,
        ];
    }
}
