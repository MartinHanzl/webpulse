<?php

namespace App\Http\Resources\Contact;

use App\Http\Resources\Activity\ActivityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactHistoryResource extends JsonResource
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
            'description' => $this->description,
            'origin' => $this->origin,
            'type' => $this->type,
            'contact_id' => $this->contact_id,
            'phase' => ContactPhaseResource::make($this->phase),
            'activity' => ActivityResource::make($this->activity),
            'created_at' => $this->created_at,
        ];
    }
}
