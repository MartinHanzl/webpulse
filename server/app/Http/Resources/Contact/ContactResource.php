<?php

namespace App\Http\Resources\Contact;

use App\Models\Contact\ContactSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'phone_prefix' => $this->phone_prefix,
            'phone' => $this->phone,
            'email' => $this->email,
            'company' => $this->company,
            'street' => $this->street,
            'city' => $this->city,
            'zip' => $this->zip,
            'occupation' => $this->occupation,
            'goal' => $this->goal,
            'note' => $this->note,
            'user_id' => $this->user_id,
            'contact_phase_id' => $this->contact_phase_id,
            'contact_source_id' => $this->contact_source_id,
            'parent_contact' => ContactSimpleResource::make($this->contact),
            'source' => ContactSourceResource::make($this->source),
            'phase' => ContactPhaseResource::make($this->phase),
            'tasks' => ContactTaskResource::collection($this->tasks),
            'history' => ContactHistoryResource::collection($this->histories),
            'contacts' => [
                'data' => ContactSimpleResource::collection($this->contacts),
            ],
            'last_contacted_at' => $this->last_contacted_at,
            'formatted_last_contacted_at' => $this->last_contacted_at?->format('Y-m-d H:i:s'),
            'next_meeting' => $this->next_meeting,
            'formatted_next_meeting' => $this->next_meeting?->format('Y-m-d H:i:s'),
        ];
    }
}
