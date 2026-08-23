<?php

namespace App\Http\Resources\Admin\Contact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactBoardCardResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'note' => $this->note,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'position' => $this->position,
            'contact_board_section_id' => $this->contact_board_section_id,
            'contact_id' => $this->contact_id,
            'contact' => ContactSimpleResource::make($this->whenLoaded('contact')),
            'contact_name' => $this->contact
                ? trim($this->contact->firstname.' '.$this->contact->lastname)
                : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
