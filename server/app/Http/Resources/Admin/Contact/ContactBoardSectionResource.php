<?php

namespace App\Http\Resources\Admin\Contact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactBoardSectionResource extends JsonResource
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
            'name' => $this->name,
            'color' => $this->color,
            'position' => $this->position,
            'cards_count' => $this->when(isset($this->cards_count), $this->cards_count),
            'cards' => $this->whenLoaded('cards', fn () => ContactBoardCardResource::collection($this->cards)),
        ];
    }
}
