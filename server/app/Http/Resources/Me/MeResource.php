<?php

namespace App\Http\Resources\Me;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'firstname' => $this->firstname,
          'lastname' => $this->lastname,
          'phone_prefix' => $this->phone_prefix,
          'phone' => $this->phone,
          'email' => $this->email,
          'invitation_code' => $this->invitation_code,
        ];
    }
}
