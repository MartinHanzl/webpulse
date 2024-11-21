<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone_prefix' => $this->phone_prefix,
            'phone' => $this->phone,
            'street' => $this->street,
            'city' => $this->city,
            'zip' => $this->zip,
            'invitation_token' => $this->invitation_token,
        ];
    }
}
