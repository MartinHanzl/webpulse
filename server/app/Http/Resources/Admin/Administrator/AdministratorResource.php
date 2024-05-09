<?php

namespace App\Http\Resources\Admin\Administrator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdministratorResource extends JsonResource
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
            'active' => (bool)$this->active,
            'group' => AdministratorGroupResource::make($this->group),
        ];
    }
}
