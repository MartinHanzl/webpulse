<?php

namespace App\Http\Resources\Contact;

use App\Models\Contact\ContactSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactSimpleResource extends JsonResource
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
            'phase' => $this->phase->name,
            'source' => $this->source->name,
            'contact' => ContactSimpleResource::make($this->contact),
        ];
    }
}
