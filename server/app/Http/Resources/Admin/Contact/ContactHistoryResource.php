<?php

namespace App\Http\Resources\Admin\Contact;

use App\Http\Resources\Admin\Activity\ActivityResource;
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
            'contact_phase_id' => $this->contact_phase_id,
            'phase' => ContactPhaseResource::make($this->phase),
            'activity_id' => $this->activity_id,
            'activity' => ActivityResource::make($this->activity),
            'created_at' => $this->created_at,
        ];
    }
}
